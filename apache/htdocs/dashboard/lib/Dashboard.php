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
            '4d' => '4 days',
            '1w' => '1 week',
            '2w' => '2 weeks',
            '4w' => '1 month',
            '8w' => '2 months',
        );
    }

	public static function getWidthHeight() {
		return array(
			'400x250' => array(400,250),
			'600x400' => array(600,400),
			'800x500' => array(800,500),
			'900x600' => array(900,600),
			'1000x700' => array(1000,700),
			'1300x900' => array(1300,900),
		);
	}

	public static function getChartSize() {
		return array(
			'400x250' => '400x250',
			'600x400' => '600x400',
			'800x500' => '800x500',
			'900x600' => '900x600',
			'1000x700' => '1000x700',
			'1300x900' => '1300x900',
		);
	}
    
    public static function getWidth() {
        return array(
            //'' => 'Time',
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
            '4d' => '4 days',
            '1w' => '1 week',
            '2w' => '2 weeks',
            '4w' => '1 month',
            '8w' => '2 months',
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

