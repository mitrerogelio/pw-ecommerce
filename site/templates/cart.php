<?php namespace ProcessWire;

$cart = session()->get('cart') ?? [];

if (input()->requestMethod() === 'POST' && session()->CSRF->hasValidToken()) {
    $action = sanitizer()->name(input()->post('action'));
    $productId = (int)input()->post('product_id');

    switch ($action) {
        case 'add':
            if ($productId > 0) {
                $qty = max(1, (int)input()->post('qty'));
                $cart[$productId] = ($cart[$productId] ?? 0) + $qty;
            }
            break;

        case 'update':
            foreach ((array)input()->post('qty') as $rawId => $rawQty) {
                $id = (int)$rawId;
                $qty = (int)$rawQty;
                if ($id > 0) {
                    if ($qty > 0) {
                        $cart[$id] = $qty;
                    } else {
                        unset($cart[$id]);
                    }
                }
            }
            break;

        case 'remove':
            if ($productId > 0) unset($cart[$productId]);
            break;
    }

    session()->set('cart', $cart);
    // PRG Pattern (Post/Redirect/Get)
    session()->redirect(page()->url);
}

$cartProducts = count($cart)
        ? pages()->find('id=' . implode('|', array_keys($cart)) . ', status<' . Page::statusMax)
        : new PageArray();

$subtotal = 0.0;
foreach ($cartProducts as $p) {
    $subtotal += (float)$p->price * ($cart[$p->id] ?? 0);
}

$cartIsEmpty = $cartProducts->count() === 0;

$suggestedProducts = pages()->find("template=product, status<" . Page::statusMax . ", limit=4, sort=random");
?>

<main id="content">
    <div class="page-wrapper">

        <h1 class="page-title">Your Cart</h1>

        <section class="cart-layout">

            <section class="cart-items" aria-label="Cart items">

                <?php if (!$cartIsEmpty): ?>

                    <?php
                    // Remove forms sit outside the update form — HTML forbids nesting forms.
                    // Each remove button references its form via the `form` attribute.
                    foreach ($cartProducts as $p):
                        ?>
                        <form id="cart-remove-<?= $p->id ?>" method="post">
                            <?= session()->CSRF->renderInput() ?>
                            <input type="hidden" name="action" value="remove">
                            <input type="hidden" name="product_id" value="<?= $p->id ?>">
                        </form>
                    <?php endforeach; ?>

                    <form id="cart-form" method="post">
                        <?= session()->CSRF->renderInput() ?>
                        <input type="hidden" name="action" value="update">

                        <table class="cart-table">
                            <thead>
                            <tr>
                                <th scope="col">Product</th>
                                <th scope="col">Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Total</th>
                                <th scope="col"><span class="sr-only">Remove</span></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($cartProducts as $p):
                                $qty = $cart[$p->id] ?? 0;
                                $lineTotal = (float)$p->price * $qty;
                                $thumb = $p->images->count() ? $p->images->first()->size(72, 72) : null;
                                ?>
                                <tr>
                                    <td class="cart-table__product-cell">
                                        <figure class="cart-table__thumb-wrap">
                                            <?php if ($thumb): ?>
                                                <img src="<?= $thumb->url ?>"
                                                     alt="<?= $p->title ?>" width="72"
                                                     height="72" loading="lazy">
                                            <?php else: ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 72 72" width="72"
                                                     height="72" aria-hidden="true" focusable="false">
                                                    <rect width="72" height="72" fill="none"/>
                                                    <path d="M26 22a10 10 0 1 1 20 0 10 10 0 0 1-20 0zM10 58l14-18 10 12 8-9 18 15H10z"
                                                          fill="currentColor" opacity="0.2"/>
                                                </svg>
                                            <?php endif; ?>
                                        </figure>
                                        <p class="cart-table__product-name">
                                            <a href="<?= $p->url ?>"><?= $p->title ?></a>
                                        </p>
                                    </td>
                                    <td class="cart-table__price-cell">$<?= number_format((float)$p->price, 2) ?></td>
                                    <td class="cart-table__qty-cell">
                                        <label for="qty-<?= $p->id ?>" class="sr-only">Quantity
                                            for <?= $p->title ?></label>
                                        <input
                                                type="number"
                                                id="qty-<?= $p->id ?>"
                                                name="qty[<?= $p->id ?>]"
                                                value="<?= $qty ?>"
                                                min="1"
                                                max="<?= (int)$p->stock ?>"
                                                class="cart-table__qty-input"
                                        >
                                    </td>
                                    <td class="cart-table__total-cell">$<?= number_format($lineTotal, 2) ?></td>
                                    <td class="cart-table__remove-cell">
                                        <?php // `form` attr submits the matching remove form above, not the update form
                                        ?>
                                        <button
                                                type="submit"
                                                form="cart-remove-<?= $p->id ?>"
                                                class="cart-table__remove-btn"
                                                aria-label="Remove <?= $p->title ?> from cart"
                                        >
                                            <svg aria-hidden="true" focusable="false" width="16" height="16"
                                                 viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="1.75"
                                                      stroke-linecap="round"/>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                            <tr class="cart-table__subtotal-row">
                                <td colspan="3" class="cart-table__subtotal-label">Subtotal</td>
                                <td class="cart-table__subtotal-value" colspan="2">
                                    $<?= number_format($subtotal, 2) ?></td>
                            </tr>
                            </tfoot>
                        </table>

                        <button type="submit" class="btn btn--outline cart-form__update-btn">Update Cart</button>
                    </form>

                <?php else: ?>

                    <p class="cart-empty">
                        Your cart is empty.
                        <a href="<?= pages()->get('/')->url ?>">Browse products</a>
                    </p>

                <?php endif; ?>

            </section>

            <aside class="cart-summary" aria-label="Order summary">
                <h2>Order Summary</h2>
                <dl class="summary-list">
                    <dt>Subtotal</dt>
                    <dd><?= $cartIsEmpty ? '&mdash;' : '$' . number_format($subtotal, 2) ?></dd>

                    <dt>Shipping</dt>
                    <dd>Calculated at checkout</dd>

                    <dt class="summary-list__total-label">Total</dt>
                    <dd class="summary-list__total-value"><?= $cartIsEmpty ? '&mdash;' : '$' . number_format($subtotal, 2) ?></dd>
                </dl>

                <a
                        href="#"
                        class="btn btn--primary btn--block"
                        <?= $cartIsEmpty ? 'aria-disabled="true" tabindex="-1"' : '' ?>
                >Proceed to Checkout</a>

                <p class="cart-secure-note">&#x1F512; Secure checkout</p>
            </aside>

        </section>

        <?php if ($suggestedProducts->count()): ?>
            <section class="cart-suggestions" aria-label="You may also like">
                <h2 class="cart-suggestions__title">You May Also Like</h2>
                <?php
                try {
                    echo wireRenderFile('partials/product-grid.php', [
                            'products' => $suggestedProducts,
                            'gridClass' => 'product-row',
                    ]);
                } catch (WireException $e) {
                    wire('log')->save('cart', $e->getMessage()); // logs to /site/assets/logs/cart.txt
                    echo '<p class="no-results">Unable to load suggestions.</p>';
                }
                ?>
            </section>
        <?php endif; ?>

    </div>
</main>