var image_index = 0;
var left_nav_hidden = 0;
var default_refresh_timeout = 4000;
var refresh_timeout = (typeof refresh_timeout !== 'undefined' ? refresh_timeout : default_refresh_timeout);

function refreshImage() {
	if (image_index < document.images.length) {
		var imagesrc = document.images[image_index].src;

		// Update a timestamp on the Graphite and Ganglia graphs.
		if (imagesrc.indexOf("ganglia") >= 0 || imagesrc.indexOf("graphite") >= 0) {
			imagesrc = imagesrc.replace(/&st=\d+/, '');
			imagesrc += '&st=' + Math.floor(new Date().getTime() / 1000);
		}

		// Update a timestamps for Cacti graphs. Read the original timestamps
		// from the image query string and update start/end times.
		if (imagesrc.indexOf("cacti") >= 0) {
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

	status = '.';
	for (i=0; i < document.images.length - image_index; i++) {
		status += '.';
	}
	$('#status').text(status);
}


$(document).ready(function() {
	setTimeout("refreshImage();", refresh_timeout);

	// onchange handler for select menus
	$("#controls select, #controls input").change(function() {
		$("#controls").submit();
	});
        
        $("button").click(function(){
            $("#leftnav").toggle('slow');
            if ( left_nav_hidden == 0 ) {
                $("#content").css('margin-left', '10px');
                left_nav_hidden = 1;
            } else {
                $("#content").css('margin-left', '200px');
                left_nav_hidden = 0;
            }
        });
});


