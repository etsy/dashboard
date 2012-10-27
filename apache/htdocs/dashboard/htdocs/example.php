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
    <label>
        <input type="checkbox" name="hide_deploys" value="true" <?php if ($hide_deploys) echo "checked"; ?> > Hide deploys
    </label>
</form>

<h1>Error Logs (<?php echo Dashboard::displayTime($time) ?>)</h1>

<?

// Fatals (Error log)
$g = new Graphite($time);
$g->setTitle('Fatals');
$g->addMetric('lineWidth(historicalAverage(webs.errorLog.fatal),3)', '#dddddd');
$g->addMetric('webs.errorLog.fatal', '#00cc00');
$g->showDeploys($show_deploys);
$g->hideLegend(true);
echo $g->getDashboardHTML(280, 220);

// Errors (Error Log)
$g = new Graphite($time);
$g->setTitle('Errors');
$g->addMetric('lineWidth(historicalAverage(webs.errorLog.error),3)', '#dddddd');
$g->addMetric('webs.errorLog.error', '#00cc00');
$g->showDeploys($show_deploys);
$g->hideLegend(true);
echo $g->getDashboardHTML(280, 220);

// Warnings (Error log)
$g = new Graphite($time);
$g->setTitle('Warnings');
$g->addMetric('lineWidth(historicalAverage(webs.errorLog.warning),3)', '#dddddd');
$g->addMetric('webs.errorLog.warning', '#00cc00');
$g->showDeploys($show_deploys);
$g->hideLegend(true);
echo $g->getDashboardHTML(280, 220);

?>


<h1>Business Graphs (<?php echo Dashboard::displayTime($time) ?>)</h1>

<?

// Logins
$g = new Graphite($time);
$g->setTitle('Logins (top), Login Errors (errors)');
$g->showDeploys($show_deploys);
$g->addMetric('lineWidth(movingAverage(historicalAverage(stats.logins.success),6),3)', '#dddddd');
$g->addMetric('lineWidth(movingAverage(historicalAverage(stats.logins.fail),6),3)', '#dddddd');
$g->addMetric('alias(movingAverage(fillValue(stats.logins.success),6),"Logins")', '#0099ff');
$g->addMetric('alias(movingAverage(fillValue(stats.logins.fail),6),"Errors")', '#0033ff');
$g->displayStacked(false);
$g->hideLegend(true);
echo $g->getDashboardHTML(350, 250);

?>

<?
$g = new Graphite($time);
$g->setTitle('Forum Posts to Help (orange) and Bugs (red)');
$g->showDeploys($show_deploys);
$g->addMetric('alias(fillValue(stats_counts.forums.new_posts.bugs),"New Posts to Bugs Forum")', '#cc0033');
$g->addMetric('alias(fillValue(stats_counts.forums.new_posts.help),"New Posts to Help Forum")', '#ff9900');
$g->hideLegend(true);
$g->displayStacked(true);
$g->setYMax(20);
echo $g->getDashboardHTML(350, 250);
?>


<h1>Web Cluster (<?php echo Dashboard::displayTime($time) ?>)</h1>

<?
$g = new Ganglia($time);
$g->addMetric('webs', null, 'apache_requests_per_second');
echo $g->getDashboardHTML();
?>

<?
$g = new Ganglia($time);
$g->addMetric('webs', null, 'apache_busy_workers');
echo $g->getDashboardHTML();
?>

<?
$g = new Ganglia($time);
$g->addReport('webs', null, 'cpu_report');
echo $g->getDashboardHTML();
?>


<h1>Outgoing Bandwidth (<?php echo Dashboard::displayTime($time) ?>)</h1>

<?
$g = new Cacti($time);
$g->addMetric(809, 0);
echo '<div class="cacti_bandwidth">' . $g->getDashboardHTML() . '</div>';
?>


</body>
</html>
