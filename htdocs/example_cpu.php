<?php

require_once 'phplib/Dashboard.php';

$metrics = array(
    "avg:1m-avg:rate:proc.stat.cpu{type=total}", "avg:1m-avg:proc.loadavg.1min{}", "sum:1m-avg:rate:apache.stats.served.kbytes{}"
);

$host = isset($_GET['host']) ? $_GET['host'] : null;

$title = "$host CPU usage";
$metric_graphs = array();

foreach ($metrics as $metric) {
    $metric_name = $metric;

    $graph = array(
        'type'    => 'tsd',
        'title'   => $metric,
        'metric' => $metric_name,
        'width' => 300,
        'height' => 200,
    );
    array_push($metric_graphs, $graph);
}

$graphs = array(
    "$host CPU usage" => $metric_graphs
);

$additional_controls = "<input type=\"hidden\" name=\"host\" value=\"$host\">";

$tabs = Dashboard::$DB_TABS;
$tab_url = Dashboard::getTabUrl(__FILE__);

include "phplib/template.php";
