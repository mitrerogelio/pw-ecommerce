<?php namespace ProcessWire;


?>

<main id="content">
    <p>Catalog goes here</p>
    <section>
        <?php foreach (pages()->find("template=product") as $product)
            echo "
        <article>
            <h3>$product->title</h3>
            <p>$product->product_description</p>
        </article>
        "; ?>
    </section>

</main>