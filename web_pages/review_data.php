<?php 
    // Start MySQL Connection
    include('dbconnect.php'); 
?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="refresh" content="8" >
    <link rel="stylesheet" href="style.css" type="text/css">

    <title>Measurements</title>
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
            margin: 0 auto;
        }
        body { font-family: "Trebuchet MS", Arial;
               text-align: center ;	
               background-color:#54a7ea;			   }
			   
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
        <h1>Measurements</h1>

        <a href="index.html">
            <img src="images/home.png" height="30" width="30" style="margin:-6px 0px 0px 20px;" />
            </a>
            <a href="nodes.php">
            <img src="images/con.png" height="30" width="30" align="" style="margin:-6px 0px 0px 20px;border-radius: 50px;" />
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

    <table border="0" cellspacing="1" cellpadding="4" style="margin-top: 10px;">
      <tr>
            <td class="table_titles">Id</td>
            <td class="table_titles">Date</td>
            <td class="table_titles">Time</td>
            <td class="table_titles">Node</td>
			<td class="table_titles">State</td>
      </tr>
	
 <?php
    // Retrieve all records and display them
    $result = mysqli_query($dbh, "SELECT * FROM (SELECT * FROM StreetLights ORDER BY id DESC LIMIT 30) sub ORDER BY id ASC");


    // Used for row color toggle
    $oddrow = true;

    // process every record
    while( $row = mysqli_fetch_array($result,MYSQLI_NUM) )
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
        echo '</tr>';
    }
?> 
    </table>
	
    </body>
</html>