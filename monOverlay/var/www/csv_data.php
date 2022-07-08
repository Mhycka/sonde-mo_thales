<?php                                                                                                 
header('Content-Disposition: attachment; filename="data_measure.csv"'); 
header('Content-type: application/csv');


$db = new SQLite3("/data/mydatabase.sqlite3");                             
$res = $db->query("SELECT timesecond, date, temperature, pressure FROM data_bmp180");
while ($row = $res->fetchArray(SQLITE3_ASSOC)) {                                                      
   echo "{$row['timesecond']},{$row['date']},{$row['temperature']},{$row['pressure']},\n";               
}

?>    
