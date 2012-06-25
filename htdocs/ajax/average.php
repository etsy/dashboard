<?php

require_once('../phplib/Dashboard.php');

$from = isset($_GET['from']) ? $_GET['from'] : '';

$targets = isset($_GET['t']) ? (array)$_GET['t'] : array();

$graphite_results = GraphiteHelper::fetchKeyedSplitRenderDataMultipleTargets($targets, $from);

$averages = array();

foreach ($targets as $target) {
    $value = isset($graphite_results[$target]) ? GraphiteHelper::getTotal($graphite_results[$target]) / count($graphite_results[$target]) : 0.0;
    $averages[$target] = $value;
}

$result = array('averages' => $averages);

header("Content-type: application/json");
print(json_encode($result));
