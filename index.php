<?php include'login.php'?>
<?php include'mysql.php'?>
<?php
	//info del client
	$client=new stdClass;
	$client->id=isset($_GET['client']) ? $_GET['client'] : 1;
	$sql="SELECT * FROM clients WHERE id=$client->id";
	$res=mysql_query($sql);
	$row=mysql_fetch_assoc($res);
	$client->nom=$row['nom'];
	$client->tel=$row['tel'];

	/** Tots els comics disponibles. primer series i després comics*/
	$series=[];
	$sql="SELECT * FROM series";
	$res=mysql_query($sql);
	while($row=mysql_fetch_assoc($res))
	{
		$serie=new stdClass;
		$serie->id=$row['id'];
		$serie->nom=$row['nom'];
		$series[]=$serie;
	}
	$comics=[];
	$sql="SELECT * FROM comics";
	$res=mysql_query($sql);
	while($row=mysql_fetch_assoc($res))
	{
		$comic=new stdClass;
		$comic->id=$row['id'];
		$comic->id_serie=$row['id_serie'];
		$comic->numero=$row['numero'];
		$comics[]=$comic;
	}
	$compres=[];
	$res=mysql_query("SELECT * FROM compres WHERE id_client=$client->id");
	while($row=mysql_fetch_assoc($res))
	{
		$compra=new stdClass;
		$compra->id=$row['id'];
		$compra->id_comic=$row['id_comic'];
		$compres[]=$compra;
	}
?>
<!doctype html><html><head>
	<?php include'meta.php'?>
	<title>Còmics</title>
	<link rel=stylesheet href=css.css>
	<script src=js.js></script>
</head><body><center>
<!--TRIA CLIENT--><?php include'triaClient.php'?>
<!--TITLE--><h2 onclick=window.location.reload()>Client — <?php echo $client->nom?></h2>
<!--RESUM-->
<div>
	<b>Resum</b>:
	<?php
		$ncomprats=count($compres);
		$ndisponibles=count($comics)-$ncomprats;
		echo "<span style=background:#af0>$ncomprats comprats</span>, <span style=background:yellow>$ndisponibles disponibles</span>. ";
	?>
	<b>Telèfon</b>: <?php echo $client->tel?>
</div>

<!--TAULA COMICS-->
<table id=taulaComics><tr><td style=border:none>
	<?php
		/*maxim numero de comics*/$n=current(mysql_fetch_assoc(mysql_query("SELECT MAX(numero) FROM comics")));
		/*numeros*/for($i=1;$i<=$n;$i++){echo "<th>$i";}
		/*recorre series*/foreach($series as $serie)
		{
			//valors per defecte per fer [-1] [+1]
			$ultim=1; 	//id comic
			$seguent=1; //numero comic

			echo "<tr><th style=text-align:left><a href=serie.php?serie=$serie->id>$serie->nom</a>";
			for($j=1;$j<=$n;$j++)
			{
				//busca el comic numero j
				$existeix=false;
				foreach($comics as $comic)
				{
					if($j==$comic->numero && $serie->id==$comic->id_serie)
					{
						$existeix=true;
						echo "<td 
								comic=$comic->id 
								num=$comic->numero 
								client=$client->id
								onclick=comicMenu(this,event)
								estat=disponible 
								titol='$serie->nom $comic->numero'> 
								En venda
								";
						$seguent=$comic->numero+1;
						$ultim=$comic->id;
						break;
					}
				}
				if(!$existeix){echo "<td estat='no-disponible'>No ha sortit";}
			}
			echo "<td>
					<button onclick=esborraComic($ultim)>-1</button> 
					<button onclick=nouComic($serie->id,$seguent)>+1</button>";
		}
	?>
</table>

<!--ESBORRA CLIENT-->
<div style="margin-top:4em">
	<button style="background:red;font-size:15px;border:1px solid #ccc;padding:0.5em" onclick=esborraClient()>Esborra client &#9760;</button>
	<script>
		function esborraClient()
		{
			if(prompt("Escriu 12345 per confirmar")=="12345")
				window.location="bin/esborraClient.php?id="+<?php echo $client->id?>
		}
	</script>
</div>

</html>

<script>
	/**MARCA ELS COMICS COMPRATS DEL CLIENT ACTUAL */
	<?php 
		$compres=json_encode($compres);
		echo "var compres=$compres;";
	?>
	compres.forEach(function(compra)
	{
		var td=document.querySelector("[comic='"+compra.id_comic+"']")
		td.setAttribute('estat','comprat');
		td.setAttribute('compra',compra.id);
		td.innerHTML="Comprat"
	})
</script>
