<?php

//NOU CLIENT
include'../mysql.php';

//entrada
$nom=mysql_real_escape_string($_GET['nom']);
$tel=mysql_real_escape_string($_GET['tel']);

//ordre sql insercio
$sql="INSERT INTO clients (nom,tel) VALUES ('$nom','$tel')";
$result=mysql_query($sql) or die('error');

//busca la id del nou client i ves-hi
$id=current(mysql_fetch_array(mysql_query("SELECT MAX(id) FROM clients")));
header("Location: ../index.php?client=$id");

?>

