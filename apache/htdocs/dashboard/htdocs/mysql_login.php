<?php

require_once dirname(dirname(__FILE__)) . '/lib/bootstrap.php';

// Get the values from the GET/POST
$graphTime = !empty($_GET['time']) ? $_GET['time'] : "1h";
$graphSize = !empty($_GET['size']) ? $_GET['size'] : "700x450";
$sizeArray = Dashboard::getWidthHeight();
$graphWidth = $sizeArray[$graphSize][0];
$graphHeight = $sizeArray[$graphSize][1];

$graphDownSample = !empty($_GET['downsample']) ? $_GET['downsample'] : "1m";

$graphInterval = "1 minute";
$title = "Logins (from tracking DB) per $graphInterval - $graphDownSample downsample";
$template = new GraphContainer($graphTime, $title);
$template->setGraphTime($graphTime);

    
/*
 * <h1>Web Cluster (<?php echo Dashboard::displayTime($time) ?>)</h1>
 */
        
{
    $graphName = "All Logins per $graphInterval - $graphDownSample downsample";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric("sum:$graphDownSample-avg:analytics.mysql.login.utm_medium.count");
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "Logins by shopper tier per $graphInterval - $graphDownSample downsample";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric("sum:$graphDownSample-avg:analytics.mysql.login.shopper_tier_id.count{shopper_tier_id=*}");
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "Logins by utm_medium: email per $graphInterval - $graphDownSample downsample";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric("sum:$graphDownSample-avg:analytics.mysql.login.utm_medium.count{utm_medium=email}");
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}


{
    $graphName = "Logins by utm_medium: display per $graphInterval - $graphDownSample downsample";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric("sum:$graphDownSample-avg:analytics.mysql.login.utm_medium.count{utm_medium=display}");
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "Logins by utm_medium: search per $graphInterval - $graphDownSample downsample";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric("sum:$graphDownSample-avg:analytics.mysql.login.utm_medium.count{utm_medium=search}");
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}



$template->render();