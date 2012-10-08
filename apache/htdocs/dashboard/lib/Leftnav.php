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
    </form>
    <hr/>
    <h3>Traffic</h3>
    <ul>
        <li><a href="mysql_login.php">Logins</a></li>
        <li><a href="apache_traffic.php">Web Traffic</a></li>
    </ul>
    <h3>Site Performance</h3>
    <ul>
        <li><a href="apache_perf_50.php">Page Serve 50</a></li>
        <li><a href="apache_perf_80.php">Page Serve 80</a></li>
        <li><a href="apache_perf_99.php">Page Serve 99</a></li>
    </ul>
    <h3>DB Tier (King)</h3>
    <ul>
        <li><a href="db_king_system.php">System Stats</a></li>
        <li><a href="db_king_mysql.php">MySql</a></li>
    </ul>
    
    <h3>Web Tier</h3>
    <ul>
        <li><a href="web_system.php">System Stats</a></li>
        <li><a href="web_mc_data.php">Memcached Data</a></li>
        <li><a href="web_mc_sess.php">Memcached Session</a></li>
        <li><a href="rails_ewok.php">Rails - ewok</a></li>
        <li><a href="rails_ab.php">Rails - ab</a></li>
    </ul>
    
    <h3>MQ Tier</h3>
    <ul>
        <li><a href="mq_system.php">System Stats</a></li>
    </ul>
</div>

