<?php

require_once 'phplib/Dashboard.php';

/** sections and graphs to be shown on the page */
$graphs = array(
    'router1' => array(
        array(
            'type' => 'fitb',
            'host' => 'router1',    # The name of the host
            'portname' => 'router1-GigabitEthernet-1-0-0',    # The name of the RRD
            'graphtype' => 'bits',    # bits / ucastpkts / errors
            'title' => 'Router 1 GE Port 1-0-0'    # Free text title
        ),
        array(
            'type' => 'fitb',
            'host' => 'router1',    # The name of the host
            'portname' => 'router1-GigabitEthernet-1-0-1',    # The name of the RRD
            'graphtype' => 'bits',    # bits / ucastpkts / errors
            'title' => 'Router 1 GE Port 1-0-1'    # Free text title
        ),
    ),
    'router1' => array(
        array(
            'type' => 'fitb',
            'host' => 'router2',    # The name of the host
            'portname' => 'router2-GigabitEthernet-1-0-0',    # The name of the RRD
            'graphtype' => 'bits',    # bits / ucastpkts / errors
            'title' => 'Router 1 GE Port 1-0-0'    # Free text title
        ),
        array(
            'type' => 'fitb',
            'host' => 'router2',    # The name of the host
            'portname' => 'router2-GigabitEthernet-1-0-1',    # The name of the RRD
            'graphtype' => 'bits',    # bits / ucastpkts / errors
            'title' => 'Router 2 GE Port 1-0-1'    # Free text title
        ),
    ),
);


/** actually draws the page */
include 'phplib/template.php';
