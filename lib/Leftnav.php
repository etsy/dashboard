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
    <label for="sampletime">Down Sample</label>
    <select name="sampletime">
        <? foreach (($view['sampletimes']) as $key => $value) { ?>
            <option value="<?= $key ?>" <? if ($key == $view['time']) { echo "selected"; } ?> ><?= $value ?></option>
        <? } ?>
    </select>
    
    <hr/>
</form>
</div>

<h3>Other Reports</h3>
<ul>
    <li><a href="market.php">Marketing</a></li>
    <li><a href="web.php">Web Performance</a></li>
    <li><a href="db.php">DB Performance</a></li>
</ul>