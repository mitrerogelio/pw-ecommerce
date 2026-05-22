<?php namespace ProcessWire;
// Lists all orders for authenticated users, sorted by newest first.

$orders = pages()->find('template=order, include=all, sort=-created');
?>

<main id="content">
    <div class="page-wrapper">

        <h1 class="page-title">Orders</h1>

        <?php if ($orders->count()): ?>
            <table class="orders-table">
                <thead>
                <tr>
                    <th scope="col">Order</th>
                    <th scope="col">Date</th>
                    <th scope="col">Customer</th>
                    <th scope="col">Total</th>
                    <th scope="col">Status</th>
                    <th scope="col"><span class="sr-only">View</span></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($orders as $o):
                    $statusTitle = $o->order_status ? $o->order_status->title : 'Pending';
                    $statusSlug  = strtolower(preg_replace('/[^a-z0-9]/i', '-', $statusTitle));
                    ?>
                    <tr>
                        <td class="orders-table__order-col">
                            <a href="<?= $o->url ?>"><?= $o->title ?></a>
                        </td>
                        <td><?= date('M j, Y', $o->created) ?></td>
                        <td><?= sanitizer()->entities($o->customer_first_name . ' ' . $o->customer_last_name) ?></td>
                        <td>$<?= number_format((float)$o->order_total, 2) ?></td>
                        <td>
                            <span class="order-status order-status--<?= $statusSlug ?>">
                                <?= $statusTitle ?>
                            </span>
                        </td>
                        <td class="orders-table__view-col">
                            <a href="<?= $o->url ?>" class="orders-table__view-link" aria-label="View <?= $o->title ?>">
                                View
                                <svg aria-hidden="true" focusable="false" width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="orders-empty">No orders have been placed yet.</p>
        <?php endif; ?>

    </div>
</main>