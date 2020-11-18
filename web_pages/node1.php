<?php 
    // Start MySQL Connection
    include('dbconnect.php'); 
?>

<?php 
    $dbusername = "user_name"; 
    $dbpassword = "password";  
    $server = "127.0.0.1"; 

    // Connect to your database
    $mysqli = new mysqli($server, $dbusername, $dbpassword, "smart_street_light");
    if($mysqli->connect_error) 
     die('Connect Error (' . mysqli_connect_errno() . ') '. mysqli_connect_error());
    
    // Execute SQL statement
    $result = $mysqli->query("SELECT StateValue FROM smart_street_light.StreetLights WHERE Node = 1 ORDER BY ID DESC LIMIT 1");
    
        while($row = $result->fetch_assoc()) {
            $SValue = $row["StateValue"];
         } 

?>


<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="refresh" content="3" >
    <link rel="stylesheet" href="style.css" type="text/css">
    <script src="http://www.amcharts.com/lib/3/plugins/dataloader/dataloader.min.js" type="text/javascript"></script>
    <script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
    <script src="https://www.amcharts.com/lib/3/serial.js"></script>
    <script src="https://www.amcharts.com/lib/3/plugins/animate/animate.min.js"></script>
    <script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
    <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
    <script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
    <script src="gauge.js"></script>
    <title>Node 1 measurements</title>

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
            width       : 70%;
            height      : 500px;
            font-size   : 11px;
        }   
        #chartdiv2 {
            margin-top: : 20px;
            width       : 60%;
            height      : 500px;
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
            Node 1
        </h1>

        <a href="index.html">
            <img src="images/home.png" height="30" width="30" style="margin:-6px 0px 0px 20px;" />
            </a>
             <a href="review_data.php">
            <img src="images/mysql.png" height="30" width="30" align="" style="margin:-6px 0px 0px 20px;" />
            </a>
                                 
            <a href="nodes.php">
            <img src="images/con.png" height="30" width="30" align="" style="margin:-6px 0px 0px 20px;border-radius: 50px;" />
            </a>
            <a href="maps.html">
            <img src="images/map.png" height="30" width="30" align="" style="margin:-6px 0px 0px 20px;border-radius: 50px;" />
            </a> 
            <a href="node2.php">
            <img src="images/two.png" height="30" width="30" style="margin:-10px 0px 0px 20px;border-radius: 50px;" />
            </a>
            <a href="node3.php">
            <img src="images/three.png" height="30" width="30" style="margin:-10px 0px 0px 20px;border-radius: 50px;" />
            </a>
            
           
            <br>
            <br>

        

    <canvas data-type="radial-gauge" data-width="300" data-height="300" data-units="%" data-title="Node 1" data-value=<?php echo "\"".$SValue."\"\n"?> data-animate-on-init="true" data-animated-value="true" data-min-value="0" data-max-value="100" data-major-ticks="0,20,40,60,80,100" data-minor-ticks="2" data-stroke-ticks="false" data-highlights='[
            { "from": 0, "to": 20, "color": "rgba(0, 153, 51,1)" },
            { "from": 40, "to": 60, "color": "rgba(204, 153, 0,1)" },
            { "from": 80, "to": 100, "color": "rgba(255, 51, 51,1)" }
        ]' data-color-plate="transparent" data-color-major-ticks="#f5f5f5" data-color-minor-ticks="#ddd" data-color-title="#fff" data-color-units="#ccc" data-color-numbers="#eee" data-color-needle-start="rgba(240, 128, 128, 1)" data-color-needle-end="rgba(255, 160, 122, .9)" data-value-box="true" data-animation-rule="bounce" data-animation-duration="500" data-border-outer-width="3" data-border-middle-width="3" data-border-inner-width="3" width="400" height="400" style="width: 400px; height: 400px; visibility: visible;"></canvas>

    <br>
    <br>

    <hr>

    <table border="0" cellspacing="1" cellpadding="4" style="margin-top: 30px; margin-bottom: 30px;">
      <tr>
            <td class="table_titles">State</td>
            <td class="table_titles">Time (h)</td> 
            <td class="table_titles">Smart Energy (kWh)</td>
            <td class="table_titles">Conventional Energy (kWh)</td>
      </tr>
    
<?php
    // Retrieve records for node 1 from table 
	// test and display the different states and the time in each state
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
        END
     FROM StreetLights WHERE Node = 1 GROUP BY State"); 
    

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

          // parse and return the output
          return eval(request.responseText);
        };
    </script>

    
    <script>
        var chart1;

        AmCharts.ready(function () {

            var chartData = AmCharts.loadJSON('data_node1.php');

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
                graph2.title = "Conventional Energy";
                graph2.valueField = "Conventional Energy (kWh)";
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
                chart2.dataProvider = AmCharts.loadJSON('data_node1.php');
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

    <h3 style="color:white; text-shadow: 2px 2px 4px #000000;">Smart Energy - Conventional Energy</h3>
    <div id="chartdiv1" style=" width: 60%; margin: 0px auto; "></div>
    <h3 style="color:white; text-shadow: 2px 2px 4px #000000;">Time in each State</h3>
    <div id="chartdiv2" style=" margin: 0px auto; "></div>
    
    </body>
</html>