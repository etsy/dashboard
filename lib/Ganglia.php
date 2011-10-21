<?php

class Ganglia {

    protected $time;
    protected $url;
    protected $base_url;

    protected $params = array(
        'metric' => '',
        'graph' => '',
        'cluster' => '',
        'hostname' => '',
        'title' => '',
        'vlabel' => '',
    );

    public function __construct($time) {
        $this->time = $time;
        $this->base_url = Config::$ganglia_base_url;
    }

    /**
     * Create a multi-metric "report" graph (as seen at top-level page for 
     * each node in Ganglia). Leave $host empty for cluster-level graphs.
     */
    public function addReport($cluster, $host, $metric, $title = '', $vlabel = '') {
        $this->params['cluster'] = $cluster;
        $this->params['graph'] = $metric; // uses 'graph' instead of 'metric'
        $this->params['hostname'] = $host;
        $this->params['title'] = $title;
        $this->params['vlabel'] = $vlabel;
    }

    /**
     * Create a single metric graph, if "host" is not provided this will display
     * as a stacked graph of all nodes in the cluster.
     */
    public function addMetric($cluster, $host, $metric, $title = '', $vlabel = '') {
        $this->params['cluster'] = $cluster;
        $this->params['hostname'] = $host;
        $this->params['metric'] = $metric;
        $this->params['title'] = $title;
        $this->params['vlabel'] = $vlabel;
    }

    /**
     * Convert a Dashboard time param (e.g. 4h) to one usable by Ganglia.
     */
    public function getTimeParam() {
        $units = array(
            'h' => 'hours',
            'd' => 'days',
            'w' => 'weeks',
            'm' => 'months',
            'y' => 'years',
        );
        preg_match("/^(\d+)([a-z])/", strtolower($this->time), $m);
        $quantity = $m[1];
        return $m[1] . $units[$m[2]];
    }

    /**
     * Return a CSS class name for the current graph type, identified
     * by the parameters in the object.
     */
    public function getClassName($size) {
        if ($this->params['metric'] && $this->params['hostname']) {
            return "singleMetric" . ucfirst(strtolower($size));
        } else if ($this->params['metric']) {
            return "stackedMetric" . ucfirst(strtolower($size));
        } else if ($this->params['graph']) {
            return "report" . ucfirst(strtolower($size));
        }
    }

    /**
     * Build the image URL for the current Ganglia object.
     */
    public function getImageURL($size) {
        // Stacked graph for entire cluster?
        $script = (!$this->params['hostname'] && !$this->params['graph']) ? '/stacked.php' : '/graph.php';

        // Query param array
        $p = array();
        $p['r'] = $this->getTimeParam();
        $p['c'] = $this->params['cluster'];
        $p['z'] = $size;

        if ($this->params['hostname']) {
            $p['h'] = $this->params['hostname'];
        }
        if ($this->params['title']) {
            $p['ti'] = $this->params['title'];
        }
        if ($this->params['vlabel']) {
            $p['vl'] = $this->params['vlabel'];
        }

        if ($this->params['metric']) {
            // single-metric graph
            $p['m'] = $this->params['metric'];

        } else if ($this->params['graph']) {
            // multi-metric graph
            $p['g'] = $this->params['graph'];
        }

        return $this->base_url . $script . '?'
            . http_build_query($p);
    }

    /**
     * Generate URL of page within Ganglia that would be associated
     * with the current graph (for clicking-through to more info).
     */
    public function getPageURL() {
        if ($this->params['graph'] || $this->params['hostname']) {
            return $this->getImageUrl('large');
        } else {
            $p = array();
            $p['r'] = $this->getTimeParam();
            $p['c'] = $this->params['cluster'];
            $p['m'] = $this->params['metric'];
            return $this->base_url . '?'
                . http_build_query($p);
        }
    }

    /**
     * Return HTML used for displaying graph.
     */
    public function getDashboardHTML($size = 'medium') {
        $class = $this->getClassName($size);
        return '<span class="ganglia ' . $class . '"><a href="'
            . $this->getPageURL()
            . '"><img src="'
            . $this->getImageURL($size)
            . '"></a></span>';
    }
}
