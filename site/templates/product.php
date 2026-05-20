<?php namespace ProcessWire;

/**
 * name, description, sku, image(s), stock quantity, category
 */

?>

<main id="content">
    <p><?= page()->title ?></p>
    <p>Description: <?= page()->product_description ?></p>
    <p>SKU: <?= page()->sku ?></p>
    <p>Stock: <?= page()->stock ?></p>
    <p>Price: $<?= page()->price ?></p>
    <p>Category: <?= page()->product_category ?></p>
    <ul><?php
        foreach (page()->images as $image) {
            $thumb = $image->size(100, 100);
            echo "<a href='$image->url'><img src='$thumb->url' ></a>";
        }
        ?>
    </ul>
</main>