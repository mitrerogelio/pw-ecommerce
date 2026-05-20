<?php namespace ProcessWire;

$rawQuery = input()->get()->text('q');
$query = trim(sanitizer()->selectorValue($rawQuery));
?>

<h1 id="headline">Search Results</h1>
<main id="content">
    <p>You searched for: <strong><?= sanitizer()->entities($query) ?></strong></p>

    <?php
    $selector = "template=product, title|product_description%=$query, id!=" . page()->id . ", status<" . Page::statusMax;
    $matches = pages()->find($selector);
    if ($matches->count()): ?>
        <p>Found <?= $matches->count() ?> result(s):</p>
        <ul class="search-results-list">
            <?php foreach ($matches as $match): ?>
                <li>
                    <h3><a href="<?= $match->url ?>"><?= $match->title ?></a></h3>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Sorry, no results matched your search terms. Try checking your spelling or using different keywords.</p>
    <?php endif; ?>
</main>
