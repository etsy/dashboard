<?php

require_once dirname(dirname(__FILE__)) . '/lib/bootstrap.php';

// Get the values from the GET/POST
$graphTime = !empty($_GET['time']) ? $_GET['time'] : "1h";
$graphSize = !empty($_GET['size']) ? $_GET['size'] : "1000x700";
$sizeArray = Dashboard::getWidthHeight();
$graphWidth = $sizeArray[$graphSize][0];
$graphHeight = $sizeArray[$graphSize][1];

$title = "Logins (from tracking DB)";
$template = new GraphContainer($graphTime, $title);
$template->setGraphTime($graphTime);

    
/*
 * <h1>Web Cluster (<?php echo Dashboard::displayTime($time) ?>)</h1>
 */
        
{
    $graphName = "All Logins";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('sum:analytics.mysql.login.utm_medium.count');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "Logins by utm_medium: email";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric("avg:analytics.mysql.login.utm_medium.count{utm_medium=email}");
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "Logins by utm_medium: display";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric("avg:analytics.mysql.login.utm_medium.count{utm_medium=display}");
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "Logins by utm_medium: search";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric("avg:analytics.mysql.login.utm_medium.count{utm_medium=search}");
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}



$template->render();