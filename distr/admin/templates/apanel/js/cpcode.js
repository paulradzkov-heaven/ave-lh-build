function helpwin(text) {
  var html = text;
  html = html.replace(/src="/gi, 'src="../' );
  html = html.replace(/&lt;/gi, '<' );
  html = html.replace(/&gt;/gi, '>' );
  var pFenster = window.open( '', null, 'height=400,width=600,toolbar=no,location=no,status=yes,menubar=no,scrollbars=yes,resizable=yes' ) ;
  var HTML = '<html><head></head><body style="font-family:verdana,arial;font-size:11px">' + html + '</body></html>' ;
  pFenster.document.write(HTML);
  pFenster.document.close();
}


function cp_pop(url, width, height, scrollbar, winname) {
  if (typeof width=='undefined' || width=='') var width = screen.width * 0.8;
  if (typeof height=='undefined' || height=='') var height = screen.height * 0.8;
  if (typeof scrollbar=='undefined') var scrollbar=1;
  if (typeof winname=='undefined') var winname='pop';
  window.open(url,winname,'left=0,top=0,width='+width+',height='+height+',scrollbars='+scrollbar+',resizable=1');
}


function cp_imagepop(url, width, height, scrollbar) {
  if (typeof width=='undefined' || width=='') var width = screen.width * 0.8;
  if (typeof height=='undefined' || height=='') var height = screen.height * 0.8;
  if (typeof scrollbar=='undefined') var scrollbar=1;
  window.open('browser.php?typ=bild&mode=fck&target='+url+'','imgpop','left=0,top=0,width='+width+',height='+height+',scrollbars='+scrollbar+',resizable=1');
}


function cp_code(v, feldname, form) {
  if (document.selection) {
    var str = document.selection.createRange().text;
    document.getElementById(feldname).focus();
    var sel = document.selection.createRange();
    sel.text = "<" + v + ">" + str + "</" + v + ">";
    return;

  } else if (document.getElementById && !document.all) {
    var txtarea = document.forms[form].elements[feldname];
    var selLength = txtarea.textLength;
    var selStart = txtarea.selectionStart;
    var selEnd = txtarea.selectionEnd;
    if (selEnd == 1 || selEnd == 2)
    selEnd = selLength;
    var s1 = (txtarea.value).substring(0,selStart);
    var s2 = (txtarea.value).substring(selStart, selEnd)
    var s3 = (txtarea.value).substring(selEnd, selLength);
    txtarea.value = s1 + '<' + v + '>' + s2 + '</' + v + '>' + s3;
    return;

  } else {
    cp_insert('<' + v + '></' + v + '> ');
  }
}


function cp_insert(what,feldname, form) {
  if (document.getElementById(feldname).createTextRange) {
    document.getElementById(feldname).focus();
    document.selection.createRange().duplicate().text = what;

  } else if (document.getElementById && !document.all) {
    var tarea = document.forms[form].elements[feldname];
    var selEnd = tarea.selectionEnd;
    var txtLen = tarea.value.length;
    var txtbefore = tarea.value.substring(0,selEnd);
    var txtafter =  tarea.value.substring(selEnd, txtLen);
    tarea.value = txtbefore + what + txtafter;

  } else {
    document.entryform.text.value += what;
  }
}

function clickInsertTitleUrl() {
  var counter = 0;
  var tempTitleUrl = 0;
  var caseSymbol = '';
  var titleUrlStart = document.formDocOption.Titel.value;
  var titleUrlEnd=new Array();
  var lenTitleUrl=titleUrlStart.length;

  while (counter != lenTitleUrl) {
    tempTitleUrl = titleUrlStart.substr(counter,1);
    counter = counter + 1;
    caseSymbol = tempTitleUrl;

    switch (caseSymbol) {
      case "!": massiv=titleUrlEnd.push("");break;
      case "#": massiv=titleUrlEnd.push("");break;
      case "$": massiv=titleUrlEnd.push("");break;
      case "%": massiv=titleUrlEnd.push("");break;
      case "&": massiv=titleUrlEnd.push("");break;
      case "'": massiv=titleUrlEnd.push("");break;
      case "(": massiv=titleUrlEnd.push("");break;
      case ")": massiv=titleUrlEnd.push("");break;
      case "*": massiv=titleUrlEnd.push("");break;
      case "+": massiv=titleUrlEnd.push("");break;
      case ",": massiv=titleUrlEnd.push("");break;
      case ".": massiv=titleUrlEnd.push("");break;
      case "/": massiv=titleUrlEnd.push("");break;
      case ":": massiv=titleUrlEnd.push("");break;
      case ";": massiv=titleUrlEnd.push("");break;
      case "<": massiv=titleUrlEnd.push("");break;
      case "=": massiv=titleUrlEnd.push("");break;
      case ">": massiv=titleUrlEnd.push("");break;
      case "?": massiv=titleUrlEnd.push("");break;
      case "@": massiv=titleUrlEnd.push("");break;
      case "[": massiv=titleUrlEnd.push("");break;
      case "\\": massiv=titleUrlEnd.push("");break;
      case "]": massiv=titleUrlEnd.push("");break;
      case "^": massiv=titleUrlEnd.push("");break;
      case "`": massiv=titleUrlEnd.push("");break;
      case "{": massiv=titleUrlEnd.push("");break;
      case "|": massiv=titleUrlEnd.push("");break;
      case "}": massiv=titleUrlEnd.push("");break;
      case "~": massiv=titleUrlEnd.push("");break;
      case " ": massiv=titleUrlEnd.push("_");break;
      case "_": massiv=titleUrlEnd.push("_");break;
      case "-": massiv=titleUrlEnd.push("-");break;
      case "0": massiv=titleUrlEnd.push("0");break;
      case "1": massiv=titleUrlEnd.push("1");break;
      case "2": massiv=titleUrlEnd.push("2");break;
      case "3": massiv=titleUrlEnd.push("3");break;
      case "4": massiv=titleUrlEnd.push("4");break;
      case "5": massiv=titleUrlEnd.push("5");break;
      case "6": massiv=titleUrlEnd.push("6");break;
      case "7": massiv=titleUrlEnd.push("7");break;
      case "8": massiv=titleUrlEnd.push("8");break;
      case "9": massiv=titleUrlEnd.push("9");break;
      case "a": massiv=titleUrlEnd.push("a");break;
      case "b": massiv=titleUrlEnd.push("b");break;
      case "c": massiv=titleUrlEnd.push("c");break;
      case "d": massiv=titleUrlEnd.push("d");break;
      case "e": massiv=titleUrlEnd.push("e");break;
      case "f": massiv=titleUrlEnd.push("f");break;
      case "g": massiv=titleUrlEnd.push("g");break;
      case "h": massiv=titleUrlEnd.push("h");break;
      case "i": massiv=titleUrlEnd.push("i");break;
      case "j": massiv=titleUrlEnd.push("j");break;
      case "k": massiv=titleUrlEnd.push("k");break;
      case "l": massiv=titleUrlEnd.push("l");break;
      case "m": massiv=titleUrlEnd.push("m");break;
      case "n": massiv=titleUrlEnd.push("n");break;
      case "o": massiv=titleUrlEnd.push("o");break;
      case "p": massiv=titleUrlEnd.push("p");break;
      case "q": massiv=titleUrlEnd.push("q");break;
      case "r": massiv=titleUrlEnd.push("r");break;
      case "s": massiv=titleUrlEnd.push("s");break;
      case "t": massiv=titleUrlEnd.push("t");break;
      case "u": massiv=titleUrlEnd.push("u");break;
      case "v": massiv=titleUrlEnd.push("v");break;
      case "w": massiv=titleUrlEnd.push("w");break;
      case "x": massiv=titleUrlEnd.push("x");break;
      case "y": massiv=titleUrlEnd.push("y");break;
      case "z": massiv=titleUrlEnd.push("z");break;
      case "A": massiv=titleUrlEnd.push("a");break;
      case "B": massiv=titleUrlEnd.push("b");break;
      case "C": massiv=titleUrlEnd.push("c");break;
      case "D": massiv=titleUrlEnd.push("d");break;
      case "E": massiv=titleUrlEnd.push("e");break;
      case "F": massiv=titleUrlEnd.push("f");break;
      case "G": massiv=titleUrlEnd.push("g");break;
      case "H": massiv=titleUrlEnd.push("h");break;
      case "I": massiv=titleUrlEnd.push("i");break;
      case "J": massiv=titleUrlEnd.push("j");break;
      case "K": massiv=titleUrlEnd.push("k");break;
      case "L": massiv=titleUrlEnd.push("l");break;
      case "M": massiv=titleUrlEnd.push("m");break;
      case "N": massiv=titleUrlEnd.push("n");break;
      case "O": massiv=titleUrlEnd.push("o");break;
      case "P": massiv=titleUrlEnd.push("p");break;
      case "Q": massiv=titleUrlEnd.push("q");break;
      case "R": massiv=titleUrlEnd.push("r");break;
      case "S": massiv=titleUrlEnd.push("s");break;
      case "T": massiv=titleUrlEnd.push("t");break;
      case "U": massiv=titleUrlEnd.push("u");break;
      case "V": massiv=titleUrlEnd.push("v");break;
      case "W": massiv=titleUrlEnd.push("w");break;
      case "X": massiv=titleUrlEnd.push("x");break;
      case "Y": massiv=titleUrlEnd.push("y");break;
      case "Z": massiv=titleUrlEnd.push("z");break;
      case "à": massiv=titleUrlEnd.push("a");break;
      case "á": massiv=titleUrlEnd.push("b");break;
      case "â": massiv=titleUrlEnd.push("v");break;
      case "ã": massiv=titleUrlEnd.push("g");break;
      case "ä": massiv=titleUrlEnd.push("d");break;
      case "å": massiv=titleUrlEnd.push("e");break;
      case "¸": massiv=titleUrlEnd.push("yo");break;
      case "æ": massiv=titleUrlEnd.push("zh");break;
      case "ç": massiv=titleUrlEnd.push("z");break;
      case "è": massiv=titleUrlEnd.push("i");break;
      case "é": massiv=titleUrlEnd.push("j");break;
      case "ê": massiv=titleUrlEnd.push("k");break;
      case "ë": massiv=titleUrlEnd.push("l");break;
      case "ì": massiv=titleUrlEnd.push("m");break;
      case "í": massiv=titleUrlEnd.push("n");break;
      case "î": massiv=titleUrlEnd.push("o");break;
      case "ï": massiv=titleUrlEnd.push("p");break;
      case "ð": massiv=titleUrlEnd.push("r");break;
      case "ñ": massiv=titleUrlEnd.push("s");break;
      case "ò": massiv=titleUrlEnd.push("t");break;
      case "ó": massiv=titleUrlEnd.push("u");break;
      case "ô": massiv=titleUrlEnd.push("f");break;
      case "õ": massiv=titleUrlEnd.push("h");break;
      case "ö": massiv=titleUrlEnd.push("ts");break;
      case "÷": massiv=titleUrlEnd.push("ch");break;
      case "ø": massiv=titleUrlEnd.push("sh");break;
      case "ù": massiv=titleUrlEnd.push("sch");break;
      case "ü": massiv=titleUrlEnd.push("");break;
      case "ú": massiv=titleUrlEnd.push("");break;
      case "û": massiv=titleUrlEnd.push("y");break;
      case "ý": massiv=titleUrlEnd.push("e");break;
      case "þ": massiv=titleUrlEnd.push("yu");break;
      case "ÿ": massiv=titleUrlEnd.push("ya");break;
      case "À": massiv=titleUrlEnd.push("a");break;
      case "Á": massiv=titleUrlEnd.push("b");break;
      case "Â": massiv=titleUrlEnd.push("v");break;
      case "Ã": massiv=titleUrlEnd.push("g");break;
      case "Ä": massiv=titleUrlEnd.push("d");break;
      case "Å": massiv=titleUrlEnd.push("e");break;
      case "¨": massiv=titleUrlEnd.push("yo");break;
      case "Æ": massiv=titleUrlEnd.push("zh");break;
      case "Ç": massiv=titleUrlEnd.push("z");break;
      case "È": massiv=titleUrlEnd.push("i");break;
      case "É": massiv=titleUrlEnd.push("j");break;
      case "Ê": massiv=titleUrlEnd.push("k");break;
      case "Ë": massiv=titleUrlEnd.push("l");break;
      case "Ì": massiv=titleUrlEnd.push("m");break;
      case "Í": massiv=titleUrlEnd.push("n");break;
      case "Î": massiv=titleUrlEnd.push("o");break;
      case "Ï": massiv=titleUrlEnd.push("p");break;
      case "Ð": massiv=titleUrlEnd.push("r");break;
      case "Ñ": massiv=titleUrlEnd.push("s");break;
      case "Ò": massiv=titleUrlEnd.push("t");break;
      case "Ó": massiv=titleUrlEnd.push("u");break;
      case "Ô": massiv=titleUrlEnd.push("f");break;
      case "Õ": massiv=titleUrlEnd.push("h");break;
      case "Ö": massiv=titleUrlEnd.push("ts");break;
      case "×": massiv=titleUrlEnd.push("ch");break;
      case "Ø": massiv=titleUrlEnd.push("sh");break;
      case "Ù": massiv=titleUrlEnd.push("sch");break;
      case "Ü": massiv=titleUrlEnd.push("");break;
      case "Ú": massiv=titleUrlEnd.push("");break;
      case "Û": massiv=titleUrlEnd.push("y");break;
      case "Ý": massiv=titleUrlEnd.push("e");break;
      case "Þ": massiv=titleUrlEnd.push("yu");break;
      case "ß": massiv=titleUrlEnd.push("ya");break;
    }
    tempTitleUrl = "";
  }
  if (document.formDocOption.Url.value) {
    if (document.formDocOption.Url.value.substr(document.formDocOption.Url.value.length-1,1) == '/') {
      titleUrlEnd = titleUrlEnd.join("") + "/";
      document.formDocOption.Url.value = document.formDocOption.Url.value + titleUrlEnd;
    } else {
      titleUrlEnd = "/" + titleUrlEnd.join("") + "/";
      document.formDocOption.Url.value = document.formDocOption.Url.value + titleUrlEnd;
    }
  } else {
    titleUrlEnd = "/" + titleUrlEnd.join("") + "/";
    document.formDocOption.Url.value = titleUrlEnd;
  }
}

var ie  = document.all  ? 1 : 0;
function selall(kselect) {
  var fmobj = document.kform;
  for (var i=0;i<fmobj.elements.length;i++) {
    var e = fmobj.elements[i];
    if ((e.name != 'allbox') && (e.type=='checkbox') && (!e.disabled)) {
      e.checked = fmobj.allbox.checked;
    }
  }
}

function CheckCheckAll(kselect) {
  var fmobj = document.kform;
  var TotalBoxes = 0;
  var TotalOn = 0;
  for (var i=0;i<fmobj.elements.length;i++) {
    var e = fmobj.elements[i];
    if ((e.name != 'allbox') && (e.type=='checkbox')) {
      TotalBoxes++;
      if (e.checked) {
        TotalOn++;
      }
    }
  }
  if (TotalBoxes==TotalOn) {
    fmobj.allbox.checked=true;
  } else {
    fmobj.allbox.checked=false;
  }
}

function select_read() {
  var fmobj = document.kform;
  for (var i=0;i<fmobj.elements.length;i++) {
    var e = fmobj.elements[i];
    if ((e.type=='hidden') && (e.value == 1) && (! isNaN(e.name) )) {
      eval("fmobj.msgid_" + e.name + ".checked=true;");
    }
  }
}

function desel() {
  var fmobj = document.uactions;
  for (var i=0;i<fmobj.elements.length;i++) {
    var e = fmobj.elements[i];
    if (e.type=='checkbox') {
     e.checked=false;
    }
  }
}