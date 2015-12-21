<?php

//ESBORRA COMPRA
include'../mysql.php';

//entrada
$id=$_GET['id'];

//ordre 
$sql="DELETE FROM compres WHERE id=$id";
$result=mysql_query($sql) or die('error');

header("Location: ".$_SERVER['HTTP_REFERER']);

?>
