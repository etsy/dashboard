<?php

require_once "phplib/Dashboard.php";

$metrics = array(
"MemTotal", "MemFree", "Buffers", "Cached", "SwapCached", "Active", "Inactive", "Active(anon)",
"Inactive(anon)", "Active(file)", "Inactive(file)", "Unevictable", "Mlocked", "SwapTotal", "SwapFree",
"Dirty", "Writeback", "AnonPages", "Mapped", "Shmem", "Slab", "SReclaimable", "SUnreclaim", "KernelStack",
"PageTables", "NFS_Unstable", "Bounce", "WritebackTmp", "CommitLimit", "Committed_AS", "VmallocTotal", "VmallocUsed",
"VmallocChunk", "HardwareCorrupted", "AnonHugePages", "HugePages_Total", "HugePages_Free", "HugePages_Rsvd",
"HugePages_Surp", "Hugepagesize", "DirectMap4k", "DirectMap2M", "DirectMap1G"
);

$host = isset($_GET['host']) ? $_GET['host'] : null;

$title = "$host Meminfo Metrics";
$metric_graphs = array();

foreach ($metrics as $metric) {
   #$metric_name = "derivative(meminfo.$host.$metric)";
   $metric_name = "meminfo.$host.$metric";

   if (preg_match("/^SockStat/", $metric)) {
       $metric_name = "netstat.$host.tcpext.$metric";
   }

   $graph = array(
        'type'    => 'graphite',
        'title'   => $metric,
        'metrics' => array(
            $metric_name
        ),  
        'width' => 300,
        'height' => 200,
   );
   array_push($metric_graphs, $graph);
}

$graphs = array(
    "$host meminfo " => $metric_graphs
);

$additional_controls = "<input type=\"hidden\" name=\"host\" value=\"$host\">";

$tabs = Dashboard::$NETWORK_TABS;
$tab_url = Dashboard::getTabUrl(__FILE__);

include "phplib/template.php";
