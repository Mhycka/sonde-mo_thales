
<?php
header("COntent-type : application/json;charset=utf-8");
header("Pragma : no-cache");
header("Expires: 0");

$db = new SQLite3('/data/mydatabase.sqlite3');
?>

<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8"/>
		<script src="https://kit.fontawesome.com/5762ffd56a.js" crossorigin="anonymous"></script>
		<link rel="stylesheet" type="text/css" href="style.css">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@500&display=swap" rel="stylesheet"> 
		<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;800&display=swap" rel="stylesheet">
	 	<link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@200;300;400;600;700;900&display=swap" rel="stylesheet">  
	</head>

	<body>

		<div id="home">
			<div classe="container-1">
				<div class = "nav"> 
					<ul>
						<li> <a href="#Download" >Download</a> </li>
						<li> <a href="#Configuration">Configuration</a> </li>
						<li> <a href="#Home" >Home</a> </li>
					</ul>
					<hr>
				</div>

				<div class="logo">	
					<h1>AJC Weather Station</h1>
				</div>

			
				<h2>Welcome to the weather station whichallow you to simply get access to meteorological data such as pressure and temperature. This also allow you to track those data along time. </h2>
			</div>
			</br></br></br>


			<div classe="container-2">
				<div class="presentation"> 
					<h3 style="text-shadow: 1px 1px grey; " class="center">Pressure </h3>
					<p>Actual Pressure :  <?php echo $db->querySingle('select pressure from data_bmp180 where date=(select max(date) from data_bmp180);'); ?> Hpa  </p>
					<p>Pressure Min: <?php echo $db->querySingle('select min(pressure) from data_bmp180'); ?> Hpa </p>
					<p>Pressure Max: <?php echo $db->querySingle('select max(pressure) from data_bmp180'); ?> Hpa  </p>				
				</div>
				<div class="presentation"> 
					<h3 style="text-shadow: 1px 1px grey;" class="center">Temperature </h3>
					<p>Actual Temperature :  <?php echo $db->querySingle('select temperature from data_bmp180 where date=(select max(date) from data_bmp180);'); ?> °C </p>
					<p>Temperature Minimum : <?php echo $db->querySingle('select min(temperature) from data_bmp180'); ?> °C </p>
					<p>Temperature Maximum : <?php echo $db->querySingle('select max(temperature) from data_bmp180'); ?> °C </p>
				</div>

			</div>
		</div>

		
		<div id="Configuration">
			<div class="presentation" style=""> 
				<form method="post" action="">
        
					<h3>Select the Measurement frequency: </h3><br><br>
					<select class="bouton" name="period" id="period">
						<option value="30" selected>30sec</option>
						<option value="60">1min</option>
						<option value="120">2min</option>
						<option value="3000">5min</option>
						<option value="3600">1h</option>
						<option value="7200">2h</option>
					</select>
			
					<button class ="bouton" action="configfreq.php"  type="submit">Selectionner</button>
				</form>
			
	
			</div>

			<div class="presentation"> 
				<h3>Downloading all data </h3>
    				<ul style="padding:0 0 0 15px;">
     				 	<a href="mesures_json" style="color: #555;">Download data json form</a>
					<a href="csv_data.php" style="color: #555;">Download data csv form</a>
    				</ul>
			</div>

		</div>
	</body>

</html>








