<?php

require_once dirname(dirname(__FILE__)) . '/lib/bootstrap.php';

// Get the values from the GET/POST
$graphTime = !empty($_GET['time']) ? $_GET['time'] : "1h";
$graphSize = !empty($_GET['size']) ? $_GET['size'] : "700x450";
$sizeArray = Dashboard::getWidthHeight();
$graphWidth = $sizeArray[$graphSize][0];
$graphHeight = $sizeArray[$graphSize][1];

$title = "PWEB Tier Rails Passenger Metrics";
$template = new GraphContainer($graphTime, $title);
$template->setGraphTime($graphTime);


    
/*
 * <h1>Web Cluster (<?php echo Dashboard::displayTime($time) ?>)</h1>
 */

 {
     $graphName = "Passenger Private Process Memory Count Aggregate";
     $tsd = new Tsd($graphTime);
     $tsd->addMetric('sum:1m-avg:rails.passenger.proc.mem.priv.total{app_type=*,host=*,cluster=pweb}');
     $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
 }
 
 {
     $graphName = "Passenger Private Process Memory Count by server";
     $tsd = new Tsd($graphTime);
     $tsd->addMetric('sum:1m-avg:rails.passenger.proc.mem.priv.total{app_type=*,host=*,cluster=pweb}');
     $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
 }

 {
     $graphName = "Apache Process Count by server";
     $tsd = new Tsd($graphTime);
     $tsd->addMetric('sum:1m-avg:rails.apache.proc.count{host=*,cluster=pweb}');
     $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
 }

 {
     $graphName = "Apache Private Memory (MB) by server";
     $tsd = new Tsd($graphTime);
     $tsd->addMetric('sum:1m-avg:rails.apache.proc.mem.priv.total{host=*,cluster=pweb}');
     $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
 }


$template->render();