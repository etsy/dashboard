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
<title>Performance Dashboard</title>
<link rel="stylesheet" type="text/css" href="css/screen.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
<script src="js/dashboard.js"></script>
</head>
<body id="perf" class="dashboard">

<div id="status"></div>

<form id="controls" action="<?= $_SERVER['PHP_SELF'] ?>">
    <select name="time">
        <? foreach (($times) as $key => $value) { ?>
            <option value="<?= $key ?>" <? if ($key == $time) { echo "selected"; } ?> ><?= $value ?></option>
        <? } ?>
    </select>
</form>

<h1>Top 3 Pages Hits and Response Times (<?php echo Dashboard::displayTime($time) ?>)</h1>

<?
$tsd = new Tsd($time);
$tsd->addMetric('sum:analytics.apache.page.count{page_type=_sales}');
$tsd->addMetric('sum:analytics.apache.page.count{page_type=_}');
$tsd->addMetric('sum:analytics.apache.page.count{page_type=_product}');
echo $tsd->getDashboardHTML(500, 250);
?>

<?
$tsd = new Tsd($time);
$tsd->addMetric('avg:analytics.apache.page.serve.95{page_type=_sales}');
echo $tsd->getDashboardHTML(500, 250);
?>

<?
$tsd = new Tsd($time);
$tsd->addMetric('avg:analytics.apache.page.serve.95{page_type=_}');
echo $tsd->getDashboardHTML(500, 250);
?>

<?
$tsd = new Tsd($time);
$tsd->addMetric('avg:analytics.apache.page.serve.95{page_type=_product}');
echo $tsd->getDashboardHTML(500, 250);
?>

<h1>VMF Hits and Response Times (<?php echo Dashboard::displayTime($time) ?>)</h1>

<?
$tsd = new Tsd($time);
$tsd->addMetric('sum:analytics.apache.page.count{page_type=_vintage-market-finds}');
$tsd->addMetric('sum:analytics.apache.page.count{page_type=_vintage-market-finds_-caty-}');
$tsd->addMetric('sum:analytics.apache.page.count{page_type=_vintage-market-finds_product}');
echo $tsd->getDashboardHTML(500, 250);
?>

<?
$tsd = new Tsd($time);
$tsd->addMetric('avg:analytics.apache.page.serve.95{page_type=_vintage-market-finds}');
echo $tsd->getDashboardHTML(500, 250);
?>

<?
$tsd = new Tsd($time);
$tsd->addMetric('avg:analytics.apache.page.serve.95{page_type=_vintage-market-finds_-caty-}');
echo $tsd->getDashboardHTML(500, 250);
?>

<?
$tsd = new Tsd($time);
$tsd->addMetric('avg:analytics.apache.page.serve.95{page_type=_vintage-market-finds_product}');
echo $tsd->getDashboardHTML(500, 250);
?>

<h1>Registration/Login Hits and Response Times (<?php echo Dashboard::displayTime($time) ?>)</h1>

<?
$tsd = new Tsd($time);
$tsd->addMetric('sum:analytics.apache.page.count{page_type=_join}');
$tsd->addMetric('sum:analytics.apache.page.count{page_type=_login}');
echo $tsd->getDashboardHTML(500, 250);
?>

<?
$tsd = new Tsd($time);
$tsd->addMetric('avg:analytics.apache.page.serve.95{page_type=_join}');
echo $tsd->getDashboardHTML(500, 250);
?>

<?
$tsd = new Tsd($time);
$tsd->addMetric('avg:analytics.apache.page.serve.95{page_type=_login}');
echo $tsd->getDashboardHTML(500, 250);
?>

<h1>Cart Hits and Response Times (<?php echo Dashboard::displayTime($time) ?>)</h1>

<?
$tsd = new Tsd($time);
$tsd->addMetric('sum:analytics.apache.page.count{page_type=_add-to-cart-ajax.json}');
$tsd->addMetric('max:analytics.apache.page.max_per_sku{env=prod}');
echo $tsd->getDashboardHTML(500, 250);
?>

<?
$tsd = new Tsd($time);
$tsd->addMetric('sum:1m-sum:analytics.mysql.order.source.order_count');
$tsd->addMetric('sum:1m-sum:analytics.mysql.cart_line.created_by.sales_event_id.count');
echo $tsd->getDashboardHTML(500, 250);
?>

<?
$tsd = new Tsd($time);
$tsd->addMetric('avg:analytics.apache.page.serve.95{page_type=_add-to-cart-ajax.json,env=prod}');
echo $tsd->getDashboardHTML(500, 250);
?>

<?
$tsd = new Tsd($time);
$tsd->addMetric('avg:analytics.apache.page.serve.95{page_type=_view-cart,env=prod}');
echo $tsd->getDashboardHTML(500, 250);
?>

</body>
</html>
