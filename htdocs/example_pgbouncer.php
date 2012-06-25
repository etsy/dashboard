<?php

require_once 'phplib/Dashboard.php';

$times = Dashboard::getTimes();

$time = !empty($_GET['time']) ? $_GET['time'] : "1h";

?>
<!DOCTYPE html>
<html>
<head>
<title>Example PgBouncer Dashboard</title>
<link rel="stylesheet" type="text/css" href="assets/css/screen.css">
<script src="assets/js/jquery-1.6.2.min.js"></script>
<script src="assets/js/dashboard.js"></script>
</head>
<body id="postgresql" class="dashboard">

<div id="status"></div>

<?
$tabs = Dashboard::$DB_TABS;
$tab_url = Dashboard::getTabUrl(__FILE__);
include 'phplib/template_tabs.php';
?>

<form id="controls" action="<?= $_SERVER['PHP_SELF'] ?>">
    <?= Controls::buildTimeControl($time, $times); ?>
</form>

<div class='section'>
<?
$activity_arr = array('pgb_v2_avg_querytime', 'pgb_v2_avg_req', 'pgb_v2_cl_active', 'pgb_v2_cl_waiting', 'pgb_v2_sv_active', 'pgb_v2_sv_idle', 'pgb_v2_sv_used');
foreach ($pgbouncer_cluster_arr as &$cluster) {
    echo "<h2>{$cluster["name"]} pgBouncer pool status (" . Dashboard::displayTime($time) . ")</h2>";
    foreach ($activity_arr as &$activity) {
        $g = new Graph_Ganglia($time);
        $g->addMetric("{$cluster["name"]}", "{$cluster["machines"]}", "{$activity}");
        echo $g->getDashboardHTML('medium', GraphConstants::THREE_GRAPH_WIDTH);
    }
}

?>    
</div>
</body>
</html>
