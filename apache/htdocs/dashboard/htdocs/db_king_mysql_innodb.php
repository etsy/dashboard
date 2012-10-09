<?php

require_once dirname(dirname(__FILE__)) . '/lib/bootstrap.php';

// Get the values from the GET/POST
$graphTime = !empty($_GET['time']) ? $_GET['time'] : "1h";
$graphSize = !empty($_GET['size']) ? $_GET['size'] : "700x450";
$sizeArray = Dashboard::getWidthHeight();
$graphWidth = $sizeArray[$graphSize][0];
$graphHeight = $sizeArray[$graphSize][1];

$title = "DB (King) innodb Metrics";
$template = new GraphContainer($graphTime, $title);
$template->setGraphTime($graphTime);
    
/*
 * <h1>Web Cluster (<?php echo Dashboard::displayTime($time) ?>)</h1>
 */
        
{
    $graphName = "innodb locks os_waits";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('sum:1m-avg:rate:mysql.innodb.locks.os_waits{cluster=db,host=*}');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "innodb locks spin_waits";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('sum:1m-avg:rate:mysql.innodb.locks.spin_waits{cluster=db,host=*}');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "innodb buffer pool read requests";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('sum:1m-avg:rate:mysql.innodb_buffer_pool_read_requests{cluster=db,host=*}');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "innodb buffer pool write requests";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('sum:1m-avg:rate:mysql.innodb_buffer_pool_write_requests{cluster=db,host=*}');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

$template->render();