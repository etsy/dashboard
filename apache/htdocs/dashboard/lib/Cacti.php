<?php

class Cacti {

    protected $time;
    protected $base_url;
    protected $params = array();

    public function __construct($time) {
        $this->base_url = Config::$cacti_base_url;
        $this->time = $time;
    }

    /**
     * Add metric to be displayed (graph_id and rra_id can be found in
     * your existing Cacit graph URLs.
     */
    public function addMetric($graph_id, $rra_id) {
        $this->params = array(
            'graph_id' => $graph_id,
            'rra_id' => $rra_id,
        );
    }

    /**
     * Return HTML for displaying graph with link to related page in Cacti
     */
    public function getDashboardHTML($width=null, $height=null) {
        $size = '';
        if ($width) {
            $size = " width='$width'";
        }
        if ($height) {
            $size .= " height='$height'";
        }
        return '<span class="cactiGraph"><a href="'
            . $this->getPageURL()
            . '"><img class="cactiGraph" src="'
            . $this->getImageURL()
            . '"'. "$size></a></span>";
    }

    /**
     * Return image URL for current graph.
     */
    public function getImageURL() {
        $p = array();
        $p['local_graph_id'] = $this->params['graph_id'];
        $p['rra_id'] = $this->params['rra_id'];
        $p['graph_start'] = time() - $this->timeToSeconds();
        $p['graph_end'] = time();
        return $this->base_url . '/graph_image.php?'
            . http_build_query($p);
    }

    /**
     * Return URL of Cacti page that would be associated with
     * the current metric being displayed.
     */
    public function getPageURL() {
        $p = array();
        $p['local_graph_id'] = $this->params['graph_id'];
        $p['rra_id'] = 'all';
        $p['action'] = 'view';
        return $this->base_url . '/graph.php?'
            . http_build_query($p);
    }

    /**
     * Convert Dashboard time period to seconds, for use by 
     * Cacti graphs.
     */
    public function timeToSeconds() {
        $units = array(
            'h' => 3600,
            'd' => 86400,
            'w' => 604800,
            'm' => 2592000,
            'y' => 31556926,
        );
        preg_match("/^(\d+)([a-z])/", strtolower($this->time), $m);
        $diff = ((int) $m[1]) * $units[$m[2]];
        return $diff;
    }

}

