<?php

class GraphFactory {
    /** @var GraphFactory */
    private static $instance;

    /** @var array */
    private static $NAMED_COLORS = array(
        'green' => '2cc900',
        'yellow' => 'bbbb00',
        'cadet-blue' => '5f9ea0',
        'orange' => 'ff6633',
        'purple' => '663399',
        'gray' => 'aaaaaa',
        'olive' => '6b8e23',
        'light-blue' => 'b0c4de',
        'medium-spring-green' => '00fa9a',
        'medium-purple' => '9370da',
        'fire-brick' => 'b22222',
        'cornflower-blue' => '6495ed',
        'light-purple' => '9966cc',
        'dark-green' => '006400',
        'dark-red' => '8b0000',
        'dark-olive-green' => '556b2f',
        'deep-pink' => 'ff1493',
        'light-salmon' => 'ffa07a',
        'lime-green' => '32cd32',
        'brown' => 'CC6600',
        'pale-green' => '98fb98',
        'orange-red' => 'ff4500',
        'pink' => 'ffc0cb',
        'plum' => 'dda0dd',
        'wheat' => 'f5deb3',
        'tan' => 'd2b48c',
        'thistle' => 'd8bfd8',
        'rosy-brown' => 'bc8f8f',
        'midnight-blue' => '191970',
        'blue' => '0000ff',
        'red' => 'ff0000',
        'pink' => 'cf17ab',
        'black' => '000000',
        'yellow-green' => '9acd32',
        'lightsteelblue' => 'b0c4de',
        'steelblue' => '4682b4',
    );

