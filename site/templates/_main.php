<?php namespace ProcessWire; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= page()->title ?> &mdash; WireCommerce</title>
    <link rel="stylesheet" href="<?= config()->urls->templates ?>styles/main.css">
</head>
<body>
<header class="site-header">
    <section class="site-header__inner">

        <a href="<?= pages()->get('/')->url ?>" class="site-logo" aria-label="WireCommerce home">
            <strong>WireCommerce</strong>
        </a>

        <nav class="primary-nav" aria-label="Primary navigation">
            <ul class="nav-list">

                <li class="nav-list__item">
                    <a href="<?= pages()->get('/')->url ?>" class="nav-list__link">Home</a>
                </li>

                <li class="nav-list__item nav-list__item--has-dropdown">
                    <button
                            class="nav-list__link nav-dropdown-trigger"
                            aria-expanded="false"
                            aria-haspopup="true"
                    >
                        Shop
                        <svg class="nav-dropdown-chevron" aria-hidden="true" focusable="false" width="12" height="12"
                             viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 4l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                  stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <div class="nav-dropdown" role="menu">
                        <ul class="nav-dropdown__list">
                            <?php
                            $categories = pages()->find("template=category-page, status<" . Page::statusMax);
                            foreach ($categories as $cat): ?>
                                <li class="nav-dropdown__item" role="none">
                                    <a href="<?= $cat->url ?>" class="nav-dropdown__link" role="menuitem">
                                        <?= $cat->title ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </li>

                <li class="nav-list__item">
                    <a href="<?= pages()->get("template=cart")->url ?>" class="nav-list__link nav-list__link--cart"
                       aria-label="Shopping cart">
                        <svg aria-hidden="true" focusable="false" width="20" height="20" viewBox="0 0 24 24" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" stroke="currentColor"
                                  stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M3 6h18" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"
                                  stroke-linejoin="round"/>
                            <path d="M16 10a4 4 0 01-8 0" stroke="currentColor" stroke-width="1.75"
                                  stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Cart
                        <span class="cart-badge" id="cart-count">0</span>
                    </a>
                </li>

            </ul>
        </nav>

        <?php
        $searchPageUrl = pages()->get("template=search")->url;
        $currentQuery = sanitizer()->entities(input()->get('q'));
        ?>
        <form
                action="<?= $searchPageUrl ?>"
                method="get"
                class="header-search"
                role="search"
        >
            <label for="header-search-input" class="sr-only">Search products</label>
            <input
                    type="search"
                    id="header-search-input"
                    name="q"
                    class="header-search__input"
                    placeholder="Search products&hellip;"
                    value="<?= $currentQuery ?>"
                    autocomplete="off"
            >
            <button type="submit" class="header-search__btn" aria-label="Submit search">
                <svg aria-hidden="true" focusable="false" width="18" height="18" viewBox="0 0 24 24" fill="none"
                     xmlns="http://www.w3.org/2000/svg">
                    <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="1.75"/>
                    <path d="M21 21l-4.35-4.35" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/>
                </svg>
            </button>
        </form>

        <button
                class="mobile-menu-toggle"
                aria-expanded="false"
                aria-controls="primary-nav"
                aria-label="Open navigation menu"
        >
            <span class="hamburger-bar"></span>
            <span class="hamburger-bar"></span>
            <span class="hamburger-bar"></span>
        </button>

    </section>
</header>

<?php if (page()->parents->count() > 0): ?>
    <nav class="breadcrumbs" aria-label="Breadcrumb">
        <ol class="breadcrumb-list">
            <li class="breadcrumb-list__item">
                <a href="<?= pages()->get('/')->url ?>" class="breadcrumb-list__link">Home</a>
            </li>
            <?php foreach (page()->parents as $parent): ?>
                <li class="breadcrumb-list__item">
                    <a href="<?= $parent->url ?>" class="breadcrumb-list__link">
                        <?= sanitizer()->entities($parent->title) ?>
                    </a>
                </li>
            <?php endforeach; ?>
            <li class="breadcrumb-list__item breadcrumb-list__item--current">
                <span aria-current="page"><?= page()->title ?></span>
            </li>
        </ol>
    </nav>
<?php endif; ?>

<main id="content">Default content</main>

<footer class="site-footer">
    <section class="site-footer__inner">

        <div class="site-footer__brand">
            <a href="<?= pages()->get('/')->url ?>" class="site-logo site-logo--footer" aria-label="WireCommerce home">
                <strong>WireCommerce</strong>
            </a>
            <p class="site-footer__tagline">The gear you need. Nothing you don't.</p>
        </div>

        <nav class="site-footer__nav" aria-label="Footer navigation">
            <ul class="footer-nav-list">
                <li class="footer-nav-list__item">
                    <?php // Developer: pages()->get('/') returns the homepage ?>
                    <a href="<?= pages()->get('/')->url ?>" class="footer-nav-list__link">Home</a>
                </li>
                <li class="footer-nav-list__item">
                    <?php // Developer: pages()->get("template=catalog") must return the catalog/shop page ?>
                    <a href="<?= pages()->get("template=catalog")->url ?>" class="footer-nav-list__link">Shop</a>
                </li>
                <li class="footer-nav-list__item">
                    <?php // Developer: pages()->get("template=search") must return the search page ?>
                    <a href="<?= pages()->get("template=search")->url ?>" class="footer-nav-list__link">Search</a>
                </li>
                <li class="footer-nav-list__item">
                    <?php // Developer: cart template must exist; see cart.php ?>
                    <a href="<?= pages()->get("template=cart")->url ?>" class="footer-nav-list__link">Cart</a>
                </li>
            </ul>
        </nav>

    </section>

    <div class="site-footer__legal">
        <small>&copy; <?= date('Y') ?> WireCommerce. All rights reserved.</small>
    </div>
</footer>

<script src="<?= config()->urls->templates ?>scripts/main.js"></script>
</body>
</html>
