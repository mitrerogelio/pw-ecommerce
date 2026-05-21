<?php namespace ProcessWire;
// Developer: Add this template in ProcessWire Admin > Setup > Templates if not already registered

// Markup Region: product detail page
?>

<main id="content">
<article class="product-detail page-wrapper">
    <div class="product-detail__layout">

        <?php /* ---- Gallery ---- */ ?>
        <section class="product-detail__gallery" aria-label="Product images">
            <?php
            // Developer: Retrieve the images field from the current product page
            $images = page()->images;
            ?>

            <?php if ($images->count() > 0): ?>

                <?php
                // Developer: Size the first image for the main gallery slot (800×600)
                $mainImg = $images->first()->size(800, 600);
                ?>
                <figure class="gallery__main">
                    <img
                        src="<?= $mainImg->url ?>"
                        alt="<?= sanitizer()->entities(page()->title) ?>"
                        width="800"
                        height="600"
                        loading="lazy"
                        id="gallery-main-img"
                    >
                </figure>

                <?php if ($images->count() > 1): ?>
                    <?php // Developer: wire gallery__thumb-btn clicks in main.js to swap the main image src ?>
                    <ul class="gallery__thumbs" aria-label="Product image thumbnails">
                        <?php
                        // Developer: Iterate all product images and render thumbnails at 120×90
                        foreach ($images as $img):
                            $thumb = $img->size(120, 90);
                        ?>
                            <li>
                                <button
                                    class="gallery__thumb-btn"
                                    type="button"
                                    aria-label="View image: <?= sanitizer()->entities($img->description ?: page()->title) ?>"
                                    data-full-src="<?= $img->size(800, 600)->url ?>"
                                    data-full-alt="<?= sanitizer()->entities($img->description ?: page()->title) ?>"
                                >
                                    <img
                                        src="<?= $thumb->url ?>"
                                        alt="<?= sanitizer()->entities($img->description ?: page()->title) ?>"
                                        width="120"
                                        height="90"
                                        loading="lazy"
                                    >
                                </button>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

            <?php else: ?>

                <figure class="gallery__placeholder" aria-label="No product image available">
                    <svg width="120" height="90" viewBox="0 0 120 90" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <rect x="1" y="1" width="118" height="88" rx="4" stroke="currentColor" stroke-width="1.5" fill="none"/>
                        <circle cx="38" cy="32" r="10" stroke="currentColor" stroke-width="1.5" fill="none"/>
                        <polyline points="8,72 40,44 60,60 82,38 112,68" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linejoin="round"/>
                    </svg>
                </figure>

            <?php endif; ?>
        </section>

        <?php /* ---- Info ---- */ ?>
        <section class="product-detail__info">

            <?php
            // Developer: Retrieve the product_category page reference field
            $category = page()->product_category;
            ?>
            <?php if ($category && $category->id): ?>
                <p class="product-detail__category">
                    <?php // Developer: Output a linked category name using the referenced page's url and title ?>
                    <a href="<?= $category->url ?>"><?= sanitizer()->entities($category->title) ?></a>
                </p>
            <?php endif; ?>

            <?php // Developer: Output the product title as the primary heading ?>
            <h1 class="product-detail__title"><?= sanitizer()->entities(page()->title) ?></h1>

            <?php // Developer: Output the SKU text field ?>
            <p class="product-detail__sku">SKU: <code><?= sanitizer()->entities(page()->sku) ?></code></p>

            <?php // Developer: Output the price decimal field, formatted to 2 decimal places ?>
            <p class="product-detail__price">$<?= number_format((float) page()->price, 2) ?></p>

            <?php
            // Developer: Check the stock integer field to determine availability status
            if ((int) page()->stock > 0):
            ?>
                <p class="product-detail__stock product-detail__stock--in">
                    In Stock (<?= (int) page()->stock ?> available)
                </p>
            <?php else: ?>
                <p class="product-detail__stock product-detail__stock--out">Out of Stock</p>
            <?php endif; ?>

            <?php // Developer: sanitize product_description output if it may contain user-supplied HTML ?>
            <div class="product-detail__description">
                <?= page()->product_description ?>
            </div>

            <?php // Developer: implement add-to-cart POST handler; currently logs to JS console only ?>
            <form class="product-detail__atc-form" method="post" action="#">
                <?php // Developer: add CSRF token hidden input when implementing server-side handler ?>

                <label for="qty" class="sr-only">Quantity</label>
                <input
                    type="number"
                    id="qty"
                    name="qty"
                    value="1"
                    min="1"
                    max="<?= (int) page()->stock ?>"
                    class="atc-qty-input"
                    aria-label="Quantity"
                >
                <?php // Developer: Disable the button and reflect out-of-stock state when stock is 0 ?>
                <button
                    type="submit"
                    class="btn btn--primary btn--atc"
                    <?= (int) page()->stock < 1 ? 'disabled aria-disabled="true"' : '' ?>
                >
                    Add to Cart
                </button>
            </form>

        </section>

    </div>
</article>
</main>
