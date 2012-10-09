<?php

require_once dirname(dirname(__FILE__)) . '/lib/bootstrap.php';

// Get the values from the GET/POST
$graphTime = !empty($_GET['time']) ? $_GET['time'] : "1h";
$graphSize = !empty($_GET['size']) ? $_GET['size'] : "700x450";
$sizeArray = Dashboard::getWidthHeight();
$graphWidth = $sizeArray[$graphSize][0];
$graphHeight = $sizeArray[$graphSize][1];

$title = "DB (king) Network Metrics";
$template = new GraphContainer($graphTime, $title);
$template->setGraphTime($graphTime);
    
/*
 * <h1>Web Cluster (<?php echo Dashboard::displayTime($time) ?>)</h1>
 */
        
{
    $graphName = 'Total Bytes IN';
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('avg:rate:proc.net.bytes{cluster=db,host=*,direction=in}');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = 'Total Bytes OUT';
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('avg:rate:proc.net.bytes{cluster=db,host=*,direction=out}');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = 'TCP Congestion Recovery';
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('avg:rate:net.stat.tcp.congestion.recovery{cluster=db,host=*}');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = 'TCP Connections Aborted';
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('avg:rate:net.stat.tcp.abort{cluster=db,host=*}');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = 'TCP Packetloss Recovery';
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('avg:rate:net.stat.tcp.packetloss.recovery{cluster=db,host=*}');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = 'TCP Failed Accept';
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('avg:rate:net.stat.tcp.failed_accept{cluster=db,host=*}');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

$template->render();