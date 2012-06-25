<?php

class Graph_Cacti {
    protected $time;
    protected $until_time;

    protected $metrics = array();

    public function __construct($time, $until=null) {
        $this->time = $time;
        $this->until_time = $until;
    }

    public function addMetric($graph_id, $rra_id = 0) {
        $this->metrics[] = array(
            'graph_id' => $graph_id,
            'rra_id' => $rra_id,
        );
    }

    public function getDashboardHTML($width = null, $height = null) {
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
               . '"' . "$size></a></span>";
    }

    public function getImageURL() {
        global $cacti_server;
        $p = array();
        $p['local_graph_id'] = $this->metrics[0]['graph_id'];
        $p['rra_id'] = $this->metrics[0]['rra_id'];
        $p['graph_start'] = $this->timeToSeconds($this->time);
        $p['graph_end'] = isset($this->until_time) ? $this->timeToSeconds($this->until_time) : time();
        return 'http://' . $cacti_server . '/cacti/graph_image.php?'
               . http_build_query($p);
    }

    public function getPageURL() {
        global $cacti_server;
        $p = array();
        $p['local_graph_id'] = $this->metrics[0]['graph_id'];
        $p['rra_id'] = 'all';
        $p['action'] = 'view';
        return 'http://' . $cacti_server . '/cacti/graph.php?'
               . http_build_query($p);
    }

    public function timeToSeconds($time) {
        $units = array(
            'h' => 3600,
            'd' => 86400,
            'w' => 604800,
            'm' => 2592000,
            'y' => 31556926,
        );
        if(preg_match("/^(\d+)([a-z])/", strtolower($time), $m)) {
            $diff = ((int)$m[1]) * $units[$m[2]];
            return time() - $diff;
        } else if (preg_match("/^(\d){10}$/", $time)) {
            return $time;
        } else {
            return time();
        }
    }
}
