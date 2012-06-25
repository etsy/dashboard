<?php

class Graph_FITB {
    protected $time;

    protected $metrics = array();

    public function __construct($time) {
        $this->time = $time;
    }

    public function addMetric($host_name, $port_name, $graph_type, $description) {
        $this->metrics[] = array(
            'host_name' => $host_name,
            'port_name' => $port_name,
            'graph_type' => $graph_type,
            'description' => $description,
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
        return '<span class="FITBGraph"><a href="'
               . $this->getPageURL()
               . '"><img class="FITBGraph" src="'
               . $this->getImageURL()
               . '"' . "$size></a></span>";
    }

    public function getImageURL() {
        global $fitb_server;
        $p = array();
        $p['host'] = $this->metrics[0]['host_name'];
        $p['rrdname'] = $this->metrics[0]['port_name'];
        $p['type'] = $this->metrics[0]['graph_type'];
        $p['duration'] = time() - $this->timeToSeconds();
        $p['friendlytitle'] = $this->metrics[0]['port_name'] . " (" . $this->metrics[0]['description'] . ")";
        return 'http://' . $fitb_server . '/graph.php?'
               . http_build_query($p);
    }

    public function getPageURL() {
        global $fitb_server;
        $p = array();
        $p['host'] = $this->metrics[0]['host_name'];
        $p['rrdname'] = $this->metrics[0]['port_name'];
        $p['type'] = $this->metrics[0]['graph_type'];
        $p['duration'] = time() - $this->timeToSeconds();
        return 'http://' . $fitb_server . '/viewgraph.php?'
               . http_build_query($p);
    }

    public function timeToSeconds() {
        $units = array(
            'h' => 3600,
            'd' => 86400,
            'w' => 604800,
            'm' => 2592000,
            'y' => 31556926,
        );
        preg_match("/^(\d+)([a-z])/", strtolower($this->time), $m);
        $diff = ((int)$m[1]) * $units[$m[2]];
        return $diff;
    }
}
