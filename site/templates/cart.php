<?php namespace ProcessWire;
// TODO: See Cart logic
// Developer: Add this template in ProcessWire Admin > Setup > Templates (name: cart) before using this file

// Developer: implement cart session logic here — replace $cartIsEmpty and $cartItems with real session data
$cartIsEmpty = true;

// Developer: implement cart session logic here — replace with actual array of cart item objects/arrays from session
$cartItems = [];

// Developer: implement cart session logic here — calculate subtotal from real cart session items
$cartSubtotal = 0.00;

// Developer: replace with session-aware product recommendations; currently shows 4 random products
$suggestedProducts = pages()->find("template=product, status<" . Page::statusMax . ", limit=4, sort=random");

?>

<?php // Markup Region: main content — replaces <main id="content"> in _main.php
?>
<main id="content">
    <section class="page-wrapper">

        <h1 class="page-title">Your Cart</h1>

        <section class="cart-layout">

            <!-- ================================================
                 CART ITEMS SECTION
            ================================================ -->
            <section class="cart-items" aria-label="Cart items">

                <?php if (!$cartIsEmpty): ?>

                    <table class="cart-table">
                        <thead>
                        <tr>
                            <th scope="col">Product</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        // Developer: loop over session cart items here; each row should have:
                        // product image thumbnail, name, unit price,
                        // qty input <input type="number">, line total, and a remove button.
                        // Example row structure is scaffolded below — wire up real data from session.
                        foreach ($cartItems as $item): ?>
                            <tr>
                                <td class="cart-table__product-cell">
                                    <figure class="cart-table__thumb-wrap">
                                        <?php // Developer: load product page for this cart item and render its first image thumbnail ?>
                                        <img
                                                src=""
                                                alt=""
                                                width="72"
                                                height="72"
                                                loading="lazy"
                                        >
                                    </figure>
                                    <?php // Developer: output product name linked to product detail page ?>
                                    <p class="cart-table__product-name"></p>
                                </td>
                                <td class="cart-table__price-cell">
                                    <?php // Developer: output formatted unit price from product page ?>
                                    <span></span>
                                </td>
                                <td class="cart-table__qty-cell">
                                    <?php // Developer: wire name/id/value to real cart item; form submission should update session quantity ?>
                                    <label for="qty-<?= sanitizer()->entities($item['id'] ?? '') ?>" class="sr-only">
                                        Quantity for <?= sanitizer()->entities($item['name'] ?? 'product') ?>
                                    </label>
                                    <input
                                            type="number"
                                            id="qty-<?= sanitizer()->entities($item['id'] ?? '') ?>"
                                            name="qty[<?= sanitizer()->entities($item['id'] ?? '') ?>]"
                                            value="<?= (int)($item['qty'] ?? 1) ?>"
                                            min="1"
                                            class="cart-table__qty-input"
                                            aria-label="Quantity"
                                    >
                                </td>
                                <td class="cart-table__total-cell">
                                    <?php // Developer: output formatted line total (unit price × qty) ?>
                                    <span></span>
                                </td>
                                <td class="cart-table__remove-cell">
                                    <?php // Developer: wire remove button to a form POST or JS handler that removes item from session cart ?>
                                    <button
                                            type="button"
                                            class="cart-table__remove-btn"
                                            aria-label="Remove <?= sanitizer()->entities($item['name'] ?? 'item') ?> from cart"
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
                            <?php // Developer: output formatted subtotal from session cart calculation ?>
                            <td class="cart-table__subtotal-value">$<?= number_format($cartSubtotal, 2) ?></td>
                        </tr>
                        </tfoot>
                    </table>

                <?php else: ?>

                    <p class="cart-empty">
                        Your cart is empty.
                        <?php // Developer: pages()->get('/') returns the homepage root page ?>
                        <a href="<?= pages()->get('/')->url ?>">Browse products</a>
                    </p>

                <?php endif; ?>

            </section>

            <!-- ================================================
                 ORDER SUMMARY ASIDE
            ================================================ -->
            <aside class="cart-summary" aria-label="Order summary">
                <h2>Order Summary</h2>
                <dl class="summary-list">
                    <dt>Subtotal</dt>
                    <?php // Developer: implement cart session logic here — replace em dash with formatted subtotal
                    ?>
                    <dd>&mdash;</dd>

                    <dt>Shipping</dt>
                    <dd>Calculated at checkout</dd>

                    <dt class="summary-list__total-label">Total</dt>
                    <?php // Developer: implement cart session logic here — replace em dash with formatted order total
                    ?>
                    <dd class="summary-list__total-value">&mdash;</dd>
                </dl>

                <?php // Developer: replace href with checkout page URL when checkout template exists
                ?>
                <a href="#" class="btn btn--primary btn--block">Proceed to Checkout</a>

                <p class="cart-secure-note">&#x1F512; Secure checkout</p>
            </aside>

        </section>

        <!-- ====================================================
             SUGGESTED PRODUCTS
        ==================================================== -->
        <?php if ($suggestedProducts->count()): ?>
            <section class="cart-suggestions" aria-label="You may also like">
                <h2 class="cart-suggestions__title">You May Also Like</h2>
                    <?php
                    try {
                        echo wireRenderFile('partials/product-grid.php', [
                                'products'  => $suggestedProducts,
                                'gridClass' => 'product-row',
                        ]);
                    } catch (WireException $e) {
                        log()->error($e);
                        echo '<p class="no-results">No products are available at this time.</p>';
                    }
                    ?>
            </section>
        <?php endif; ?>

    </section>
</main>
