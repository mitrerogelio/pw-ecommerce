<?php namespace ProcessWire;
$categories = pages()->find("template=category-page, status<" . Page::statusMax);
$products = pages()->find("template=product, limit=15 , status<" . Page::statusMax);

?>

<main id="content">
    <section class="page-wrapper">
        <?php if ($categories->count()): ?>
            <nav class="category-strip" aria-label="Browse by category">
                <ul class="category-strip__list">
                    <?php foreach ($categories as $cat): ?>
                        <li class="category-strip__item">
                            <a href="<?= $cat->url ?>" class="category-strip__link"><?= $cat->title ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        <?php endif; ?>

        <section class="catalog-section">
            <header class="catalog-section__header">
                <h2 class="catalog-section__title">All Products</h2>
                <h3 class="catalog-section__count"><?= $products->count() ?> products</h3>
            </header>
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
