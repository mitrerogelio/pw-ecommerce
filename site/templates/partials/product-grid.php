<?php namespace ProcessWire;

// Pass gridClass='product-row' for a compact 2→4 col layout; defaults to 'product-grid' (1→2→4 col).
$gridClass = $gridClass ?? 'product-grid';

if (empty($products)) {
    return;
}

?>

<ul class="<?= $gridClass ?>" role="list">
    <?php foreach ($products as $product): ?>
        <li>
            <article class="product-card">
                <a href="<?= $product->url ?>" class="product-card__link"
                   aria-label="<?= sanitizer()->entities($product->title) ?>">
                    <figure class="product-card__image-wrap">
                        <?php // Developer: Check for product images and render thumbnail or placeholder ?>
                        <?php if ($product->images->count()): ?>
                            <?php // Developer: Resize first image to 400x300 for card thumbnail ?>
                            <?php $thumb = $product->images->first()->size(400, 300); ?>
                            <img
                                    src="<?= $thumb->url ?>"
                                    alt="<?= sanitizer()->entities($product->title) ?>"
                                    loading="lazy"
                                    width="400"
                                    height="300"
                            >
                        <?php else: ?>
                            <svg class="product-card__placeholder-icon"
                                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 60"
                                 aria-hidden="true" focusable="false">
                                <rect width="80" height="60" fill="none"/>
                                <path d="M32 20a8 8 0 1 1 16 0 8 8 0 0 1-16 0zm-20 28 12-16 8 10 6-7 14 13H12z"
                                      fill="currentColor" opacity="0.25"/>
                            </svg>
                        <?php endif; ?>
                    </figure>
                </a>
                <div class="product-card__body">
                    <?php // Developer: Output category badge if product_category is set ?>
                    <?php if ($product->product_category->id): ?>
                        <span class="product-card__category">
                                    <?= sanitizer()->entities($product->product_category->title) ?>
                                </span>
                    <?php endif; ?>
                    <h3 class="product-card__name">
                        <a href="<?= $product->url ?>">
                            <?= sanitizer()->entities($product->title) ?>
                        </a>
                    </h3>
                    <p class="product-card__price">$<?= number_format($product->price, 2) ?></p>
                </div>
            </article>
        </li>
    <?php endforeach; ?>
</ul>
