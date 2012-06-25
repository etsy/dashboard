<?php

class Graph_GraphitePie {
    private $time;

    private $title;

    private $metrics;

    private $legend_keys;

    /** @var float */
    private $threshold;

    /** @var string */
    private $bgColor;

    /** @var array */
    private $colors;

    private $show_labels = true;

    private $show_legend = false;

    private $sort_legend = false;

    private $is_ajax = false;

    private $use_average = false;

    /**
     * @param string $time
     * @param string $title
     * @param array $metrics
     * @param array $legend_keys
     * @param array $colors
     */
    public function __construct($time, $title, $metrics, $legend_keys = array(), $colors = array()) {
        $this->time = $time;
        $this->title = $title;
        $this->metrics = $metrics;
        $this->legend_keys = $legend_keys;
        $this->colors = $colors;
    }

    public function setThreshold($threshold) {
        $this->threshold = $threshold;
    }

    public function setBgColor($color) {
        $this->bgColor = $color;
    }

    public function setShowLegend($on_off) {
        $this->show_legend = $on_off ? true : false;
    }

    public function setSortLegend($on_off) {
        $this->sort_legend = $on_off ? true : false;
    }

    public function setShowLabels($on_off) {
        $this->show_labels = $on_off ? true : false;
    }

    public function setisAjax($on_off) {
        $this->is_ajax = $on_off ? true : false;
    }

    public function setUseAverage($on_off) {
        $this->use_average = $on_off ? true : false;
    }

    public function getDashboardHTML($width, $height, $add_integral = true) {
        $id = 'graphite-pie-' . str_replace('.', '', microtime(true));

        $legend_width = $width - 20;
        $graph_height = $height + 20;

        $show_labels = $this->show_labels ? 'true' : 'false';
        $show_legend = $this->show_legend ? 'true' : 'false';
        $sort_legend = $this->sort_legend ? 'true' : 'false';

        if ($this->is_ajax) {
            $time = GraphiteHelper::getTimeParam($this->time);
            $metrics = json_encode($this->metrics);
            $legend_keys = json_encode($this->legend_keys);
            $threshold = isset($this->threshold) ? $this->threshold : 'null';

            $formatted_colors = array();
            foreach ((array)$this->colors as $color_description) {
                $color = GraphFactory::getHexColorValue($color_description);
                $formatted_colors[] = "#$color";
            }

            $formatted_use_average = $this->use_average ? 'true' : 'false';

            $colors = json_encode($formatted_colors);

            return <<<EOF
            <div class='flotGraph graphite-pie'
                 data-time='{$time}'
                 data-metrics='{$metrics}'
                 data-legend-keys='{$legend_keys}'
                 data-colors='{$colors}'
                 data-show-labels='{$show_labels}'
                 data-show-legend='{$show_legend}'
                 data-sort-legend='{$sort_legend}'
                 data-threshold='{$threshold}'
                 data-use-average='{$formatted_use_average}'
            >
                <p class='html_title' style='width: {$legend_width}px;'>{$this->title}</p>
                <div class='flot-container' id='{$id}' style='height: {$graph_height}px; width: {$width}px;'></div>
            </div>
EOF;
        } else {
            $targets = array();

            foreach ($this->metrics as $metric) {
                $targets[] = $add_integral ? "integral($metric)" : $metric;
            }

            $data = GraphiteHelper::fetchSplitRenderDataMultipleTargets($targets, $this->time);

            $json_data_values = array();

            foreach ($data as $index => $split_data) {
                if ($add_integral) {
                    $method = count($this->legend_keys) > $index ? $this->legend_keys[$index] : $this->metrics[$index];
                } else {
                    $header = $split_data[0];
                    list($method) = explode(',', $header);
                }

                if (count($split_data) > 1) {
                    $data_points = explode(',', $split_data[1]);

                    if ($add_integral) {
                        $total = GraphiteHelper::getMaxValue($data_points);
                    } else {
                        $total = GraphiteHelper::getTotal($data_points);
                    }

                    $json_data_values[] = '{label:"' . $method . '", data:' . $total . '}';
                }
            }

            $json_data = '[' . implode(',', $json_data_values) . ']';
            $threshold_setting = isset($this->threshold) ? "threshold: {$this->threshold}," : '';
            $stroke_setting = isset($this->bgColor) ? "stroke: { color: '{$this->bgColor}' }," : '';

            $formatted_colors = array();

            if ($this->colors) {
                foreach ($this->colors as $color_description) {
                    $color = GraphFactory::getHexColorValue($color_description);
                    $formatted_colors[] = "'#$color'";
                }
                $colors = 'colors: [' . implode(',', $formatted_colors) . '],';
            } else {
                $colors = '';
            }

            if ($this->show_legend && !$this->show_labels) {
                $label_formatter = <<<EOF
                    labelFormatter: function(label,data) {
                        return label + ' (' + (Math.round(data.percent*100)/100) + '%)';
                    },
EOF;
            } else {
                $label_formatter = "";
            }

            return <<<EOF
        <div class='flotGraph'>
            <p class='html_title' style='width: {$legend_width}px;'>{$this->title}</p>
            <div id='{$id}' style='height: {$graph_height}px; width: {$width}px;'></div>
            <script type="text/javascript">

                var data = {$json_data};
        jQuery.plot(jQuery("#{$id}"), data,
        {
                $colors
                series: {
                    pie: {
                        $stroke_setting
                        show: true,
                        label: {
                            show: {$show_labels}
                        },
                        combine: {
                            $threshold_setting
                            color: '#999'
                        }
                    }
                },
                legend: {
                    {$label_formatter}
                    show: {$show_legend}
                },
                grid: {
                    hoverable: true
                }
        });
        </script>
        </div>
EOF;
        }
    }
}
