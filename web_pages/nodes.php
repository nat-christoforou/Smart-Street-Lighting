<?php 
    // Start MySQL Connection
    include('dbconnect.php'); 
?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!--meta http-equiv="refresh" content="5" -->
    <link rel="stylesheet" href="style.css" type="text/css">
    <script src="http://www.amcharts.com/lib/3/plugins/dataloader/dataloader.min.js" type="text/javascript"></script>
    <script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
    <script src="https://www.amcharts.com/lib/3/serial.js"></script>
    <script src="https://www.amcharts.com/lib/3/plugins/animate/animate.min.js"></script>
    <script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
    <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
    <script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
    <title>All nodes measurements</title>

    <style type="text/css">
        .table_titles, .table_cells_odd, .table_cells_even {
                padding-right: 20px;
                padding-left: 20px;
                
                color: #000;
                text-align: center ;    
        }
        .table_titles {
            color: #FFF;
            background-color: #666;
            text-align: center ;    
        }
        .table_cells_odd {
            background-color: #CCC;
            text-align: center ;    
        }
        .table_cells_even {
            background-color: #FAFAFA;
            text-align: center ;    
        }
        table {
            border: 4px solid #333;
            text-align: center ;    
            margin-left:400px;
            margin: 0px auto;
            font-size: 10px;
        }
        body { font-family: "Trebuchet MS", Arial;
               text-align: center ; 
               background-color:#54a7ea;               
        }              
        h1  { 
            margin-top:10px;
            margin-right:280px;
            margin-bottom:40px;
            margin-left:280px;
            font-weight: bold; 
            font-style:normal; 
            text-decoration: none; 
            background-color:#0e6ab5;
            color:white;
            text-shadow: 2px 2px 4px #000000;
            border-radius: 30px;
            padding-top:20px ;
            padding-bottom:20px ;
            
        }
        #chartdiv1 {
            width       : 50%;
            height      : 400px;
            font-size   : 11px;
        }   
        #chartdiv2 {
            margin-top: : 20px;
            width       : 60%;
            height      : 400px;
            font-size   : 11px;
        }  
        #chartdiv3 {
            margin-top: : 20px;
            width       : 50%;
            height      : 400px;
            font-size   : 11px;
        } 
        #chartdiv4 {
            margin-top: : 20px;
            width       : 50%;
            height      : 400px;
            font-size   : 11px;
        }
        #chartdiv5 {
            margin-top: : 20px;
            width       : 50%;
            height      : 400px;
            font-size   : 11px;
        } 
    </style>

</head>

    <body>
        <div style="color:#FFFFFF;" align="right">
             <?php
                  echo "Today is " . date("d/m/Y") . "<br>";
            ?>
        </div>
        <div style="width:100%;font-size:20pt;text-shadow: 2px 2px 4px #000000;color:#FFFFFF;text-align:center;">  Τμήμα Ηλεκτρολόγων Μηχανικών Πανεπιστημίου Πατρών
        </div>
        <div style="width:100%;font-size:18pt;color:#FFFFFF;text-shadow: 2px 2px 4px #000000;text-align:center;margin-bottom: 20px;">  Π.Μ.Σ στην "Κατανεμημένη πράσινη ηλεκτρική ενέργεια και οι προηγμένες δικτυακές υποδομές για τη διαχείριση και την οικονομία της"
        </div>
        
        <h1>
            All Nodes
        </h1>

        <a href="index.html">
            <img src="images/home.png" height="30" width="30" style="margin:-6px 0px 0px 20px;" />
            </a>
             
            <a href="review_data.php">
            <img src="images/mysql.png" height="30" width="30" align="" style="margin:-6px 0px 0px 20px;" />
            </a>
            <a href="maps.html">
            <img src="images/map.png" height="30" width="30" align="" style="margin:-6px 0px 0px 20px;border-radius: 50px;" />
            </a>   
            <a href="node1.php">
            <img src="images/one.png" height="30" width="30" style="margin:-10px 0px 0px 20px;border-radius: 50px;" />
            </a>     
            <a href="node2.php">
            <img src="images/two.png" height="30" width="30" style="margin:-10px 0px 0px 20px;border-radius: 50px;" />
            </a>    
            <a href="node3.php">
            <img src="images/three.png" height="30" width="30" style="margin:-10px 0px 0px 20px;border-radius: 50px;" />
            </a>                    
            
            <br>
            <br>


    <table border="0" cellspacing="1" cellpadding="4" style="margin-top: 30px; margin-bottom: 30px;">
      <tr>
            <td class="table_titles">State</td>
            <td class="table_titles">Time (h)</td> 
            <td class="table_titles">Energy (kWh)</td>
            <td class="table_titles">Conventional Energy with LDR (kWh)</td>
            <td class="table_titles">Conventional Energy (kWh)</td>
            <td class="table_titles">Cost with Smart System (Euro)</td>
            <td class="table_titles">Cost with Conv. System with LDR (Euro)</td>
            <td class="table_titles">Cost with Conv. System (Euro)</td>
      </tr>
    
