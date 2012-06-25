<?php

/** shorten.php is a helper to shorten your graph URLs.
 * It requires that you have your own shortening system, or use an external 
 * service like bit.ly, which takes a long URL and returns a short one.
 * Update the "example.com" URL below with the URL to your shortening service.
 * $long_url will be replaced with the original URL.
 */

header('Content-type: application/json');

$long_url = isset($_GET['long_url']) ? $_GET['long_url'] : null;

$result = array('success' => false);

if ($long_url) {
    $short_url = file_get_contents("http://example.com/shorten.php?longurl=$long_url");

    if ($short_url) {
        $result = array('success' => true, 'short_url' => $short_url);
    }
}

echo json_encode($result);
