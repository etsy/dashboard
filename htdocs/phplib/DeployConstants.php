<?php

/**
 * We log each deploy as a metric in Graphite. This helps us keep track of which 
 * type of deploy was done, and when.
 * This array controls which deploys we show on various graphs, and how to find 
 * them in graphite.
 */
class DeployConstants {
    public static $deploys = array(
        'web' => array('color' => '#ff0000', 'title' => 'Web', 'target' => 'deploys.web.production'),
        'search' => array('color' => '#006633', 'title' => 'Search', 'target' => 'deploys.search.production'),
        'blog' => array('color' => '#ff00ff', 'title' => 'Blog', 'target' => 'deploys.blog.production'),
        'chef' => array('color' => '#000000', 'title' => 'Chef', 'target' => 'deploys.chef.production'),
    );
}
