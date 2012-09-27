<!DOCTYPE html>
<html lang='en'>
<head>
    <title>Web Cluster Dashboard</title>
    <link rel="stylesheet" type="text/css" href="css/screen.css">
    <link rel="stylesheet" type="text/css" href="css/container.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
    <script src="js/dashboard.js"></script>
</head>
<body id="webcluster" class="dashboard">

    <div id="container">

        <div id="top">
            <div id="status"></div>
            <h1><?php echo $view['title']; ?> - <?php echo $view['time']; ?></h1>
            <div style="float: right;"><button>Show/Hide</button></div>
        </div>

        <div id="leftnav">

            <?php echo $view['leftnav']; ?>

        </div>


        <div id="content">
            <?php
                if ( array_key_exists('body', $view ) ) {
                    foreach ( $view['body'] as $graph ) {
                        print("$graph\n");
                    }
                } else {
                    echo "no graphs";
                }
            ?>
        </div>


        <div id="footer">
            &nbsp;
        </div>
    </div>
    
</body>
</html>

