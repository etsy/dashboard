<?php

/**
 * Sample:
 *
 * $gds = new GraphiteDataSummary('stats.shop.all', GraphiteDataSummary::HOUR, 1);
 * $data = $gds->getData();
 * $percent_changes = $gds->getPercentDeltas();
 */
class GraphiteDataSummary {
    const   MINUTE = 60;
    const   HOUR = 3600;
    const   DAY = 86400;
    const   WEEK = 604800;
    const   MONTH = 'month';

    /** @var int */
    private $from = null;

    /** @var int */
    private $until = null;

    /** @var string */
    private $target = null;

    /** @var string */
    private $base_url = 'render?rawData=true';

    /** @var int */
    private $granularity = null;

    /** @var array */
    private $data = null;

    /**
     * @param string $graph_name
     * @param int $granularity
     * @param int $periods
     * @param int $time
     */
    public function __construct($graph_name, $granularity = self::DAY, $periods = 1, $time = null) {
        $this->target = $graph_name;
        $this->granularity = $granularity;

        if ($time === null) {
            $time = time();
        }

        //the data we need is periods + 1 back from the time
        $this->from = $time - (($periods + 1) * $granularity);

        if ($this->from < 0) {
            throw new InvalidArgumentException("Data origin point is before the epoch!");
        }

        $this->until = $time;
    }

    public function getDataURL() {
        global $graphite_server;
        return sprintf('%s/%s&%s', $graphite_server, $this->base_url,
            http_build_query(array('from' => $this->from, 'until' => $this->until, 'target' => $this->target)));
    }

    public function getData($fresh = false) {
        if ($fresh || !is_array($this->data)) {
            $this->data = $this->fetchData();
        }

        return array_slice($this->data, 1);
    }

    public function getPercentDeltas() {
        if (!is_array($this->data)) {
            $this->getData();
        }

        $rval = array();

        for ($i = 1; $i < count($this->data); $i++) {
            $rval[] = (($this->data[$i] - $this->data[$i - 1]) / $this->data[$i - 1]) * 100;
        }

        return $rval;
    }

    private function fetchData() {
        $handle = fopen($this->getDataURL(), 'r');

        if ($handle === false) {
            throw new Exception(sprintf("Could not open stream for url '%s'", $this->getDataURL()));
        }

        $metadata = explode(',', stream_get_line($handle, 1024, '|'));

        if (count($metadata) != 4) {
            throw new UnexpectedValueException(sprintf("Dataset metadata was not valid: %s", print_r($metadata, true)));
        }

        $index = 0;
        $data_period = $metadata[3];
        $pit = $metadata[1];
        $next_boundary = $metadata[1] + $this->granularity;
        $end = $metadata[2];

        $collector = array(0);

        while (!feof($handle)) {
            $data = stream_get_line($handle, 64, ',');

            if ($data === false) {
                throw new RuntimeException(sprintf("Stream error on %s", $this->getDataURL()));
            }

            $collector[$index] += floatval($data); //Nones resolve to 0
            $pit += $data_period;

            if ($pit >= $next_boundary) {
                $collector[$index] *= $data_period;
                if ($pit >= $end) break;
                $index++;
                $collector[$index] = 0;
                $next_boundary += $this->granularity;

            }
        }

        fclose($handle);
        return $collector;
    }
}
