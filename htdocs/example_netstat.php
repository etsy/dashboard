<?php

require_once "phplib/Dashboard.php";

$metrics = array(
    "SockStat-inuse", "SockStat-orphan", "SockStat-tw", "SockStat-alloc", 
    "ArpFilter", "DelayedACKLocked", "DelayedACKLost", "DelayedACKs", "EmbryonicRsts", 
    "ListenDrops", "ListenOverflows", "LockDroppedIcmps", "OfoPruned", "OutOfWindowIcmps", 
    "PAWSActive", "PAWSEstab", "PAWSPassive", "PruneCalled", "RcvPruned", "SyncookiesFailed", 
    "SyncookiesRecv", "SyncookiesSent", "TCPAbortFailed", "TCPAbortOnClose", "TCPAbortOnData", 
    "TCPAbortOnLinger", "TCPAbortOnMemory", "TCPAbortOnSyn", "TCPAbortOnTimeout", 
    "TCPDirectCopyFromBacklog", "TCPDirectCopyFromPrequeue", "TCPDSACKOfoRecv", "TCPDSACKOfoSent", 
    "TCPDSACKOldSent", "TCPDSACKRecv", "TCPDSACKUndo", "TCPFACKReorder", "TCPFastRetrans", 
    "TCPForwardRetrans", "TCPFullUndo", "TCPHPAcks", "TCPHPHits", "TCPHPHitsToUser", "TCPLoss", 
    "TCPLossFailures", "TCPLossUndo", "TCPLostRetransmit", "TCPPartialUndo", "TCPPrequeued", "TCPPrequeueDropped", 
    "TCPPureAcks", "TCPRcvCollapsed", "TCPRenoFailures", "TCPRenoRecovery", "TCPRenoRecoveryFail", "TCPRenoReorder", 
    "TCPSackFailures", "TCPSackRecovery", "TCPSackRecoveryFail", "TCPSACKReneging", "TCPSACKReorder", 
    "TCPSchedulerFailed", "TCPSlowStartRetrans", "TCPTimeouts", "TCPTSReorder", "TW", 
    "TWKilled", "TWRecycled"
);

$host = isset($_GET['host']) ? $_GET['host'] : null;

$title = "$host Netstat TCP Ext Metrics";
$metric_graphs = array();

foreach ($metrics as $metric) {
   $metric_name = "derivative(netstat.$host.tcpext.$metric)";

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
    "$host Netstat TCP Ext" => $metric_graphs
);

$additional_controls = "<input type=\"hidden\" name=\"host\" value=\"$host\">";

$tabs = Dashboard::$NETWORK_TABS;
$tab_url = Dashboard::getTabUrl(__FILE__);

include "phplib/template.php";
