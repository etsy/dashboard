<?php $i = 0;
if (!isset($funnels)) {
    $funnels = array();
}
foreach($funnels as $title => $inner) { ?>
<h1 id="<?=slugify($title)?>"><a href="#<?=slugify($title)?>"><?=$title?></a></h1>

<div class="clear section">
<?php foreach ($inner as $inner_title => $funnel) {
    $steps = array();
    $i++;
    $j = 0;
    foreach ($funnel['steps'] as $key => $step) {
        $tuple = array($j++);
        $g = new Graph_Graphlot($time);
        $g->addMetric($funnel['prefix'] . ".$key");
        $step_data = $g->getData(false);
        $tuple[] = Graph_Graphlot::sum($step_data[0]["data"]);
        $steps[] = $tuple;
    }
    $data = array(array("label" => $inner_title, 'data' => $steps));
    $data = json_encode($data);
    $ticks = Graph_Graphlot::zip(range(0, count($steps) - 1), array_values($funnel['steps']));
    $options = json_encode(array(
        "xaxis" => array(
            'ticks' => $ticks,
        ),
    ));
    $slug = "";
    $slug = slugify($title) . '_' . slugify($inner_title);
    $container_id = "f-container-{$i}";
    $container_selector = "#{$container_id}";
?>

    <div id="<?=$container_id?>" class="container<?if(isset($funnel['wide']) && $funnel['wide']):?> container-wide<?endif?>">
        <h2 id="<?=$slug?>"><a href="#<?=$slug?>"><?=$inner_title?></a></h2>
        <div class="graph<?php if(isset($funnel['tall']) && $funnel['tall']):?> graph-tall<?endif?>"></div>
        <div class="legend"></div>
    </div>
    <script>
        var o;
        var o = getDefaultFunnelOptions();
        var e = $("<?=$container_selector ?> .graph");

        o.legend.container = "<?=$container_selector ?> .legend";
        <?php if (isset($options)):?>
        o = $.extend(true, {}, o, <?=$options?>);
        <?php endif ?>
        e.bind("plothover", showFunnelTip);
        $.plot(e, <?=$data?>, o);
    </script>

<?php } // end foreach $inner ?>

</div>

<?php } // end foreach $graphs ?>
