<?php
$page_title = $_GET['title'];
$width = !empty($_GET['width']) ? $_GET['width'] : 800;
$image_src = $_GET['url'];

$regex = '/target\=(.*?)\&/';
preg_match_all($regex, urldecode($image_src), $matches);

?>

<html>
<head><title><?= $page_title ?></title></head>
<body>
<div>
    <a href="<?= $image_src ?>"><img src="<?= $image_src ?>"/></a>
</body>
</html>
