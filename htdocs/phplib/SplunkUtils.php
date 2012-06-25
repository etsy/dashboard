<?php

class SplunkUtils {
    /**
     * @param string $query
     * @return string
     */
    public static function splunkSearchUrl($query) {
        global $splunk_server;
        return "https://{$splunk_server}/en-US/app/search/flashtimeline?q=search " . urlencode(trim($query));
    }

    /**
     * @param string $content
     * @param string $link
     * @return string
     */
    public static function htmlUrl($content, $link) {
        return sprintf('<a href="%s">%s</a>', $link, $content);
    }
}
