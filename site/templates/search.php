<?php namespace ProcessWire;
$rawQuery = input()->get()->text('q');
$query = trim(sanitizer()->selectorValue($rawQuery));
$queryDisplay = sanitizer()->entities($rawQuery);

$matches = null;
if ($query !== '') {
    $selector = "template=product, title|product_description%=$query, id!=" . page()->id . ", status<" . Page::statusMax;
    $matches = pages()->find($selector);
}

?>
<main id="content">
    <section class="page-wrapper">

        <header class="search-header">
            <h1 class="search-header__title">
                <?= $query !== '' ? 'Search Results' : 'Search' ?>
            </h1>

            <form class="search-page-form" role="search" method="get" action="<?= page()->url ?>">
                <label for="search-page-input" class="sr-only">Search products</label>
                <input
                        type="search"
                        id="search-page-input"
                        name="q"
                        value="<?= $queryDisplay ?>"
                        placeholder="Search products&hellip;"
                        autocomplete="off"
                        aria-label="Search products"
                >
                <button type="submit" aria-label="Submit search">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                         aria-hidden="true" focusable="false">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                </button>
            </form>

            <?php if ($query !== '' && $matches !== null): ?>
                <p class="search-meta">
                    Showing <strong><?= $matches->count() ?> result<?= $matches->count() !== 1 ? 's' : '' ?></strong>
                    for &ldquo;<em><?= $queryDisplay ?></em>&rdquo;
                </p>
            <?php elseif ($query === ''): ?>
                <p class="search-meta search-meta--empty">Enter a search term above to find products.</p>
            <?php endif; ?>
        </header>

        <section class="search-results" aria-label="Search results">

            <?php if ($query !== '' && $matches !== null && $matches->count() > 0): ?>

                <?php
                try {
                    echo wireRenderFile('partials/product-grid.php', [
                            'products' => $matches
                    ]);
                } catch (WireException $e) {
                    wireLog()->error($e->getMessage());
                    echo '<p class="no-results">No products are available at this time.</p>';
                }
                ?>
            <?php endif ?>
        </section>

    </section>
</main>
