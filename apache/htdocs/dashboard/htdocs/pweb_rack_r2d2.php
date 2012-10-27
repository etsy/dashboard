<?php

require_once dirname(dirname(__FILE__)) . '/lib/bootstrap.php';

// Get the values from the GET/POST
$graphTime = !empty($_GET['time']) ? $_GET['time'] : "1h";
$graphSize = !empty($_GET['size']) ? $_GET['size'] : "700x450";
$sizeArray = Dashboard::getWidthHeight();
$graphWidth = $sizeArray[$graphSize][0];
$graphHeight = $sizeArray[$graphSize][1];

$title = "PWEB Tier Rack: r2d2 Metrics";
$template = new GraphContainer($graphTime, $title);
$template->setGraphTime($graphTime);


    
/*
 * <h1>Web Cluster (<?php echo Dashboard::displayTime($time) ?>)</h1>
 */

{
 $graphName = "Rack:r2d2 Process Count Aggregate";
 $tsd = new Tsd($graphTime);
 $tsd->addMetric('sum:rails.rack.proc.count{cluster=pweb,rack_proc=okl_r2d2_prod_rails_current}');
 $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
  $graphName = "Rack:r2d2 Process Count by server";
  $tsd = new Tsd($graphTime);
  $tsd->addMetric('sum:rails.rack.proc.count{cluster=pweb,rack_proc=okl_r2d2_prod_rails_current,host=*}');
  $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
  $graphName = "Rack:r2d2 Private Process Memory Count Aggregate";
  $tsd = new Tsd($graphTime);
  $tsd->addMetric('sum:rails.rack.proc.mem.priv.total{cluster=pweb,rack_proc=okl_r2d2_prod_rails_current}');
  $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
   $graphName = "Rack:r2d2 Private Process Memory Count by server";
   $tsd = new Tsd($graphTime);
   $tsd->addMetric('sum:rails.rack.proc.mem.priv.total{cluster=pweb,rack_proc=okl_r2d2_prod_rails_current,host=*}');
   $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}


$template->render();