// Copyright 2010 Google Inc. All Rights Reserved.

/* Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * @fileoverview This file contains all javascript used by analyticsCharts.html
 * to generate visual charts with data populated by the Google Analytics Export
 * API.
 * @author api.alexl@google.com (Alexander Lucas)
 */

// Load the Google data JavaScript client library.
google.load('gdata', '2.x', {packages: ['analytics']});

// Set the callback function when the library is ready.
google.setOnLoadCallback(init);

/**
 * This is called once the Google Data JavaScript library has been loaded.
 * It creates a new AnalyticsService object, adds a click handler to the
 * authentication button and updates the button text depending on the status.
 */
function init() {
  myService = new google.gdata.analytics.AnalyticsService('charts_sample');
  scope = 'https://www.google.com/analytics/feeds';
  var button = document.getElementById('authButton');

  // Add a click handler to the Authentication button.
  button.onclick = function() {
    // Test if the user is not authenticated.
    if (!google.accounts.user.checkLogin(scope)) {
      // Authenticate the user.
      google.accounts.user.login(scope);
    } else {
      // Log the user out.
      google.accounts.user.logout();
      getStatus();
    }
  }
  getStatus();
}

/**
 * Utility method to display the user controls if the user is
 * logged in. If user is logged in, get Account data and
 * get Report Data buttons are displayed.
 */
function getStatus() {
  
  var graphs = document.getElementById('graphs');
  var loginButton = document.getElementById('authButton');
  if (!google.accounts.user.checkLogin(scope)) {
    loginButton.innerHTML = 'Access Google Analytics';
  } else {
    loginButton.innerHTML = 'Logout of Google Analytics';
    processAllGraphs();
  }
}

function processAllGraphs() {
  var graphDivs = document.getElementsByClassName("gaGraph");
  for(var i=0; i < graphDivs.length; i++) {
    var graph = graphDivs[i];
    var table_id = graph.getAttribute('table_id');
    var metrics = graph.getAttribute('metrics');
    var sort = graph.getAttribute('sort');
    var dimensions = graph.getAttribute('dimensions');
    var filter = graph.getAttribute('filter');

    var myFeedUri = ['https://www.google.com/analytics/feeds/data',
    '?start-date=2011-11-01',
    '&end-date=2012-05-16',
    '&dimensions=', dimensions,
    '&metrics=', metrics, 
    '&sort=', sort,
    '&filters=', filter,
    '&max-results=20',
    '&ids=', table_id].join('');
    myService.getDataFeed(myFeedUri, buildHandler(graph.id), handleError);
  }
}

/**
 * Handle the data returned by the Export API for the Visitors by VisitorType
 * query, by converting the query into an image URL for a chart, and embedding
 * that chart within the HTML.
 * inner parts of an HTML table and inserting into the HTML File.
 * @param {object} result Parameter passed back from the feed handler.
 */
function buildHandler(divName){
var handleDataFeed = function(result) {

    document.getElementById(divName).innerHTML = '';
    var gaChartData = getVisitorChartData(result);
    var d1 = [];
    var days = gaChartData['days'];
    var returningVisitors = gaChartData['returningVisitors'];
    var newVisitors = gaChartData['newVisitors'];

    for(var i=0; i < days.length; i++){
      d1.push([parseInt(days[i]), returningVisitors[i] + newVisitors[i]]);
    }
    console.log(d1);
    $.plot('#' + divName, [d1], {xaxes: [ { mode: 'time' } ]});
    /*var barChart = getBarChart(gaChartData);
    drawChart(divName, barChart.getURL());

    var lineChart = getLineChartFromBarChart(barChart);
    drawChart(divName, lineChart.getURL());*/

  }
  return handleDataFeed;
}

/**
 * Handle the data returned by the Export API for the Entrances VS Bounces
 * query by converting the query into an image URL for a chart, and embedding
 * that chart within the HTML.
 * @param {object} result Parameter passed back from the feed handler.
 */
function handleBounceFeed(result) {

  document.getElementById('entrancesDiv').innerHTML = '';
  var chartData = getBounceChartData(result);

  // Initializes settings for the chart - type, width, colors, etc.  Sets to a
  // grouped bar chart.
  var chart = getEBChart(chartData);
  drawChart('entrancesDiv', chart.getURL());

  // How does it look as a line graph?  Easy to find out.  Just change the chart
  // type, and update the HTML.
  chart.setParam('cht', 'lc');
  drawChart('entrancesDiv', chart.getURL());

  // We can even turn this into a compound chart very easily.
  // The following parameters need to be changed.
  chart.setParams({
    'chm': 'D,0033FF,1,0,5,1', // add a line marker.  Reference here:
    'cht': 'bvg', // change base chart type to bar chart
    // t1 denotes that the base chart should only render the first dataset.
    // The second dataset is being reserved for the line.
    'chd': chart.getParam('chd').replace('t', 't1')
  });

  drawChart('entrancesDiv', chart.getURL());
}

