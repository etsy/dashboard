<?php

require_once dirname(dirname(__FILE__)) . '/lib/bootstrap.php';

// Get the values from the GET/POST
$graphTime = !empty($_GET['time']) ? $_GET['time'] : "1h";
$graphSize = !empty($_GET['size']) ? $_GET['size'] : "700x450";
$sizeArray = Dashboard::getWidthHeight();
$graphWidth = $sizeArray[$graphSize][0];
$graphHeight = $sizeArray[$graphSize][1];

$graphDownSample = !empty($_GET['downsample']) ? $_GET['downsample'] : "1m";

$title = "WEB Tier Processor Metrics - $graphDownSample Downsample";
$template = new GraphContainer($graphTime, $title);
$template->setGraphTime($graphTime);
    
/*
 * <h1>Web Cluster (<?php echo Dashboard::displayTime($time) ?>)</h1>
 */

 {
     $graphName = "CPU Total use % Aggregate - $graphDownSample";
     $tsd = new Tsd($graphTime);
     $tsd->addMetric("avg:$graphDownSample-avg:rate:proc.stat.cpu{cluster=web,type=total}");
     $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
 }
        
{
    $graphName = "CPU Total use % by server - $graphDownSample";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric("avg:$graphDownSample-avg:rate:proc.stat.cpu{cluster=web,type=total,host=*}");
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}
{
    $graphName = "Load Avg. last minute Aggregate - $graphDownSample";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric("avg:$graphDownSample-avg:proc.loadavg.1min{cluster=web}");
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "Load Avg. last minute by server - $graphDownSample";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric("avg:$graphDownSample-avg:proc.loadavg.1min{cluster=web,host=*}");
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

 {
     $graphName = "CPU IOWAIT % Aggregate - $graphDownSample";
     $tsd = new Tsd($graphTime);
	 //$graphWidth = !empty($_GET['width']) ? $_GET['width'] : 1000;
     $tsd->addMetric("avg:$graphDownSample-avg:rate:proc.stat.cpu{cluster=web,type=iowait}");
     $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
 }
        
{
    $graphName = "CPU IOWAIT % by server - $graphDownSample";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric("avg:$graphDownSample-avg:rate:proc.stat.cpu{cluster=web,type=iowait,host=*}");
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

$template->render();