<?php

require_once dirname(dirname(__FILE__)) . '/lib/bootstrap.php';

// Get the values from the GET/POST
$graphTime = !empty($_GET['time']) ? $_GET['time'] : "1h";
$graphSize = !empty($_GET['size']) ? $_GET['size'] : "700x450";
$graphDownSample = !empty($_GET['downsample']) ? $_GET['downsample'] : "1m";
#$graphDownSampleType = !empty($_GET['downsampletype']) ? $_GET['downsampletype'] : "sum";
$sizeArray = Dashboard::getWidthHeight();
$graphWidth = $sizeArray[$graphSize][0];
$graphHeight = $sizeArray[$graphSize][1];

$graphInterval = "10 seconds";
$title = "Apache Traffic per $graphInterval - $graphDownSample downsample";
$template = new GraphContainer($graphTime, $title);
$template->setGraphTime($graphTime);
    
/*
 * <h1>Web Cluster (<?php echo Dashboard::displayTime($time) ?>)</h1>
 */
        
{
    $graphName = "Total Page Views per $graphInterval - $graphDownSample downsample";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric("sum:$graphDownSample-avg:analytics.apache.ten_sec.page.count");
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "Page Views by web server per $graphInterval - $graphDownSample downsample";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric("sum:$graphDownSample-avg:analytics.apache.ten_sec.page.count{host=*}");
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "Total /sales Page views per $graphInterval - $graphDownSample downsample";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric("sum:$graphDownSample-avg:analytics.apache.ten_sec.page.count{page_type=_sales}");
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "Total /product Page views per $graphInterval - $graphDownSample downsample";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric("sum:$graphDownSample-avg:analytics.apache.ten_sec.page.count{page_type=_product}");
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "Total /add-to-cart-ajax.json Page views per $graphInterval - $graphDownSample downsample";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric("sum:$graphDownSample-avg:analytics.apache.ten_sec.page.count{page_type=_add-to-cart-ajax.json}");
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}


{
    $graphName = "Total DS Level 1 Page views per $graphInterval - $graphDownSample downsample";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric("sum:$graphDownSample-avg:analytics.apache.ten_sec.page.count{page_type=ds_l1}");
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "Total DS Level 2 Page views per $graphInterval - $graphDownSample downsample";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric("sum:$graphDownSample-avg:analytics.apache.ten_sec.page.count{page_type=ds_l2}");
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}


$template->render();