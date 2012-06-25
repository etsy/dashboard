<?php
require_once 'phplib/Dashboard.php';

/** the title used for the page */
$title = 'Chef';

/** a short alphanumeric string (used for CSS) */
$namespace = 'chef';

/** Chef server hostname */
$chefserver = 'chef_server_hostname';

/** Ganglia cluster which containts your chef server */
$gangliacluster = 'Utilities';

/** sections and graphs to be shown on the page */
$graphs = array(
    'Chef API' => array(
        array(
            'type' => 'ganglia',
            'metric' => array('source' => $gangliacluster, 'node' => $chefserver, 'datum' => 'apache_average_response_time'),
            'width' => GraphConstants::THREE_GRAPH_WIDTH,
        ),
        array(
            'type' => 'ganglia',
            'metric' => array('source' => $gangliacluster, 'node' => $chefserver, 'datum' => 'apache_perc95_response_time'),
            'width' => GraphConstants::THREE_GRAPH_WIDTH,
        ),
        array(
            'type' => 'ganglia',
            'metric' => array('source' => $gangliacluster, 'node' => $chefserver, 'datum' => 'apache_requests_per_second'),
            'width' => GraphConstants::THREE_GRAPH_WIDTH,
        ),
    ),

    'Run Times' => array(
        array(
            'type' => 'graphite',
            'title' => 'Average Elapsed Times',
            'metrics' => array(
                'keepLastValue(averageSeries(chef.runs.*.elapsed_time))',
            ),
            'colors' => array('blue'),
            'width' => 440,
            'height' => 280,
        ), 
        array(
            'type' => 'graphite',
            'title' => 'Max Elapsed Times',
            'metrics' => array(
                'maxSeries(chef.runs.*.elapsed_time)',
            ),
            'colors' => array('blue'),
            'width' => 440,
            'height' => 280,
        ), 
        array(
            'type' => 'graphite',
            'title' => 'All Elapsed Times',
            'metrics' => array(
                'chef.runs.*.elapsed_time',
            ),
            'colors' => array('blue'),
            'width' => 440,
            'height' => 280,
        ), 
    ),

    // these batch runs into buckets
    // otherwise the numbers don't line up enough to give 
    // a semi-accurate picture, its not perfect though
    'Status' => array(
        array(
            'type' => 'graphite',
            'title' => 'successful runs (blue), failed runs (red)',
            'metrics' => array(
                "sum(summarize(chef.runs.*.success,'10min'))",
                "sum(summarize(chef.runs.*.fail,'10min'))",
            ),
            'colors' => array('blue', 'red'),
            'width' => 440,
            'height' => 280,
        ), 
        array(
            'type' => 'graphite',
            'title' => 'updated resources',
            'metrics' => array(
                "sum(summarize(chef.runs.*.updated_resources,'10min'))",
            ),
            'colors' => array('blue'),
            'width' => 440,
            'height' => 280,
        ), 
    ),

    'Deploys' => array(
        array(
            'type' => 'graphite',
            'title' => 'dev (green), prod (red)',
            'metrics' => array(
                'drawAsInfinite(deploys.chef.development)',
                'drawAsInfinite(deploys.chef.production)',
            ),
            'colors' => array('green', 'red'),
            'width' => 440,
            'height' => 280,
        ), 
    ),

);

/** actually draws the page */
include 'phplib/template.php';
