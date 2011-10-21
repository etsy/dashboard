<?php

class Dashboard {

    public static function getTimes() {
        return array(
            '' => 'Time',
            '1h' => '1 hour',
            '2h' => '2 hours',
            '4h' => '4 hours',
            '12h' => '12 hours',
            '1d' => '1 day',
            '2d' => '2 days',
            '1w' => '1 week',
            '1m' => '1 month',
        );
    }

    public static function displayTime($time) {
        $units = array('h' => 'hour',
            'd' => 'day',
            'w' => 'week',
            'm' => 'month',
            'y' => 'year',
        );
        list($t, $u) = self::_parseTime($time);
        return $t . ' ' . $units[$u] . (($t > 1) ? 's' : '');
    }

    public static function epochSecondsForTime($time) {
        list($t, $u) = self::_parseTime($time);
        $unit_seconds = 0;
        if ($u == 'h') $unit_seconds = 3600;
        if ($u == 'd') $unit_seconds = 86400;
        if ($u == 'w') $unit_seconds = 86400 * 7;
        if ($u == 'm') $unit_seconds = 86400 * 30;
        if ($u == 'y') $unit_seconds = 86400 * 365;
        return time() - ($t * $unit_seconds);
    }

    private static function _parseTime($time) {
        preg_match("/^(\d+)([a-z])/", strtolower($time), $m);
        return array($m[1], $m[2]);
    }

}

