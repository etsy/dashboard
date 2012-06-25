<?php

require_once "../phplib/Dashboard.php";

$urls = array_values(Dashboard::$HADOOP_TABS);
header('Location: ' . $urls[0]);
