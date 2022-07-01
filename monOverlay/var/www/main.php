<html>

	<head>
	<title>Sonde@mo</title>
	</head>

	<body>
		<?php
	
		$output = file_get_contents('./sys/class/gpio/gpio17/value', true);
Â		echo $output;

		?>

		<form action="gpio.php"  method="get">
			<input type="submit" name="gpio17" value="on"/>
		</form> 
	
	</body>
	
	
</html>
