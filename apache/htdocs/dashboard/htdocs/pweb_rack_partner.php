<?php

require_once dirname(dirname(__FILE__)) . '/lib/bootstrap.php';

// Get the values from the GET/POST
$graphTime = !empty($_GET['time']) ? $_GET['time'] : "1h";
$graphSize = !empty($_GET['size']) ? $_GET['size'] : "700x450";
$sizeArray = Dashboard::getWidthHeight();
$graphWidth = $sizeArray[$graphSize][0];
$graphHeight = $sizeArray[$graphSize][1];

$title = "PWEB Tier Rack: Partner Metrics";
$template = new GraphContainer($graphTime, $title);
$template->setGraphTime($graphTime);


    
/*
 * <h1>Web Cluster (<?php echo Dashboard::displayTime($time) ?>)</h1>
 */

{
 $graphName = "Rack:Partner Process Count Aggregate";
 $tsd = new Tsd($graphTime);
 $tsd->addMetric('sum:rails.rack.proc.count{cluster=pweb,rack_proc=okl_partner_portal_prod_rails_current}');
 $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
  $graphName = "Rack:Partner Process Count by server";
  $tsd = new Tsd($graphTime);
  $tsd->addMetric('sum:rails.rack.proc.count{cluster=pweb,rack_proc=okl_partner_portal_prod_rails_current,host=*}');
  $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
  $graphName = "Rack:Partner Private Process Memory Count Aggregate";
  $tsd = new Tsd($graphTime);
  $tsd->addMetric('sum:rails.rack.proc.mem.priv.total{cluster=pweb,rack_proc=okl_partner_portal_prod_rails_current}');
  $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
   $graphName = "Rack:Partner Private Process Memory Count by server";
   $tsd = new Tsd($graphTime);
   $tsd->addMetric('sum:rails.rack.proc.mem.priv.total{cluster=pweb,rack_proc=okl_partner_portal_prod_rails_current,host=*}');
   $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}


$template->render();