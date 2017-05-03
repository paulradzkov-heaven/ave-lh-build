/*-------------------------------------------------------------------
Player 3 javascript API
-------------------------------------------------------------------*/
function sendEvent(swf,typ,prm) { 
	thisMovie(swf).sendEvent(typ,prm); 
};
function getUpdate(typ,pr1,pr2,swf) {
	if(typ == "state" && swf == "jstest") {
		gid('stateshow').innerHTML = pr1;
	}
};
function loadFile(swf,obj) {
	thisMovie(swf).loadFile(obj); 
};
function getLength(swf) { 
	var len = thisMovie(swf).getLength(); 
	alert('the length of the playlist is: '+len);
};
function addItem(swf,obj,idx) {
	thisMovie(swf).addItem(obj,idx);
};
function removeItem(swf,idx) {
	thisMovie(swf).removeItem(idx);
};
function itemData(swf,idx) { 
	var obj = thisMovie(swf).itemData(idx);
	var txt = "";
	for(var i in obj) { 
		txt += i+": "+obj[i]+"\n";
	}
	alert(txt);
};
/*-------------------------------------------------------------------
Player 4 javascript API
-------------------------------------------------------------------*/
function thisMovie(movieName) {
	return document.getElementById(movieName);
	/*
	if(navigator.appName.indexOf("Microsoft") != -1) {
		return window[movieName];
	} else {
		return document[movieName];
	}
	*/
};
var tracing = false;
function printTrace(str) {
	if(tracing == true) {
		var itm = gid('tracecode');
		var txt = itm.innerHTML + str + '\n';
		itm.innerHTML = txt;
		itm.scrollTop = itm.scrollHeight;
	}
};
function toggleTrace() {
	var itm = gid('tracecode');
	if (tracing == true) { 
		tracing = false;
		itm.style.display = 'none';
	} else { 
		tracing = true;
		itm.innerHTML = '';
		itm.style.display = 'block';
	}
};
var configobj;
var playlistobj;
function printConfig() {
	tracing = false; 
	var cfg = thisMovie('player').getConfig();
	configobj = cfg;
	printData(cfg);
}
function printPlaylist() {
	tracing = false;
	var ply = thisMovie('player').getPlaylist();
	playlistobj = ply;
	var txt = "";
	for(var i=0; i<ply.length; i++) {
		txt += i+":\r\n";
		for(var itm in ply[i]) {
			txt += "-> "+itm+": "+ply[i][itm]+"\r\n";
		}
	}
	var itm = gid('tracecode');
	itm.innerHTML = txt;
}
function printData(cfg) { 
	var txt = "";
	for(var itm in cfg) {
		txt += itm+": "+cfg[itm]+"\r\n";
	}
	var itm = gid('tracecode');
	itm.innerHTML = txt;
	itm.style.display = 'block';
}
//function playerReady(obj) {
//	printTrace("PLAYER READY (id:"+obj['id']+",version:"+obj['version']+",client:"+obj['client']+')');
//};
function submitSend() {
	var typ = gid('eventtype').value;
	var prm = gid('eventdata').value;
	thisMovie('player').sendEvent(typ,prm);
	return false;
};
function submitSubs() {
	var sbs = gid('subscription').value;
	var evt = sbs.substr(1);
	var typ = sbs.substr(0,1);
	if(typ == 'm') {
		thisMovie('player').addModelListener(evt,'tracer.trace');
	} else if (typ== 'c') {
		thisMovie('player').addControllerListener(evt,'tracer.trace');
	} else if (typ == 'v') {
		thisMovie('player').addViewListener(evt,'tracer.trace');
	}
	return false;
};
function submitPlug() {
	var typ = gid('plugurl').value;
	var prm = gid('plugvars').value;
	thisMovie('player').loadPlugin(typ,prm);
	return false;
};
var tracer = new Object();
tracer.trace = function(cfg) {
	var txt = "";
	for(var itm in cfg) {
		txt += itm+": "+cfg[itm]+"\r\n";
	}
	alert(txt);
}