/**
 * Given the max value that will appear in the chart, this method
 * determines a good max value and increment size for the chart, such that it's
 * easy to read.  This is a very basic algorithm that starts off with a max
 * chart value one order of magnitude larger than the value passed in (for
 * instance, for 123 it will set a chart max of 1000), and then reduces that
 * chart max to a point where it can be cleanly divided into 5 increments, and
 * the value passed in is greater than 1/2 of the chart max.
 * @param {int} currMax The maximum value that will be displayed in the chart.
 * @return {array}  A two-item array containing the max chart value, and
 *     suggested increment.
 */
function getScaleData(currMax) {
  var result = [0, 0];
  // Determine order of magnitude (number of digits left of decimal).
  var magnitude = Math.log(currMax) / Math.LN10;
  magnitude = Math.ceil(magnitude);

  var newMax = Math.pow(10, magnitude);
  if (newMax / 5 > currMax) {
    newMax = newMax / 5;
  }
  while (newMax > (currMax * 2)) {
    newMax = newMax / 2;
  }

  var step = newMax / 5;
  result[0] = newMax;
  result[1] = step;
  return result;
}

/**
 * Helper method to fill up arrays so they remain the same size.
 * Helpful for situations like data over time, where values might not be
 * returned for specific dates in one array, but they are for the other, so the
 * sizes must be kept in sync.
 * @param {array} firstArray the first array that needs to be synced in size.
 * @param {array} secondArray the second array that needs to be synced in size.
 */
function fillToSameSize(firstArray, secondArray) {
  if (firstArray.length < secondArray.length) {
    firstArray.push(0);
  } else if (secondArray.length < firstArray.length) {
    secondArray.push(0);
  }
}

/**
 * Populates the chart data object with data from the "visitor type" query.
 * for simplicity, dimension / metric names are hardcoded.
 * @param {object} result The object containing the response from the API call.
 * @return {object} An object holding all data to be used in creating the chart.
 */
function getVisitorChartData(result) {
  var entries = result.feed.getEntries();
  var returningVisitors = [];
  var newVisitors = [];
  var days = [];
  var maxReturningVisitors = 0;
  var maxNewVisitors = 0;

  for (var i = 0, entry; entry = entries[i]; ++i) {
    var visType = entry.getValueOf('ga:visitorType');
    var numVisits = entry.getValueOf('ga:visits');
    var day = new Date(parseInt(entry.getValueOf('ga:year'), 10), parseInt(entry.getValueOf('ga:month'), 10)).getTime(); // convert to base 10


    // Because the Export API will not include entries for a dimension with no
    // data on a particular day, it's important to make sure the visitor arrays
    // stay in sync with the days array.  We do this using the helper method
    // "fillToSameSize", which makes sure that if only one type of visitor was
    // recorded for a specific day, 0's are filled in for the other visitor type
    // on that same day.
    if (!days.length) {
      days.push(day);
    } else {
      var lastDay = days[days.length - 1];
      if (day != lastDay) {
        days.push(day);
        fillToSameSize(newVisitors, returningVisitors);
      }
    }

    if (visType == 'New Visitor') {
      newVisitors.push(numVisits);
      maxNewVisitors = Math.max(maxNewVisitors, numVisits);
    } else {
      returningVisitors.push(numVisits);
      maxReturningVisitors = Math.max(maxReturningVisitors, numVisits);
    }
  }
  fillToSameSize(newVisitors, returningVisitors);

  return {
    'returningVisitors': returningVisitors,
    'newVisitors': newVisitors,
    'maxNewVisitors': maxNewVisitors,
    'maxReturningVisitors': maxReturningVisitors,
    'days': days
  };
}

/**
 * Populates chart data object with data from "entrances and bounces" query.
 * for simplicity, dimension / metric names are hardcoded.
 * @param {object} result The object containing the response from the api call.
 * @return {object} An object holding all data to be used in creating the chart.
 */
