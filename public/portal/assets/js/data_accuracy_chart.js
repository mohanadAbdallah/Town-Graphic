/* ------------------------------------------------------------------------------
 *
 *  # Echarts - Line chart with zoom example
 *
 *  Demo JS code for line chart with zoom option [light theme]
 *
 * ---------------------------------------------------------------------------- */


// Setup module
// ------------------------------
 var EchartsLinesZoomLight = function() {


    //
    // Setup module components
    //

    // Line chart with zoom
    var _linesZoomLightExample = function() {
        if (typeof echarts == 'undefined') {
            console.warn('Warning - echarts.min.js is not loaded.');
            return;
        }

        // Define element
        var line_zoom_element = document.getElementById('line_zoom');


        //
        // Charts configuration
        //
        var times = [];
        var readingArray = [];
        var pred1Array = [];
        var pred2Array = [];
        var pred3Array = [];
        console.log(data);
        for(var i = 0;i < data.length;i++){
            var firstTime = data[i]['date'].split(' ')[1];
            var finalTime = firstTime.split(':');
            finalTime = finalTime[0]+':'+finalTime[1];
            times.push(finalTime);

            var itemReading = (data[i]['reading'] !== null && data[i]['reading'] !== '') ?
                Number(data[i]['reading']).toFixed(2) :
                null;

            var prediction1Reading = (data[i]['prediction'] !== null && data[i]['prediction'] !== '') ?
                Number(data[i]['prediction']).toFixed(2) :
                null;
            var prediction2Reading = (data[i]['prediction2'] !== null && data[i]['prediction2'] !== '') ?
                Number(data[i]['prediction2']).toFixed(2) :
                null;
            var prediction3Reading = (data[i]['prediction3'] !== null && data[i]['prediction3'] !== '') ?
                Number(data[i]['prediction3']).toFixed(2) :
                null;
            readingArray.push(itemReading);
            pred1Array.push(prediction1Reading);
            pred2Array.push(prediction2Reading);
            pred3Array.push(prediction3Reading);
        }
        if (line_zoom_element) {

            // Initialize chart
            var line_zoom = echarts.init(line_zoom_element);


            //
            // Chart config
            //

            // Options
            line_zoom.setOption({

                // Define colors
                color: ['#629de0', "#de783c", "#2ec313", '#838383'],

                // Global text styles
                textStyle: {
                    fontFamily: 'Roboto, Arial, Verdana, sans-serif',
                    fontSize: 13
                },

                // Chart animation duration
                animationDuration: 750,

                // Setup grid
                grid: {
                    left: 0,
                    right: 40,
                    top: 35,
                    bottom: 60,
                    containLabel: true
                },

                // Add legend
                legend: {
                    data: [read, pred1, pred2,pred3],
                    itemHeight: 8,
                    itemGap: 20
                },

                // Add tooltip
                tooltip: {
                    trigger: 'axis',
                    backgroundColor: 'rgba(0,0,0,0.75)',
                    padding: [10, 15],
                    textStyle: {
                        fontSize: 13,
                        fontFamily: 'Roboto, sans-serif'
                    }
                },

                // Horizontal axis
                xAxis: [{
                    type: 'category',
                    boundaryGap: false,
                    axisLabel: {
                        color: '#333'
                    },
                    axisLine: {
                        lineStyle: {
                            color: '#999'
                        }
                    },
                    data: times
                }],

                // Vertical axis
                yAxis: [{
                    type: 'value',
                    axisLabel: {
                        formatter: '{value} ',
                        color: '#333'
                    },
                    axisLine: {
                        lineStyle: {
                            color: '#999'
                        }
                    },
                    splitLine: {
                        lineStyle: {
                            color: ['#eee']
                        }
                    },
                    splitArea: {
                        show: true,
                        areaStyle: {
                            color: ['rgba(250,250,250,0.1)', 'rgba(0,0,0,0.01)']
                        }
                    }
                }],

                // Zoom control
                dataZoom: [
                    {
                        type: 'inside',
                        start: 30,
                        end: 70
                    },
                    {
                        show: true,
                        type: 'slider',
                        start: 30,
                        end: 70,
                        height: 40,
                        bottom: 0,
                        borderColor: '#ccc',
                        fillerColor: 'rgba(0,0,0,0.05)',
                        handleStyle: {
                            color: '#585f63'
                        }
                    }
                ],

                // Add series
                series: [
                    {
                        name: read,
                        type: 'line',
                        smooth: true,
                        symbolSize: 6,
                        itemStyle: {
                            normal: {
                                borderWidth: 2
                            }
                        },
                        lineStyle: {
                            width: 5
                        },
                        data: readingArray
                    },
                    {
                        name: pred1,
                        type: 'line',
                        smooth: true,
                        symbolSize: 6,
                        itemStyle: {
                            normal: {
                                borderWidth: 2
                            }
                        },
                        data: pred1Array
                    },
                    {
                        name: pred2,
                        type: 'line',
                        smooth: true,
                        symbolSize: 6,
                        itemStyle: {
                            normal: {
                                borderWidth: 2
                            }
                        },
                        data: pred2Array
                    },
                    {
                        name: pred3,
                        type: 'line',
                        smooth: true,
                        symbolSize: 6,
                        itemStyle: {
                            normal: {
                                borderWidth: 2
                            }
                        },
                        data: pred3Array
                    }
                ]
            });
        }


        //
        // Resize charts
        //

        // Resize function
        var triggerChartResize = function() {
            line_zoom_element && line_zoom.resize();
        };

        // On sidebar width change
        var sidebarToggle = document.querySelector('.sidebar-control');
        sidebarToggle && sidebarToggle.addEventListener('click', triggerChartResize);

        // On window resize
        var resizeCharts;
        window.addEventListener('resize', function() {
            clearTimeout(resizeCharts);
            resizeCharts = setTimeout(function () {
                triggerChartResize();
            }, 200);
        });
    };


    //
    // Return objects assigned to module
    //

    return {
        init: function() {
            _linesZoomLightExample();
        }
    }
}();

