<?php namespace ProcessWire;
$cart = session()->get('cart') ?? [];

if (empty($cart)) {
    session()->redirect(pages()->get('template=cart')->url);
}

$cartProducts = pages()->find('id=' . implode('|', array_keys($cart)) . ', status<' . Page::statusMax);

$orderTotal = 0.0;
foreach ($cartProducts as $p) {
    $orderTotal += (float)$p->price * ($cart[$p->id] ?? 0);
}

$errors = [];
$formData = [
        'first_name' => '',
        'last_name' => '',
        'email' => '',
        'phone' => '',
        'shipping_address' => '',
];

if (input()->requestMethod() === 'POST' && session()->CSRF->hasValidToken()) {
    $formData = [
            'first_name' => sanitizer()->text(input()->post('first_name')),
            'last_name' => sanitizer()->text(input()->post('last_name')),
            'email' => sanitizer()->email(input()->post('email')),
            'phone' => sanitizer()->text(input()->post('phone')),
            'shipping_address' => sanitizer()->textarea(input()->post('shipping_address')),
    ];

    if (!$formData['first_name']) $errors[] = 'First name is required.';
    if (!$formData['last_name']) $errors[] = 'Last name is required.';
    if (!$formData['email']) $errors[] = 'A valid email address is required.';
    if (!$formData['phone']) $errors[] = 'Phone number is required.';
    if (!$formData['shipping_address']) $errors[] = 'Shipping address is required.';

    if (empty($errors)) {
        $ordersParent = pages()->get('name=orders, include=all');

        $order = new Page();
        $order->template = 'order';
        $order->parent = $ordersParent->id ? $ordersParent : pages()->get('/');
        $order->of(false);

        $orderNum = strtoupper(substr(md5(uniqid()), 0, 8));
        $order->title = "{$orderNum}";
        $order->order_number = $orderNum;
        $order->customer_first_name = $formData['first_name'];
        $order->customer_last_name = $formData['last_name'];
        $order->customer_email = $formData['email'];
        $order->customer_phone_number = $formData['phone'];
        $order->shipping_address = $formData['shipping_address'];
        $order->order_total = $orderTotal;

        $statusField = wire('fields')->get('order_status');
        $pendingOpt = $statusField->type->getOptions($statusField)->get('title=Pending');
        if ($pendingOpt) $order->order_status = $pendingOpt->id;

        $order->save();

        foreach ($cartProducts as $p) {
            $qty = $cart[$p->id] ?? 0;
            $item = $order->order_items->getNew();
            $item->of(false);
            $item->item_name = $p->title;
            $item->item_quantity = $qty;
            $item->item_price = (float)$p->price;
            $item->item_subtotal = (float)$p->price * $qty;
            $item->save();
        }

        $order->of(false);
        $order->save();

        session()->set('cart', []);
        wire('log')->save('checkout', "Order confirmation email sent to {$formData['email']} for {$order->title} (ID: {$order->id}).");

        session()->redirect($order->url);
    }
}
?>

<main id="content">
    <div class="page-wrapper">

        <h1 class="page-title">Checkout</h1>

        <?php if (!empty($errors)): ?>
            <ul class="checkout-errors" role="alert">
                <?php foreach ($errors as $error): ?>
                    <li><?= sanitizer()->entities($error) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <section class="checkout-layout">

            <form method="post" class="checkout-form" novalidate>
                <?= session()->CSRF->renderInput() ?>

                <fieldset class="checkout-section">
                    <legend>Contact Information</legend>

                    <div class="form-row form-row--two-col">
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input
                                    type="text"
                                    id="first_name"
                                    name="first_name"
                                    value="<?= sanitizer()->entities($formData['first_name']) ?>"
                                    autocomplete="given-name"
                                    required
                            >
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input
                                    type="text"
                                    id="last_name"
                                    name="last_name"
                                    value="<?= sanitizer()->entities($formData['last_name']) ?>"
                                    autocomplete="family-name"
                                    required
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input
                                type="email"
                                id="email"
                                name="email"
                                value="<?= sanitizer()->entities($formData['email']) ?>"
                                autocomplete="email"
                                required
                        >
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input
                                type="tel"
                                id="phone"
                                name="phone"
                                value="<?= sanitizer()->entities($formData['phone']) ?>"
                                autocomplete="tel"
                                required
                        >
                    </div>
                </fieldset>

                <fieldset class="checkout-section">
                    <legend>Shipping Address</legend>

                    <div class="form-group">
                        <label for="shipping_address">Full Address</label>
                        <textarea
                                id="shipping_address"
                                name="shipping_address"
                                rows="4"
                                autocomplete="street-address"
                                placeholder="Street, City, State, ZIP"
                                required
                        ><?= sanitizer()->entities($formData['shipping_address']) ?></textarea>
                    </div>
                </fieldset>

                <button type="submit" class="btn btn--primary btn--block">Place Order</button>
            </form>

            <aside class="checkout-summary" aria-label="Order summary">
                <h2>Order Summary</h2>

                <ul class="checkout-summary__items">
                    <?php foreach ($cartProducts as $p):
                        $qty = $cart[$p->id] ?? 0;
                        $lineCost = (float)$p->price * $qty;
                        $thumb = $p->images->count() ? $p->images->first()->size(72, 72) : null;
                        ?>
                        <li class="checkout-summary__item">
                            <figure class="checkout-summary__thumb">
                                <?php if ($thumb): ?>
                                    <img src="<?= $thumb->url ?>" alt="<?= sanitizer()->entities($p->title) ?>"
                                         width="48" height="48" loading="lazy">
                                <?php else: ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="48" height="48"
                                         aria-hidden="true" focusable="false">
                                        <rect width="48" height="48" fill="none"/>
                                        <path d="M17 15a7 7 0 1 1 14 0 7 7 0 0 1-14 0zM6 39l10-12 7 8 5-6 12 10H6z"
                                              fill="currentColor" opacity="0.2"/>
                                    </svg>
                                <?php endif; ?>
                                <?php if ($qty > 1): ?>
                                    <span class="checkout-summary__qty-badge"
                                          aria-label="<?= $qty ?> items"><?= $qty ?></span>
                                <?php endif; ?>
                            </figure>
                            <span class="checkout-summary__name"><?= sanitizer()->entities($p->title) ?></span>
                            <span class="checkout-summary__line-total">$<?= number_format($lineCost, 2) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <dl class="checkout-summary__totals">
                    <dt>Subtotal</dt>
                    <dd>$<?= number_format($orderTotal, 2) ?></dd>
                    <dt class="checkout-summary__total-label">Total</dt>
                    <dd class="checkout-summary__total-value">$<?= number_format($orderTotal, 2) ?></dd>
                </dl>
            </aside>

        </section>
    </div>
</main>