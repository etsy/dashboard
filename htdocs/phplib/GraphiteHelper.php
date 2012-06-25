<?php

/**
 * Helper class for fetching metrics buckets from Graphite.
 */
class GraphiteHelper {

    const GRAPHITE_URL = '/metrics/find/';

    const GRAPHITE_RENDER_URL = '/render/';

    const CONNECTION_TIMEOUT = 10;

    const TIMEOUT = 15;

    /**
     * @param string $graphite_endpoint
     * @return array
     */
    public static function fetchChildMetrics($graphite_endpoint) {
        $metrics = self::fetchMetrics($graphite_endpoint . "*");

        $children = array();

        foreach ($metrics as $metric) {
            $children[] = $metric['name'];
        }

        return $children;
    }

    /**
     * @param string $graphite_endpoint
     * @return array
     */
    public static function fetchMetricsPaths($graphite_endpoint) {
        $metrics = self::fetchMetrics($graphite_endpoint);
        $paths = array();

        foreach ($metrics as $metric) {
            $paths[] = $metric['path'];
        }

        return $paths;
    }

    /**
     * @param string $graphite_endpoint
     * @return array
     */
    public static function fetchMetricsPathsAndNames($graphite_endpoint) {
        $metrics = self::fetchMetrics($graphite_endpoint);
        $paths = array();
        foreach ($metrics as $metric) {
            $paths[] = array("path" => $metric['path'], "name" => $metric['name']);
        }

        return $paths;
    }

    /**
     * @param array $paths
     * @return array
     */
    public static function groupMetricsByLeaf($paths) {
        $grouped_paths = array();
        foreach ($paths as $path) {
            if (empty($grouped_paths[$path['name']])) {
                $grouped_paths[$path['name']] = array();
            }
            $grouped_paths[$path['name']][] = $path;
        }
        var_dump($grouped_paths);
        return $grouped_paths;
    }

    /**
     * @param string $graphite_endpoint
     * @return array
     */
    public static function fetchMetrics($graphite_endpoint) {
        global $graphite_server;
        $handler = curl_init();
        $params = array(
            'format' => 'completer',
            'query' => $graphite_endpoint
        );
        $opts = array(
            CURLOPT_CONNECTTIMEOUT => self::CONNECTION_TIMEOUT,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => self::TIMEOUT,
            CURLOPT_URL => $graphite_server . self::GRAPHITE_URL,
            CURLOPT_POSTFIELDS => http_build_query($params, null, '&'),
        );
        curl_setopt_array($handler, $opts);
        $json = json_decode(curl_exec($handler), 1);
        return isset($json['metrics']) && $json['metrics'] ? $json['metrics'] : array();
    }

    /**
     * @param string $query
     * @return mixed
     */
    public static function fetchRenderData($query) {
        global $graphite_server;
        $url = $graphite_server . self::GRAPHITE_RENDER_URL . '?' . $query;

        $handler = curl_init();
        $opts = array(
            CURLOPT_CONNECTTIMEOUT => self::CONNECTION_TIMEOUT,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => self::TIMEOUT,
            CURLOPT_HTTPGET => true,
            CURLOPT_HEADER => 0,
            CURLOPT_URL => $url,
        );
        curl_setopt_array($handler, $opts);

        $result = curl_exec($handler);

        if (curl_getinfo($handler, CURLINFO_HTTP_CODE) != 200) {
            return false;
        } else {
            return $result;
        }
    }

    /**
     * split left hand "headerdata" and right hand "data points"
     * @param string $query
     * @return array
     */
    public static function fetchSplitRenderData($query) {
        $response = self::fetchRenderData($query);
        return explode("|", $response);
    }

    public static function fetchSplitRenderDataMultipleTargets($targets, $from) {
        $query = self::buildMultipleTargetQuery($targets, $from);

        $response = self::fetchRenderData($query);

        if ($response === false) {
            return array();
        }

        $metrics = explode("\n", $response);

        $non_empty_metrics = array_filter($metrics, array("GraphiteHelper", "nonEmpty"));

        return array_map(array("GraphiteHelper", "splitOnPipes"), $non_empty_metrics);
    }

    public static function fetchKeyedSplitRenderDataMultipleTargets($targets, $from) {
        $query = self::buildMultipleTargetQuery($targets, $from);
        return self::fetchKeyedSplitRenderData($query);
    }

