<?php
header('Content-Disposition: attachment; filename="data_mesure.json"');                                                                                                 
header('Content-type: application/json');

$where = '';
if (isset($_GET['modulo'])) {
	$where = 'WHERE NOT (timesecond % ' . $_GET['modulo'] . ')';
}

$db = new SQLite3("/data/mydatabase.sqlite3", SQLITE3_OPEN_READONLY);                             
$res = $db->query("SELECT temperature, pressure, date, timesecond FROM data_bmp180 $where;Â");
                                                                                                      
while ($row = $res->fetchArray(SQLITE3_ASSOC)) {                                                      
   $jsonArray[] = $row;                                     
}
                                                                               
echo json_encode($jsonArray);                                                                      

?>  
