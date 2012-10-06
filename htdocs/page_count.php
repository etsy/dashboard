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

<h1>System Metrics</h1>

<h2>CPU Total Use Percent by host (<?php echo Dashboard::displayTime($time) ?>)</h2>

<?
$tsd = new Tsd($time);
$tsd->addMetric('avg:1m-avg:rate:proc.stat.cpu{cluster=db,type=total,host=*}');
echo $tsd->getDashboardHTML(900, 500);
?>

<h2>Load Avg. Last Min (<?php echo Dashboard::displayTime($time) ?>)</h2>

<?
$tsd = new Tsd($time);
$tsd->addMetric('avg:1m-avg:proc.loadavg.1min{cluster=db,host=*}');
echo $tsd->getDashboardHTML(900, 500);;
?>
<BR>
<BR>
<h1>MySql Metrics</h1>

<h2>king-prod-db01 Query Count (<?php echo Dashboard::displayTime($time) ?>)</h2>

<?
$tsd = new Tsd($time);
$tsd->addMetric('sum:1m-avg:rate:mysql.com_select{host=sac-prod-db-01.unix.newokl.com}');
$tsd->addMetric('sum:1m-avg:rate:mysql.com_insert{host=sac-prod-db-01.unix.newokl.com}');
$tsd->addMetric('sum:1m-avg:rate:mysql.com_update{host=sac-prod-db-01.unix.newokl.com}');
$tsd->addMetric('sum:1m-avg:rate:mysql.com_replace{host=sac-prod-db-01.unix.newokl.com}');
$tsd->addMetric('sum:1m-avg:rate:mysql.com_update_multi{host=sac-prod-db-01.unix.newokl.com}');
$tsd->addMetric('sum:1m-avg:rate:mysql.com_load{host=sac-prod-db-01.unix.newokl.com}');
$tsd->addMetric('sum:1m-avg:rate:mysql.com_delete{host=sac-prod-db-01.unix.newokl.com}');

echo $tsd->getDashboardHTML(900, 500);
?>

<h2>king-prod-db02 Query Count (<?php echo Dashboard::displayTime($time) ?>)</h2>

<?
$tsd = new Tsd($time);
$tsd->addMetric('sum:1m-avg:rate:mysql.com_select{host=sac-prod-db-02.unix.newokl.com}');
$tsd->addMetric('sum:1m-avg:rate:mysql.com_insert{host=sac-prod-db-02.unix.newokl.com}');
$tsd->addMetric('sum:1m-avg:rate:mysql.com_update{host=sac-prod-db-02.unix.newokl.com}');
$tsd->addMetric('sum:1m-avg:rate:mysql.com_replace{host=sac-prod-db-02.unix.newokl.com}');
$tsd->addMetric('sum:1m-avg:rate:mysql.com_update_multi{host=sac-prod-db-02.unix.newokl.com}');
$tsd->addMetric('sum:1m-avg:rate:mysql.com_load{host=sac-prod-db-02.unix.newokl.com}');
$tsd->addMetric('sum:1m-avg:rate:mysql.com_delete{host=sac-prod-db-02.unix.newokl.com}');

echo $tsd->getDashboardHTML(900, 500);
?>

<h2>Replication - seconds behind master (<?php echo Dashboard::displayTime($time) ?>)</h2>

<?
$tsd = new Tsd($time);
$tsd->addMetric('sum:mysql.slave.seconds_behind_master{host=*}');
echo $tsd->getDashboardHTML(900, 500);
?>

<h2>SELECT type - select_scan</h2>

<?
$tsd = new Tsd($time);
$tsd->addMetric('sum:rate:mysql.select_scan{host=*}');
echo $tsd->getDashboardHTML(900, 500);
?>

<h2>SELECT type - select_range_check</h2>
<?
$tsd = new Tsd($time);
$tsd->addMetric('sum:rate:mysql.select_range_check{host=*}');
echo $tsd->getDashboardHTML(900, 500);
?>

<h2>SELECT type - select_range</h2>
<?
$tsd = new Tsd($time);
$tsd->addMetric('sum:rate:mysql.select_range{host=*}');
echo $tsd->getDashboardHTML(900, 500);
?>

<h2>SELECT type - select_full_range_join</h2>
<?
$tsd = new Tsd($time);
$tsd->addMetric('sum:rate:mysql.select_full_range_join{host=*}');
echo $tsd->getDashboardHTML(900, 500);
?>

<h2>SELECT type - select_full_join</h2>
<?
$tsd = new Tsd($time);
$tsd->addMetric('sum:rate:mysql.select_full_join{host=*}');
echo $tsd->getDashboardHTML(900, 500);
?>



</body>
</html>
