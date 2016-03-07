<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
if($_SERVER['SERVER_NAME']=='localhost')
{
	mysql_connect("127.0.0.1","root","");
	mysql_select_db("comics");
}
else
{
	mysql_connect("mysql.hostinger.es","u317696172_lluis","lluislluis1");
	mysql_select_db("u317696172_comic");
}
?>