function getBounceChartData(result) {
  var entries = result.feed.getEntries();
  var returningVisitors = [];
  var newVisitors = [];
  var days = [];
  var maxReturningVisitors = 0;
  var maxNewVisitors = 0;

  for (var i = 0, entry; entry = entries[i]; ++i) {
      console.log(entry);
    var visType = entry.getValueOf('ga:daysSinceLastVisit');
    var numVisits = entry.getValueOf('ga:visits');
    var day = parseInt(entry.getValueOf('ga:month'), 10); // convert to base 10


    // Because the Export API will not include entries for a dimension with no
    // data on a particular day, it's important to make sure the visitor arrays
    // stay in sync with the days array.  We do this using the helper method
    // "fillToSameSize", which makes sure that if only one type of visitor was
    // recorded for a specific day, 0's are filled in for the other visitor type
    // on that same day.
    if (!days.length) {
      days.push(day);
    } else {
      var lastDay = days[days.length - 1];
      if (day != lastDay) {
        days.push(day);
        fillToSameSize(newVisitors, returningVisitors);
      }
    }

    if (visType == 'New Visitor') {
      newVisitors.push(numVisits);
      maxNewVisitors = Math.max(maxNewVisitors, numVisits);
    } else {
      returningVisitors.push(numVisits);
      maxReturningVisitors = Math.max(maxReturningVisitors, numVisits);
    }
  }
  fillToSameSize(newVisitors, returningVisitors);

  return {
    'returningVisitors': returningVisitors,
    'newVisitors': newVisitors,
    'maxNewVisitors': maxNewVisitors,
    'maxReturningVisitors': maxReturningVisitors,
    'days': days
  };
}

/**
 * Sets up chart metadata (chart type, title, legend, etc) for a bar chart.
 * @param {object} chartData The object holding all data to be used in creating
 *     the chart.
 * @return {object} Chart object representing the chart to be drawn.
 */
function getBarChart(chartData) {

  var chart = getChartObj();
  var returningVisitorsStr = chartData.returningVisitors.join(',');
  var newVisitorsStr = chartData.newVisitors.join(',');
  var maxValue = chartData.maxReturningVisitors + chartData.maxNewVisitors;

  scaleData = getScaleData(maxValue);

  // Set chart meta-data
  chart.setParams({
    'chs': '500x150', // Image dimensions
    'chxt': 'x,y', // Axes
    'chts': '000000,15', // Title Style
    'cht': 'bvs', // Chart Type (Bar, Vertical, Stacked)
    'chco': 'a3d5f7,389ced', // Colors
    'chbh': 'a,5,20', // Width & Spacing
    'chm': 'N,FF0000,-1,,12', // Markers
    'chtt': 'Visitors+By+Type', // Title
    'chdl': 'Returning+Visitors|New+Visitors', // Legend
    'chd': 't:' + returningVisitorsStr + '|' + newVisitorsStr, // Chart Data
    'chxl': '0:|' + chartData.days.join('|'), // Axis Labels
    'chds': '0,' + scaleData[0], // Scaling
    'chxr': '1,0,' + scaleData.join(',') // Axis Scaling
  });
  return chart;
}

/**
 * Small helper method, gets the sum of values in an array.
 * @param {array} input An array of numbers.
 * @return {number} The sum of the array.
 */
function getArraySum(input) {
  var total = 0;

  for (var i = 0; i < input.length; i++) {
    total += input[i];
  }
  return total;
}

/**
 * Sets up chart metadata (chart type, title, legend, etc) for a pie chart.
 * @param {object} chartData The object holding all data to be used in creating
 *     the chart.
 * @return {object} Chart object representing the chart to be drawn.
 */
function getPieChart(chartData) {
  var chart = getChartObj();
  var newVisitors = getArraySum(chartData.newVisitors);
  var returningVisitors = getArraySum(chartData.returningVisitors);

  chartData.maxValue = returningVisitors + newVisitors;

  chart.setParams({
    'chs': '500x150', // Image dimensions
    'chts': '000000,15', // Title Style
    'cht': 'p3', // Chart Type
    'chco': 'a3d5f7,389ced', // Colors
    'chtt': 'Visitors+By+Type', // Title
    'chdl': 'Returning+Visitors|New+Visitors', // Legend
    'chd': 't:' + returningVisitors + ',' + newVisitors, // Chart Data
    'chl': returningVisitors + '|' + newVisitors, // Labels
    'chds': '0,' + chartData.maxValue // Max Value
  });
  return chart;
}

/**
 * Sets up chart metadata (chart type, title, legend, etc) for
 * entrances/bounces chart (starts off as a bar chart).
 * @param {object} chartData The object holding all data to be used in creating
 *     the chart.
 * @return {object} Chart object representing the chart to be drawn.
 */
