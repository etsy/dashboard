<?php

require_once dirname(dirname(__FILE__)) . '/lib/bootstrap.php';

// Get the values from the GET/POST
$graphTime = !empty($_GET['time']) ? $_GET['time'] : "1h";

$title = "Web Cluster";
$template = new GraphContainer($graphTime, $title);
$template->setGraphTime($graphTime);

    
/*
 * <h1>Web Cluster (<?php echo Dashboard::displayTime($time) ?>)</h1>
 */
        
{
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('avg:1m-avg:rate:proc.stat.cpu{cluster=web,type=total}');
    $template->addGraph($tsd->getDashboardHTML(500, 250));
}

{
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('avg:1m-avg:proc.loadavg.1min{cluster=web}');
    $template->addGraph($tsd->getDashboardHTML(500, 250));
}

{
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('sum:1m-avg:rate:apache.stats.served.kbytes{cluster=web}');
    $template->addGraph($tsd->getDashboardHTML(500, 250));
}

$template->render();