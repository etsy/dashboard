<?php

require_once dirname(dirname(__FILE__)) . '/lib/bootstrap.php';

// Get the values from the GET/POST
$graphTime = !empty($_GET['time']) ? $_GET['time'] : "1h";
$graphSize = !empty($_GET['size']) ? $_GET['size'] : "1000x700";
$sizeArray = Dashboard::getWidthHeight();
$graphWidth = $sizeArray[$graphSize][0];
$graphHeight = $sizeArray[$graphSize][1];

$title = "Web Tier Memcached Data";
$template = new GraphContainer($graphTime, $title);
$template->setGraphTime($graphTime);

    
/*
 * <h1>Web Cluster (<?php echo Dashboard::displayTime($time) ?>)</h1>
 */

 {
     $graphName = "Memcached Kilobytes Aggregate";
     $tsd = new Tsd($graphTime);
     $tsd->addMetric('sum:1m-avg:memcached.kilobytes{type=data,cluster=web}');
     $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
 }
 
 {
     $graphName = "Memcached Kilobytes by server";
     $tsd = new Tsd($graphTime);
     $tsd->addMetric('sum:1m-avg:memcached.kilobytes{type=data,cluster=web,host=*}');
     $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
 }
 
 
 {
     $graphName = "Memcached CMD GET Aggregate";
     $tsd = new Tsd($graphTime);
     $tsd->addMetric('sum:1m-avg:rate:memcached.cmd.get{type=data,cluster=web}');
     $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
 }
 
 {
     $graphName = "Memcached CMD GET by Server";
     $tsd = new Tsd($graphTime);
     $tsd->addMetric('avg:1m-avg:rate:memcached.cmd.get{type=data,cluster=web,host=*}');
     $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
 }  
 
 {
     $graphName = "Memcached CMD SET Aggregate";
     $tsd = new Tsd($graphTime);
     $tsd->addMetric('sum:1m-avg:rate:memcached.cmd.set{type=data,cluster=web}');
     $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
 }
 
 {
     $graphName = "Memcached CMD SET by server";
     $tsd = new Tsd($graphTime);
     $tsd->addMetric('avg:1m-avg:rate:memcached.cmd.set{type=data,cluster=web,host=*}');
     $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
 } 
 
 
 
 {
     $graphName = "Memcached Current Connections by server";
     $tsd = new Tsd($graphTime);
     $tsd->addMetric('sum:1m-avg:memcached.connections.current{type=data,cluster=web,host=*}');
     $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
 }
 
 {
     $graphName = "Memcached Kilobytes Read Aggregate";
     $tsd = new Tsd($graphTime);
     $tsd->addMetric('sum:1m-avg:rate:memcached.kilobytes.read{type=data,cluster=web}');
     $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
 }
 
 {
     $graphName = "Memcached Kilobytes Read by server";
     $tsd = new Tsd($graphTime);
     $tsd->addMetric('avg:1m-avg:rate:memcached.kilobytes.read{type=data,cluster=web,host=*}');
     $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
 }
 
 {
     $graphName = "Memcached Kilobytes Written Aggregate";
     $tsd = new Tsd($graphTime);
     $tsd->addMetric('avg:1m-sum:rate:memcached.kilobytes.written{type=data,cluster=web}');
     $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
 }

 {
     $graphName = "Memcached Kilobytes Written by server";
     $tsd = new Tsd($graphTime);
     $tsd->addMetric('avg:1m-avg:rate:memcached.kilobytes.written{type=data,cluster=web,host=*}');
     $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
 }
 
 {
     $graphName = "Memcached Items Aggregate";
     $tsd = new Tsd($graphTime);
     $tsd->addMetric('sum:1m-avg:memcached.items.current{type=data,cluster=web}');
     $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
 } 
    


$template->render();