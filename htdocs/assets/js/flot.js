
function getDefaultFunnelOptions() {
    return $.extend(true, getDefaultOptions(), {
        xaxis: {
            mode: null,
            max: null,
            timeFormat: null,
            tickLength: 0
        },
        series: {
            points: { show: false },
            bars: {
                show: true,
                align: 'center',
                barWidth: 0.92,
                lineWidth: 1
            }
        }
    });
}

function getDefaultOptions () {
    return {
        colors: ["#53777A", "#C02942","#ECD078", "#D95B43", "#542437"],
        yaxis: {
            min: 0,
            tickDecimals: 0,
            tickFormatter: function(v) {return commafy(v); }
        },
        xaxis: {
            mode: 'time',
            max: +(new Date),
            timeFormat: '%b %d, %y'
        },
        grid: {
            clickable: false,
            hoverable: true,
            borderWidth: 0
        },
        series: {
            lines: { show: true, lineWidth: 1 },
            points: { show: true, lineWidth: 1, radius: 2, fillColor: "#CCC" },
            shadowSize: 0
        },
        legend: {
            show: true
        },
        trendline: {
            show: false
        }
    };
} 

showTip = function(e, pos, item) {
    var tt = $("#tooltip");
    if (item) {
        var idx = item.dataIndex + "|" + item.seriesIndex;
        if (tt.attr("data-idx") != idx) {
            var count = item.datapoint[1];
            if (item.series.stack === true) {
                count = count - item.datapoint[2];
            }
            tt.attr("data-idx", idx);
            tt.hide();
            tt.css({
                top: 5 + pos.pageY,
                left: 5 + pos.pageX});
            tt.find("#tt-title").text(item.series.label);
            tt.find("#tt-count").text(commafy(count));
            tt.find("#tt-date").text($.plot.formatDate(new Date(item.datapoint[0]), "%b %d, %y"));
            tt.fadeIn(200);
        }
    } else {
        tt.hide().attr("data-idx", "");
    }
    return tt;
};

showFunnelTip = function(e, pos, item) {
    var tt = showTip.apply(this, arguments);
    if (item) {
        var index = item.dataIndex;
        var label = item.series.xaxis.ticks[index].label;
        var previous = index > 0  ? item.series.data[index - 1][1] : item.datapoint[1];
        var count = item.datapoint[1];
        var stepConversion = 100 * (count/previous);
        stepConversion = ("" + stepConversion).substr(0, 6);
        tt.find("#tt-title").text(label);
        tt.find("#tt-date").text("Step Conversion " + stepConversion + "%");
    }
};

commafy = function (s) {
    s += "";
    return s.replace(/(^|[^\w.])(\d{4,})/g, function($0, $1, $2) {
            return $1 + $2.replace(/\d(?=(?:\d\d\d)+(?!\d))/g, "$&,");
        });
};