<?php
    // Retrieve records for node 1 from table test and display the different states and the time in each state
    $result = mysqli_query($dbh, "SELECT State, COUNT(*)/3600 AS Time,
        CASE 
            WHEN State = 'Full On' THEN (1*120*(COUNT(*)/3600))/1000
            WHEN State = '50% Night' THEN (0.5*120*(COUNT(*)/3600))/1000
            WHEN State = '50% Day' THEN (0.5*120*(COUNT(*)/3600))/1000
            WHEN State = 'Off' THEN (0*120*(COUNT(*)/3600))/1000
        END,
        CASE 
            WHEN State = 'Full On' THEN (1*120*(COUNT(*)/3600))/1000
            WHEN State = '50% Night' THEN (1*120*(COUNT(*)/3600))/1000
            WHEN State = '50% Day' THEN (1*120*(COUNT(*)/3600))/1000
            WHEN State = 'Off' THEN (0*120*(COUNT(*)/3600))/1000
        END, 
        CASE 
            WHEN State = 'Full On' THEN (1*120*(COUNT(*)/3600))/1000
            WHEN State = '50% Night' THEN (1*120*(COUNT(*)/3600))/1000
            WHEN State = '50% Day' THEN (0*120*(COUNT(*)/3600))/1000
            WHEN State = 'Off' THEN (0*120*(COUNT(*)/3600))/1000
        END,
        CASE 
            WHEN State = 'Full On' THEN ((1*120*(COUNT(*)/3600))/1000) * 0.107
            WHEN State = '50% Night' THEN ((0.5*120*(COUNT(*)/3600))/1000) * 0.107
            WHEN State = '50% Day' THEN ((0.5*120*(COUNT(*)/3600))/1000) *0.107
            WHEN State = 'Off' THEN 0
        END,
        CASE 
            WHEN State = 'Full On' THEN ((1*120*(COUNT(*)/3600))/1000) * 0.107
            WHEN State = '50% Night' THEN ((1*120*(COUNT(*)/3600))/1000) * 0.107
            WHEN State = '50% Day' THEN ((1*120*(COUNT(*)/3600))/1000) * 0.107
            WHEN State = 'Off' THEN 0
        END,
        CASE 
            WHEN State = 'Full On' THEN ((1*120*(COUNT(*)/3600))/1000) * 0.107
            WHEN State = '50% Night' THEN ((1*120*(COUNT(*)/3600))/1000) * 0.107
            WHEN State = '50% Day' THEN ((0*120*(COUNT(*)/3600))/1000) * 0.107
            WHEN State = 'Off' THEN 0
        END
     FROM StreetLights GROUP BY State"); 
    

   // Used for row color toggle
    $oddrow = true;

    // process every record
    while( $row = mysqli_fetch_array($result, MYSQLI_NUM) )
    {
        if ($oddrow) 
        { 
            $css_class=' class="table_cells_odd"'; 
        }
        else
        { 
            $css_class=' class="table_cells_even"'; 
        }

        $oddrow = !$oddrow;

        echo '<tr>';
        echo '   <td'.$css_class.'>'.$row[0].'</td>';
        echo '   <td'.$css_class.'>'.$row[1].'</td>';
        echo '   <td'.$css_class.'>'.$row[2].'</td>';
        echo '   <td'.$css_class.'>'.$row[3].'</td>';
        echo '   <td'.$css_class.'>'.$row[4].'</td>';
        echo '   <td'.$css_class.'>'.$row[5].'</td>';
        echo '   <td'.$css_class.'>'.$row[6].'</td>';
        echo '   <td'.$css_class.'>'.$row[7].'</td>';
        echo '</tr>';
    }
?>

    <script>
        AmCharts.loadJSON = function(url) {
          // create the request
          if (window.XMLHttpRequest) {
            // IE7+, Firefox, Chrome, Opera, Safari
            var request = new XMLHttpRequest();
          } else {
            // code for IE6, IE5
            var request = new ActiveXObject('Microsoft.XMLHTTP');
          }

          // load it
          // the last "false" parameter ensures that our code will wait before the
          // data is loaded
          request.open('GET', url, false);
          request.send();

          // parse adn return the output
          return eval(request.responseText);
        };
    </script>


    <script>
        var chart1;

        AmCharts.ready(function () {

            var chartData = AmCharts.loadJSON('all_data.php');

                // SERIAL CHART
                chart1 = new AmCharts.AmSerialChart();
                chart1.dataProvider = chartData;
                chart1.categoryField = "State";
                chart1.color = "#FFFFFF";
                chart1.fontSize = 14;
                chart1.startDuration = 1;
                chart1.plotAreaFillAlphas = 0.2;
                chart1.legend = [{
                      "title": "One",
                      "color": "#3366CC"
                    }, {
                      "title": "Two",
                      "color": "#FFCC33"
                    }]
                // the following two lines makes chart 3D
                chart1.angle = 30;
                chart1.depth3D = 60;

                // AXES
                // category
                var categoryAxis = chart1.categoryAxis;
                categoryAxis.gridAlpha = 0.2;
                categoryAxis.gridPosition = "start";
                categoryAxis.gridColor = "#FFFFFF";
                categoryAxis.axisColor = "#FFFFFF";
                categoryAxis.axisAlpha = 0.5;
                categoryAxis.dashLength = 5;

                // value
                var valueAxis = new AmCharts.ValueAxis();
                valueAxis.stackType = "3d"; // This line makes chart 3D stacked (columns are placed one behind another)
                valueAxis.gridAlpha = 0.2;
                valueAxis.gridColor = "#FFFFFF";
                valueAxis.axisColor = "#FFFFFF";
                valueAxis.axisAlpha = 0.5;
                valueAxis.dashLength = 5;
                valueAxis.title = "Energy (kWh)";
                valueAxis.titleColor = "#FFFFFF";
                valueAxis.unit = "";
                chart1.addValueAxis(valueAxis);

                // GRAPHS
                // first graph
                var graph1 = new AmCharts.AmGraph();
                graph1.title = "Smart Energy";
                graph1.valueField = "Smart Energy (kWh)";
                graph1.type = "column";
                graph1.lineAlpha = 0;
                graph1.lineColor = "#1b9b1d";
                graph1.fillAlphas = 1;
                graph1.balloonText = "<b>[[value]] kWh</b>";
                chart1.addGraph(graph1);

                // second graph
                var graph2 = new AmCharts.AmGraph();
                graph2.title = "Conventional Energy with LDR";
                graph2.valueField = "Conventional Energy with LDR (kWh)";
                graph2.type = "column";
                graph2.lineAlpha = 0;
                graph2.lineColor = "#af1616";
                graph2.fillAlphas = 1;
                graph2.balloonText = "<b>[[value]] kWh</b>";
                chart1.addGraph(graph2);

                chart1.write("chartdiv1");
            });

        </script>

        <script>
            var chart2;


            AmCharts.ready(function () {
                // SERIAL CHART
                chart2 = new AmCharts.AmSerialChart();
                chart2.dataProvider = AmCharts.loadJSON('all_data.php');
                chart2.categoryField = "State";
                chart2.startDuration = 1;

                // AXES
                // category
                var categoryAxis = chart2.categoryAxis;
                categoryAxis.labelRotation = 0;
                categoryAxis.gridPosition = "start";

                var valueAxis2 = new AmCharts.ValueAxis();
                valueAxis2.gridAlpha = 0.2;
                valueAxis2.gridColor = "#FFFFFF";
                valueAxis2.axisColor = "#FFFFFF";
                valueAxis2.axisAlpha = 0.5;
                valueAxis2.dashLength = 5;
                valueAxis2.title = "Time (h)";
                valueAxis2.titleColor = "black";
                valueAxis2.unit = "";
                chart2.addValueAxis(valueAxis2);

                // GRAPH
                var graph = new AmCharts.AmGraph();
                graph.valueField = "Time (h)";
                graph.balloonText = "[[category]]: <b>[[value]] hours</b>";
                graph.type = "column";
                graph.lineAlpha = 0;
                graph.fillAlphas = 0.8;
                graph.lineColor = "#041599";
                chart2.addGraph(graph);

                // CURSOR
                var chartCursor = new AmCharts.ChartCursor();
                chartCursor.cursorAlpha = 0;
                chartCursor.zoomable = false;
                chartCursor.categoryBalloonEnabled = false;
                chart2.addChartCursor(chartCursor);

                chart2.creditsPosition = "top-right";

                chart2.write("chartdiv2");
            });
        </script>

        <script>
        var chart3;

        AmCharts.ready(function () {


                // SERIAL CHART
                chart3 = new AmCharts.AmSerialChart();
                chart3.dataProvider = AmCharts.loadJSON('all_data.php');
                chart3.categoryField = "State";
                chart3.color = "#FFFFFF";
                chart3.fontSize = 14;
                chart3.startDuration = 1;
                chart3.plotAreaFillAlphas = 0.2;
                chart3.legend = [{
                      "title": "Smart System",
                      "color": "#3366CC"
                    }, {
                      "title": "Conventional System",
                      "color": "#FFCC33"
                    }]
                // the following two lines makes chart 3D
                chart3.angle = 30;
                chart3.depth3D = 60;

                // AXES
                // category
                var categoryAxis3 = chart3.categoryAxis;
                categoryAxis3.gridAlpha = 0.2;
                categoryAxis3.gridPosition = "start";
                categoryAxis3.gridColor = "#FFFFFF";
                categoryAxis3.axisColor = "#FFFFFF";
                categoryAxis3.axisAlpha = 0.5;
                categoryAxis3.dashLength = 5;

                // value
                var valueAxis3 = new AmCharts.ValueAxis();
                valueAxis3.stackType = "3d"; // This line makes chart 3D stacked (columns are placed one behind another)
                valueAxis3.gridAlpha = 0.2;
                valueAxis3.gridColor = "#FFFFFF";
                valueAxis3.axisColor = "#FFFFFF";
                valueAxis3.axisAlpha = 0.5;
                valueAxis3.dashLength = 5;
                valueAxis3.title = "Cost (Euro)";
                valueAxis3.titleColor = "#FFFFFF";
                valueAxis3.unit = "";
                chart3.addValueAxis(valueAxis3);

                // GRAPHS
                // first graph
                var graph3 = new AmCharts.AmGraph();
                graph3.title = "Smart Energy";
                graph3.valueField = "Smart Cost (Euro)";
                graph3.type = "column";
                graph3.lineAlpha = 0;
                graph3.lineColor = "#1b9b1d";
                graph3.fillAlphas = 1;
                graph3.balloonText = "<b>[[value]] Euro</b>";
                chart3.addGraph(graph3);

                // second graph
                var graph4 = new AmCharts.AmGraph();
                graph4.title = "Conventional Energy";
                graph4.valueField = "Conventional Cost with LDR (Euro)";
                graph4.type = "column";
                graph4.lineAlpha = 0;
                graph4.lineColor = "#af1616";
                graph4.fillAlphas = 1;
                graph4.balloonText = "<b>[[value]] Euro</b>";
                chart3.addGraph(graph4);

                chart3.write("chartdiv3");
            });

        </script>

        <script>
        var chart4;

        AmCharts.ready(function () {


                // SERIAL CHART
                chart4 = new AmCharts.AmSerialChart();
                chart4.dataProvider = AmCharts.loadJSON('all_data.php');
                chart4.categoryField = "State";
                chart4.color = "#FFFFFF";
                chart4.fontSize = 14;
                chart4.startDuration = 1;
                chart4.plotAreaFillAlphas = 0.2;
                chart4.legend = [{
                      "title": "Smart System",
                      "color": "#3366CC"
                    }, {
                      "title": "Conventional System",
                      "color": "#FFCC33"
                    }]
                // the following two lines makes chart 3D
                chart4.angle = 30;
                chart4.depth3D = 60;

                // AXES
                // category
                var categoryAxis4 = chart4.categoryAxis;
                categoryAxis4.gridAlpha = 0.2;
                categoryAxis4.gridPosition = "start";
                categoryAxis4.gridColor = "#FFFFFF";
                categoryAxis4.axisColor = "#FFFFFF";
                categoryAxis4.axisAlpha = 0.5;
                categoryAxis4.dashLength = 5;

                // value
                var valueAxis4 = new AmCharts.ValueAxis();
                valueAxis4.stackType = "3d"; // This line makes chart 3D stacked (columns are placed one behind another)
                valueAxis4.gridAlpha = 0.2;
                valueAxis4.gridColor = "#FFFFFF";
                valueAxis4.axisColor = "#FFFFFF";
                valueAxis4.axisAlpha = 0.5;
                valueAxis4.dashLength = 5;
                valueAxis4.title = "Cost (Euro)";
                valueAxis4.titleColor = "#FFFFFF";
                valueAxis4.unit = "";
                chart4.addValueAxis(valueAxis4);

                // GRAPHS
                // first graph
                var graph5 = new AmCharts.AmGraph();
                graph5.title = "Smart Energy";
                graph5.valueField = "Smart Cost (Euro)";
                graph5.type = "column";
                graph5.lineAlpha = 0;
                graph5.lineColor = "#1b9b1d";
                graph5.fillAlphas = 1;
                graph5.balloonText = "<b>[[value]] Euro</b>";
                chart4.addGraph(graph5);

                // second graph
                var graph6 = new AmCharts.AmGraph();
                graph6.title = "Conventional Energy";
                graph6.valueField = "Conventional Cost (Euro)";
                graph6.type = "column";
                graph6.lineAlpha = 0;
                graph6.lineColor = "#af1616";
                graph6.fillAlphas = 1;
                graph6.balloonText = "<b>[[value]] Euro</b>";
                chart4.addGraph(graph6);

                chart4.write("chartdiv4");
            });

        </script>

        <script>
        var chart5;

        AmCharts.ready(function () {


                // SERIAL CHART
                chart5 = new AmCharts.AmSerialChart();
                chart5.dataProvider = AmCharts.loadJSON('all_data.php');
                chart5.categoryField = "State";
                chart5.color = "#FFFFFF";
                chart5.fontSize = 14;
                chart5.startDuration = 1;
                chart5.plotAreaFillAlphas = 0.2;
                chart5.legend = [{
                      "title": "Smart System",
                      "color": "#3366CC"
                    }, {
                      "title": "Conventional System",
                      "color": "#FFCC33"
                    }]
                // the following two lines makes chart 3D
                chart5.angle = 30;
                chart5.depth3D = 60;

                // AXES
                // category
                var categoryAxis5 = chart5.categoryAxis;
                categoryAxis5.gridAlpha = 0.2;
                categoryAxis5.gridPosition = "start";
                categoryAxis5.gridColor = "#FFFFFF";
                categoryAxis5.axisColor = "#FFFFFF";
                categoryAxis5.axisAlpha = 0.5;
                categoryAxis5.dashLength = 5;

                // value
                var valueAxis5 = new AmCharts.ValueAxis();
                valueAxis5.stackType = "3d"; // This line makes chart 3D stacked (columns are placed one behind another)
                valueAxis5.gridAlpha = 0.2;
                valueAxis5.gridColor = "#FFFFFF";
                valueAxis5.axisColor = "#FFFFFF";
                valueAxis5.axisAlpha = 0.5;
                valueAxis5.dashLength = 5;
                valueAxis5.title = "Energy (kwh)";
                valueAxis5.titleColor = "#FFFFFF";
                valueAxis5.unit = "";
                chart5.addValueAxis(valueAxis5);

                // GRAPHS
                // first graph
                var graph7 = new AmCharts.AmGraph();
                graph7.title = "Smart Energy";
                graph7.valueField = "Smart Energy (kWh)";
                graph7.type = "column";
                graph7.lineAlpha = 0;
                graph7.lineColor = "#1b9b1d";
                graph7.fillAlphas = 1;
                graph7.balloonText = "<b>[[value]] kWh</b>";
                chart5.addGraph(graph7);

                // second graph
                var graph8 = new AmCharts.AmGraph();
                graph8.title = "Conventional Energy";
                graph8.valueField = "Conventional Energy (kWh)";
                graph8.type = "column";
                graph8.lineAlpha = 0;
                graph8.lineColor = "#af1616";
                graph8.fillAlphas = 1;
                graph8.balloonText = "<b>[[value]] kWh</b>";
                chart5.addGraph(graph8);

                chart5.write("chartdiv5");
            });

        </script>


    <h3 style="color:white; text-shadow: 2px 2px 4px #000000;">Time in each State</h3>
    <div id="chartdiv2" style=" margin: 0px auto; "></div>

    <h3 style="color:white; text-shadow: 2px 2px 4px #000000;">Smart Energy - Conventional Energy (LDR)</h3>
    <div id="chartdiv1" style=" width: 60%; margin: 0px auto; "></div>

    <h3 style="color:white; text-shadow: 2px 2px 4px #000000;">Smart Energy - Conventional Energy (without LDR)</h3>
    <div id="chartdiv5" style=" width: 60%; margin: 0px auto; "></div>

    <h3 style="color:white; text-shadow: 2px 2px 4px #000000;">Costs (Conv. System with LDR)</h3>
    <div id="chartdiv3" style=" width: 60%; margin: 0px auto; "></div>

    <h3 style="color:white; text-shadow: 2px 2px 4px #000000;">Costs (Conv. System without LDR)</h3>
    <div id="chartdiv4" style=" width: 60%; margin: 0px auto; "></div>
    
    
    </body>
</html>