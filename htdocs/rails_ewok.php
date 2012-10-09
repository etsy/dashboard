<?php

require_once dirname(dirname(__FILE__)) . '/lib/bootstrap.php';

// Get the values from the GET/POST
$graphTime = !empty($_GET['time']) ? $_GET['time'] : "1h";
$graphSize = !empty($_GET['size']) ? $_GET['size'] : "1000x700";
$sizeArray = Dashboard::getWidthHeight();
$graphWidth = $sizeArray[$graphSize][0];
$graphHeight = $sizeArray[$graphSize][1];

$title = "Web Tier Rails - ewok Metrics";
$template = new GraphContainer($graphTime, $title);
$template->setGraphTime($graphTime);


    
/*
 * <h1>Web Cluster (<?php echo Dashboard::displayTime($time) ?>)</h1>
 */

 {
     $graphName = "Passenger Private Process Memory Count Aggregate";
     $tsd = new Tsd($graphTime);
     $tsd->addMetric('sum:1m-avg:rails.passenger.proc.mem.priv.total{app_type=king}');
     $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
 }
 
 {
     $graphName = "Passenger Private Process Memory Count by server";
     $tsd = new Tsd($graphTime);
     $tsd->addMetric('sum:1m-avg:rails.passenger.proc.mem.priv.total{app_type=king,host=*}');
     $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
 }
 
{
 $graphName = "Rack Process Count Aggregate";
 $tsd = new Tsd($graphTime);
 $tsd->addMetric('sum:rails.rack.proc.count{rack_proc=okl_king_prod_rails_current}');
 $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
  $graphName = "Rack Process Count by server";
  $tsd = new Tsd($graphTime);
  $tsd->addMetric('sum:rails.rack.proc.count{rack_proc=okl_king_prod_rails_current,host=*}');
  $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}
        
{
  $graphName = "Rack Private Process Memory Count Aggregate";
  $tsd = new Tsd($graphTime);
  $tsd->addMetric('sum:rails.rack.proc.mem.priv.total{rack_proc=okl_king_prod_rails_current}');
  $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
   $graphName = "Rack Private Process Memory Count by server";
   $tsd = new Tsd($graphTime);
   $tsd->addMetric('sum:rails.rack.proc.mem.priv.total{rack_proc=okl_king_prod_rails_current,host=*}');
   $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}



$template->render();