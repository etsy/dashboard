<?php
require_once 'Dashboard.php';

$show_trends = !empty($_GET['trendline']) ? (bool)$_GET['trendline'] : false;
$times = Dashboard::getAnalyticsTimes();

function slugify($str) {
    return str_replace(' ', '_', $str);
}

?><!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">

        <title>Dashboard <?php echo (!empty($page_title) ? ": $page_title" :"");?></title>
        <link rel="stylesheet" type="text/css" href="assets/css/screen.css">
        <link rel="stylesheet" type="text/css" href="assets/css/flot.css">
        <script type="text/javascript" src="assets/js/jquery-1.6.2.min.js"></script>
        <script type="text/javascript" src="assets/js/jquery.flot.js"></script>
        <script type="text/javascript" src="assets/js/flot/jquery.flot.stack.js"></script>
        <script type="text/javascript" src="assets/js/jquery.flot.trendline.js"></script>
        <script type="text/javascript" src="assets/js/flot.js"></script>
        <script type="text/javascript" src="assets/js/dashboard.js"></script>
        <?php
            if (isset($additional_head)){
                echo($additional_head);
            }
        ?>
    </head>
    <body>
    <?php include 'template_tabs.php'; ?>
<?php
if (!isset($no_controls)) {
?>
    <form id="controls" action="<?= $_SERVER['PHP_SELF'] ?>">
        <?php if (!isset($hide_date_selector)) : ?>
        <?= Controls::buildTimeControl($time, $times); ?>
        <?php endif; ?>
        <?php if (!isset($no_trendline_control)) : ?>
        <input type="checkbox" id="trendlines" name="trendline" value="show" <?php if ($show_trends) { echo 'checked'; } ?>/>
        <label for="trendlines">Show Trendlines</label>
        <?php endif; ?>
        <?php
            if (isset($extra_controls)){
                echo($extra_controls);
            }
        ?>
    </form>
<?php } ?>

    <?php $i = 0;
    if (!isset($graphs)) {
        $graphs = array();
    }
    foreach($graphs as $title => $inner) { ?>
	<h1 id="<?=slugify($title)?>"><a href="#<?=slugify($title)?>"><?=$title?></a></h1>

    <div class="clear section">
    <?php foreach ($inner as $index => $graph) {
            if (empty($graph)) {
                print '<div class="container"></div>';
                continue;
            }
            if ($index === 'description') {
                print "<p class=\"graph-description\">$graph</p>\n";
                continue;
            }
            $i += 1;
            $g = new Graph_Graphlot($time);
            if (isset($start_time)){
                $g->from = $start_time;
            }
            if (isset($end_time)){
                $g->until = $end_time;
            }
            $options = (object)array();
            foreach ($graph['metrics'] as $m) {
                call_user_func_array(array($g, 'addMetric'), $m);
                // $g->addMetric($m[0], $m[1]);
            }
            if (isset($graph['coarsen'])) {
                $g->coarsen($graph['coarsen']);
            }
            if (isset($graph['options']) && $graph['options']) {
                $options = $graph['options'];
            }
            $options = json_encode($options);
            $data = $g->getData(true);
            $slug = slugify($title) . '_' . slugify($graph['title']);
    ?>
    <div id="g-container-<?=$i?>" class="container<?php if(isset($graph['wide']) && $graph['wide']):?> container-wide<?php endif?>">
        <h2 id="<?=$slug?>"><a href="#<?=$slug?>"><?=$graph["title"]?></a></h2>
        <div class="graph<?php if(isset($graph['tall']) && $graph['tall']):?> graph-tall<?php endif?>"></div>
        <div class="legend"></div>
        <div class="graphite-link"><a href="<?=$g->getImageURL(700,500)?>">View in Graphite</a></div>
    </div>
    <script>
        var o;
        <?php
        $container_id = "#g-container-{$i}";
        if (defined('GRAPH_TYPE') && GRAPH_TYPE == "stack_bar"){?>
            o = getStackGraphOptions();
        <?php }else{ ?>
            o = getDefaultOptions();
        <?php } ?>
        var e = $("<?=$container_id ?> .graph");
        o.legend.container = "<?=$container_id ?> .legend";
        o = $.extend(true, {}, o, <?=$options?>);
        <?php if ($show_trends) { ?>
        o.trendline.show = true;
        <?php } ?>
        e.bind("plothover", showTip);
        var data = <?=$data?>;
        if (typeof(dataCallback) === 'function'){
            data = dataCallback(data, '<?=$container_id ?>');
        }
        $.plot(e, data, o);
    </script>
    <?php } // end foreach $inner ?>
    </div>
    <?php } // end foreach $graphs ?>
    <?php
    if (isset($extra_html)) {
        echo $extra_html;
    }
    ?>
    <?php 
    if (isset($funnels)) {
        include 'flot_funnel_tpl.php';
	} ?>
    <div id="tooltip">
        <div id="tt-title"></div>
        <div id="tt-count"></div>
        <div id="tt-date"></div>
    </div>
    </body>
</html>

