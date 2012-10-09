<div id="stylized" class="left_nav_form">
    <h3>Options</h3>
    <form id="controls" action="<?= $_SERVER['PHP_SELF'] ?>">
        <label for="time">Graph Time</label>
        <select name="time">
            <? foreach (($view['times']) as $key => $value) { ?>
                <option value="<?= $key ?>" <? if ($key == $view['time']) { echo "selected"; } ?> ><?= $value ?></option>
            <? } ?>
        </select>
        <br/>
		<label for="size">Graph Size</label>
		<select name="size">
		    <? foreach (($view['size']) as $value) { ?>
		        <option value="<?= $value ?>" <? if ($value == $view['size']) { echo "selected"; } ?> ><?= $value ?></option>
		    <? } ?>
		</select>
    </form>
    <hr/>
    <h3>Traffic</h3>
        <li><a href="mysql_login.php">Logins</a></li>
        <li><a href="apache_traffic.php">Web Traffic</a></li>
    <hr >
    <h3>Site Performance</h3>
    	<b>Page Serve Time</b>
    		<li><a href="apache_perf_50.php">Page Serve 50</a></li>
        	<li><a href="apache_perf_80.php">Page Serve 80</a></li>
        	<li><a href="apache_perf_99.php">Page Serve 99</a></li>
    <hr >
    <h3>DB Tier (King)</h3>
    	<b>System</b>
    		<li><a href="db_king_system_proc.php">Processor</a></li>
    	<b>Mysql</b>
        	<li><a href="db_king_mysql_query.php">Query Usage</a></li>
    <hr >
    <h3>Web Tier</h3>
    	<b>System</b>
    		<li><a href="web_system_proc.php">Processor</a></li>
        <b>Memcached</b>
        	<li><a href="web_mc_data.php">Data</a></li>
        	<li><a href="web_mc_sess.php">Session</a></li>
        <b>Rails</b>
        	<li><a href="rails_ewok.php">ewok</a></li>
        	<li><a href="rails_ab.php">ab_test</a></li>
    <hr >
    <h3>MQ Tier</h3>
		<b>System</b>
        	<li><a href="mq_system_proc.php">Processor</a></li>
</div>

