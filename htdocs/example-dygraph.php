<?php

require_once dirname(dirname(__FILE__)) . '/lib/bootstrap.php';

$times = Dashboard::getTimes();

$time = !empty($_GET['time']) ? $_GET['time'] : "1h";
$hide_deploys = !empty($_GET['hide_deploys']) ? true : false;
$show_deploys = (!$hide_deploys);

?>
<!DOCTYPE html>
<html>
<head>
<title>Deploy Dashboard</title>
<link rel="stylesheet" type="text/css" href="css/screen.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
<script src="js/dashboard.js"></script>
<script type="text/javascript" src="js/dygraph-combined.js"></script>
<script type="text/javascript" src="js/Graphite-to-Dygraph.js"></script>

</head>
<body id="deploy" class="dashboard">

<div id="status"></div>

<form id="controls" action="<?= $_SERVER['PHP_SELF'] ?>">
    <select name="time">
        <? foreach (($times) as $key => $value) { ?>
            <option value="<?= $key ?>" <? if ($key == $time) { echo "selected"; } ?> ><?= $value ?></option>
        <? } ?>
        <script type="text/javascript">
				var etsyTime = "<?php echo Dashboard::displayTime($time) ?>";
				var periodSelected = "<?php echo Dashboard::epochSecondsForTime($time) ?>";
		</script> 
    </select>
</form>

<div id="graphdiv"></div><div id="graphdiv-source"></div>

<script type="text/javascript">

				//Note: environment, service_name, grid and metric just get strung together. 
				params = {environment: "PROD", service_name: "<Service Name>", grid: "<Service Grid>",metric: "load_one.sum",targetdiv: "graphdiv", periodSelected: periodSelected};
				createDygraph(params);

</script>


</body>
</html>
