<?php

//NOU COMIC A LA VENTA
include'../mysql.php';

//entrada
$id_serie = $_GET['id_serie'];
$numero = $_GET['numero'];

//mira si ja exsisteix
$n=mysql_num_rows(mysql_query("SELECT 1 FROM comics WHERE id_serie=$id_serie && numero=$numero"));
if($n)
	die("Aquest número ja existeix! <a href='".$_SERVER['HTTP_REFERER']."'>Enrere</a>");

//ordre
$sql="INSERT INTO comics (id_serie,numero) VALUES ($id_serie,$numero)";
$result=mysql_query($sql) or die('error');

//torna
header("Location: ".$_SERVER['HTTP_REFERER']);

?>

