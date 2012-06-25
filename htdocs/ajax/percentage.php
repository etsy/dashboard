<?php

require_once('../phplib/Dashboard.php');

$from = isset($_GET['from']) ? $_GET['from'] : '';
$max = isset($_GET['max']) ? (float)$_GET['max'] : null;

$numerators = isset($_GET['n']) ? (array)$_GET['n'] : array();
$denominators = isset($_GET['d']) ? (array)$_GET['d'] : array();

$graphite_results = GraphiteHelper::fetchKeyedSplitRenderDataMultipleTargets(array_merge($numerators, $denominators), $from);

$details = array();
$values = array();

$current_denominator = $denominators[0];

foreach ($numerators as $index => $numerator) {
    $denominator = isset($denominators[$index]) ? $denominators[$index] : $current_denominator;

    $numerator_value = isset($graphite_results[$numerator]) ? GraphiteHelper::getTotal($graphite_results[$numerator]) / count($graphite_results[$numerator]) : 0.0;
    $denominator_value = isset($graphite_results[$denominator]) ? GraphiteHelper::getTotal($graphite_results[$denominator]) / count($graphite_results[$denominator]) : 0.0;

    $value = $denominator_value != 0.0 ? ($numerator_value / $denominator_value) * 100 : 0.0;

    if ($max != null) {
        $value = min($value, $max);
    }

    $values[] = $value;
    $details[] = array($numerator_value, $denominator_value);
}

$result = array(
    array(
        'measures' => $values,
        'details' => $details,
        'ranges' => array(100),
    ),
);

header("Content-type: application/json");
print(json_encode($result));
