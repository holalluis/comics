/** Mostra el menu d'opcions pel comic "element"*/
function comicMenu(element,ev)
{
	var id_client=element.getAttribute('client');
	var id_comic=element.getAttribute('comic');
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
		div.innerHTML+="<button class=opcio onclick=comprarComic("+id_client+","+id_comic+")>Comprar</button>"
	}
	if(element.getAttribute('estat')=='comprat')
	{
		var compra=element.getAttribute('compra')
		div.innerHTML+="<button class=opcio onclick=esborraCompra("+compra+")>Esborra compra</button>";
	}
}

/** Nou comic a la base de dades [+1]*/
function nouComic(id_serie,numero)
{
	window.location='bin/nouComic.php?id_serie='+id_serie+'&numero='+numero
}

/** Nova compra a la base de dades*/
function comprarComic(id_client,comic)
{
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
