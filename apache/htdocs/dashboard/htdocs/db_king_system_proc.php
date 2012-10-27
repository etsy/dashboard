<?php

require_once dirname(dirname(__FILE__)) . '/lib/bootstrap.php';

// Get the values from the GET/POST
$graphTime = !empty($_GET['time']) ? $_GET['time'] : "1h";
$graphSize = !empty($_GET['size']) ? $_GET['size'] : "700x450";
$sizeArray = Dashboard::getWidthHeight();
$graphWidth = $sizeArray[$graphSize][0];
$graphHeight = $sizeArray[$graphSize][1];

$graphDownSample = !empty($_GET['downsample']) ? $_GET['downsample'] : "1m";

$title = "DB (King) System Metrics - $graphDownSample Downsample";
$template = new GraphContainer($graphTime, $title);
$template->setGraphTime($graphTime);
    
/*
 * <h1>Web Cluster (<?php echo Dashboard::displayTime($time) ?>)</h1>
 */
        
{
    $graphName = "CPU Total use % - $graphDownSample";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric("avg:$graphDownSample-avg:rate:proc.stat.cpu{cluster=db,type=total,host=*}");
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "Load Avg. last minute - $graphDownSample";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric("avg:$graphDownSample-avg:proc.loadavg.1min{cluster=db,host=*}");
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "CPU IOWAIT % by server - $graphDownSample";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric("avg:$graphDownSample-avg:rate:proc.stat.cpu{cluster=db,type=iowait,host=*}");
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

$template->render();