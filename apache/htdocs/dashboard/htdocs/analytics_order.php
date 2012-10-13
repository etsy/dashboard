<?php

require_once dirname(dirname(__FILE__)) . '/lib/bootstrap.php';

// Get the values from the GET/POST
$graphTime = !empty($_GET['time']) ? $_GET['time'] : "1h";
$graphSize = !empty($_GET['size']) ? $_GET['size'] : "700x450";
$graphDownSample = !empty($_GET['downsample']) ? $_GET['downsample'] : "1m";
$sizeArray = Dashboard::getWidthHeight();
$graphWidth = $sizeArray[$graphSize][0];
$graphHeight = $sizeArray[$graphSize][1];

$graphInterval = "1 minute";

$title = "King Order Metrics per $graphInterval - $graphDownSample Downsample";
$template = new GraphContainer($graphTime, $title);
$template->setGraphTime($graphTime);

    
/*
 * <h1>Web Cluster (<?php echo Dashboard::displayTime($time) ?>)</h1>
 */
        
{
    $graphName = "Order Count per $graphInterval - $graphDownSample Downsample";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric("avg:$graphDownSample-avg:analytics.mysql.order.source.order_count");
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}



$template->render();