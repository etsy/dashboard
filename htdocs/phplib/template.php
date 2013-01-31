<?php
    require_once "template_init.inc.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?></title>
    <link rel="stylesheet" type="text/css" href="assets/css/screen.css">

    <?php if (isset($_GET['black'])) : ?>
    <link rel="stylesheet" type="text/css" href="assets/css/screen-black.css">
    <?php endif; ?>

    <?php if (isset($page_css_imports)) : ?>
        <?php foreach (array_unique($page_css_imports) as $page_css_import) : ?>
            <link rel="stylesheet" type="text/css" href="<?= $page_css_import ?>">
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (isset($jquery_version)) : ?>
<<<<<<< HEAD
    <script type="text/javascript" src="assets/js/jquery-<?= $jquery_version ?>.js"></script>
    <?php else : ?>
    <script type="text/javascript" src="assets/js/jquery-1.6.2.min.js"></script>
    <?php endif; ?>
=======
    <script type="text/javascript" src="assets/js/jquery-<?= $jquery_version ?>.js"></script>
    <? else : ?>
    <script type="text/javascript" src="assets/js/jquery-1.6.2.min.js"></script>
    <? endif; ?>
>>>>>>> p/makeerehomeable

    <?php if (isset($page_js_imports)) : ?>
        <?php foreach (array_unique($page_js_imports) as $page_js_import) : ?>
            <script type="text/javascript" src="<?= $page_js_import ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php 
    /**
     * When viewing time windows of 1 day or more, use a longer time out
     * for the XHR that refreshes images.
     *
     * Why?  Well, it turns out that you can effectively DDoS a Graphite
     * server by rapidly requesting a lot of graphs that span a long time
     * interval.  This is especially so if said graphs contain many metrics.
     */

    if (preg_match('/^\d+[^h]$/', $time)) {
        echo '        <script type="text/javascript">var refresh_timeout = 3600 * 1000;</script>';
        echo "\n";
    }
    ?>
    <script type="text/javascript" src="assets/js/dashboard.js"></script>
</head>
<body id="<?= $namespace ?>" class="dashboard">
<div id="status"></div>

<?php include('template_tabs.php'); ?>
<?php include('template_controls.php'); ?>
<?php include("template_content.php"); ?>

</body>
</html>
