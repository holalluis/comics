<?php

//ESBORRA COMIC: comics i compres!
include'../mysql.php';

//entrada
$id=$_GET['id'];

//ordre 
$sql="DELETE FROM comics WHERE id=$id";
$result=mysql_query($sql) or die('error');
$sql="DELETE FROM compres WHERE id_comic=$id";
$result=mysql_query($sql) or die('error');

//torna
header("Location: ".$_SERVER['HTTP_REFERER']);

?>
