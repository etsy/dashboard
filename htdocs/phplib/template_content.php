<?php

$has_ganglia = false;
$has_jenkins = false;
$has_ga = false;

if (isset($graphs)){
    foreach ((array)$graphs as $section) {
        foreach ((array)$section as $graph) {
            if ($graph['type'] == 'ganglia') {
                $has_ganglia = true;
            } elseif ($graph['type'] == 'jenkins') {
                $has_jenkins = true;
            } elseif ($graph['type'] == 'google_analytics') {
                $has_ga = true;
            }
        }
    }
}


?>
<?php if ($has_ga): ?>
   <div class="notice"><button id="authButton">Loading...</button></div>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript" src="assets/js/google_analytics_graphs.js"></script>
    <script language="javascript" type="text/javascript" src="assets/js/jquery-1.6.2.min.js"></script>
    <script language="javascript" type="text/javascript" src="assets/js/jquery.flot.js"></script>
<?php endif; ?>

<?php if ($has_ganglia && $time != '1h'): ?>
    <div class="notice">Some timeframes may cause <strong>Ganglia</strong>-generated graphs to be missing some of the most recent data, which can appear as if 1/2 of the server pool suddenly dropped out. This is a known issue. Balance this information with other graphs when determining if you just broke something!</div>
<?php endif; ?>

<?php if ($has_jenkins && $time != '1h'): ?>
    <div class="notice"><strong>Jenkins</strong> can only display graphs in three time intervals: <em>one-hour, one-day </em>and<em> one-week.</em>  Jenkins graphs displayed here are using the time interval closest to the one you selected.</div>
<?php endif; ?>

<?php if (isset($html_for_header)) { print $html_for_header; } ?>

<div id="frame">
    <?= GraphFactory::getInstance()->getDashboardSectionsHTML($graphs,
                                                              $time,
                                                              $show_deploys); ?>

    <?php if (isset($additional_html)) { print $additional_html; } ?>
</div>
