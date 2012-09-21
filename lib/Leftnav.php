<form id="controls" action="<?= $_SERVER['PHP_SELF'] ?>">
    <select name="time">
        <? foreach (($view['times']) as $key => $value) { ?>
            <option value="<?= $key ?>" <? if ($key == $view['time']) { echo "selected"; } ?> ><?= $value ?></option>
        <? } ?>
    </select>
    
    <select name="sampletime">
        <? foreach (($view['sampletimes']) as $key => $value) { ?>
            <option value="<?= $key ?>" <? if ($key == $view['time']) { echo "selected"; } ?> ><?= $value ?></option>
        <? } ?>
    </select>
</form>