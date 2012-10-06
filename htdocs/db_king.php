<?php

require_once dirname(dirname(__FILE__)) . '/lib/bootstrap.php';

// Get the values from the GET/POST
$graphTime = !empty($_GET['time']) ? $_GET['time'] : "1h";

$title = "DB (King) Metrics";
$template = new GraphContainer($graphTime, $title);
$template->setGraphTime($graphTime);
$graphWidth = 900;
$graphHeight = 550;

    
/*
 * <h1>Web Cluster (<?php echo Dashboard::displayTime($time) ?>)</h1>
 */
        
{
    $graphName = "CPU Total use %";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('avg:1m-avg:rate:proc.stat.cpu{cluster=db,type=total,host=*}');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "Load Avg. last minute";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('avg:1m-avg:proc.loadavg.1min{cluster=db,host=*}');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

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


$template->render();