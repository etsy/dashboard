<?php

/** the title used for the page */
$title = 'Hadoop: DFS Detail';

require_once '../phplib/Dashboard.php';

$filesystem_graphs[] = array(
    'type' => 'ganglia',
    'is_report' => true,
    'metric' => array('source' => $gangliacluster_nn, 'node' => $hadoopnn, 'datum' => "hadoop_dfs_space_report")
);

$want_graphs = array("dfs.FSDirectory.files_deleted","dfs.FSNamesystem.FilesTotal", "dfs.FSNamesystem.TotalLoad", "dfs.FSNamesystem.BlocksTotal");
foreach ($want_graphs as $thisgraph) {
    $filesystem_graphs[] = array(
        'type' => 'ganglia',
        'width' => GraphConstants::THREE_GRAPH_WIDTH,
        'metrics' => array(
            array('source' => $gangliacluster_nn,
                  'node' => $hadoopnn,
                  'datum' => $thisgraph),
        ),
    );
}

$want_graphs = array("CorruptBlocks", "ExcessBlocks", "MissingBlocks", "PendingDeletionBlocks", "PendingReplicationBlocks", "ScheduledReplicationBlocks", "UnderReplicatedBlocks");
foreach ($want_graphs as $thisgraph) {
    $block_graphs[] = array(
        'type' => 'ganglia',
        'width' => GraphConstants::FOUR_GRAPH_WIDTH,
        'metrics' => array(
            array('source' => $gangliacluster_nn,
                  'node' => $hadoopnn,
                  'datum' => "dfs.FSNamesystem.$thisgraph"),
        ),
    );
}

$want_graphs = array("blocks_read", "blocks_removed", "blocks_written");
foreach ($want_graphs as $thisgraph) {
    $operations_graphs[] = array(
        'type' => 'ganglia',
        'no_legend' => 'true',
        'width' => GraphConstants::THREE_GRAPH_WIDTH,
        'metrics' => array(
            array('source' => $gangliacluster_dn,
                  'datum' => "dfs.datanode.$thisgraph"),
        ),
    );
}

$want_graphs = array("copyBlockOp_avg_time", "heartBeats_avg_time", "readBlockOp_avg_time", "replaceBlockOp_avg_time", "writeBlockOp_avg_time", "blockChecksumOp_avg_time");
foreach ($want_graphs as $thisgraph) {
    $latency_graphs[] = array(
        'type' => 'ganglia',
        'no_legend' => 'true',
        'width' => GraphConstants::THREE_GRAPH_WIDTH,
        'metrics' => array(
            array('source' => $gangliacluster_dn,
                  'datum' => "dfs.datanode.$thisgraph"),
        ),
    );
}

$datanode_graphs[] = array(
    'type' => 'ganglia',
    'no_legend' => 'true',
    'metrics' => array(
        array('source' => $gangliacluster_dn,
              'datum' => "dfs.datanode.volumeFailures"),
    ),
);


$graphs = array(
    'Filesystem Overview' => $filesystem_graphs,
    'Block Status' => $block_graphs,
    'DFS Operations' => $operations_graphs,
    'DFS Operations latency' => $latency_graphs,
    'Datanode Status' => $datanode_graphs,
);

$tabs = Dashboard::$HADOOP_TABS;
$tab_url = Dashboard::getTabUrl(__FILE__);

include '../phplib/template.php';