var StatisticWidgets = function() {

// Initialize module
// ------------------------------
var _progressArcMulti = function(element, size, goal) {
    if (typeof d3 == 'undefined') {
        console.warn('Warning - d3.min.js is not loaded.');
        return;
    }

    // Initialize chart only if element exsists in the DOM
    if(element) {

        // Main variables
        var d3Container = d3.select(element),
            radius = size,
            thickness = 20,
            startColor = '#66BB6A',
            midColor = '#FFA726',
            endColor = '#EF5350';

        // Colors
        var color = d3.scale.linear()
            .domain([0, 70, 100])
            .range([startColor, midColor, endColor]);


        // Create chart
        // ------------------------------

        // Add svg element
        var container = d3Container.append("svg");

        // Add SVG group
        var svg = container
            .attr('width', radius * 2)
            .attr('height', radius + 20);


        // Construct chart layout
        // ------------------------------

        // Pie
        var arc = d3.svg.arc()
            .innerRadius(radius - thickness)
            .outerRadius(radius)
            .startAngle(-Math.PI / 2);


        // Append chart elements
        // ------------------------------

        //
        // Group arc elements
        //

        // Group
        var chart = svg.append('g')
            .attr('transform', 'translate(' + radius + ',' + radius + ')');

        // Background
        var background = chart.append('path')
            .datum({
                endAngle: Math.PI / 2
            })
            .attr({
                'd': arc,
                'class': 'd3-state-empty'
            });

        // Foreground
        var foreground = chart.append('path')
            .datum({
                endAngle: -Math.PI / 2
            })
            .style('fill', startColor)
            .attr('d', arc);

        // Counter value
        var value = svg.append('g')
            .attr('transform', 'translate(' + radius + ',' + (radius * 0.9) + ')')
            .append('text')
            .text(0 + '%')
            .attr({
                'class': 'd3-text',
                'text-anchor': 'middle'
            })
            .style({
                'font-size': 19,
                'font-weight': 400
            });


        //
        // Min and max text
        //

        // Group
        var scale = svg.append('g')
            .attr('transform', 'translate(' + radius + ',' + (radius + 15) + ')')
            .attr('class', 'd3-text opacity-75')
            .style({
                'font-size': 12
            });

        // Max
        scale.append('text')
            .text(100)
            .attr({
                'text-anchor': 'middle',
                'x': (radius - thickness / 2)
            });

        // Min
        scale.append('text')
            .text(0)
            .attr({
                'text-anchor': 'middle',
                'x': -(radius - thickness / 2)
            });


        //
        // Animation
        //
        update(totalError);


        // Update
        function update(v) {
            v = d3.format('.0f')(v);
            foreground.transition()
                .duration(750)
                .style('fill', function() {
                    return color(v);
                })
                .call(arcTween, v);

            value.transition()
                .duration(750)
                .call(textTween, v);
        }

        // Arc
        function arcTween(transition, v) {
            var newAngle = v / 100 * Math.PI - Math.PI / 2;
            transition.attrTween('d', function(d) {
                var interpolate = d3.interpolate(d.endAngle, newAngle);
                return function(t) {
                    d.endAngle = interpolate(t);
                    return arc(d);
                };
            });
        }

        // Text
        function textTween(transition, v) {
            transition.tween('text', function() {
                var interpolate = d3.interpolate(this.innerHTML, v),
                    split = (v + '').split('.'),
                    round = (split.length > 1) ? Math.pow(10, split[1].length) : 1;
                return function(t) {
                    this.innerHTML = d3.format('.0f')(Math.round(interpolate(t) * round) / round) + '<tspan>%</tspan>';
                };
            });
        }
    }
};
    return {
        init: function() {
            _progressArcMulti("#arc_multi", 78, 700);
        }
    }
}();
document.addEventListener('DOMContentLoaded', function() {
    EchartsLinesZoomLight.init();
    StatisticWidgets.init();
});
