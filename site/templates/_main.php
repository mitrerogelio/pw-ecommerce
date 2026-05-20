<?php namespace ProcessWire;

// Optional main output file, called after rendering page’s template file. 
// This is defined by $config->appendTemplateFile in /site/config.php, and
// is typically used to define and output markup common among most pages.

/** @var Page $page */
/** @var Pages $pages */
/** @var Config $config */

$home = $pages->get('/'); /** @var HomePage $home */

?>

<!DOCTYPE html>
<html lang="en">
<head id="html-head">
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title><?php echo $page->title ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo $config->urls->templates; ?>styles/main.css"/>
    <script src="<?php echo $config->urls->templates; ?>scripts/main.js"></script>
</head>
<body id="html-body">

<nav id="topnav">
    <?php echo $home->and($home->children)->implode(" / ", "<a href='{url}'>{title}</a>"); ?>
</nav>

<hr/>

<header>
    <h1 id="headline">
        <?php if ($page->parents->count()): // breadcrumbs ?>
            <?php echo $page->parents->implode(" &gt; ", "<a href='{url}'>{title}</a>"); ?> &gt;
        <?php endif; ?>
        <?php echo $page->title; // headline
        ?>
    </h1>
    <form action="<?= pages()->get('template=search')->url ?>" method="get" class="search-form">
        <input type="text" name="q" placeholder="Search catalog"
               value="<?= sanitizer()->entities(input()->get('q')) ?>">
        <button type="submit">Search</button>
    </form>
</header>

<main id="content">
    Default content
</main>

<?php if ($page->hasChildren): ?>
    <nav>
        <ul>
            <?php echo $page->children->each("<li><a href='{url}'>{title}</a></li>"); ?>
        </ul>
    </nav>
<?php endif; ?>

</body>
</html>