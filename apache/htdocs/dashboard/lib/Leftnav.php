<div id="stylized" class="left_nav_form">
    <h3>Options</h3>
    <form id="controls" action="<?= $_SERVER['PHP_SELF'] ?>">
        <label for="time"><b>Graph Time</b></label>
        <select name="time">
            <? foreach (($view['times']) as $key => $value) { ?>
                <option value="<?= $key ?>" <? if ($key == $view['time']) { echo "selected"; } ?> ><?= $value ?></option>
            <? } ?>
        </select>
        <br/>
		<label for="size"><b>Graph Size</b></label>
		<select name="size">
		    <? foreach (($view['size'])  as $key => $value) { ?>
		        <option value="<?= $key ?>" <? if ($key == $view['size']) { echo "selected"; } ?> ><?= $value ?></option>
		    <? } ?>
		</select>
		<br/>
		<label for="downsample"><b>Downsample Time</b></label>
		<select name="downsample">
		    <? foreach (($view['downsample'])  as $key => $value) { ?>
		        <option value="<?= $key ?>" <? if ($key == $view['downsample']) { echo "selected"; } ?> ><?= $value ?></option>
		    <? } ?>
		</select>
		<br/>
    </form>
    <hr/>
    <h3>Traffic</h3>
        <li><a href="mysql_login.php">Login (Tracking)</a></li>
        <li><a href="apache_traffic.php">Web Traffic</a></li>
    <hr >
    <h3>Site Performance</h3>
    	<b>Page Serve Aggregate</b>
    		<li><a href="apache_perf_50_agg.php">Page Serve 50</a></li>
        	<li><a href="apache_perf_80_agg.php">Page Serve 80</a></li>
        	<li><a href="apache_perf_99_agg.php">Page Serve 99</a></li>
		<b>Page Serve By Server</b>
    		<li><a href="apache_perf_50_server.php">Page Serve 50</a></li>
        	<li><a href="apache_perf_80_server.php">Page Serve 80</a></li>
        	<li><a href="apache_perf_99_server.php">Page Serve 99</a></li>
    <hr >
    <h3>DB Tier (King)</h3>
    	<b>System</b>
    		<li><a href="db_king_system_proc.php">Processor</a></li>
			<li><a href="db_king_system_disk.php">Disk</a></li>
			<li><a href="db_king_system_net.php">Network</a></li>
			<li><a href="db_king_system_mem.php">Memory</a></li>
    	<b>Mysql</b>
        	<li><a href="db_king_mysql_query.php">Summary</a></li>
			<li><a href="db_king_mysql_innodb.php">innodb</a></li>
    <hr >
    <h3>WEB Tier</h3>
    	<b>System</b>
    		<li><a href="web_system_proc.php">Processor</a></li>
			<li><a href="web_system_disk.php">Disk</a></li>
			<li><a href="web_system_net.php">Network</a></li>
			<li><a href="web_king_system_mem.php">Memory</a></li>
        <b>Memcached</b>
        	<li><a href="web_mc_data.php">Data</a></li>
        	<li><a href="web_mc_sess.php">Session</a></li>
        <b>Rails</b>
        	<li><a href="rails_ewok.php">ewok</a></li>
        	<li><a href="rails_ab.php">ab_test</a></li>
		<b>mysql-proxy</b>
        	<li><a href="web_mysql-proxy.php">king</a></li>
    <hr >
	<h3>PWEB Tier</h3>
    	<b>System</b>
    		<li><a href="pweb_system_proc.php">Processor</a></li>
			<li><a href="pweb_system_disk.php">Disk</a></li>
			<li><a href="pweb_system_net.php">Network</a></li>
			<li><a href="pweb_king_system_mem.php">Memory</a></li>
        <b>Memcached (partner)</b>
        	<li><a href="pweb_mc_data.php">Data</a></li>
        	<li><a href="pweb_mc_sess.php">Session</a></li>
        <b>Rails</b>
        	<li><a href="pweb_rails_pass.php">all passenger</a></li>
            <li><a href="pweb_rack_partner.php">rack:partner</a></li>
            <li><a href="pweb_rack_warehouse.php">rack:warehouse</a></li>
            <li><a href="pweb_rack_kamino.php">rack:kamino</a></li>
            <li><a href="pweb_rack_nexus.php">rack:nexus</a></li>
            <li><a href="pweb_rack_nexus_reloaded.php">rack:nexus_rel</a></li>
            <li><a href="pweb_rack_r2d2.php">rack:r2d2</a></li>
		<b>mysql-proxy (partner)</b>
        	<li><a href="pweb_mysql-proxy.php">partner</a></li>
    <hr >
    <h3>MQ Tier</h3>
		<b>System</b>
        	<li><a href="mq_system_proc.php">Processor</a></li>
			<li><a href="mq_system_disk.php">Disk</a></li>
			<li><a href="mq_system_net.php">Network</a></li>
			<li><a href="mq_king_system_mem.php">Memory</a></li>
		<b>Queue</b>
			<li><a href="mq_queue.php">Queue Length</a></li>
		<b>mysql-proxy</b>
        	<li><a href="mq_mysql-proxy.php">king</a></li>
</div>

