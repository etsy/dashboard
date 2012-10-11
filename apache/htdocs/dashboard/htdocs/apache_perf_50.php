<?php

require_once dirname(dirname(__FILE__)) . '/lib/bootstrap.php';

// Get the values from the GET/POST
$graphTime = !empty($_GET['time']) ? $_GET['time'] : "1h";
$graphSize = !empty($_GET['size']) ? $_GET['size'] : "700x450";
$graphDownSample = !empty($_GET['downsample']) ? $_GET['downsample'] : "1m";
$sizeArray = Dashboard::getWidthHeight();
$graphWidth = $sizeArray[$graphSize][0];
$graphHeight = $sizeArray[$graphSize][1];

$perf_percent = "50";
$graphInterval = "10 seconds";

$title = "Apache Page Serve Time - $perf_percent % Percentile per $graphInterval per $graphInterval - $graphDownSample Downsample";
$template = new GraphContainer($graphTime, $title);
$template->setGraphTime($graphTime);

    
/*
 * <h1>Web Cluster (<?php echo Dashboard::displayTime($time) ?>)</h1>
 */
        
{
    $graphName = "All Page Serve Time (ms) - $perf_percent % per $graphInterval per $graphInterval - $graphDownSample Downsample";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric("avg:$graphDownSample-avg:analytics.apache.ten_sec.page.serve.$perf_percent");
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "All Page Serve Time (ms) per serve- $perf_percent % per $graphInterval per $graphInterval - $graphDownSample Downsample";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric("avg:$graphDownSample-avg:analytics.apache.ten_sec.page.serve.$perf_percent{host=*}");
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "/sales Page Serve Time (ms) per serve- $perf_percent % per $graphInterval per $graphInterval - $graphDownSample Downsample";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric("avg:$graphDownSample-avg:analytics.apache.ten_sec.page.serve.$perf_percent{page_type=_sales}");
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "/product Page Serve Time (ms) per serve- $perf_percent % per $graphInterval per $graphInterval - $graphDownSample Downsample";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric("avg:$graphDownSample-avg:analytics.apache.ten_sec.page.serve.$perf_percent{page_type=_product}");
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "/add-to-cart-ajax.json Page Serve Time (ms) per serve- $perf_percent % per $graphInterval per $graphInterval - $graphDownSample Downsample";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric("avg:$graphDownSample-avg:analytics.apache.ten_sec.page.serve.$perf_percent{page_type=_add-to-cart-ajax.json}");
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}


{
    $graphName = "DS Level 1 Page Serve Time (ms) per serve- $perf_percent % per $graphInterval per $graphInterval - $graphDownSample Downsample";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric("avg:$graphDownSample-avg:analytics.apache.ten_sec.page.serve.$perf_percent{page_type=ds_l1}");
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "DS Level 2 Page Serve Time (ms) per serve- $perf_percent % per $graphInterval per $graphInterval - $graphDownSample Downsample";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric("avg:$graphDownSample-avg:analytics.apache.ten_sec.page.serve.$perf_percent{page_type=ds_l2}");
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}


$template->render();