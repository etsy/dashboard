/* 
 To set a custom refresh timeout for images, simply set
 refresh_timeout any time before this script is loaded.

 Long timeouts are preferable for graphs that show more than an
 hour or two of data as it reduces the load on Graphite.
 DEFINITELY do this for any graph that shows more than a few days
 of data or your dashboard will wind up DDOSing graphite01!
 */

var image_index = 0;
var default_refresh_timeout = 4000;
var refresh_timeout = (typeof refresh_timeout !== 'undefined' ? refresh_timeout : default_refresh_timeout);

/* Define your graph servers here */
var ganglia_server = 'ganglia.example.com';
var graphite_server = 'graphite.example.com';
var cacti_server = 'cacti.example.com';
var tsd_server = 'tsd.example.com';

function refreshImage() {
    if (image_index < document.images.length) {
        var imagesrc = document.images[image_index].src;

        // Update a timestamp on the graphite and ganglia graphs.
        if (imagesrc.indexOf(ganglia_server) >= 0 || imagesrc.indexOf(ganglia_server) >= 0) {
            imagesrc = imagesrc.replace(/&st=\d+/, '');
            imagesrc += '&st=' + Math.floor(new Date().getTime() / 1000);
        }

        // Update a timestamps for cacti graphs. Read the original timestamps
        // from the image query string and update start/end times.
        if (imagesrc.indexOf(cacti_server) >= 0) {
            match = imagesrc.match(/graph_start=\d+/);
            start_time = match[0].split('=')[1];
            match = imagesrc.match(/graph_end=\d+/);
            end_time = match[0].split('=')[1];

            seconds_diff = end_time - start_time;
            new_start = 'graph_start=' + (Math.floor(new Date().getTime() / 1000) - seconds_diff);
            new_end = 'graph_end=' + Math.floor(new Date().getTime() / 1000);

            imagesrc = imagesrc.replace(/graph_start=\d+/, new_start);
            imagesrc = imagesrc.replace(/graph_end=\d+/, new_end);
        }

        document.images[image_index].src = imagesrc;
    }

    image_index++;
    if (image_index > document.images.length) {
        image_index = 0;
    }
    setTimeout("refreshImage();", refresh_timeout);

    var status = '.';
    for (i = 0; i < document.images.length - image_index; i++) {
        status += '.';
    }

    $('#status').text(status);
}


$(document).ready(function() {
    setTimeout("refreshImage();", refresh_timeout);

    // onchange event handler for select menus
    $("#controls select, #controls input").change(function() {
        $(this).parents('form').submit();
    });
});

