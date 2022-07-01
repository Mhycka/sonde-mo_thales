<?php

	if(isset($_GET['gpio17']))
	{
		exec('echo 1 > /sys/class/gpio/gpio17/value');
	}
	else
	{
		exec('echo 0 > /sys/class/gpio/gpio17/value');
	}


header('Location:/main.php');
exit;

?>
