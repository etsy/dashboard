<?php

class Graph_Ganglia {
    protected $time;
    protected $until_time;
    protected $url;
    protected $default_url;
    protected $dev_default_url;

    protected $params = array(
        'metric' => '', 'graph' => '', 'cluster' => '',
        'hostname' => '', 'title' => '', 'vlabel' => '',
    );

    public function __construct($time, $until=null) {
        global $ganglia_server;
        global $ganglia_server_dev;
        $default_url = "http://" . $ganglia_server;
        $dev_ganglia_url = "http://" . $ganglia_server_dev;
        $this->time = $time;
        $this->until_time = $until;
        $this->url = $default_url;
    }

    public function isDev($is_dev) {
        if ($is_dev) {
            $this->url = $this->dev_ganglia_url;
        } else {
            $this->url = $this->default_url;
        }
    }

    /**
     * Create a multi-metric graph (as seen at top-level for each node in ganglia)
     * Leave $host empty for cluster level graphs.
     */
    public function addReport($cluster, $host, $metric, $title = '', $vlabel = '', $nolegend = '') {
        $this->params['cluster'] = $cluster;
        $this->params['graph'] = $metric; // uses 'graph' instead of 'metric'
        $this->params['hostname'] = $host;
        $this->params['title'] = $title;
        $this->params['vlabel'] = $vlabel;
        $this->params['nolegend'] = $nolegend;
    }

    /**
     * Create a single metric graph, if "host" is not provided this will display
     * as a stacked graph of all nodes in the cluster.
     */
    public function addMetric($cluster, $host, $metric, $title = '', $vlabel = '', $nolegend = '') {
        $this->params['cluster'] = $cluster;
        $this->params['hostname'] = $host;
        $this->params['metric'] = $metric;
        $this->params['title'] = $title;
        $this->params['vlabel'] = $vlabel;
        $this->params['nolegend'] = $nolegend;
    }

    public function showDeploys($show = true) {
        if($show) {
            $this->params['show_deploys'] = true;
        } else {
            $this->params['show_deploys'] = false;
        }
    }

    public function getTimeParam($time) {
        $units = array('h' => 'hours',
                       'd' => 'days',
                       'w' => 'weeks',
                       'm' => 'months',
                       'y' => 'years',
        );
        if(preg_match("/^(\d+)([a-z])/", strtolower($time), $m)) {
            $quantity = $m[1];
            return $m[1] . $units[$m[2]];
        } else {
            return $time;
        }
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

    public function getImageURL($size) {
        global $ganglia_server;

        // Stacked graph for entire cluster?
        $script = (!$this->params['hostname'] && !$this->params['graph']) ? 'stacked.php' : 'graph.php';

        // Query param array
        $p = array();
        if(isset($this->until_time)) {
            $p['r'] = 'custom';
            $p['from'] = $this->getTimeParam($this->time);
            $p['until'] = $this->getTimeParam($this->until_time);
        } else {
            $p['r'] = $this->getTimeParam($this->time);
        }
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

        if ($this->params['nolegend']) {
            $p['nolegend'] = $this->params['nolegend'];
        }

        // Sometimes people call the graph directly instead of using
        // the GraphFactory route. Show them deploys, as before.
        if (!isset($this->params['show_deploys'])) {
            $this->params['show_deploys'] = true;
        }
        if ($this->params['show_deploys']) {
            $p['show_deploys'] = 1;
        } else {
            $p['hide_deploys'] = 1;
        }

        if ($this->params['metric']) {
            // single-metric graph
            $p['m'] = $this->params['metric'];

        } else if ($this->params['graph']) {
            // multi-metric graph
            $p['g'] = $this->params['graph'];
        }

        return 'http://' . $ganglia_server . '/'
               . $script . '?'
               . http_build_query($p);
    }

    public function getPageURL() {
        if ($this->params['graph'] || $this->params['hostname']) {
            return $this->getImageUrl('large');
        } else {
            $p = array();
            $p['r'] = $this->getTimeParam($this->time);
            $p['c'] = $this->params['cluster'];
            $p['m'] = $this->params['metric'];
            return $this->url . '?'
                   . http_build_query($p);
        }
    }

    public function getPageURLForHostnameAndCluster($hostname, $cluster) {
        $p = array();
        $p['r'] = Dashboard::displayTime($this->time);
        $p['h'] = $hostname;
        $p['c'] = $cluster;
        return $this->url . '?'
               . http_build_query($p);
    }

    public function getDashboardHTML($size = 'medium', $width = null) {
        $class = is_null($width) ? $this->getClassName($size) : '';
        $style = is_null($width) ? '' : "style='width:{$width}px;'";
        $img_width = is_null($width) ? '' : "width='{$width}px'";
        return '<span class="ganglia ' . $class . '" ' . $style . '><a href="'
               . $this->getPageURL()
               . '"><img src="'
               . $this->getImageURL($size)
               . '"'
               . $img_width
               . '></a></span>';
    }
}