    /**
     * @return GraphFactory
     */
    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new GraphFactory();
        }

        return self::$instance;
    }

    private function __construct() {
    }

    /**
     *
     * @param array $sections
     * @param string $time
     * @param string $until
     * @param bool $show_deploys
     * @return string
     *
     * Note - this duplicates getDashboardSectionsHtml, except has until param.
     * If code is accepted upstream, we should replace all place we call previous function
     * with this, then delete previous function
     */
    public function getDashboardSectionsHTMLWithUntil($sections, $time, $until, $show_deploys) {
        $html = '';
        foreach ((array)$sections as $title => $graph_configs) {
            $html .= $this->getDashboardSectionHTMLWithUntil($title, $graph_configs, $time, $until, $show_deploys);
        }
        return $html;
    }

    /**
     * @param string $title
     * @param array $graph_configs
     * @param string $time
     * @param string $until
     * @param bool $show_deploys
     * @return string
     *
     * Note - should be temporary until we replace getDashboardSectionHTML using until param
     */
    public function getDashboardSectionHTMLWithUntil($title, $graph_configs, $time, $until, $show_deploys) {
        $html = "<div class='section'>";
        $html .= $this->getDashboardTitleHTML($title, $time, $until);
        foreach ((array)$graph_configs as $graph_config) {
            $graph_config['time'] = $time;
            $graph_config['until'] = $until;
            $graph_config['show_deploys'] = $show_deploys;
            $html .= $this->getDashboardHTML($graph_config);
        }
        $html .= "</div>";
        return $html;
    }

    /**
     * DEPRECATED
     *
     * @param array $sections
     * @param string $time
     * @param bool $show_deploys
     * @return string
     */
    public function getDashboardSectionsHTML($sections, $time, $show_deploys) {
        $html = '';
        foreach ((array)$sections as $title => $graph_configs) {
            $html .= $this->getDashboardSectionHTML($title, $graph_configs, $time, $show_deploys);
        }
        return $html;
    }

    /**
     * DEPRECATED
     *
     * @param string $title
     * @param array $graph_configs
     * @param string $time
     * @param bool $show_deploys
     * @return string
     */
    public function getDashboardSectionHTML($title, $graph_configs, $time, $show_deploys) {
        $html = "<div class='section'>";
        $html .= $this->getDashboardTitleHTML($title, $time);
        foreach ((array)$graph_configs as $graph_config) {
            $html .= $this->getDashboardHTML($graph_config, $time, $show_deploys);
        }
        $html .= "</div>";
        return $html;
    }

    public function getDashboardTitleHTML($title, $time = null, $until = null) {
        $time_html = '';
        $id = preg_replace("/[^a-z0-9]/", "", strtolower($title));
        if (!empty($time)) {
            $time_html = "(" . Dashboard::displayTime($time);
            if (!empty($until)) {
                $time_html .= " - " . Dashboard::displayTime($until);
            }
            $time_html .= ")";
        }
        return "<h2 id=\"$id\" class=\"section-title\"><a href=\"#$id\">$title</a> {$time_html}</h2>";
    }

    /**
     * @param array $graph_config
     * @param string $time
     * @param bool $show_deploys
     * @return string
     */
    public function getDashboardHTML($graph_config, $time = '1h', $show_deploys = true) {
        // TODO - once all functions below use only $graph_config param, we can remove these three lines
        // Hopefully, we can also remove $time and $show_deploys params passed into this function as well
        $width = isset($graph_config['width']) ? $graph_config['width'] : GraphConstants::THREE_GRAPH_WIDTH;
        $height = isset($graph_config['height']) ? $graph_config['height'] : GraphConstants::HEIGHT;
        $graph_time = isset($graph_config['time']) ? $graph_config['time'] : $time;

        // TODO - remove above wdith/height/grapht_time lines, migrate to storing values in graph_config
        $graph_config['width'] = isset($graph_config['width']) ? $graph_config['width'] : GraphConstants::THREE_GRAPH_WIDTH;
        $graph_config['height'] = isset($graph_config['height']) ? $graph_config['height'] : GraphConstants::HEIGHT;
        $graph_config['graph_time'] = isset($graph_config['time']) ? $graph_config['time'] : $time;
        if(!isset($graph_config['show_deploys'])) {
            $graph_config['show_deploys'] = $show_deploys;
        }

        if (isset($graph_config['type'])) {
            switch ($graph_config['type']) {
                case 'graphite':
                    return $this->getGraphiteDashboardHTML($graph_config);
                case 'graphite_pie':
                    return $this->getGraphitePieDashboardHTML($graph_config, $graph_time, $width, $height);
                case 'graphite_percentage':
                    return $this->getGraphitePercentageDashboardHTML($graph_config, $graph_time, $width, $height);
                case 'ganglia':
                    return $this->getGangliaDashboardHTML($graph_config);
                case 'cacti':
                    $until_time = isset($graph_config['until']) ? $graph_config['until'] : null;
                    $g = new Graph_Cacti($graph_time, $until_time);
                    $g->addMetric($graph_config['metric']);
                    return $g->getDashboardHTML($width, $height);
                case 'html':
                    $title = isset($graph_config['title']) ? $graph_config['title'] : '';
                    $html = $title ? "<h3>$title</h3>" : '';
                    $html .= isset($graph_config['html']) ? $graph_config['html'] : '';
                    return $html;
                case 'newrelic':
                    if ($graph_time != $graph_config['metric']['time']) { return; }
                    $graph_url = $graph_config['metric']['url'];
                    $g = new Graph_NewRelic($graph_time, $graph_url);
                    return $g->getDashboardHTML($width, $height, false);
                case 'fitb':
                    $g = new Graph_FITB($graph_time);
                    $g->addMetric($graph_config['host'], $graph_config['portname'], $graph_config['graphtype'], $graph_config['title']);
                    return $g->getDashboardHTML();
                case 'tsd':
                    $g = new Graph_Tsd($graph_time);
                    $g->addMetric($graph_config['metric']);
                    return $g->getDashboardHTML($width, $height);
                default:
                    return '';
            }
        } else {
            return '';
        }
    }

    /**
     * @param array $graph_config
     * @return array
     */
    private function getMetrics($graph_config) {
        if (isset($graph_config['metrics'])) {
            return $graph_config['metrics'];
        } elseif (isset($graph_config['metric'])) {
            return array($graph_config['metric']);
        } else {
            return array();
        }
    }

    /**
     * @param array $graph_config
     * @return array
     */
    private function getColors($graph_config) {
        if (isset($graph_config['colors'])) {
            return $graph_config['colors'];
        } else if (isset($graph_config['color'])) {
            return array($graph_config['color']);
        } else {
            $metrics = $this->getMetrics($graph_config);
            return array_keys(array_slice(self::$NAMED_COLORS, 0, sizeof($metrics)));
        }
    }

    public static function getHexColorValue($name) {
        return isset(self::$NAMED_COLORS[$name]) ? self::$NAMED_COLORS[$name] : $name;
    }

    /**
     * @param array $graph_config
     * @param string $graph_time
     * @param int $width
     * @return string
     */
    private function getGangliaDashboardHTML($graph_config) {
        $is_dev = isset($graph_config['is_dev']) ? $graph_config['is_dev'] : false;
        $is_report = isset($graph_config['is_report']) ? $graph_config['is_report'] : false;
        $no_legend = isset($graph_config['no_legend']) ? $graph_config['no_legend'] : false;
        $until = isset($graph_config['until']) ? $graph_config['until'] : null;

        $g = new Graph_Ganglia($graph_config['graph_time'], $until);
        $g->isDev($is_dev);

        $metrics = $this->getMetrics($graph_config);

        for ($i = 0; $i < count($metrics); $i++) {
            $metric = $metrics[$i];

            $source = isset($metric['source']) ? $metric['source'] : null;
            $node = isset($metric['node']) ? $metric['node'] : null;
            $datum = isset($metric['datum']) ? $metric['datum'] : null;

            if ($is_report) {
                $g->addReport($source, $node, $datum);
            } else {
                $title = isset($graph_config['title']) ? $graph_config['title'] : '';
                $g->addMetric($source, $node, $datum, $title, null, $no_legend);
            }
        }

        if ($graph_config['show_deploys']) {
            $g->showDeploys(true);
        } else {
            $g->showDeploys(false);
        }

        return $g->getDashboardHTML('medium', $graph_config['width']);
    }

    /**
     * @param array $graph_config
     * @param string $graph_time
     * @param int $width
     * @param int $height
     * @return string
     */
    private function getGraphitePieDashboardHTML($graph_config, $graph_time, $width, $height) {
        $title = isset($graph_config['title']) ? $graph_config['title'] : '';
        $metrics = $this->getMetrics($graph_config);
        $legend_keys = isset($graph_config['legend_keys']) ? $graph_config['legend_keys'] : array();
        $colors = $this->getColors($graph_config);
        $add_integral = isset($graph_config['add_integral']) ? $graph_config['add_integral'] : true;
        $is_black = isset($graph_config['is_black']) ? $graph_config['is_black'] : isset($_GET['black']);
        $show_legend = isset($graph_config['show_legend']) ? $graph_config['show_legend'] : false;
        $sort_legend = isset($graph_config['sort_legend']) ? $graph_config['sort_legend'] : false;
        $show_labels = isset($graph_config['show_labels']) ? $graph_config['show_labels'] : true;
        $is_ajax = isset($graph_config['is_ajax']) ? $graph_config['is_ajax'] : false;
        $use_average = isset($graph_config['use_average']) ? $graph_config['use_average'] : false;

        $graphite_pie = new Graph_GraphitePie($graph_time, $title, $metrics, $legend_keys, $colors);
        $graphite_pie->setShowLegend($show_legend);
        $graphite_pie->setSortLegend($sort_legend);
        $graphite_pie->setShowLabels($show_labels);
        $graphite_pie->setIsAjax($is_ajax);
        $graphite_pie->setUseAverage($use_average);

        if (isset($graph_config['threshold'])) {
            $graphite_pie->setThreshold($graph_config['threshold']);
        }

        if ($is_black) {
            $graphite_pie->setBgColor('black');
        }

        return $graphite_pie->getDashboardHTML($width, $height, $add_integral);
    }

    /**
     * @param array $graph_config
     * @param string $graph_time
     * @param int $width
     * @param int $height
     * @return string
     */
    private function getGraphitePercentageDashboardHTML($graph_config, $graph_time, $width, $height) {
        $title = isset($graph_config['title']) ? $graph_config['title'] : '';
        $numerator_metrics = isset($graph_config['numerator_metrics']) ? $graph_config['numerator_metrics'] : '';
        $denominator_metrics = isset($graph_config['denominator_metrics']) ? $graph_config['denominator_metrics'] : '';
        $legend_keys = isset($graph_config['legend_keys']) ? $graph_config['legend_keys'] : array();
        $id = isset($graph_config['id']) ? $graph_config['id'] : '';

        $graphite = new Graph_GraphitePercentage($graph_time, $title, $numerator_metrics, $denominator_metrics);
        $graphite->setLegendKeys($legend_keys);

        if (isset($graph_config['is_bar']) && $graph_config['is_bar']) {
            $graphite->setIsBarPercentage();
        }

        return $graphite->getDashboardHTML($width, $height, $id);
    }

    /**
     * @param array $graph_config
     * @return string
     */
    private function getGraphiteDashboardHTML($graph_config) {
        $graph_time = $graph_config['graph_time'];
        $show_deploys = $graph_config['show_deploys'];
        $width = $graph_config['width'];
        $height = $graph_config['height'];

        $title = isset($graph_config['title']) ? $graph_config['title'] : '';
        $show_legend = isset($graph_config['show_legend']) ? $graph_config['show_legend'] : false;
        $stacked = isset($graph_config['stacked']) ? $graph_config['stacked'] : false;
        $line_mode = isset($graph_config['line_mode']) ? $graph_config['line_mode'] : false;
        $area_mode = isset($graph_config['area_mode']) ? $graph_config['area_mode'] : false;
        $vtitle = isset($graph_config['vtitle']) ? $graph_config['vtitle'] : false;
        $y_max = isset($graph_config['y_max']) ? $graph_config['y_max'] : false;
        $hide_axes = isset($graph_config['hide_axes']) ? $graph_config['hide_axes'] : false;
        $hide_grid = isset($graph_config['hide_grid']) ? $graph_config['hide_grid'] : false;
        $is_black = isset($graph_config['is_black']) ? $graph_config['is_black'] : isset($_GET['black']);
        $is_pie_chart = isset($graph_config['is_pie_chart']) ? $graph_config['is_pie_chart'] : false;
        $show_html_legend = isset($graph_config['show_html_legend']) ? $graph_config['show_html_legend'] : false;
        $show_copy_url = isset($graph_config['show_copy_url']) ? $graph_config['show_copy_url'] : true;
        $numerator_metrics = isset($graph_config['numerator_metrics']) ? $graph_config['numerator_metrics'] : array();
        $denominator_metrics = isset($graph_config['denominator_metrics']) ? $graph_config['denominator_metrics'] : array();
        $ratio_suffix = isset($graph_config['ratio_suffix']) ? $graph_config['ratio_suffix'] : null;
        $legend_keys = isset($graph_config['legend_keys']) ? $graph_config['legend_keys'] : array();
        $is_ajax = isset($graph_config['is_ajax']) ? $graph_config['is_ajax'] : false;
        $xaxis_type = isset($graph_config['xaxis_type']) ? $graph_config['xaxis_type'] : 'date';
        $yaxis_type = isset($graph_config['yaxis_type']) ? $graph_config['yaxis_type'] : null;

        $until_time = isset($graph_config['until']) ? $graph_config['until'] : null;

        if ($is_pie_chart) {
            // deploys don't work with pie charts
            $graph_config['show_deploys'] = false;
        }

        $metrics = $this->getMetrics($graph_config);
        $colors = $this->getColors($graph_config);

        $g = new Graph_Graphite($graph_time, $until_time);
        $g->setTitle($title);
        $g->setVTitle($vtitle);
        if ($line_mode) {
            $g->setLineMode($line_mode);
        }
        if ($area_mode) {
            $g->setAreaMode($area_mode);
        }
        if ($is_pie_chart) {
            $g->displayPieChart(true);
        }
        if ($is_black) {
            $g->setBgColor('black');
            $g->setFgColor('white');
        }

        for ($i = 0; $i < count($metrics); $i++) {
            $metric = $metrics[$i];
            $color = null;
            if (isset($colors[$i])) {
                if (isset(self::$NAMED_COLORS[$colors[$i]])) {
                    $color = '#' . self::$NAMED_COLORS[$colors[$i]];
                } elseif (preg_match('/^[a-fA-F0-9]{6}$/', $colors[$i], $matches)) {
                    $color = '#' . $colors[$i];
                }
            }
            $g->addMetric($metric, $color);
        }

        // Show deploys on low grain
        if (isset($graph_config['show_deploys'])) {
            $show_deploys_graph = $graph_config['show_deploys'];
        } else {
            $show_deploys_graph = $show_deploys;
        }

        if ($show_deploys_graph && ($graph_time == "1h" || $graph_time == "2h" || $graph_time == "4h")) {
            $g->showDeploys(true);
        } else {
            $g->showDeploys(false);
        }

        $g->hideLegend(!$show_legend);
        $g->displayStacked($stacked);
        $g->hideGrid($hide_grid);
        $g->hideAxes($hide_axes);
        $g->useHtmlLegend($show_html_legend);
        $g->showCopyUrl($show_copy_url);
        $g->setLegendKeys($legend_keys);
        $g->isAjax($is_ajax);
        $g->setXaxisType($xaxis_type);
        $g->setYaxisType($yaxis_type);

        if ($y_max) {
            $g->setYMax($y_max);
        }

        if (array_key_exists('y_min', $graph_config)) {
            $g->setYMin($graph_config['y_min']);
        }

        if ($numerator_metrics && $denominator_metrics) {
            $g->setRatioMetrics($numerator_metrics, $denominator_metrics);
            $g->setRatioSuffix($ratio_suffix);
        }

        $html_legend = $this->getLegendHtml($metrics, $colors, $graph_config);

        return $g->getDashboardHTML($width, $height, $html_legend);
    }

    /**
     * @param array $metrics
     * @param array $colors
     * @param array $graph_config
     * @return string
     */
    private function getLegendHtml($metrics, $colors, $graph_config) {
        $show_html_legend = isset($graph_config['show_html_legend']) ? $graph_config['show_html_legend'] : false;

        if ($show_html_legend) {
            $html = "<p class='html_legend'>";

            if (isset($graph_config['legend_keys'])) {
                $keys = $graph_config['legend_keys'];

                for ($i = 0; $i < count($keys) && $i < count($metrics); $i++) {
                    $key = $keys[$i];

                    if ($i != 0 && !empty($html) && !empty($key)) {
                        $html .= " &middot; ";
                    }

                    if (!empty($key) && isset($colors[$i]) && !empty($colors[$i])) {
                        if (isset(self::$NAMED_COLORS[$colors[$i]])) {
                            $color_i = self::$NAMED_COLORS[$colors[$i]];
                        } else {
                            $color_i = $colors[$i];
                        }
                        $html .= '<span style="color:#' . $color_i . ';">' . $key . '</span>';
                    } else {
                        $html .= $key;
                    }
                }
            }

            $html .= "</p>";

            return $html;
        } else if (isset($graph_config['legend_html'])) {
            return $graph_config['legend_html'];
        } else {
            return '';
        }
    }

    /**
     * @param string $graph_time
     * @return string
     */
    private function getIntervalForJenkins($graph_time) {
        if (preg_match('/^\d+h/', $graph_time)) {
            return 'hour';
        } elseif (preg_match('/^\d+d/', $graph_time)) {
            return 'day';
        } elseif (preg_match('/^\d+[wm]/', $graph_time)) {
            return 'week';
        } else {
            return null;
        }
    }

    /**
     * @param string $deploy_title
     * @return bool
     */
    public static function isHiddenDeployType($deploy_title) {
        if (isset($_GET[$deploy_title])) {
            return true;
        } else {
            return false;
        }
    }
}
