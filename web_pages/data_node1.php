<?php 
/* Fetch the data from the first node*/

// Connect to mysql 
$link = ($GLOBALS["___mysqli_ston"] = mysqli_connect( '127.0.0.1',  'user_name',  'password' )); 
if ( !$link ) { 
  die( 'Could not connect: ' . mysqli_error($GLOBALS["___mysqli_ston"]) ); 
} 

// Select the data base 
$db = mysqli_select_db( $link , "smart_street_light"); 
if ( !$db ) { 
  die ( 'Error selecting database \'smart_street_light\' : ' . mysqli_error($GLOBALS["___mysqli_ston"]) ); 
} 

// Fetch the data 
$query = " 
  SELECT State, COUNT(*)/3600 AS Time,
        CASE 
            WHEN State = 'Full On' THEN (1*120*(COUNT(*)/3600))/1000
            WHEN State = '50% Night' THEN (0.5*120*(COUNT(*)/3600))/1000
            WHEN State = '50% Day' THEN (0.5*120*(COUNT(*)/3600))/1000
            WHEN State = 'Off' THEN 0
        END   AS SEnergy,
        CASE 
            WHEN State = 'Full On' THEN (1*120*(COUNT(*)/3600))/1000
            WHEN State = '50% Night' THEN (1*120*(COUNT(*)/3600))/1000
            WHEN State = '50% Day' THEN (1*120*(COUNT(*)/3600))/1000
            WHEN State = 'Off' THEN 0
        END AS CEnergy
     FROM StreetLights WHERE Node = 1 GROUP BY State  ";


$result = mysqli_query($GLOBALS["___mysqli_ston"],  $query ); 

// All good? 
if ( !$result ) { 
  // Nope 
  $message  = 'Invalid query: ' . mysqli_error($GLOBALS["___mysqli_ston"]) . "\n"; 
  $message .= 'Whole query: ' . $query; 
  die( $message ); 
} 

// Print out rows 
$prefix = ''; 
echo "[\n"; 
while ( $row = mysqli_fetch_assoc( $result ) ) { 
  echo $prefix . " {\n"; 
  echo '  "State": "' . $row['State'] . '",' . "\n"; 
  echo '  "Time (h)": "' . $row['Time'] . '",' . "\n";
  echo '  "Smart Energy (kWh)": "' . $row['SEnergy'] . '",' . "\n";
  echo '  "Conventional Energy (kWh)": ' . $row['CEnergy'] . "\n"; 
  echo " }"; 
  $prefix = ",\n"; 
} 
echo "\n]"; 

// Close the connection 
((is_null($___mysqli_res = mysqli_close($link))) ? false : $___mysqli_res); 
?> 