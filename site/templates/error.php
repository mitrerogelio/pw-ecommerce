<?php namespace ProcessWire;

?>

<main id="content">
    <section class="error-page page-wrapper">
        <p class="error-page__code">404</p>
        <h1 class="error-page__title">Page Not Found</h1>
        <p class="error-page__message">The page you're looking for doesn't exist or may have been moved.</p>
        <a href="<?= pages()->get('/')->url ?>" class="btn btn--primary">Back to Home</a>
    </section>
</main>
