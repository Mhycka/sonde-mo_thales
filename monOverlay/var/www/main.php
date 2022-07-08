<?php
header("COntent-type : application/json;charset=utf-8");
header("Pragma : no-cache");
header("Expires: 0");

$db = new SQLite3('/data/mydatabase.sqlite3');

$nfields = 2;
$api = 'dl_json_data.php';
$title = 'Température (°C)';
$field = 'temperature';
$title2 = 'Pression (HPa)';
$field2 = 'pressure';
if (isset($_GET['only'])) {
	if ($_GET['only'] == 'pression') {
		$nfields = 1;
		//$api = 'pression';  // we can use the mesures API
		$title = 'Pression (kPa)';
		$field = 'pressure';
	} else if ($_GET['only'] == 'temperature') {
		$nfields = 1;
		//$api = 'temperature';  // we can use the mesures API
	}
}

$uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
$fetch_url = '/' . $api . '/?' . $uri_parts[1];
?>

<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8"/>
		<script src="https://kit.fontawesome.com/5762ffd56a.js" crossorigin="anonymous"></script>
		<link rel="stylesheet" type="text/css" href="style2.css">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@500&display=swap" rel="stylesheet"> 
		<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;800&display=swap" rel="stylesheet">
	 	<link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@200;300;400;600;700;900&display=swap" rel="stylesheet">  
	</head>

	<body>
		<div id="home">
			<div class="header">
				<div class="logo">	
					 <h1>AJC </h1> <h2>Weather Station</h2>
				</div>			
				<p>Welcome to the weather station which allow you to simply get access to meteorological data such as pressure and temperature. This also allow you to track those data along time. </p>
			</div>


			<div class="DataCard">
				<div>
					<h3 class="titleCard press"> Pressure </h3>
					<ul class="Card">
						<li><p>Actual Pressure : <span><?php echo $db->querySingle('select pressure from data_bmp180 where date=(select max(date) from data_bmp180);'); ?> Hpa</span></p></li>
						<li><p>Pressure Min : <span><?php echo $db->querySingle('select min(pressure) from data_bmp180'); ?> Hpa</span></p></li>
						<li><p>Pressure Max : <span><?php echo $db->querySingle('select max(pressure) from data_bmp180'); ?> Hpa</span></p></li>
					</ul>
				</div>
				<div>
					<h3 class="titleCard temp"> Temperature </h3>
					<ul class="Card">
						<li><p>Actual Temperature : <span><?php echo $db->querySingle('select temperature from data_bmp180 where date=(select max(date) from data_bmp180);'); ?> °C</span></p></li>
						<li><p>Temperature Minimum : <span><?php echo $db->querySingle('select min(temperature) from data_bmp180'); ?> °C</span></p></li>
						<li><p>Temperature Maximum : <span><?php echo $db->querySingle('select max(temperature) from data_bmp180'); ?> °C</span></p></li>
					</ul>
				</div>
			</div>
		</div>

		<div onload="updateChart()" class="chart">
			<div class="chartMenu">
				<form action="" method="GET">
					Start date:<input type="date" name="start" />
					Stop date:<input type="date" name="stop" />
					Scale:<select name="tmod">
						<option value="43200">12H</option>
						<option value="10800">3H</option>
						<option value="3600">1H</option>
						<option value="1800">1/2H</option>
						<option value="900">1/4H</option>
					</select>
					&nbsp;&nbsp;
					<button onclick="submit()">Apply</button>
				</form>
				&nbsp;&nbsp;
				<button onclick="updateChart()">Refresh</button>
				&nbsp;&nbsp;
				Timezone: <div id="tz">...</div>
				</p>
			</div>
			<div class="chartCard">
				<div class="chartBox">
					<canvas id="myChart"></canvas>
				</div>
			</div>
			<script type="text/javascript" src="/js/chart.js"></script>
			<script>
			// fetch block
			function updateChart() {
				async function fetchData() {
					const url = '<?php echo $fetch_url; ?>';
					const response = await fetch(url);
					//wait until the request has been completed
					const datapoints = await response.json();
					//console.log(datapoints);
					return datapoints;
				};
	 
				fetchData().then(datapoints => {
					const xDate = datapoints.map(
						
						function(index) { return index.date; }

						
						//function(index) { return index.iso8601; }
					)
	 
					const yValues = datapoints.map(
						function(index) { return index.<?php echo $field; ?>; }
					)

					<?php if ($nfields > 1) {
						echo "
						const yValues2 = datapoints.map(
							function(index) { return index.$field2; }
						)";
					} ?>
	 
					//console.log(xDate);
					//console.log(yValues);
	 
					document.getElementById("tz").innerHTML = Intl.DateTimeFormat().resolvedOptions().timeZone;
					myChart.config.data.labels = xDate;
	 
					myChart.config.data.datasets[0].data = yValues;
					myChart.config.data.datasets[0].label = "<?php echo $title; ?>";
					<?php if ($nfields > 1) {
						echo "
							myChart.config.data.datasets[1].data = yValues2;
							myChart.config.data.datasets[1].label = \"$title2\";
						";
					} ?>
	 
					myChart.update();
				});
			}
	 
			// setup
			const data = {
				labels: ['Loading...'],
				datasets: [{
					label: '',
					borderColor: 'rgb(255, 0, 0)',
					backgroundColor: 'rgba(255, 0, 0, 0.1)',
					data: [],
					borderWidth: 1,
					yAxisID: 'y'
				}
				<?php if ($nfields > 1) {
					echo "          ,
						{
							label: 'other',
							borderColor: 'rgb(0, 0, 255)',
							backgroundColor: 'rgba(0, 0, 255, 0.1)',
							data: [],
							borderWidth: 1,
							yAxisID: 'y1'
						}
					";
				} ?>
				]
			};
	 
			// config
			const config = {
				type: 'line',
				data,
				options: {
					elements: {
						point: {
							radius: '1',
						}
					},
					scales: {
						y: {
							beginAtZero: false,
							backgroundColor: 'rgba(255, 0, 0, 0.1)',
							grid: {
								color: 'rgba(255, 0, 0, 0.3)',
							},
						}
						<?php if ($nfields > 1) {
							echo "                  ,
								y1: {
									beginAtZero: false,
									backgroundColor: 'rgba(0, 0, 255, 0.1)',
									grid: {
										color: 'rgba(0, 0, 255, 0.3)'
									},
								}
							";
						} ?>
					}
				}
			};
	 
			// render init block
			const myChart = new Chart(
				document.getElementById('myChart'),
				config
			);
			</script>
		</div>

		<div id="configData">
			<div>
				<h3 class="titleCard titlefooter">Select the Measurement frequency: </h3>
					<form method="post" action="" class="btnFrequency">
							<select class="btn" name="period" id="period">
                                                                               <option value="30000" selected>30 sec</option>
                                                                                <option value="60000">1 min</option>
                                                                                <option value="120000">2 min</option>
                                                                                <option value="300000">5 min</option>
                                                                                <option value="600000">10 min</option>
							</select>
<button class ="btn" action="<?php if($_POST){$fp = fopen('/data/config_bmp180todb', 'w'); $freq = $_POST['period']; fwrite($fp, $freq ); fclose($fp); }?> "type="submit">Selectionner</button>

					</form>
			</div>

			<div>
				<h3 class="titleCard titlefooter">Downloading all data </h3>
				<ul class="ddl">
					<a href="dl_json_data.php">Download data json form</a>
					<a href="csv_data.php">Download data csv form</a>
				</ul>
			</div>
		</div>
	</body>
</html>
