<div style="background-color:#0aaff1;text-align:;padding:1em;color:white">
	Client
	<select onchange="window.location='index.php?client='+this.value">
		<?php
			$sql="SELECT * FROM clients";
			$res=mysql_query($sql);
			while($row=mysql_fetch_array($res))
			{
				$id=$row['id'];
				$nom=$row['nom'];
				if($id==$client->id) 
					echo "<option value=$id selected=true>$nom";
				else 
					echo "<option value=$id>$nom";
			}
		?>
	</select>
	<button onclick=mostraMenuNouClient(event)>+ Afegir nou client</button>
</div>

<script>
	function mostraMenuNouClient(ev)
	{
		var menu=document.querySelector("#menuNouClient")
		menu.style.left=ev.pageX+"px"
		menu.style.top=ev.pageY+"px"
		menu.style.display='block'
		document.getElementsByName('nom')[0].focus()
	}
</script>

<form id=menuNouClient action="bin/nouClient.php" style=display:none>
	<input name=nom placeholder=Nom autocomplete=off required><br>
	<input name=tel placeholder=Telèfon autocomplete=off required><br>
	<button type=submit>Ok</button>
	<button type=button onclick="this.parentNode.style.display='none'">Cancela</button>
</form>
