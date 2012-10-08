<?php

class Dashboard {

    public static function getTimes() {
        return array(
            '' => 'Time',
            '10m' => '10 minutes',
            '30m' => '30 minutes',
            '1h' => '1 hour',
            '2h' => '2 hours',
            '4h' => '4 hours',
            '8h' => '8 hours',
            '12h' => '12 hours',
            '18h' => '18 hours',
            '1d' => '1 day',
            '2d' => '2 days',
            '1w' => '1 week',
            '2w' => '2 weeks',
            '4w' => '4 weeks',
        );
    }
    
    public static function getSampleTimes() {
        return array(
            '' => 'Default',
            '5m' => '5 miinutes',
            '20m' => '20 miinutes',
            '1h' => '1 hour',
            '1d' => '1 day',
            '1w' => '1 week',
        );
    }

    public static function displayTime($time) {
        $units = array('m' => 'minute',
            'h' => 'hour',
            'd' => 'day',
            'w' => 'week',
            'y' => 'year',
        );
        list($t, $u) = self::_parseTime($time);
        return $t . ' ' . $units[$u] . (($t > 1) ? 's' : '');
    }

    public static function epochSecondsForTime($time) {
        list($t, $u) = self::_parseTime($time);
        $unit_seconds = 0;
        if ($u == 'm') $unit_seconds = 60;
        if ($u == 'h') $unit_seconds = 3600;
        if ($u == 'd') $unit_seconds = 86400;
        if ($u == 'w') $unit_seconds = 86400 * 7;
        if ($u == 'y') $unit_seconds = 86400 * 365;
        return time() - ($t * $unit_seconds);
    }

    private static function _parseTime($time) {
        preg_match("/^(\d+)([a-z])/", strtolower($time), $m);
        return array($m[1], $m[2]);
    }

}

