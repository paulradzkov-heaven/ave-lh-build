function popup(datei,name,breite,hoehe,noresize)
{ 
	var posX=(screen.availWidth-breite)/2;
	var posY=(screen.availHeight-hoehe)/2;
	var resizable = (noresize==1) ? 0 : 1;
	window.open(datei,name,"resizable="+resizable+",scrollbars=1,width=" + breite + ",height=" + hoehe + "screenX=" + posX + ",screenY=" + posY + ",left=" + posX + ",top=" + posY + "");
}


function galpop(datei,name,breite,hoehe,noresize)
{ 
	var posX=(screen.availWidth-breite)/2;
	var posY=(screen.availHeight-hoehe)/2;
	var resizable = (noresize==1) ? 0 : 1;
	var scrollbar = (document.all) ? 0 : 1;
	window.open(datei,name,"resizable="+resizable+",scrollbars="+scrollbar+",width=" + breite + ",height=" + hoehe + "screenX=" + posX + ",screenY=" + posY + ",left=" + posX + ",top=" + posY + "");
}


function textCounter(field, countfield, maxlimit)
{ 
	if (field.value.length > maxlimit)
	{ 
		field.value = field.value.substring(0, maxlimit);
	} else { 
		countfield.value = maxlimit - field.value.length;
	}
}


function elemX (element) {
	var x = 0;
	while (element) {
		x += element.offsetLeft;
		element = element.offsetParent;
	}
	return x;
}

function elemY (element) {
	var y = 0;
	while (element) {
		y += element.offsetTop;
		element = element.offsetParent;
	}
	return y;
}

function getWidth (element) {
	return element.offsetWidth;
}

function getHeight (element) {
	return element.offsetHeight;
}

function elemObj(elementId) {
	if (document.all)
		return document.all[elementId];
	else if (document.getElementById)
		return document.getElementById(elementId);
	else
		return null;
}