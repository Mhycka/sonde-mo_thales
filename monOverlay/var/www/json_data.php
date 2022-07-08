<?php
header('Content-Disposition: attachment; filename="data_mesure.json"');                                                                                                 
header('Content-type: application/json');

$where = '';
if (isset($_GET['modulo'])) {
	$where = 'WHERE NOT (timesecond % ' . $_GET['modulo'] . ')';
}
if (isset($_GET['limit'])){
	$where = ' LIMIT ' . $_GET['limit']';
}
if (isset($_GET['day'])){
	$where = 'WHERE DATE_FORMAT(date, "%Y-%m-%d")=' . $_GET['day'] ;
}

if (isset($_GET['temp'])){
        $where = 'WHERE temperature =' . $_GET['temp'] ;
}



$db = new SQLite3("/data/mydatabase.sqlite3", SQLITE3_OPEN_READONLY);                             
$res = $db->query("SELECT temperature, pressure, date, timesecond FROM data_bmp180 $where;Â");
                                                                                                      
while ($row = $res->fetchArray(SQLITE3_ASSOC)) {                                                      
   $jsonArray[] = $row;                                     
}
                                                                               
echo json_encode($jsonArray);                                                                      

?>  
