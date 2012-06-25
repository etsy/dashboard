<?php
$hosts = isset($_GET['hosts']) ? $_GET['hosts'] : 'localhost';
$hosts = preg_split('/,\s*/', $hosts);

$response = array();
$response['results'] = array();

foreach ($hosts as $host) {
    if (strpos($host, ':') !== false) {
        list($host, $port) = explode(':', $host);
    } else {
        $port = 4730;
    }

    $fp = fsockopen($host, $port, $errno, $errstr, 3);

    if (!$fp) {
        $response['ok'] = false;
        $response['error'] = "Failed connecting to $host:$port -- $errstr ($errno)";
        break;

    } else {
        $response['ok'] = true;

        $out = "status\n";
        fwrite($fp, $out);
        while (!feof($fp)) {
            $line = trim(fgets($fp, 128));
            if ($line == ".") {
                break;
            }
        
            $data = explode("\t", $line);

            if (!isset($response['results'][$data[0]])) {
                $response['results'][$data[0]] = array(
                    'total' => 0,
                    'running' => 0,
                    'workers' => 0
                );
            }

            $response['results'][$data[0]]['total'] += intval($data[1]);
            $response['results'][$data[0]]['running'] += intval($data[2]);
            $response['results'][$data[0]]['workers'] += intval($data[3]);
       }

        fclose($fp);
    }
}

header("Content-type: application/json");
print(json_encode($response));

