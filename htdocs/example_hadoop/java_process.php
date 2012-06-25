<?php

// the title used for the page
$title = 'Hadoop: Java Processes';

require_once '../phplib/Dashboard.php';

$want_graphs = array("gcCount", "gcTimeMillis", "logError", "logWarn", "memHeapCommittedM", "memHeapUsedM", "memNonHeapCommittedM", "memNonHeapUsedM");
foreach ($want_graphs as $thisgraph) {
    $tasktracker_graphs[] = array(
        'type' => 'ganglia',
        'no_legend' => true,
        'width' => GraphConstants::FOUR_GRAPH_WIDTH,
        'metrics' => array(
            array('source' => $gangliacluster_dn,
                  'datum' => "jvm.TaskTracker.metrics.$thisgraph"),
        ),
    );
}


foreach ($want_graphs as $thisgraph) {
    $datanode_graphs[] = array(
        'type' => 'ganglia',
        'no_legend' => true,
        'width' => GraphConstants::FOUR_GRAPH_WIDTH,
        'metrics' => array(
            array('source' => $gangliacluster_dn,
                  'datum' => "jvm.DataNode.metrics.$thisgraph"),
        ),
    );
}

foreach ($want_graphs as $thisgraph) {
    $map_graphs[] = array(
        'type' => 'ganglia',
        'no_legend' => true,
        'width' => GraphConstants::FOUR_GRAPH_WIDTH,
        'metrics' => array(
            array('source' => $gangliacluster_dn,
                  'datum' => "jvm.MAP.metrics.$thisgraph"),
        ),
    );
}

foreach ($want_graphs as $thisgraph) {
    $shuffle_graphs[] = array(
        'type' => 'ganglia',
        'no_legend' => true,
        'width' => GraphConstants::FOUR_GRAPH_WIDTH,
        'metrics' => array(
            array('source' => $gangliacluster_dn,
                  'datum' => "jvm.SHUFFLE.metrics.$thisgraph"),
        ),
    );
}

foreach ($want_graphs as $thisgraph) {
    $namenode_graphs[] = array(
        'type' => 'ganglia',
        'width' => GraphConstants::FOUR_GRAPH_WIDTH,
        'metrics' => array(
            array('source' => $gangliacluster_nn,
                  'node' => $hadoopnn,
                  'datum' => "jvm.NameNode.metrics.$thisgraph"),
        ),
    );
}


$graphs = array(
    'TaskTracker JVM Metrics' => $tasktracker_graphs,
    'Datanode JVM Metrics' => $datanode_graphs,
    'Map JVM Metrics' => $map_graphs,
    'Shuffle JVM Metrics' => $shuffle_graphs,
    'Namenode JVM Metrics' => $namenode_graphs,
);

$tabs = Dashboard::$HADOOP_TABS;
$tab_url = Dashboard::getTabUrl(__FILE__);

include '../phplib/template.php';
