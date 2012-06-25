<?php

/** We store our Postgres query counts in Graphite, which lets us easily pull 
 * them out here.
 */

require_once "phplib/Dashboard.php";

$title = "Postgresql Data Changing Queries";

$queries = array("INSERT", "UPDATE", "DELETE");

$aggr_graph = array(
    array(
        'type' => 'graphite',
        'title' => 'aggregate',
        'metrics' => array(
            "sumSeries(postgresql_query.INSERT.*)",
            "sumSeries(postgresql_query.UPDATE.*)",
            "sumSeries(postgresql_query.DELETE.*)",
        ),
        'width' => 1102,
        'height' => 200,
        'stacked' => true,
        'show_html_legend' => true,
        'legend_keys' => array( "INSERT", "UPDATE", "DELETE" )
    )
);

$graphs = array(
    "aggregated INSERT/UPDATE/DELETE" => $aggr_graph
);

$tabs = Dashboard::$DB_TABS;
$tab_url = Dashboard::getTabUrl(__FILE__);

include "phplib/template.php";
