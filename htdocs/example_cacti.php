<?php

// the title used for the page
$title = 'Cacti Example';

require_once 'phplib/Dashboard.php';

$cacti_graphs = array(
    'system01-cpu' => array(
            'type' => 'cacti',
            'metric' => 5776,
            'width' => 603,
            'height' => 269,
    ),
    'file02-nfs' => array(
            'type' => 'cacti',
            'metric' => '5777',
            'width' => 603,
            'height' => 269
    ),
    'system02-cpu' => array(
            'type' => 'cacti',
            'metric' => '5787',
            'width' => 603,
            'height' => 269
    ),
    'system02-nfs' => array(
            'type' => 'cacti',
            'metric' => '5788',
            'width' => 603,
            'height' => 269
    ),
);

$network_graphs = array(
    'system01-link01' => array(
        'type' => 'fitb',
        'host' => 'router1',
        'portname' => 'router1-port1',
        'graphtype' => 'bits',
        'title' => 'router1 uplink to system01'
    ),
    'system01-link02' => array(
        'type' => 'fitb',
        'host' => 'router2',
        'portname' => 'router2-port1',
        'graphtype' => 'bits',
        'title' => 'router2 uplink to system02'
    ),
   'system02-link01' => array(
        'type' => 'fitb',
        'host' => 'router1',
        'portname' => 'router1-port2',
        'graphtype' => 'bits',
        'title' => 'router1 uplink to system02'
    ),
    'system02-link02' => array(
        'type' => 'fitb',
        'host' => 'router2',
        'portname' => 'router2-port2',
        'graphtype' => 'bits',
        'title' => 'router2 uplink to system02'
    ),

);

$graphs = array(
    'System Utilisation' => $cacti_graphs,
    'Network Activity' => $network_graphs,
);

include 'phplib/template.php';
