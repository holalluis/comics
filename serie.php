<?php include'mysql.php'?>
<?php
	//GET serie
	$serie=new stdClass;
	$serie->id=isset($_GET['serie']) ? $_GET['serie'] : 1;
	$sql="SELECT * FROM series WHERE id=$serie->id";
	$res=mysql_query($sql);
	$row=mysql_fetch_assoc($res);
	$serie->nom=$row['nom'];

	//tots els comics de la serie
	$comics=[];
	$sql="SELECT * FROM comics WHERE id_serie=$serie->id";
	$res=mysql_query($sql);
	while($row=mysql_fetch_assoc($res))
	{
		$comic=new stdClass;
		$comic->id=$row['id'];
		$comic->numero=$row['numero'];
		$comics[]=$comic;
	}

	//tots els clients
	$clients=[];
	$sql="SELECT * FROM clients ORDER BY nom";
	$res=mysql_query($sql);
	while($row=mysql_fetch_assoc($res))
	{
		$client=new stdClass;
		$client->id=$row['id'];
		$client->nom=$row['nom'];
		$clients[]=$client;
	}

?>
<!doctype html><html><head>
	<?php include'meta.php'?>
	<title>Còmics</title>
	<link rel=stylesheet href=css.css>
	<script src=js.js></script>
</head><body><center>
<!--TRIA SERIE--><?php include'triaSerie.php'?>
<!--TITLE--><h2 onclick=window.location.reload()>Sèrie — <?php echo $serie->nom?></h2>

<!--BOTONS [-1][+1]-->
<?php
	/*maxim numero de la serie*/
	$n=current(mysql_fetch_assoc(mysql_query("SELECT MAX(numero) FROM comics WHERE id_serie=$serie->id")));
	//troba la id de l'últim còmic de la serie
	$ultim=1;
	foreach($comics as $comic)
	{
		if($comic->numero==$n)
		{
			$ultim=$comic->id; break;
		}
	}
	$seguent=$n+1; //numero comic
	echo "<td>
			<button onclick=esborraComic($ultim)>-1</button> 
			<button onclick=nouComic($serie->id,$seguent)>+1</button>";
?>

<!--TAULA DE CLIENTS I NUMEROS-->
<table id=taulaComics>
	<tr><td style=border:none>
	<?php
		/*header numeros*/
		for($i=1;$i<=$n;$i++){echo "<th>$i";}
		/*recorre clients*/
		foreach($clients as $client)
		{
			echo "<tr> <th style=text-align:left> <a href=index.php?client=$client->id>$client->nom</a>";
			/*cada columna és un numero*/
			for($i=1;$i<=$n;$i++)
			{
				/*troba el comic corresponent de numero i*/
				foreach($comics as $comic)
				{
					if($i==$comic->numero)
					{
						echo "<td 
							comic=$comic->id 
							num=$comic->numero 
							client=$client->id
							onclick=comicMenu(this,event)
							estat=disponible
							titol='$serie->nom $comic->numero'> 
							En venda";
						break;
					}
				}
			}
		}
	?>
</table>

<script>
	/**MARCA ELS COMICS COMPRATS*/ 
	<?php 
		//totes les compres dels comics de la serie
		$compres=[];
		$sql="	SELECT 	compres.id, compres.id_comic, compres.id_client 
				FROM 	compres, comics 
				WHERE 	comics.id=compres.id_comic AND comics.id_serie=$serie->id";
		$res=mysql_query($sql);
		while($row=mysql_fetch_assoc($res))
		{
			$compra=new stdClass;
			$compra->id=$row['id'];
			$compra->id_comic=$row['id_comic'];
			$compra->id_client=$row['id_client'];
			$compres[]=$compra;
		}

		//passa a javascript
		$compres=json_encode($compres);
		echo "var compres=$compres;";
	?>
	compres.forEach(function(compra)
	{
		var td=document.querySelector("td[client='"+compra.id_client+"'][comic='"+compra.id_comic+"']")
		td.setAttribute('estat','comprat')
		td.setAttribute('compra',compra.id);
		td.innerHTML="Comprat";
	})
</script>
