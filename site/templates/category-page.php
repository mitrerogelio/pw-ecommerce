<?php namespace ProcessWire;
// product_category field is a page reference (single):
$products = pages()->find("template=product, product_category=" . page()->id . ", status<" . Page::statusMax);

?>

<main id="content">
    <section class="page-wrapper">

        <header class="category-header">
            <h1 class="category-header__title"><?= page()->title ?></h1>
            <h3 class="category-header__count"><?= $products->count() ?> products</h3>
        </header>

        <section class="catalog-section">
            <h2 class="sr-only">All <?= page()->title ?> Products</h2>

            <?php
            try {
                echo wireRenderFile('partials/product-grid.php', [
                        'products' => $products
                ]);
            } catch (WireException $e) {
                wireLog()->error($e->getMessage());
                echo '<p class="no-results">No products are available at this time.</p>';
            }
            ?>
        </section>

    </section>
</main>
