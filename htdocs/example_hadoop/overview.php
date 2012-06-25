<?php

// the title used for the page
$title = 'Hadoop: Overview';

require_once '../phplib/Dashboard.php';

$page_js_imports = array(
    '/assets/js/jquery-ui-1.8.16.min.js'
);

// The important Ganglia graphs
$cluster_graphs = array("cpu_report", "load_report", "mem_report", "network_report");
$ganglia_graphs = array();
foreach ($cluster_graphs as $thisgraph) {
    $ganglia_graphs[] = array(
        'type' => 'ganglia',
        'is_report' => true,
        'width' => GraphConstants::FOUR_GRAPH_WIDTH,
        'metrics' => array(
            array('source' => $gangliacluster_dn,
                'node' => null,
                'datum' => $thisgraph),
        ),
    );
}

$running_graphs = array(
    'running_maps' => array(
        'type' => 'ganglia',
        'no_legend' => 'true',
        'metric' => array('source' => $gangliacluster_dn, 'datum' => 'mapred.tasktracker.maps_running'),
    ),
    'running_reducers' => array(
        'type' => 'ganglia',
        'no_legend' => 'true',
        'metric' => array('source' => $gangliacluster_dn, 'datum' => 'mapred.tasktracker.reduces_running'),
    ),
);


$capacityreports = array("hadoop_dfs_space_report", "hadoop_map_capacity_report", "hadoop_reduce_capacity_report");
$capacity_graphs = array();
foreach ($capacityreports as $thisgraph) {
    $capacity_graphs[] = array(
        'type' => 'ganglia',
        'is_report' => true,
        'metric' => array('source' => $gangliacluster_nn, 'node' => $hadoopnn, 'datum' => $thisgraph)
    );
}

$graphs = array(
    'Cluster Hardware Overview' => $ganglia_graphs,
    'Running Now' => $running_graphs,
    'Capacity' => $capacity_graphs,
);

$tabs = Dashboard::$HADOOP_TABS;
$tab_url = Dashboard::getTabUrl(__FILE__);

include '../phplib/template.php';
