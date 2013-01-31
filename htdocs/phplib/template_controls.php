<?php
require_once "Controls.php";
// FIXME - Can remove when we've added until to all graph pages
if (!isset($until)) {
    $until = null;
}
?>
<form id="controls" class='controls' action="<?= $_SERVER['PHP_SELF'] ?>">
    <div class="controls-wrapper">
        <div class="controls-inner">
            <input type="hidden" name="m" value="1"/>
            <?= Controls::buildTimeControl($time, $times, $until); ?>
<?php 
// FIXME - hacky. Just until we can get all graphs using controls to accept until parameter
// For now, just doing this on deploy graphs page
if (strpos($_SERVER['REQUEST_URI'], 'deploy.php') !== false) {
    echo Controls::buildUntilControl($time, $until, $times);
}
?>
            <?= Controls::buildShowDeploysControl($hide_deploys); ?>
        </div>
        <?php if (isset($additional_controls)) : ?>
        <div class="controls-inner">
            <?= $additional_controls ?>
        </div>
        <?php endif; ?>
        <div class="black">
            <?php if (isset($_GET['black'])): ?>
            <a href="?" title='white'><img src='images/white.jpg' width='24' height='30'/></a>
            <?php else : ?>
            <a href="?black" title='black'><img src='images/black.jpg' width='37' height='30'/></a>
            <?php endif; ?>
        </div>
    </div>
</form>