    public static function buildMultipleTargetQuery($targets, $from) {
        $query = '';

        $formatted_from = strpos($from, '-') === FALSE ? "-$from" : $from;

        foreach ($targets as $target) {
            $query .= sprintf('target=%s&from=%s&', urlencode($target), $formatted_from);
        }

        return $query . 'rawData';
    }

    /**
     * @param array $graphite_values
     * @return float
     */
    public static function getMaxValue($graphite_values) {
        $max = 0.0;

        foreach ($graphite_values as $value) {
            if ($value != 'None' && $value > $max) {
                $max = $value;
            }
        }

        return $max;
    }

    public static function nonEmpty($input) {
        return !empty($input);
    }

    public static function splitOnPipes($input) {
        return explode("|", $input);
    }

    public static function splitOnCommas($input) {
        return explode(",", $input);
    }

    public static function fetchKeyedSplitRenderData($query) {
        $response = self::fetchRenderData($query);

        $unparsed_results = explode("\n", $response);

        return self::convertSplitRenderDataToKeyedSplitRenderData($unparsed_results);
    }

    public static function convertSplitRenderDataToKeyedSplitRenderData($unparsed_results) {
        $results = array();

        foreach ((array)$unparsed_results as $unparsed_result) {
            if (strpos($unparsed_result, '|') !== false) {
                list($header, $points) = explode('|', $unparsed_result);

                $header_values = explode(',', $header);
                $data_points = explode(',', $points);

                $results[$header_values[0]] = $data_points;
            }
        }

        return $results;
    }

    /**
     * @param string $query
     * @return array just the right hand "data points"
     */
    public static function fetchDataPoints($query) {
        $response = self::fetchSplitRenderData($query);
        return count($response) >= 2 && !empty($response[1]) ? explode(",", $response[1]) : array();
    }

    /**
     * @param string $time
     * @return string
     */
    public static function getTimeParam($time) {
        $units = array(
            'h' => 'hours',
            'd' => 'days',
            'w' => 'weeks',
            'm' => 'months',
            'y' => 'years',
        );

        if (empty($time)) {
            return;
        } else if (preg_match("/^(\d+)([a-z])/", strtolower(trim($time)), $m)) {
            return '-' . $m[1] . $units[$m[2]];
        } else if (preg_match("/^(\d){10}$/", $time)) {
            return $time;
        }
    }

    public static function getStartTime($time) {
        // wrapping this while we migrate to just using one of these dupe'd functions...
        return strtotime(self::getTimeParam($time));
    }

    public static function getHitCountTime($time) {
        $start_time = self::getStartTime($time);

        if (time() - $start_time < (60 * 60 * 2 + 60)) {
            return '5minutes';
        } else if (time() - $start_time < (60 * 60 * 4 + 60)) {
            return '10minutes';
        } else if (time() - $start_time < (60 * 60 * 8 + 60)) {
            return '20minutes';
        } else if (time() - $start_time < (60 * 60 * 24 + 60)) {
            return '30minutes';
        } else if (time() - $start_time < (60 * 60 * 24 * 2 + 60)) {
            return '1hour';
        } else {
            return '2hours';
        }
    }

    /**
     * @param array $data_points graphite data points, containing the string 'None' for points with no data
     * @return float
     */
    public static function getAverage($data_points) {
        $total = 0.0;
        $count = 0;

        foreach ((array)$data_points as $point) {
            if ($point != 'None') {
                $total += $point;
                $count++;
            }
        }

        return $count != 0 ? $total / $count : 0.0;
    }

    /**
     * @param array $data_points
     * @return float
     */
    public static function getTotal($data_points) {
        $total = 0.0;

        foreach ((array)$data_points as $point) {
            if ($point != 'None') {
                $total += $point;
            }
        }

        return $total;
    }

    /**
     * Fetch a list of graphite graphs starting with a key (or partial).
     * "$exclude" (regex) will skip any keys matching
     *
     * @param string $namespace
     * @param string $exclude regex
     * @return array
     */
    public static function getGraphiteList($namespace, $exclude) {
        global $graphite_server;
        $raw_list = file_get_contents("http://{$graphite_server}/metrics/find?query=$namespace*");
        $json_list = json_decode($raw_list, true);
        $list = array();

        foreach ($json_list as $node) {
            if (!isset($node["id"])) {
                continue;
            }
            if ($exclude && preg_match($exclude, $node["id"])) {
                continue;
            }
            $list[] = preg_replace("/$namespace/", "", $node["id"]);
        }

        return $list;
    }
}
