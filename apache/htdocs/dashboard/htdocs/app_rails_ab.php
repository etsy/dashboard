<?php

require_once dirname(dirname(__FILE__)) . '/lib/bootstrap.php';

// Get the values from the GET/POST
$graphTime = !empty($_GET['time']) ? $_GET['time'] : "1h";
$graphSize = !empty($_GET['size']) ? $_GET['size'] : "700x450";
$sizeArray = Dashboard::getWidthHeight();
$graphWidth = $sizeArray[$graphSize][0];
$graphHeight = $sizeArray[$graphSize][1];

$title = "REFRESH Tier Rails - ab_test Metrics";
$template = new GraphContainer($graphTime, $title);
$template->setGraphTime($graphTime);

    
/*
 * <h1>Web Cluster (<?php echo Dashboard::displayTime($time) ?>)</h1>
 */

 {
     $graphName = "Passenger Private Process Memory Count Aggregate";
     $tsd = new Tsd($graphTime);
     $tsd->addMetric('sum:1m-avg:rails.passenger.proc.mem.priv.total{app_type=ab_test,cluster=app}');
     $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
 }
 
 {
     $graphName = "Passenger Private Process Memory Count by server";
     $tsd = new Tsd($graphTime);
     $tsd->addMetric('sum:1m-avg:rails.passenger.proc.mem.priv.total{app_type=ab_test,host=*,cluster=app}');
     $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
 }
 
{
 $graphName = "Rack Process Count Aggregate";
 $tsd = new Tsd($graphTime);
 $tsd->addMetric('sum:rails.rack.proc.count{rack_proc=okl_ab_test_prod_rails_current,cluster=app}');
 $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
  $graphName = "Rack Process Count by server";
  $tsd = new Tsd($graphTime);
  $tsd->addMetric('sum:rails.rack.proc.count{rack_proc=okl_ab_test_prod_rails_current,host=*,cluster=app}');
  $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}
        
{
  $graphName = "Rack Private Process Memory Count Aggregate";
  $tsd = new Tsd($graphTime);
  $tsd->addMetric('sum:rails.rack.proc.mem.priv.total{rack_proc=okl_ab_test_prod_rails_current,cluster=app}');
  $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
   $graphName = "Rack Private Process Memory Count by server";
   $tsd = new Tsd($graphTime);
   $tsd->addMetric('sum:rails.rack.proc.mem.priv.total{rack_proc=okl_ab_test_prod_rails_current,host=*,cluster=app}');
   $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}



$template->render();