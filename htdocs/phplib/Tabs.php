<?php

class Tabs {
    /**
     * @param string $filename
     * @return string
     */
    public static function getTabUrl($filename) {
        $split = explode("/htdocs/", $filename);

        if (count($split) == 2) {
            return "/{$split[1]}";
        } else {
            error_log("Bad key for tab url: $filename");
            return '';
        }
    }

    /**
     * @param string $url
     * @return bool
     */
    public static function isAbsoluteLink($url) {
        return substr($url, 0, 5) == 'http:' || substr($url, 0, 6) == 'https:';
    }

    public static function getLinkTitle($title, $url) {
        if (is_int($title)) {
            return self::buildDefaultTabTitle($url);
        } else {
            return $title;
        }
    }

    /**
     * @param string $url
     * @return string
     */
    public static function getLinkTarget($url) {
        return self::isAbsoluteLink($url) ? 'target="_new"' : '';
    }

    /**
     * @param string $url
     * @return string
     */
    public static function getLinkIcon($url) {
        return self::isAbsoluteLink($url) ? '<img src="images/icon_new_window.gif" height="12" width="12"/>' : '';
    }

    /**
     * @param string $url
     * @return string
     */
    public static function buildDefaultTabTitle($url) {
        $split = explode("/", $url);
        $title = $split[count($split) - 1];
        $title = str_replace('.php', '', $title);
        $title = str_replace('_', ' ', $title);
        $title = ucwords($title);
        return $title;
    }
}
