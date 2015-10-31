<?php

require_once 'Dashboard.php';

$times = isset($times) ? $times : Dashboard::getTimes();

if (!isset($time)) {
    $time = !empty($_GET['time']) ? $_GET['time'] : '1h';
}

if (!isset($hide_deploys)) {
    $hide_deploys = !empty($_GET['hide_deploys']) ? $_GET['hide_deploys'] : false;
}

$show_deploys = (!$hide_deploys);

if (isset($graphs)) {
    if (Dashboard::hasGraphitePie($graphs)) {
        $page_js_imports = array_merge(
            isset($page_js_imports) ? $page_js_imports : array(),
            array(
                "assets/js/flot/jquery.flot.js",
                "assets/js/flot/jquery.flot.pie.js",
                "assets/js/flot.js",
                "assets/js/graphite_percentage.js",
            ));
    }

    if (Dashboard::hasGraphitePercentage($graphs)) {
        $page_js_imports = array_merge(
            isset($page_js_imports) ? $page_js_imports : array(),
            array(
                "assets/js/d3-2.9.1.min.js",
                "assets/js/graphite_percentage.js",
                "assets/js/graphite_bar_percentage.js",
            ));
    }

    $has_graphite_with_html_legend = false;

    foreach ((array)$graphs as $section) {
        foreach ((array)$section as $graph) {
            if (isset($graph['type']) && $graph['type'] == 'graphite' &&
                    isset($graph['show_html_legend']) && $graph['show_html_legend'] == true
            ) {
                $has_graphite_with_html_legend = true;
            }
        }
    }

    if ($has_graphite_with_html_legend) {
        $page_js_imports = array_merge(
            isset($page_js_imports) ? $page_js_imports : array(),
            array(
                "assets/js/zeroclipboard-0.7/ZeroClipboard.js",
                "assets/js/copyurl.js",
            ));
        $page_css_imports = array_merge(
            isset($page_css_imports) ? $page_css_imports : array(),
            array(
                "assets/css/copyurl.css",
            ));
    }
}
