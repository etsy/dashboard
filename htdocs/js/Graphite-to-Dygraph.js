
function getGraphiteURL(metric, periodSelected)
{
	//graphite host
	var url = "http://graphite.example.com";
	
	//add target and everything needed by graphite URL API...
	url = url + "/render/?";
	
	url = url + metric;
	
	urlbase = url;
	
	//set "json" as the response format
	url = url + "&format=json&jsonp=?";

	
	url = url + "&from=" + periodSelected;
	console.log(url);
	return {url: url, urlbase: urlbase};

}

function createDygraph(params){
			//Get Graphite URL
			
			metric = "&target=ganglia.GU-" + params.environment + "-" + params.service_name + "." + params.grid + "\*." + params.grid +"\*." + params.metric;

			result = getGraphiteURL(metric, params.periodSelected)
			url = result.url
			urlbase = result.urlbase

			
			//Get JSON data from Graphite
			$.getJSON(url, function(result){

				metricAlias = params.environment + "-" + params.service_name + "." + params.grid + " " + params.metric;

				var graphiteData = new Object();
				var graphLabels = ["DateTime"];
						       		       
				$.each(result, function(i, item){
			      
			      //"Headers for native format(Array) must be specified via the labels option. 
			      //There's no other way to set them. -http://dygraphs.com/data.html#array"
				  target=item.target.split('.');
				  graphLabels.push(target[3]);
				  
				  //fill out the array with the metrics
				  $.each(item["datapoints"], function(key, val) {
				    tempDate = val[1];
				    
				    if (!(tempDate in graphiteData)) {
				    	graphiteData[tempDate] = [];
				    }
				    
				    //I've chosen to 0 out any null values, otherwise additional data series 
				    //could be inserted into previous data series array
				    if (val[0] === null) { val[0] = 0; } 
				    
				    graphiteData[tempDate].push([val[0]]);

				  });		
				});
				
				//console.log("graphiteData: ", graphiteData);
				
				//need to flatten the hash to an array for Dygraph
				var dygraphData = [];
				
				for (var key in graphiteData) {
				   if (graphiteData.hasOwnProperty(key)) {

				     tempArray = [];
				     tempArray.push(new Date(key * 1000));
				     
				     dataSeries = graphiteData[key];
						
				     for (var key in dataSeries) {
				       if (dataSeries.hasOwnProperty(key)) {
				         tempArray.push(dataSeries[key]);
				       }
				     }
				     dygraphData.push(tempArray);
				   }
				}
				
				//console.log("dygraphData: ",dygraphData);
				//You have the data Array now, so construct the graph:
				g = new Dygraph(
			    document.getElementById(params.targetdiv),
				dygraphData,
			    { fillGraph: true,
			      labelsKMB: true,
			      animatedZooms: true,
			      logscale: false,
			      //stackedGraph: true,
			      //legend: "always",
			      title: metricAlias,
			      titleHeight: 22,
			      //xlabel: metricAlias,
			      labels: graphLabels,
				  labelsDivStyles: {
                    'text-align': 'right',
                    'background': 'none'
                  },
			    }
			   );
			   $("#" + params.targetdiv + "-source").append("<a href=\"" + urlbase + "\">(g_img,</a><a href=\"" + url + "\">g_json)</a>");
			   //console.log(g);
			});
			
			
}