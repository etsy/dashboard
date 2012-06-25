<?php

// the title used for the page
$title = 'Hadoop: Hbase Detail';

require_once '../phplib/Dashboard.php';

$overview_graphs[] = array(
    'type' => 'ganglia',
    'width' => GraphConstants::THREE_GRAPH_WIDTH,
    'metrics' => array(
        array('source' => $gangliacluster_nn,
              'node' => $hadoopnn,
              'datum' => "hbase.master.cluster_requests"),
    ),
);

$want_graphs = array("regions", "requests", "storefiles", "stores");
foreach ($want_graphs as $thisgraph) {
    $overview_graphs[] = array(
        'type' => 'ganglia',
        'width' => GraphConstants::THREE_GRAPH_WIDTH,
        'no_legend' => true,
        'metrics' => array(
            array('source' => $gangliacluster_dn,
                  'datum' => "hbase.regionserver.$thisgraph"),
        ),
    );
}

$want_graphs = array("Count", "EvictedCount", "Free", "HitRatio", "HitCount", "MissCount");
foreach ($want_graphs as $thisgraph) {
    $blockcache_graphs[] = array(
        'type' => 'ganglia',
        'no_legend' => true,
        'width' => GraphConstants::FOUR_GRAPH_WIDTH,
        'metrics' => array(
            array('source' => $gangliacluster_dn,
                  'datum' => "hbase.regionserver.blockCache$thisgraph"),
        ),
    );
}

$want_graphs = array("fsReadLatency_avg_time", "fsWriteLatency_avg_time", "fsSyncLatency_avg_time");
foreach ($want_graphs as $thisgraph) {
    $latency_graphs[] = array(
        'type' => 'ganglia',
        'width' => GraphConstants::THREE_GRAPH_WIDTH,
        'no_legend' => true,
        'metrics' => array(
            array('source' => $gangliacluster_dn,
                  'datum' => "hbase.regionserver.$thisgraph"),
        ),
    );
}

$want_graphs = array("fsReadLatency_num_ops", "fsWriteLatency_num_ops", "fsSyncLatency_num_ops");
foreach ($want_graphs as $thisgraph) {
    $operations_graphs[] = array(
        'type' => 'ganglia',
        'no_legend' => true,
        'width' => GraphConstants::THREE_GRAPH_WIDTH,
        'metrics' => array(
            array('source' => $gangliacluster_dn,
                  'datum' => "hbase.regionserver.$thisgraph"),
        ),
    );
}



$want_graphs = array("memstoreSizeMB", "storefileIndexSizeMB");
foreach ($want_graphs as $thisgraph) {
    $size_graphs[] = array(
        'type' => 'ganglia',
        'no_legend' => 'true',
        'width' => GraphConstants::THREE_GRAPH_WIDTH,
        'metrics' => array(
            array('source' => $gangliacluster_dn,
                  'datum' => "hbase.regionserver.$thisgraph"),
        ),
    );
}

$want_graphs = array(
    "stats.timers.hbase.mutateRow.upper_90", 
    "stats.timers.hbase.getVer.upper_90",
    "stats.timers.hbase.mutateRow.count", 
    "stats.timers.hbase.getVer.count"
);
foreach ($want_graphs as $thisgraph) {
    $thrift_graphs[] = array(
        'type' => 'graphite',
        'title' => $thisgraph,
        'width' => GraphConstants::TWO_GRAPH_WIDTH,
        'metrics' => array(
            $thisgraph
        ),
    );
}



$graphs = array(
    'Hbase Overview' => $overview_graphs,
    'Thrift Stats' => $thrift_graphs,
    'Block Cache' => $blockcache_graphs,
    'Hbase Latency' => $latency_graphs,
    'Hbase Operations' => $operations_graphs,
    'Sizes' => $size_graphs,
);

$tabs = Dashboard::$HADOOP_TABS;
$tab_url = Dashboard::getTabUrl(__FILE__);

include '../phplib/template.php';
