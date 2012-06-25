<?php

class Graph_NewRelic {
    /** @var string */
    protected $time;
    protected $graph_url;

    /** @param string $time */
    public function __construct($time, $graph_url) {
        $this->time = $time;
        $this->graph_url = $graph_url;
    }

    /**
     * @param int $width
     * @param int $height
     * @return string
     */
    public function getDashboardHTML($width = 600, $height = 300) {
        global $html_for_header;
        return $html_for_header .
               "<span class='newRelicGraph' style='width:{$width}px;'>" .
               "<iframe src=\"$this->graph_url\" width=\"$width\" height=\"$height\" scrolling=\"no\" frameborder=\"no\"></iframe>" .
               "</span>";
    }
}
