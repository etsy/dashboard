<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="description" content="Etsy Time" />
    <meta name="language" content="en/US" />
    <title>Time</title>
    <script src="assets/js/jquery-1.6.2.min.js"></script>
    <script src="assets/js/search/vendor/jquery.tmpl.min.js"></script>
    <script src="assets/js/moment.min.js"></script>
    <script src="assets/js/search/vendor/jquery.address-1.4.min.js"></script>
    <link rel="stylesheet" type="text/css" href="assets/css/time.css" />
  </head>
  <body>
    <div id="settings">
      <select id="settings_from">
        <option value="now">Right Now</option>
        <option value="from_utc_epoch_seconds">From UTC Epoch Seconds</option>
      </select>
      <div id="settings_from_utc_epoch_seconds" class="settings_input">
         <input type="text" id="input_utc_epoch_seconds" />
      </div>
      <div id="settings_from_utc_date" class="settings_input">
      ?
      </div>
      <div id="local_time_message">Times Based on Local Time: <span id="local_time"></span></div>
    </div>
    <div id="time_boxes"></div>
    <script type="text/html" id="time_section">
      <section id="${id}" class="time_section">
        <h1>${title} <span class="offset">UTC ${ offsetHours }</span></h1>
        <ul>
          <li>
            <div class="time readable" id="${id}_time_readable"></div>
          </li>
          <li>
            <div class="time 24h" id="${id}_time_24h"></div>
          </li>
          <li>
            <label for="${id}_time_utc_epoch_seconds">Epoch Seconds</label>
            <div class="time utc_epoch_seconds" id="${id}_time_utc_epoch_seconds"></div>
           </li>
           <li>
              <label for="${id}_time_epoch_millis">Epoch Milliseconds</label>
              <div class="time epoch_millis" id="${id}_time_epoch_millis"></div>
           </li>
         </ul>
      </section>
    </script>
    <script src="assets/js/time.js"></script>
  </body>
</html>
