<?php

//ESBORRA CLIENT: client i compres!
include'../mysql.php';

//entrada
$id=$_GET['id'];

//ordre 
$sql="DELETE FROM clients WHERE id=$id";
$result=mysql_query($sql) or die('error');
$sql="DELETE FROM compres WHERE id_client=$id";
$result=mysql_query($sql) or die('error');

//torna
header("Location: ../index.php");

?>
