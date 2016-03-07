<?php

//comprova password
if(isset($_POST['pass']))
{
	if($_POST['pass']=="girona")
	{
		setcookie('login',1,time()+86400,'/') or exit("error setting cookie");
		header("Location: index.php");
	}
	else
		die('<center>Contrasenya incorrecta. <button onclick=window.location="index.php">Enrere</button>');
}

//demana password
if(!isset($_COOKIE['login']))
{
	?>
		<center>
		<h3>Inicia sessió</h3>
		<form method=POST>
			<input type=password name=pass>
			<button>Entra</button>
		</form>
	<?php
	die();
}

?>
