<?php

require_once dirname(dirname(__FILE__)) . '/lib/bootstrap.php';

// Get the values from the GET/POST
$graphTime = !empty($_GET['time']) ? $_GET['time'] : "1h";
$graphSize = !empty($_GET['size']) ? $_GET['size'] : "700x450";
$sizeArray = Dashboard::getWidthHeight();
$graphWidth = $sizeArray[$graphSize][0];
$graphHeight = $sizeArray[$graphSize][1];

$title = "DB (King) Disk Metrics";
$template = new GraphContainer($graphTime, $title);
$template->setGraphTime($graphTime);
    
/*
 * <h1>Web Cluster (<?php echo Dashboard::displayTime($time) ?>)</h1>
 */
        
{
    $graphName = 'Partition Total Millisecond Time';
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('sum:rate:iostat.part.msec_total{cluster=db,host=*}');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = 'Partition Write Requests';
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('sum:rate:iostat.disk.write_requests{cluster=db,host=*}');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = 'Partition Read Requests';
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('sum:rate:iostat.disk.read_requests{cluster=db,host=*}');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

$template->render();