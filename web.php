<?php

require_once dirname(__FILE__) . '/lib/bootstrap.php';

$times = Dashboard::getTimes();

$time = !empty($_GET['time']) ? $_GET['time'] : "1h";
$hide_deploys = !empty($_GET['hide_deploys']) ? true : false;
$show_deploys = (!$hide_deploys);

?>
<!DOCTYPE html>
<html>
<head>
<title>Deploy Dashboard</title>
<link rel="stylesheet" type="text/css" href="css/screen.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
<script src="js/dashboard.js"></script>
</head>
<body id="deploy" class="dashboard">

<div id="status"></div>

<form id="controls" action="<?= $_SERVER['PHP_SELF'] ?>">
    <select name="time">
        <? foreach (($times) as $key => $value) { ?>
            <option value="<?= $key ?>" <? if ($key == $time) { echo "selected"; } ?> ><?= $value ?></option>
        <? } ?>
    </select>
</form>

<h1>Web Cluster (<?php echo Dashboard::displayTime($time) ?>)</h1>

<?
$tsd = new Tsd($time);
$tsd->addMetric('avg:1m-avg:rate:proc.stat.cpu{cluster=web,type=total}');
echo $tsd->getDashboardHTML(500, 250);
?>

<?
$tsd = new Tsd($time);
$tsd->addMetric('avg:1m-avg:proc.loadavg.1min{cluster=web}');
echo $tsd->getDashboardHTML(500, 250);
?>

<?
$tsd = new Tsd($time);
$tsd->addMetric('sum:1m-avg:rate:apache.stats.served.kbytes{cluster=web}');
echo $tsd->getDashboardHTML(500, 250);
?>

<h1>Sales Page Hits and Response Times (<?php echo Dashboard::displayTime($time) ?>)</h1>

<?
$tsd = new Tsd($time);
$tsd->addMetric('sum:store.latency.hits{page=sales}');
echo $tsd->getDashboardHTML(500, 250);
?>

<?
$tsd = new Tsd($time);
$tsd->addMetric('avg:10m-avg:store.latency.percentile_80{page=sales}');
echo $tsd->getDashboardHTML(500, 250);
?>


</body>
</html>
