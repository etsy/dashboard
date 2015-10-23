<?php

// the title used for the page
$title = 'Splunk Example';

require_once 'phplib/Dashboard.php';

$graphs = array(
    'splunk' => array(
        array(
            'type' => 'splunk',
            'query' => 'test query 1',
            'title' => 'Test Query 1<br>',
        ),
        array(
            'type' => 'splunk',
            'query' => 'test query 2',
            'title' => 'Test Query 2<br>',
        ),
    ),
);

$tabs = Dashboard::$DEPLOY_TABS;
$tab_url = Dashboard::getTabUrl(__FILE__);

include 'phplib/template.php';
