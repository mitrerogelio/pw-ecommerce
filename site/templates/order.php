<?php namespace ProcessWire;
// Displays the order confirmation page after a successful checkout.

$statusTitle = page()->order_status ? page()->order_status->title : 'Pending';
$statusSlug  = strtolower(preg_replace('/[^a-z0-9]/i', '-', $statusTitle));
?>

<main id="content">
    <div class="page-wrapper">

        <header class="order-confirm-header">
            <span class="order-confirm-icon" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="12" r="11" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M7 12.5l3.5 3.5L17 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
            <h1>Order Confirmed</h1>
            <p class="order-confirm-subtitle">
                Thank you, <?= sanitizer()->entities(page()->customer_first_name) ?>!
                A confirmation has been sent to <strong><?= sanitizer()->entities(page()->customer_email) ?></strong>.
            </p>
        </header>

        <section class="order-layout">

            <section class="order-details-main" aria-label="Order details">

                <dl class="order-meta">
                    <div class="order-meta__item">
                        <dt>Order #</dt>
                        <dd><?= sanitizer()->entities(page()->order_number) ?></dd>
                    </div>
                    <div class="order-meta__item">
                        <dt>Date</dt>
                        <dd><?= date('M j, Y', page()->created) ?></dd>
                    </div>
                    <div class="order-meta__item">
                        <dt>Status</dt>
                        <dd>
                            <span class="order-status order-status--<?= $statusSlug ?>">
                                <?= $statusTitle ?>
                            </span>
                        </dd>
                    </div>
                </dl>

                <table class="order-items-table">
                    <caption class="sr-only">Items in this order</caption>
                    <thead>
                    <tr>
                        <th scope="col">Product</th>
                        <th scope="col">Price</th>
                        <th scope="col">Qty</th>
                        <th scope="col">Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach (page()->order_items as $item): ?>
                        <tr>
                            <td><?= $item->item_name ?></td>
                            <td>$<?= number_format((float)$item->item_price, 2) ?></td>
                            <td><?= (int)$item->item_quantity ?></td>
                            <td>$<?= number_format((float)$item->item_subtotal, 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr class="order-items-table__total-row">
                        <td colspan="3">Order Total</td>
                        <td>$<?= number_format((float)page()->order_total, 2) ?></td>
                    </tr>
                    </tfoot>
                </table>

            </section>

            <aside class="order-customer-info" aria-label="Customer information">

                <section class="order-customer-section">
                    <h2>Customer</h2>
                    <address>
                        <p><?= sanitizer()->entities(page()->customer_first_name . ' ' . page()->customer_last_name) ?></p>
                        <p><a href="mailto:<?= sanitizer()->entities(page()->customer_email) ?>"><?= sanitizer()->entities(page()->customer_email) ?></a></p>
                        <p><?= sanitizer()->entities(page()->customer_phone_number) ?></p>
                    </address>
                </section>

                <section class="order-customer-section">
                    <h2>Shipping Address</h2>
                    <address>
                        <?= nl2br(sanitizer()->entities(page()->shipping_address)) ?>
                    </address>
                </section>

            </aside>

        </section>

        <div class="order-confirm-actions">
            <a href="<?= pages()->get('/')->url ?>" class="btn btn--primary">Continue Shopping</a>
        </div>

    </div>
</main>