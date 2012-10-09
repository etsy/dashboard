<?php

require_once dirname(dirname(__FILE__)) . '/lib/bootstrap.php';

// Get the values from the GET/POST
$graphTime = !empty($_GET['time']) ? $_GET['time'] : "1h";
$graphSize = !empty($_GET['size']) ? $_GET['size'] : "1000x700";
$sizeArray = Dashboard::getWidthHeight();
$graphWidth = $sizeArray[$graphSize][0];
$graphHeight = $sizeArray[$graphSize][1];

$title = "MQ System Metrics";
$template = new GraphContainer($graphTime, $title);
$template->setGraphTime($graphTime);

    
/*
 * <h1>Web Cluster (<?php echo Dashboard::displayTime($time) ?>)</h1>
 */
        
{
    $graphName = "CPU Total use %";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('avg:1m-avg:rate:proc.stat.cpu{cluster=mq,type=total,host=*}');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "Load Avg. last minute";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('avg:1m-avg:proc.loadavg.1min{cluster=mq,host=*}');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

$template->render();