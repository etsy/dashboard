<?php

// the title used for the page
$title = 'Hadoop: Jobs Detail';

require_once '../phplib/Dashboard.php';

$jobtracker_overview[] = array(
    'type' => 'ganglia',
    'is_report' => true,
    'width' => GraphConstants::FOUR_GRAPH_WIDTH,
    'metric' => array('source' => $gangliacluster_nn, 'node' => $hadoopnn, 'datum' => "hadoop_map_capacity_report")
);
$jobtracker_overview[] = array(
    'type' => 'ganglia',
    'is_report' => true,
    'width' => GraphConstants::FOUR_GRAPH_WIDTH,
    'metric' => array('source' => $gangliacluster_nn, 'node' => $hadoopnn, 'datum' => "hadoop_reduce_capacity_report")
);


$want_graphs = array("completed", "failed", "killed", "preparing", "running", "submitted");
foreach ($want_graphs as $thisgraph) {
    $jobstatus_graphs[] = array(
        'type' => 'ganglia',
        'width' => GraphConstants::THREE_GRAPH_WIDTH,
        'metrics' => array(
            array('source' => $gangliacluster_nn,
                  'node' => $hadoopnn,
                  'datum' => "mapred.jobtracker.jobs_$thisgraph"),
        ),
    );
}

$want_graphs = array("blacklisted_maps", "blacklisted_reduces", "heartbeats", "trackers_blacklisted", "trackers_decommissioned", "trackers");
foreach ($want_graphs as $thisgraph) {
    $jobtrackeradmin_graphs[] = array(
        'type' => 'ganglia',
        'width' => GraphConstants::THREE_GRAPH_WIDTH,
        'metrics' => array(
            array('source' => $gangliacluster_nn,
                  'node' => $hadoopnn,
                  'datum' => "mapred.jobtracker.$thisgraph"),
        ),
    );
}

$want_graphs = array("maps_completed", "maps_failed", "maps_killed", "maps_launched", "waiting_maps", "reserved_map_slots");
foreach ($want_graphs as $thisgraph) {
    $mapstatus_graphs[] = array(
        'type' => 'ganglia',
        'width' => GraphConstants::THREE_GRAPH_WIDTH,
        'metrics' => array(
            array('source' => $gangliacluster_nn,
                  'node' => $hadoopnn,
                  'datum' => "mapred.jobtracker.$thisgraph"),
        ),
    );
}

$want_graphs = array("reduces_completed", "reduces_failed", "reduces_killed", "reduces_launched", "waiting_reduces", "reserved_reduce_slots");
foreach ($want_graphs as $thisgraph) {
    $reducestatus_graphs[] = array(
        'type' => 'ganglia',
        'width' => GraphConstants::THREE_GRAPH_WIDTH,
        'metrics' => array(
            array('source' => $gangliacluster_nn,
                  'node' => $hadoopnn,
                  'datum' => "mapred.jobtracker.$thisgraph"),
        ),
    );
}



$want_graphs = array("completed", "failed_ping", "failed_timeout");
foreach ($want_graphs as $thisgraph) {
    $tasktracker_graphs[] = array(
        'type' => 'ganglia',
        'no_legend' => 'true',
        'width' => GraphConstants::THREE_GRAPH_WIDTH,
        'metrics' => array(
            array('source' => $gangliacluster_dn,
                  'datum' => "mapred.tasktracker.tasks_$thisgraph"),
        ),
    );
}




$graphs = array(
    'Jobtracker Overview' => $jobtracker_overview,
    'Job Status' => $jobstatus_graphs,
    'JobTracker Admin' => $jobtrackeradmin_graphs,
    'Map Status' => $mapstatus_graphs,
    'Reduce Status' => $reducestatus_graphs,
    'TaskTracker Status' => $tasktracker_graphs,
);

$tabs = Dashboard::$HADOOP_TABS;
$tab_url = Dashboard::getTabUrl(__FILE__);

include '../phplib/template.php';
