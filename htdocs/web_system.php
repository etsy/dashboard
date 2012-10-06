<?php

require_once dirname(dirname(__FILE__)) . '/lib/bootstrap.php';

// Get the values from the GET/POST
$graphTime = !empty($_GET['time']) ? $_GET['time'] : "1h";

$title = "Web Tier System Metrics";
$template = new GraphContainer($graphTime, $title);
$template->setGraphTime($graphTime);
$graphWidth = 900;
$graphHeight = 550;

    
/*
 * <h1>Web Cluster (<?php echo Dashboard::displayTime($time) ?>)</h1>
 */

 {
     $graphName = "CPU Total use % Aggregate";
     $tsd = new Tsd($graphTime);
     $tsd->addMetric('avg:1m-avg:rate:proc.stat.cpu{cluster=web,type=total}');
     $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
 }
        
{
    $graphName = "CPU Total use % by server";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('avg:1m-avg:rate:proc.stat.cpu{cluster=web,type=total,host=*}');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}
{
    $graphName = "Load Avg. last minute Aggregate";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('avg:1m-avg:proc.loadavg.1min{cluster=web}');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "Load Avg. last minute by server";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('avg:1m-avg:proc.loadavg.1min{cluster=web,host=*}');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

$template->render();