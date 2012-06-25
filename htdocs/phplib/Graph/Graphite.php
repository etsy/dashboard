<?php

class Graph_Graphite {
    protected $time;
    protected $until_time;
    protected $title;
    protected $vtitle;
    protected $metrics = array();
    protected $legend_keys = array();
    protected $hide_legend = false;
    protected $hide_grid = false;
    protected $hide_axes = false;
    protected $stacked = false;
    protected $y_min = 0;
    protected $y_max = null;
    protected $line_width;
    protected $line_mode = null;
    protected $area_mode = null;
    protected $pie_chart = false;
    protected $bg_color = null;
    protected $fg_color = null;
    protected $host = null;
    protected $use_html_legend = false;
    protected $show_copy_url = false;
    protected $numerator_metrics = null;
    protected $denominator_metrics = null;
    protected $is_ajax = false;
    protected $xaxis_type = null;
    protected $yaxis_type = null;
    protected $ratio_suffix = null;

    public function __construct($time, $until=null) {
        $this->time = $time;
        $this->until_time = $until;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setVTitle($vtitle) {
        $this->vtitle = $vtitle;
    }

    public function setRatioSuffix($value) {
        $this->ratio_suffix = $value;
    }

    public function setLineMode($mode) {
        $this->line_mode = $mode;
    }

    public function setAreaMode($mode) {
        $this->area_mode = $mode;
    }

    public function hideLegend($hide) {
        $this->hide_legend = $hide;
    }

    public function hideGrid($hide) {
        $this->hide_grid = (bool)$hide;
    }

    public function hideAxes($hide) {
        $this->hide_axes = (bool)$hide;
    }

    public function setLineWidth($width) {
        $this->line_width = (int)$width;
    }

    public function setBgColor($color) {
        $this->bg_color = $color;
    }

    public function setFgColor($color) {
        $this->fg_color = $color;
    }

    public function displayStacked($stack) {
        $this->stacked = (bool)$stack;
    }

    public function displayPieChart($pie_chart) {
        $this->pie_chart = (bool)$pie_chart;
    }

    public function setLegendKeys($legend_keys) {
        $this->legend_keys = $legend_keys;
    }

    public function setXaxisType($axis_type) {
        $this->xaxis_type = $axis_type;
    }

    public function setYaxisType($axis_type) {
        $this->yaxis_type = $axis_type;
    }

    public function useHtmlLegend($value) {
        $this->use_html_legend = (bool)$value;
    }

    public function showCopyUrl($value) {
        $this->show_copy_url = (bool)$value;
    }

    public function isAjax($value) {
        $this->is_ajax = (bool)$value;
    }

    // Set y_min to 'null' to unlock from zero
    public function setYMin($y_min) {
        $this->y_min = $y_min;
    }

    public function setYMax($y_max) {
        $this->y_max = $y_max;
    }

    /**
     * @param array $numerator_metrics
     * @param array $denominator_metrics
     */
    public function setRatioMetrics($numerator_metrics, $denominator_metrics) {
        $this->numerator_metrics = $numerator_metrics;
        $this->denominator_metrics = $denominator_metrics;
    }

    /**
     * @param string $metric
     * @param string $color
     * @param bool $prepend
     */
    public function addMetric($metric, $color = null, $prepend = false) {
        $metric = array(
            'target' => $metric,
            'color' => $color,
        );

        if ($prepend) {
            array_unshift($this->metrics, $metric);
        } else {
            $this->metrics[] = $metric;
        }
    }

    public function showDeploys($show = true) {
        if ($show) {
            foreach (array_reverse(DeployConstants::$deploys) as $deploy_name => $deploy) {
                if (!GraphFactory::isHiddenDeployType($deploy_name)) {
                    $target = "alias(drawAsInfinite({$deploy['target']}), 'Deploy: {$deploy['title']}')";
                    $this->addMetric($target, $deploy['color'], true);
                }
            }
        }
    }

    public function getDeployColor($name) {
        return DeployConstants::$deploys[$name]['color'];
    }

    /**
     * @param int $width
     * @param int $height
     * @param bool $stand_alone
     * @param bool $show_title
     * @param bool $force_show_legend
     * @return string
     */
    public function getImageURL($width = 800, $height = 600, $stand_alone = false, $show_title = true, $force_show_legend = false) {
        global $graphite_server;
        $this->host = $graphite_server;

        $p = array(
            'from' => GraphiteHelper::getTimeParam($this->time),
            'until' => GraphiteHelper::getTimeParam($this->until_time),
            'width' => $width,
            'height' => $height,
        );
        if ($this->title && $show_title) {
            $p['title'] = $this->title;
        }
        if ($this->vtitle) {
            $p['vtitle'] = $this->vtitle;
            $p['hideaxes'] = 'false';
        }
        if ($this->hide_legend && !$stand_alone && !$force_show_legend) {
            $p['hideLegend'] = $this->hide_legend;
        }
        if ($this->hide_grid) {
            $p['hideGrid'] = $this->hide_grid;
        }
        if ($this->hide_axes) {
            $p['hideAxes'] = $this->hide_axes;
        }
        if ($this->line_width) {
            $p['lineWidth'] = $this->line_width;
        }
        if ($this->stacked) {
            $p['areaMode'] = 'stacked';
        }

        // no if block allows us to set it to null to remove the 0 default
        $p['yMin'] = $this->y_min;

        if ($this->y_max !== null) {
            $p['yMax'] = $this->y_max;
        }
        if ($this->line_mode !== null) {
            $p['lineMode'] = $this->line_mode;
        }
        if ($this->area_mode !== null) {
            $p['areaMode'] = $this->area_mode;
        }
        if ($this->pie_chart) {
            $p['graphType'] = 'pie';
        }
        if ($this->bg_color && $this->fg_color) {
            $p['bgcolor'] = $this->bg_color;
            $p['fgcolor'] = $this->fg_color;
        }

        $targets = array();
        $colors = array();

        foreach ($this->metrics as $m) {
            $targets[] = 'target=' . urlencode($m['target']);
            if ($m['color']) {
                $colors[] = urlencode($m['color']);
            }
        }

        $url = 'http://' . $this->host . '/render?'
                . http_build_query($p)
                . '&' . implode('&', $targets)
                . '&colorList=' . implode(',', $colors);

        if ($stand_alone) {
            return "http://{$_SERVER['HTTP_HOST']}/large_graph.php?title=" . urlencode($this->title) . "&width=$width&url=" . urlencode($url);
        } else {
            return $url;
        }
    }

    public function getDashboardHTML($width, $height, $html_legend = "") {
        if ($this->is_ajax) {
            return $this->getAjaxDashboardHtml($width, $height, $html_legend);
        } else {
            return $this->getImageDashboardHtml($width, $height, $html_legend);
        }
    }

    private function getAjaxDashboardHtml($width, $height, $html_legend = '') {
        $graph_time = GraphiteHelper::getTimeParam($this->time);

        $targets = array();
        $colors = array();

        foreach ($this->metrics as $m) {
            $targets[] = $m['target'];
            if ($m['color']) {
                $colors[] = $m['color'];
            }
        }

        $metrics = json_encode($targets);
        $legend_keys = json_encode($this->legend_keys);
        $colors = json_encode($colors);
        $title = $this->getTitleHtml($this->title, $width);
        $legend = $html_legend ?
                "<p class='html_legend' style='width: {$width}px;'>{$html_legend}</p>" :
                '';

        $id = 'graphite-chart-' . str_replace('.', '', microtime(true));
        $xaxis_type = $this->xaxis_type ? json_encode($this->xaxis_type) : 'null';
        $yaxis_type = $this->yaxis_type ? json_encode($this->yaxis_type) : 'null';

        return <<<EOF
        <div class="flotGraph graphite-chart"
             data-time='{$graph_time}'
             data-metrics='{$metrics}'
             data-legend-keys='{$legend_keys}'
             data-colors='{$colors}'
             data-xaxis-type='{$xaxis_type}'
             data-yaxis-type='{$yaxis_type}'
        >
            {$title}
            <div id='{$id}' class='flot-container' style='height: {$height}px; width: {$width}px;'></div>
            {$legend}
        </div>
EOF;
    }

    private function getImageDashboardHtml($width, $height, $html_legend = '') {
        $image_link = $this->getImageURL(800, 600, true);
        $image_src = $this->getImageURL($width, $height, false, !$this->use_html_legend);
        $copy_link = $this->getImageURL(800, 600, false, true, true);

        $title = $this->use_html_legend ? $this->getTitleHtml($this->title, $width, $copy_link) : '';
        $legend = $this->use_html_legend && $html_legend ?
                "<p class='html_legend' style='width: {$width}px;'>{$html_legend}</p>" :
                '';

        $copyable_class = $this->show_copy_url && $this->use_html_legend ? 'copyable' : '';

        return "<span class='graphiteGraph {$copyable_class}' style='width: {$width}px;' data-id='" . md5($copy_link) . "'>"
                . $title
                . '<a href="' . $image_link . '">'
                . '<img src="' . $image_src . '" width="' . $width . '" height="' . $height . '">'
                . '</a>'
                . $legend
                . '</span>';
    }

    private function getTitleHtml($title, $width, $image_link = null) {
        return $title ?
                "<p class='html_title' style='width: {$width}px;'>" .
                        "<span>{$this->title}</span>" .
                        $this->getRatioHtml() .
                        $this->getCopyUrlHtml($image_link) .
                        "</p>" :
                '';
    }

    private function getRatioHtml() {
        $time = GraphiteHelper::getTimeParam($this->time);
        $numerator = json_encode($this->numerator_metrics);
        $denominator = json_encode($this->denominator_metrics);
        $ratio_suffix = $this->ratio_suffix ? " {$this->ratio_suffix}" : '';

        if ($this->numerator_metrics && $this->denominator_metrics) {
            return <<<EOF
                <span class="legend-graphite-percentage"
                      data-time="{$time}"
                      data-numerator='{$numerator}'
                      data-denominator='{$denominator}'
                      >
                    (<span class="value_whole">-</span><span class="value_point">.</span><span class="value_decimal">-</span>%{$ratio_suffix})
                </span>
EOF;
        } else {
            return '';
        }
    }

    private function getCopyUrlHtml($image_link) {
        if ($this->show_copy_url && !empty($image_link)) {
            $id = md5($image_link);
            $encoded_link = urlencode($image_link);

            return "<span class='copy-container' id='url-copy-container-{$id}'>" .
                    "<span class='copy-source' data-encoded-long-url='{$encoded_link}' data-long-url='{$image_link}'></span>" .
                    "<span class='copy-button' id='url-copy-button-{$id}'>clip</span>" .
                    "</span>";
        } else {
            return '';
        }
    }
}
