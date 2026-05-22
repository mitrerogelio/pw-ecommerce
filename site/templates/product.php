<?php namespace ProcessWire;

?>

<main id="content">
    <article class="product-detail page-wrapper">
        <div class="product-detail__layout">

            <section class="product-detail__gallery" aria-label="Product images">
                <?php
                $images = page()->images;
                ?>

                <?php if ($images->count() > 0): ?>

                    <?php
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
                        <ul class="gallery__thumbs" aria-label="Product image thumbnails">
                            <?php
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
                        <svg width="120" height="90" viewBox="0 0 120 90" fill="none" xmlns="http://www.w3.org/2000/svg"
                             aria-hidden="true">
                            <rect x="1" y="1" width="118" height="88" rx="4" stroke="currentColor" stroke-width="1.5"
                                  fill="none"/>
                            <circle cx="38" cy="32" r="10" stroke="currentColor" stroke-width="1.5" fill="none"/>
                            <polyline points="8,72 40,44 60,60 82,38 112,68" stroke="currentColor" stroke-width="1.5"
                                      fill="none" stroke-linejoin="round"/>
                        </svg>
                    </figure>

                <?php endif; ?>
            </section>

            <section class="product-detail__info">

                <?php
                $category = page()->product_category;
                ?>
                <?php if ($category && $category->id): ?>
                    <p class="product-detail__category">
                        <a href="<?= $category->url ?>"><?= $category->title ?></a>
                    </p>
                <?php endif; ?>

                <h1 class="product-detail__title"><?= page()->title ?></h1>
                <p class="product-detail__sku">SKU: <code><?= page()->sku ?></code></p>

                <p class="product-detail__price">$<?= number_format((float)page()->price, 2) ?></p>

                <?php
                if ((int)page()->stock > 0):
                    ?>
                    <p class="product-detail__stock product-detail__stock--in">
                        In Stock (<?= (int)page()->stock ?> available)
                    </p>
                <?php else: ?>
                    <p class="product-detail__stock product-detail__stock--out">Out of Stock</p>
                <?php endif; ?>

                <article class="product-detail__description">
                    <?= page()->product_description ?>
                </article>

                <form class="product-detail__atc-form" method="post" action="<?= pages()->get('template=cart')->url ?>">
                    <?= session()->CSRF->renderInput() ?>

                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="product_id" value="<?= page()->id ?>">

                    <label for="qty" class="sr-only">Quantity</label>
                    <input
                            type="number"
                            id="qty"
                            name="qty"
                            value="1"
                            min="1"
                            max="<?= (int)page()->stock ?>"
                            class="atc-qty-input"
                            aria-label="Quantity"
                    >

                    <button
                            type="submit"
                            class="btn btn--primary btn--atc"
                            <?= (int)page()->stock < 1 ? 'disabled aria-disabled="true"' : '' ?>
                    >
                        Add to Cart
                    </button>
                </form>
            </section>
        </div>
    </article>
</main>
