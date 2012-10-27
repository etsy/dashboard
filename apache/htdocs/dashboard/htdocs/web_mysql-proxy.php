<?php

require_once dirname(dirname(__FILE__)) . '/lib/bootstrap.php';

// Get the values from the GET/POST
$graphTime = !empty($_GET['time']) ? $_GET['time'] : "1h";
$graphSize = !empty($_GET['size']) ? $_GET['size'] : "700x450";
$graphDownSample = !empty($_GET['downsample']) ? $_GET['downsample'] : "1m";
$sizeArray = Dashboard::getWidthHeight();
$graphWidth = $sizeArray[$graphSize][0];
$graphHeight = $sizeArray[$graphSize][1];

$title = "APP Tier mysql-proxy-process Data - $graphDownSample Downsample";
$template = new GraphContainer($graphTime, $title);
$template->setGraphTime($graphTime);

    
/*
 * <h1>Web Cluster (<?php echo Dashboard::displayTime($time) ?>)</h1>
 */

 {
     $graphName = "mysql-proxy rss size (mb)";
     $tsd = new Tsd($graphTime);
     $tsd->addMetric("sum:$graphDownSample-avg:process.mon.rss_mb{process=mysql-proxy,app=king,host=*,cluster=web}");
     $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
 }
 
 {
     $graphName = "mysql-proxy vsize size (mb)";
     $tsd = new Tsd($graphTime);
     $tsd->addMetric("sum:$graphDownSample-avg:process.mon.vsize_mb{process=mysql-proxy,app=king,host=*,cluster=web}");
     $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
 }

 {
     $graphName = "mysql-proxy percent cpu";
     $tsd = new Tsd($graphTime);
     $tsd->addMetric("sum:$graphDownSample-avg:process.mon.pcpu{process=mysql-proxy,app=king,host=*,cluster=web}");
     $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
 }
    


$template->render();