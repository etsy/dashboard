<?php

class TimingUtils {
    /**
     * @param string $graph_title
     * @param string $metric_parent
     * @param array $metrics
     * @param string $average
     * @param int $performance_expectation
     * @param int $width
     * @param array $children
     * @return array
     */
    public static function buildAverageGraph($graph_title, $metric_parent, $metrics, $average, $performance_expectation, $width, $children = array()) {
        $average_metrics = array();
        $average_legend_keys = array();
        $colors = null;

        if (!$children && count($metrics) == 1) {
            $average_metrics[] = "lineWidth(historicalAverage(keepLastValue(stats.timers.$metric_parent.{$metrics[0]}.all.$average)),3)";
            $average_legend_keys[] = "";
            $colors = array('dddddd', '00cc00', 'bbbb00');
        }

        foreach ($metrics as $metric) {
            $average_metrics[] = "keepLastValue(stats.timers.$metric_parent.$metric.all.$average)";

            if (count($metrics) == 1) {
                $average_legend_keys[] = $children ? "everyone" : "";
            } else {
                $average_legend_keys[] = $children ? "$metric (everyone)" : "$metric";
            }

            foreach ($children as $child) {
                $average_metrics[] = "keepLastValue(stats.timers.$metric_parent.$metric.$child.$average)";
                if (count($metrics) == 1) {
                    $average_legend_keys[] = "$child";
                } else {
                    $average_legend_keys[] = "$metric ($child)";
                }
            }
        }

        $average_metrics[] = 'dashed(constantLine(' . $performance_expectation . '))';

        return self::buildGraphite(
            "$graph_title<br/>Response Time ($average)",
            $average_metrics,
            $average_legend_keys,
            $width,
            $colors);
    }

    public static function buildPie($graph_title, $metric_parent, $metrics, $width, $children) {
        $count_metrics = array();
        $legend_keys = array();

        foreach ($metrics as $metric) {
            foreach ($children as $child) {
                $count_metrics[] = "stats.timers.$metric_parent.$metric.$child.count";

                if (count($metrics) == 1) {
                    $legend_keys[] = "$child";
                } else {
                    $legend_keys[] = "$metric ($child)";
                }
            }
        }

        $graph = self::buildGraphitePie($graph_title, $count_metrics, $legend_keys, $width);

        return $graph;
    }

    /**
     * @param string $graph_title
     * @param string $metric_parent
     * @param array $metrics
     * @param int $width
     * @param array $children
     * @return array
     */
    public static function buildCountGraph($graph_title, $metric_parent, $metrics, $width, $children = array()) {
        $count_metrics = array();
        $legend_keys = array();
        $colors = null;

        if ($children) {
            foreach ($metrics as $metric) {
                $main_metric = "fillValue(diffSeries(stats.timers.$metric_parent.$metric.all.count";

                foreach ($children as $child) {
                    $main_metric .= ",stats.timers.$metric_parent.$metric.$child.count";
                }

                $main_metric .= "),0)";

                $count_metrics[] = $main_metric;

                if (count($metrics) == 1) {
                    $legend_keys[] = "everyone else";
                } else {
                    $legend_keys[] = "$metric (everyone else)";
                }

                foreach ($children as $child) {
                    $count_metrics[] = "fillValue(stats.timers.$metric_parent.$metric.$child.count,0)";
                    if (count($metrics) == 1) {
                        $legend_keys[] = "$child";
                    } else {
                        $legend_keys[] = "$metric ($child)";
                    }
                }
            }
        } else {
            if (count($metrics) == 1) {
                $count_metrics[] = "lineWidth(historicalAverage(fillValue(stats.timers.$metric_parent.{$metrics[0]}.all.count,0)),3)";
                $legend_keys[] = "";
                $colors = array('dddddd', '00cc00');
            }

            foreach ($metrics as $metric) {
                $count_metrics[] = "fillValue(stats.timers.$metric_parent.$metric.all.count,0)";

                if (count($metrics) == 1) {
                    $legend_keys[] = '';
                } else {
                    $legend_keys[] = $metric;
                }
            }
        }

        $graph = self::buildGraphite($graph_title . '<br/>Count', $count_metrics, $legend_keys, $width, $colors);

        if ($children) {
            $graph['stacked'] = true;
        }

        return $graph;
    }

    /**
     * @param string $title
     * @param array $metrics
     * @param array(string) $legend_keys
     * @param int $width
     * @param array(string) $colors
     * @return array
     */
    public static function buildGraphite($title, $metrics, $legend_keys, $width, $colors = null) {
        $config = array(
            'type' => 'graphite',
            'title' => $title,
            'metrics' => $metrics,
            'legend_keys' => $legend_keys,
            'show_html_legend' => true,
            'width' => $width,
            'height' => GraphConstants::HEIGHT,
        );

        if ($colors != null) {
            $config['colors'] = $colors;
        }

        return $config;
    }

    /**
     * @param string $title
     * @param array $metrics
     * @param array(string) $legend_keys
     * @param int $width
     * @param array(string) $colors
     * @return array
     */
    public static function buildGraphitePie($title, $metrics, $legend_keys, $width, $colors = null) {
        $config = array(
            'type' => 'graphite_pie',
            'title' => $title,
            'metrics' => $metrics,
            'legend_keys' => $legend_keys,
            'width' => $width,
            'height' => GraphConstants::HEIGHT,
            'is_ajax' => true,
        );

        if ($colors != null) {
            $config['colors'] = $colors;
        }

        return $config;
    }

    /**
     * @param string $metric_parent
     * @param array $methods_to_filter
     * @return array
     */
    public static function getAllMethods($metric_parent, $methods_to_filter) {
        $methods = GraphiteHelper::fetchChildMetrics("stats.timers.$metric_parent.");

        $valid_methods = array();

        foreach ($methods as $method) {
            if (!in_array($method, $methods_to_filter)) {
                $valid_methods[] = $method;
            }
        }

        return $valid_methods;
    }

    /**
     * @param array $categorized_methods_map
     * @param string $category
     * @return array
     */
    public static function getCategorizedMethods($categorized_methods_map, $category = null) {
        if ($category && $category != 'UNCATEGORIZED') {
            return isset($categorized_methods_map[$category]) ? $categorized_methods_map[$category] : array();
        } else {
            $categorized_methods = array();

            foreach ($categorized_methods_map as $methods) {
                $categorized_methods = array_merge($categorized_methods, $methods);
            }

            sort($categorized_methods);

            return $categorized_methods;
        }
    }

    /**
     * @param array $all_methods
     * @param array $categorized_methods_map
     * @return array
     */
    public static function getUncategorizedMethods($all_methods, $categorized_methods_map) {
        $categorized_methods = self::getCategorizedMethods($categorized_methods_map, 'UNCATEGORIZED');

        $uncategorized_methods = array();

        foreach ($all_methods as $method) {
            if (!in_array($method, $categorized_methods)) {
                $uncategorized_methods[] = $method;
            }
        }

        return $uncategorized_methods;
    }

    /**
     * @param int $expectation
     * @return int
     */
    public static function getUnacceptableExpectation($expectation) {
        return $expectation * 4;
    }
}
