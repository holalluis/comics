<!--dins de serie.php-->
<div style="background-color:#0aaff1;text-align:;padding:1em;color:white">
	Sèrie
	<select onchange="window.location='serie.php?serie='+this.value">
		<?php
			$sql="SELECT * FROM series";
			$res=mysql_query($sql);
			while($row=mysql_fetch_array($res))
			{
				$id=$row['id'];
				$nom=$row['nom'];
				if($id==$serie->id) 
					echo "<option value=$id selected=true>$nom";
				else 
					echo "<option value=$id>$nom";
			}
		?>
	</select>
	<button onclick=mostraMenuNovaSerie(event)>+ Afegir nova serie</button>
</div>

<script>
	function mostraMenuNovaSerie(ev)
	{
		var menu=document.querySelector("#menuNovaSerie")
		menu.style.left=ev.pageX+"px"
		menu.style.top=ev.pageY+"px"
		menu.style.display='block'
		document.getElementsByName('nom')[0].focus()
	}
</script>

<form id=menuNovaSerie action="bin/novaSerie.php" style=display:none>
	<input name=nom placeholder=Nom autocomplete=off required><br>
	<button type=submit>Ok</button>
	<button type=button onclick="this.parentNode.style.display='none'">Cancela</button>
</form>
