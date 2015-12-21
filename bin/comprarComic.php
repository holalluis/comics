<?php

//NOVA COMPRA
include'../mysql.php';

//entrada
$id_client = $_GET['id_client'];
$id_comic  = $_GET['id_comic'];

//ordre
$sql = "INSERT INTO compres (id_client,id_comic) VALUES ($id_client,$id_comic)";
$result=mysql_query($sql) or die('error');

//torna
header("Location: ".$_SERVER['HTTP_REFERER']);

?>
