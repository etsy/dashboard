<?php

class Graph_Graphlot extends Graph_Graphite {

    private $options = array();

    private $nullval = null;

    public $from;
    public $until;

    public function filterNulls($setting) {
        if ($setting) {
            $this->nullval = 0;
        } else {
            $this->nullval = null;
        }
    }

    public function addMetric($metric, $alias=null, $options=null, $functions=array()) {
        parent::addMetric($metric);
        $i = count($this->metrics) - 1;
        $this->metrics[$i]["alias"] = $alias;
        $this->metrics[$i]["options"] = $options;
        $this->metrics[$i]["functions"] = $functions;
    }

    public function coarsen($time_period) {
        foreach ($this->metrics as $k => $m) {
            $t = $m["target"];
            $this->metrics[$k]["target"] = "coarsen($t, $time_period)";
        }
    }

    public static function zip($a1, $a2, $nullval = null) {
        $r = array();
        foreach ($a1 as $k => $v) {
            $a2val = isset($a2[$k]) ? $a2[$k] : $nullval;
			$r[] = array($v, $a2val);
        }
        return $r;
    }

    public function setOption($o = array()) {
        $this->options = array_merge($this->options, $o);
    }

    public function getDataUrl() {
        return $this->getImageURL(0,0)."&rawData=True";
    }

    public function getTimeParam() {
        if (!empty($this->from)){
            return $this->from;
        }
        return parent::getTimeParam();
    }

    public function getImageURL($width = 800, $height = 600, $stand_alone = false, $show_title = true, $force_show_legend = false) {
        $url = parent::getImageURL($width, $height, $stand_alone, $show_title, $force_show_legend);
        if (!empty($this->until)){
            $url .= "&until={$this->until}";
        }
        return $url;
    }

    public function getData($encode_json = true) {

        $url = $this->getDataUrl();
        $handle = fopen($url, 'r');
        $metrics = $this->metrics;
        $series = array();

        while (($line = fgets($handle)) !== false) {
            $metric = array_shift($metrics);
            $target = $metric["target"];
            $alias = $metric["alias"];
            $options = $metric["options"];
            $functions = $metric["functions"];
            list($metadata, $data) = explode('|', str_replace($target, '', $line));
            $metadata   = explode(',', $metadata);
            $start_date = (int)$metadata[count($metadata) - 3];
            $end_date   = (int)$metadata[count($metadata) - 2];
            $bucket     = (int)$metadata[count($metadata) - 1];
            $parsed_data = array();

            foreach (explode(',', $data) as $d) {
                $parsed_data[] = trim($d) == "None" ? $this->nullval : doubleval($d);
            }

            $data = $parsed_data;

            $x = array($start_date * 1000);
            $y = $data;

            $steps = ($end_date - $start_date) / $bucket;
            $cursor = $start_date;
            for ($i = 0; $i < $steps; $i++) {
                $cursor += $bucket;
                $x[] = $cursor * 1000;
            }
            $zipped = self::zip($x, $y, $this->nullval);
            foreach($functions as $fn => $args) {
                $args = (array)$args;
                array_unshift($args, $zipped);
                $fn = __CLASS__.'::'.$fn;
                $zipped = call_user_func_array($fn, $args);
            }
            $into = array(
                "label" => $alias ? $alias : $target,
                "data" => $zipped);

            if (!empty($options)) {
                foreach ($options as $key => $value) {
                    $into[$key] = $value;
                }
            }
            $series[] = $into;
        }
        return $encode_json ? json_encode($series) : $series;
    }

    static function rollingSum($data, $numPoints) {
        $out = array();
        $index = 0;
        $tmp = 0;
        foreach ($data as $tuple) {
            list($dt, $val) = $tuple;
            if ($val==null) {
                continue;
            }
            $index++;
            $tmp += $val;
            if ($index >= $numPoints) {
                $v = $tmp;
                $index = 0;
                $tmp = 0;
                $out[] = array($dt, $v);
            }
        }
        $out = self::removeZeros($out);
        return $out;
    }

    static function movingAverage($data, $numPoints) {
        $out = array();
        $index = 0;
        $tmp = 0;
        foreach ($data as $tuple) {
            list($dt, $val) = $tuple;
            if ($val==null) {
                continue;
            }
            $index++;
            $tmp += $val;
            if ($index >= $numPoints) {
                $v = $tmp / $numPoints;
                $index = 0;
                $tmp = 0;
                $out[] = array($dt, $v);
            }
        }
        $out = self::removeZeros($out);
        return $out;
    }

    private static function removeZeros($list) {
        foreach($list as $k => $tuple) {
            if($list[$k][1] === 0) $list[$k][1] = null;
        }
        return $list;
    }


	// these functions operate on time series data and return a scalar unit
	static function sum($data) {
		$sum = 0;
		foreach ($data as $tuple) {
			list($dt, $val) = $tuple;
			$sum += $val;
		}
		return $sum;
	}

	static function avg($data) {
		$tmp = 0;
		$index = 0;
        foreach ($data as $tuple) {
            list($dt, $val) = $tuple;
            if ($val==null) {
                continue;
            }
            $index++;
            $tmp += $val;
        }
        return $tmp / $index;
	}
}
