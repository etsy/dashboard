<?php

class Graph_GraphitePercentage {
    const NUMBER_PERCENTAGE = "graphite-percentage";
    const BAR_PERCENTAGE = "graphite-bar-percentage";

    private $time;

    private $title;

    private $numerator_metrics;

    private $denominator_metrics;

    private $legend_keys = array();

    private $percentage_type = self::NUMBER_PERCENTAGE;

    /**
     * @param string $time
     * @param string $title
     * @param array $numerator_metrics
     * @param array $denominator_metrics
     */
    public function __construct($time, $title, $numerator_metrics, $denominator_metrics) {
        $this->time = $time;
        $this->title = $title;
        $this->numerator_metrics = $numerator_metrics;
        $this->denominator_metrics = $denominator_metrics;
    }

    public function setIsNumberPercentage() {
        $this->percentage_type = self::NUMBER_PERCENTAGE;
    }

    public function setIsBarPercentage() {
        $this->percentage_type = self::BAR_PERCENTAGE;
    }

    /**
     * @param array $legend_keys
     */
    public function setLegendKeys($legend_keys) {
        $this->legend_keys = $legend_keys;
    }

    public function getDashboardHTML($width, $height, $id) {
        $time = GraphiteHelper::getTimeParam($this->time);
        $max = json_encode(100);
        $numerator = json_encode($this->numerator_metrics);
        $denominator = json_encode($this->denominator_metrics);
        $legend_keys = json_encode($this->legend_keys);

        if ($this->percentage_type == self::NUMBER_PERCENTAGE) {
            $interior_html = <<<EOF
                <span class="col_left">
                <span class="value_whole">--</span>
                </span><span class="col_right">
                <span
                    class="value_point">.</span><span
                    class="value_decimal">--</span><span
                    class="value_symbol">%</span>
                <span class="unit">{$this->title}</span>
                </span>
EOF;
        } else {
            $interior_html = '';
        }

        return <<<EOF
            <span id="{$id}" class="graphiteGraph {$this->percentage_type} stat"
                  style="width:{$width}px; height: {$height}px;"
                  data-time="{$time}"
                  data-title='{$this->title}'
                  data-max='{$max}'
                  data-numerator='{$numerator}'
                  data-denominator='{$denominator}'
                  data-legend-keys='{$legend_keys}'
                  >
                  {$interior_html}
            </span>
EOF;
    }
}
