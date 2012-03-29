<?php

require_once dirname(dirname(__FILE__)) . '/lib/bootstrap.php';

$times = Dashboard::getTimes();

$time = !empty($_GET['time']) ? $_GET['time'] : "1h";
$hide_deploys = !empty($_GET['hide_deploys']) ? true : false;
$show_deploys = (!$hide_deploys);

?>
<!DOCTYPE html>
<html>
<head>
<title>Database Dashboard</title>
<link rel="stylesheet" type="text/css" href="css/screen.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
<script src="js/dashboard.js"></script>
</head>
<body id="database" class="dashboard">

<div id="status"></div>

<form id="controls" action="<?= $_SERVER['PHP_SELF'] ?>">
    <select name="time">
        <? foreach (($times) as $key => $value) { ?>
            <option value="<?= $key ?>" <? if ($key == $time) { echo "selected"; } ?> ><?= $value ?></option>
        <? } ?>
    </select>
</form>

<h1>Database Cluster (<?php echo Dashboard::displayTime($time) ?>)</h1>

<?
$tsd = new Tsd($time);
$tsd->addMetric('avg:1m-avg:rate:proc.stat.cpu{cluster=db,type=total}');
echo $tsd->getDashboardHTML(500, 250);
?>

<?
$tsd = new Tsd($time);
$tsd->addMetric('avg:1m-avg:proc.loadavg.1min{host=sac-prod-db-01.unix.newokl.com}');
$tsd->addMetric('avg:1m-avg:proc.loadavg.1min{host=sac-prod-db-02.unix.newokl.com}');
echo $tsd->getDashboardHTML(500, 250);
?>

<?
$tsd = new Tsd($time);
$tsd->addMetric('avg:1m-avg:rate:proc.stat.cpu{host=sac-prod-db-01.unix.newokl.com,type=total}');
$tsd->addMetric('avg:1m-avg:rate:proc.stat.cpu{host=sac-prod-db-02.unix.newokl.com,type=total}');
echo $tsd->getDashboardHTML(500, 250);
?>


</body>
</html>
