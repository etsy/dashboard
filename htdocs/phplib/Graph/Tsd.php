<?php

class Graph_Tsd {

    protected $time;
    protected $metrics = array();
    protected $hide_legend = false;
    protected $y_min = 0;
    protected $y_max = null;
    protected $base_url = null;

    public function __construct($time) {
        global $tsd_server;
        $this->base_url = "http://$tsd_server";
        $this->time = $time;
    }

    public function hideLegend($hide) {
        $this->hide_legend = (bool) $hide;
    }

    // Set y_min to 'null' to unlock from zero
    public function setYMin($y_min) {
        $this->y_min = $y_min;
    }

    public function setYMax($y_max) {
        $this->y_max = $y_max;
    }

    /**
     * Add a metric to the current Tsd object. For Tsd, you can call this
     * method multiple times to stack multiple metrics together in one image.
     */
    public function addMetric($metric, $prepend = false) {
        $metric = array(
            'm' => $metric
        );
        
        if ($prepend) {
            array_unshift($this->metrics, $metric);
        } else {
            $this->metrics[] = $metric;
        }
    }
    
    /**
     * Call this if you want to clear metrics and start from scratch
     */
    public function clearMetrics() {
        $this->metrics[] = array();
    }

    /**
     * Convert Dashboard time period to a value usable by Tsd URLs.
     */
    public function getTimeParam() {
        $units = array('h' => 'h-ago',
            'd' => 'd-ago',
            'w' => 'w-ago',
            'm' => 'm-ago',
            'y' => 'y-ago',
        );
        preg_match("/^(\d+)([a-z])/", strtolower($this->time), $m);
        //if ($m[2] == 'm') {
        //    return '' . ($m[1] * 30) . '' .$units[$m[2]];
        //}
        return $m[1] . $units[$m[2]];
    }

    /**
     * Get Tsd image URL that will display all of the added metrics and deploy lines.
     */
    public function getImageURL($width = 800, $height = 600, $stand_alone = false) {
        $p = array(
            'start' => $this->getTimeParam(),
            'wxh' => '' . $width . 'x' . $height
        );
        if ($this->hide_legend && !$stand_alone) {
            $p['nokey'] = $this->hide_legend;
        }
        if ($this->y_min !== null) {
            $range = '[' . $this->y_min . ':';
            if ($this->y_max !== null) {
               $range .= $this->y_max;
            }
            $p['yrange'] = $range . ']';
        }

        $targets = array();

        foreach ($this->metrics as $m) {
            $targets[] = 'm=' . $m['m']; //urlencode($m['m']);
        }

        return $this->base_url . '/q?'
            . http_build_query($p)
            . '&' . implode('&', $targets)
            . '&png';
    }
    
    /**
     * 
     */
    public function replaceOrAddSampleTime() {
        
    }

    /**
     * Return HTML for the current Tsd image, with link to a larger size.
     */
    public function getDashboardHTML($width, $height, $html_legend = "") {
        return '<span class="tsdGraph" style="width: ' . $width . 'px;">'
            . '<a href="' . $this->getImageURL(800, 600, true) . '">'
            . '<img src="' . $this->getImageURL($width, $height) . '" width="' . $width . '" height="' . $height . '">'
            . ($html_legend ? '<p class="html_legend" style="width: ' . $width . 'px;">' . $html_legend . '</p>' : '')
            . '</a></span>';
    }
}