function getEBChart(chartData) {
  var chart = getChartObj();
  var bouncesStr = chartData.bounces.join(',');
  var entrancesStr = chartData.entrances.join(',');

  scaleData = getScaleData(chartData.maxValue);

  chart.setParams({
    'chs': '500x150', // Image dimensions
    'chxt': 'x,y', // axes
    'chts': '000000,15', // Title Style
    'cht': 'bvg', // Chart Type (Bar, Vertical, Grouped)
    'chco': 'a3d5f7,389ced', // Colors
    'chbh': 'a,5,20', // Width/Spacing
    'chtt': 'Entrances+vs+Bounces', // Title
    'chdl': 'Entrances|Bounces', // Legend
    'chd': 't:' + entrancesStr + '|' + bouncesStr, // Chart Data
    'chxl': '0:|' + chartData.days.join('|'), // Axis Labels
    'chds': '0,' + scaleData[0], // Scaling
    'chxr': '1,0,' + scaleData.join(',') // Axis Scaling
  });
  return chart;
}

/**
 * Inserts an image tag into the DOM, inside an HTML tag specified by ID,
 * and with the image sourced to a specified URL.
 * @param {string} parentElementId The ID of the DOM element to insert the
 *   image tag into.
 * @param {string} url The URL to use as the source of the inserted image.
 */
function drawChart(parentElementId, url) {
  document.getElementById(parentElementId).innerHTML +=
      '<img src="' + url + '" /><br />';
}

/**
 * Using a bar chart object as a starting point, creates and returns a line
 * chart object with the same internal dataset.  Helper method meant to show
 * how easy converting between the two chart types is.
 * @param {object} barChart Bar chart wrapper used as basis for the line chart.
 * @return {object} A line chart object.
 */
function getLineChartFromBarChart(barChart) {

  var lineChart = getChartObj();
  lineChart.setParams(barChart.getParams());
  lineChart.setParam('cht', 'lc');
  lineChart.setParam('chm', '');
  return lineChart;
}

/**
 * Alert any errors that come from the API request.
 * @param {object} e The error object returned by the Analytics API.
 */
function handleError(e) {
  var error = 'There was an error!\n';
  if (e.cause) {
    error += e.cause.status;
  } else {
    error.message;
  }
  alert(error);
}


/**
 * Abstracts much of what is similar between different chart types out into
 * a convenient wrapper object.  The object has an internal set of all chart
 * parameters types used in this example application, as well as methods for
 * getting/setting those parameters, and converting them into a URL used to pull
 * the image of the resulting chart.
 * @return {object} Chart wrapper object representing the chart to be displayed,
 *   including all the relevant data and configuration options.
 */
function getChartObj() {
  var params_ = {
    'chs': '', // Image Dimensions
    'chtt': '', // Title
    'chxt': '', // Axes
    'chts': '', // Title Style
    'cht': '', // Chart type
    'chd': '', // Data
    'chdl': '', // Legend
    'chco': '', // Colors
    'chbh': '', // Width and spacing
    'chxl': '', // Axis Labels
    'chds': '', // Scaling
    'chxr': '', // Axis Scaling
    'chm': '', // Chart Markers
    'chl': '' // Data Labels
  };

  var baseURL_ = 'http://chart.apis.google.com/chart';

/**
 * Method to get all the parameters of a chart object.
 * @return {object} An object containing all the key/value pairs that make up
 *     this chart object.
 */
  function getParams_() {
    return params_;
  }

/**
 * Method to get the value of a specific chart parameter by name.
 * @param {string} key The name of the parameter to return.
 * @return {string} The current value of the chart parameter specified by key.
 */
  function getParam_(key) {
    return params_[key];
  }

/**
 * Sets a parameter.  Only does so if the key is already defined in the
 * "params_" member variable.  Otherwise the parameter is ignored and discarded.
 * @param {string} key The name of the parameter being set.
 * @param {string} val The value to insert at the specified key.
 */
  function setParam_(key, val) {
    if (params_[key] !== undefined) {
      params_[key] = val;
    }
  }

/**
 * Sets multiple parameters at once.  Each key/value pair is only inserted if
 * the key is defined in the "params_" member variable.  Otherwise the parameter
 * is ignored and discarded.
 * @param {string} obj Object made up of key/value pairs to be set in this
 *     object.
 */
  function setParams_(obj) {
    for (key in obj) {
      setParam_(key, obj[key]);
    }
  }

  /**
   * Given a base URL and an array of parameters, construct the complete URL.
   * @return {string} The complete URL for the chart.
  */
  function getURL_() {
    paramArray = [];
    for (key in params_) {
      if (params_[key]) {
        pairStr = [key, params_[key]].join('=');
        paramArray.push(pairStr);
      }
    }
    paramStr = paramArray.join('&');
    url = [baseURL_, paramStr].join('?');
    return url;
  }

  return {
    'getParam': getParam_,
    'getParams': getParams_,
    'setParam': setParam_,
    'setParams': setParams_,
    'getURL': getURL_
  };
}