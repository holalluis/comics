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
		$compra->data=$row['data'];
		$compres[]=$compra;
	}
?>
<!doctype html><html><head>
	<meta charset=utf-8>
	<title>Còmics</title>
	<link rel=stylesheet href=css.css>
	<script>
		/** Mostra el menu d'opcions pel comic "element"*/
		function comicMenu(element,ev)
		{
			//amaga tots els altres comicMenus
			var altres=document.querySelectorAll("div.comicMenu")
			for(var i=0;i<altres.length;i++){altres[i].style.display='none';}
			var id=element.id 
			var titol=element.getAttribute('titol')
			//nou div
			var div=document.createElement('div')
			div.className="comicMenu"
			document.body.appendChild(div)
			div.style.left=ev.pageX+"px"
			div.style.top=ev.pageY+"px"
			div.onclick=function(){div.parentNode.removeChild(div)}
			/**omple div element*/
			div.innerHTML="<div style=text-align:right><span>"+titol+"</span>&emsp;<span><button>X</button></span></div>"
			if(element.getAttribute('estat')=='disponible')
			{
				div.innerHTML+="<button class=opcio onclick=comprarComic("+id+")>Comprar</button>"
			}
			if(element.getAttribute('estat')=='comprat')
			{
				var compra=element.getAttribute('compra')
				div.innerHTML+="<button class=opcio onclick=esborraCompra("+compra+")>Esborra compra</button>";
			}
		}

		/** Nou comic a la base de dades */
		function nouComic(id_serie,numero)
		{
			window.location='bin/nouComic.php?id_serie='+id_serie+'&numero='+numero
		}

		/** Nova compra a la base de dades*/
		function comprarComic(comic)
		{
			var id_client=<?php echo $client->id?>;
			window.location="bin/comprarComic.php?id_client="+id_client+"&id_comic="+comic
		}
		/** Esborra comic i compres de la base de dades*/
		function esborraComic(comic)
		{
			window.location="bin/esborraComic.php?id="+comic
		}
		/** Esborra compra a la base de dades*/
		function esborraCompra(compra)
		{
			window.location="bin/esborraCompra.php?id="+compra
		}
	</script>
</head><body><center>
<!--TRIA CLIENT--><?php include'triaClient.php'?>
<!--TITLE--><h1>Còmics — <?php echo $client->nom?></h1>
<!--RESUM-->
<div>
	<b>Resum</b>:
	<?php
		$ncomprats=count($compres);
		$ndisponibles=count($comics)-$ncomprats;
		echo "$ncomprats comprats, $ndisponibles disponibles. ";
	?>
	<b>Telèfon</b>: <?php echo $client->tel?>
</div>

<!--TAULA COMICS-->
<table id=taulaComics><tr><td style=border:none><tr><td style=border:none>
	<?php
		/*maxim numero de comics*/$n=current(mysql_fetch_assoc(mysql_query("SELECT MAX(numero) FROM comics")));
		/*numeros*/for($i=1;$i<=$n;$i++){echo "<th>$i";}
		/*recorre series*/foreach($series as $serie)
		{
			//valors per defecte per fer [-1] [+1]
			$ultim=1; 	//id comic
			$seguent=1; //numero comic

			echo "<tr><th style=text-align:left>$serie->nom";
			for($j=1;$j<=$n;$j++)
			{
				//busca el comic
				foreach($comics as $comic)
				{
					if($j==$comic->numero && $serie->id==$comic->id_serie)
					{
						echo "<td 
								id=$comic->id 
								num=$comic->numero 
								onclick=comicMenu(this,event)
								estat=disponible 
								titol='$serie->nom $comic->numero'> 
								Pendent";
						$seguent=$comic->numero+1;
						$ultim=$comic->id;
						break;
					}
				}
			}
			echo "<td style=border:none>
					<button onclick=esborraComic($ultim)>-1</button> 
					<button onclick=nouComic($serie->id,$seguent)>+1</button>";
		}
	?>
</table>

<!--ESBORRA CLIENT-->
<div style="margin-top:4em">
	<button style=background:red;font-size:15px onclick=esborraClient()>Esborra client &#9760;</button>
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
		var td=document.getElementById(compra.id_comic)
		td.setAttribute('estat','comprat');
		td.setAttribute('compra',compra.id);
		td.innerHTML="&check;"
	})
</script>
