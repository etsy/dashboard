<?php

require_once dirname(dirname(__FILE__)) . '/lib/bootstrap.php';

// Get the values from the GET/POST
$graphTime = !empty($_GET['time']) ? $_GET['time'] : "1h";
$graphSize = !empty($_GET['size']) ? $_GET['size'] : "700x450";
$sizeArray = Dashboard::getWidthHeight();
$graphWidth = $sizeArray[$graphSize][0];
$graphHeight = $sizeArray[$graphSize][1];

$title = "DB (King) Metrics";
$template = new GraphContainer($graphTime, $title);
$template->setGraphTime($graphTime);
    
/*
 * <h1>Web Cluster (<?php echo Dashboard::displayTime($time) ?>)</h1>
 */
        
{
    $graphName = "king-prod-db01 query types";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('sum:1m-avg:rate:mysql.com_select{host=sac-prod-db-01.unix.newokl.com}');
    $tsd->addMetric('sum:1m-avg:rate:mysql.com_insert{host=sac-prod-db-01.unix.newokl.com}');
    $tsd->addMetric('sum:1m-avg:rate:mysql.com_update{host=sac-prod-db-01.unix.newokl.com}');
    $tsd->addMetric('sum:1m-avg:rate:mysql.com_replace{host=sac-prod-db-01.unix.newokl.com}');
    $tsd->addMetric('sum:1m-avg:rate:mysql.com_update_multi{host=sac-prod-db-01.unix.newokl.com}');
    $tsd->addMetric('sum:1m-avg:rate:mysql.com_load{host=sac-prod-db-01.unix.newokl.com}');
    $tsd->addMetric('sum:1m-avg:rate:mysql.com_delete{host=sac-prod-db-01.unix.newokl.com}');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "king-prod-db02 query types";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('sum:1m-avg:rate:mysql.com_select{host=sac-prod-db-02.unix.newokl.com}');
    $tsd->addMetric('sum:1m-avg:rate:mysql.com_insert{host=sac-prod-db-02.unix.newokl.com}');
    $tsd->addMetric('sum:1m-avg:rate:mysql.com_update{host=sac-prod-db-02.unix.newokl.com}');
    $tsd->addMetric('sum:1m-avg:rate:mysql.com_replace{host=sac-prod-db-02.unix.newokl.com}');
    $tsd->addMetric('sum:1m-avg:rate:mysql.com_update_multi{host=sac-prod-db-02.unix.newokl.com}');
    $tsd->addMetric('sum:1m-avg:rate:mysql.com_load{host=sac-prod-db-02.unix.newokl.com}');
    $tsd->addMetric('sum:1m-avg:rate:mysql.com_delete{host=sac-prod-db-02.unix.newokl.com}');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "Replication - seconds behind master";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('sum:mysql.slave.seconds_behind_master{host=*}');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "king-prod-db01 SELECT types";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('sum:rate:mysql.select_scan{host=sac-prod-db-01.unix.newokl.com}');
    $tsd->addMetric('sum:rate:mysql.select_range_check{host=sac-prod-db-01.unix.newokl.com}');
    $tsd->addMetric('sum:rate:mysql.select_range{host=sac-prod-db-01.unix.newokl.com}');
    $tsd->addMetric('sum:rate:mysql.select_full_range_join{host=sac-prod-db-01.unix.newokl.com}');
    $tsd->addMetric('sum:rate:mysql.select_full_join{host=sac-prod-db-01.unix.newokl.com}');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "king-prod-db02 SELECT types";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('sum:rate:mysql.select_scan{host=sac-prod-db-02.unix.newokl.com}');
    $tsd->addMetric('sum:rate:mysql.select_range_check{host=sac-prod-db-02.unix.newokl.com}');
    $tsd->addMetric('sum:rate:mysql.select_range{host=sac-prod-db-02.unix.newokl.com}');
    $tsd->addMetric('sum:rate:mysql.select_full_range_join{host=sac-prod-db-02.unix.newokl.com}');
    $tsd->addMetric('sum:rate:mysql.select_full_join{host=sac-prod-db-02.unix.newokl.com}');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "Connections by host";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('sum:rate:mysql.connections{cluster=db,host=*}');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "king-prod-db01 Connection States";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('sum:mysql.connection_states{cluster=db,host=sac-prod-db-01.unix.newokl.com,state=*}');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "king-prod-db02 Connection States";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('sum:mysql.connection_states{cluster=db,host=sac-prod-db-02.unix.newokl.com,state=*}');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "king-prod-db03 Connection States";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('sum:mysql.connection_states{cluster=db,host=sac-prod-pdb-01.unix.newokl.com,state=*}');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}


$template->render();