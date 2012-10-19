
	function nuevoAjax()
	{
		var xmlHttp=null;
		try
		{
			// Firefox, Opera 8.0+, Safari
			xmlHttp=new XMLHttpRequest();
		}
		catch (e)
		{
			//Internet Explorer
			try
			{
				xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
			}
			catch (e)
			{
				xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
		}
		
		return xmlHttp;
	}		

	//-------------------------------------------------------------------------------


	function VentanaModal(url, w, h) {
		if (window.showModalDialog) {
			var caracteristicas = "dialogWidth="+ w +"px;dialogHeight="+ h +"px;center=yes;help=no;resizable=no;status=no";
			window.showModalDialog(url, "modal", caracteristicas);
		  }
		  else
		  {
			var caracteristicas = "height="+ h +", width="+ w +", toolbar=0, directories=0, status=0, linemenubar=0, modal=yes, dialog=yes";
			var ancho = (screen.width/2)-(w/2);
			var alto = (screen.height/2)-(h/2);

			NuevaVentana = window.open(url,'modal',caracteristicas);
			NuevaVentana.moveTo(ancho,alto);
			NuevaVentana.focus();
			
		  }		
	}

	//-------------------------------------------------------------------------------

	function RedondeoDecimales(pValor) {
		var obj = document.getElementById('txt');
		var vec_num;
		var vResult = 0;
		var parte_dec = '';
		
		vec_num = pValor.split('.');
		if (vec_num.length == 1) {vResult = vec_num[0];}

		parte_dec = (vec_num[1]+'000000000000').substring(0, 2);
		if (parte_dec > 50) {vResult = parseInt(vec_num[0], 10) + 1;}
		if (parte_dec <= 50) {vResult = vec_num[0]+'.50';}
		
		return vResult;
	}
	
	//-------------------------------------------------------------------------------

	function numFormat(p_valor, dec, miles)	{
		var num = p_valor, signo=3, expr;
		var cad = ""+p_valor;
		var ceros = "", pos, pdec, i;
		for (i=0; i < dec; i++)
		ceros += '0';
		pos = cad.indexOf('.')
		if (pos < 0)
			cad = cad+"."+ceros;
		else {
			pdec = cad.length - pos -1;
			if (pdec <= dec) {
				for (i=0; i< (dec-pdec); i++)
				cad += '0';
			}
			else {
				num = num*Math.pow(10, dec);
				num = Math.round(num);
				num = num/Math.pow(10, dec);
				cad = new String(num);
			}
		}
		pos = cad.indexOf('.')
		if (pos < 0) pos = cad.lentgh
		if (cad.substr(0,1)=='-' || cad.substr(0,1) == '+')
			signo = 4;
		if (miles && pos > signo)
		do {
			expr = /([+-]?\d)(\d{3}[\.\,]\d*)/
			cad.match(expr)
			cad=cad.replace(expr, RegExp.$1+','+RegExp.$2)
		}
		while (cad.indexOf(',') > signo)
			if (dec<0) cad = cad.replace(/\./,'')
		return cad;
	}
	
	//-------------------------------------------------------------------------------
	
	var nav4 = window.Event ? true : false;
	function acceptNum(evt){
		// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57
		var key = nav4 ? evt.which : evt.keyCode;
		return (key <= 13 || (key >= 48 && key <= 57) || (key >= 96 && key <= 105) || key == 46);
	}
	
	//-------------------------------------------------------------------------------
	
	function ValorNulo(obj) {
		if (obj.value==null || obj.value=='' || isNaN(obj.value)) {
			obj.value = '0';
		}
	}
	
	//-------------------------------------------------------------------------------
	
	function sortSelect(obj){
		var o = new Array();
		for (var i=0; i<obj.options.length; i++){
			o[o.length] = new Option(obj.options[i].text, obj.options[i].value, obj.options[i].defaultSelected, obj.options[i].selected);
		}
		o = o.sort(
			function(a,b){ 
				if ((a.text+"") < (b.text+"")) { return -1; }
				if ((a.text+"") > (b.text+"")) { return 1; }
				return 0;
			} 
		);
	
		for (var i=0; i<o.length; i++){
			obj.options[i] = new Option(o[i].text, o[i].value, o[i].defaultSelected, o[i].selected);
		}
	}

	//-------------------------------------------------------------------------------
	
	//funciones de validacion de fecha
	function esDigito(sChr){
		var sCod = sChr.charCodeAt(0);
		return ((sCod > 47) && (sCod < 58));
	}
	
	function valSep(oTxt){
		var bOk = false;
		bOk = bOk || ((oTxt.value.charAt(2) == "-") && (oTxt.value.charAt(5) == "-"));
		bOk = bOk || ((oTxt.value.charAt(2) == "/") && (oTxt.value.charAt(5) == "/"));
		return bOk;
	}
	
	function finMes(oTxt){
		var nMes = parseInt(oTxt.value.substr(3, 2), 10);
		var nRes = 0;
		switch (nMes){
			case 1: nRes = 31; break;
			case 2: nRes = 29; break;
			case 3: nRes = 31; break;
			case 4: nRes = 30; break;
			case 5: nRes = 31; break;
			case 6: nRes = 30; break;
			case 7: nRes = 31; break;
			case 8: nRes = 31; break;
			case 9: nRes = 30; break;
			case 10: nRes = 31; break;
			case 11: nRes = 30; break;
			case 12: nRes = 31; break;
		}
		return nRes;
	}
	
	function valDia(oTxt){
		var bOk = false;
		var nDia = parseInt(oTxt.value.substr(0, 2), 10);
		bOk = bOk || ((nDia >= 1) && (nDia <= finMes(oTxt)));
		return bOk;
	}
		
	function valMes(oTxt){
		var bOk = false;
		var nMes = parseInt(oTxt.value.substr(3, 2), 10);
		bOk = bOk || ((nMes >= 1) && (nMes <= 12));
		return bOk;
	}
	
	function valAno(oTxt){
		var bOk = true;
		var nAno = oTxt.value.substr(6);
		bOk = bOk && (nAno.length == 4);
		if (bOk){
			for (var i = 0; i < nAno.length; i++){
				bOk = bOk && esDigito(nAno.charAt(i));
			}
		}
		return bOk;
	}
	
	function valFecha(oTxt){
		var bOk = true;
		var vecTMP;
		var sDia, sMes = '';


		oTxt.value = replaceAll('-', '/', oTxt.value);
		oTxt.value = replaceAll('.', '/', oTxt.value);
		oTxt.value = replaceAll(' ', '/', oTxt.value);
		
		//si puso año mes y dia formateo bien la fecha
		vecTMP = oTxt.value.split('/');
		if (vecTMP.length == 3) {
			sDia = '0'+vecTMP[0];
			sDia = sDia.substring(sDia.length-2, sDia.length);
	
			sMes = '0'+vecTMP[1];
			sMes = sMes.substring(sMes.length-2, sMes.length);
			
			oTxt.value = sDia+'/'+sMes+'/'+vecTMP[2];
		}
		
		if (oTxt.value != ""){
			bOk = bOk && (valAno(oTxt));
			bOk = bOk && (valMes(oTxt));
			bOk = bOk && (valDia(oTxt));
			bOk = bOk && (valSep(oTxt));
			if (!bOk){
				alert("Fecha inválida");
				//oTxt.value = "";
				oTxt.focus();
			}
		}
	}

	//FIN - funciones de validacion de fecha
	//-------------------------------------------------------------------------------

	
	//Cambia todas las cadenas sCadenaBuscada por sCadena_a_remplazar en sCadenaOrigen
	function replaceAll(sCadenaBuscada, sCadena_a_remplazar, sCadenaOrigen) {
		var idx = sCadenaOrigen.indexOf(sCadenaBuscada);
	
	
		while ( idx > -1 ) {
			sCadenaOrigen = sCadenaOrigen.replace(sCadenaBuscada, sCadena_a_remplazar);
			idx = sCadenaOrigen.indexOf(sCadenaBuscada);
		}
	
		return sCadenaOrigen;
	}
	
	
	// LTrim(string) : Returns a copy of a string without leading spaces.
	function ltrim(str)
	{
	   var whitespace = new String(" \t\n\r");
	   var s = new String(str);
	   if (whitespace.indexOf (s.charAt(0)) != -1) {
		  var j=0, i = s.length;
		  while (j < i && whitespace.indexOf(s.charAt(j)) != -1)
			 j++;
		  s = s.substring(j, i);
	   }
	   return s;
	}
	
	//RTrim(string) : Returns a copy of a string without trailing spaces.
	function rtrim(str)
	{
	   var whitespace = new String(" \t\n\r");
	   var s = new String(str);
	   if (whitespace.indexOf(s.charAt(s.length-1)) != -1) {
		  var i = s.length - 1;       // Get length of string
		  while (i >= 0 && whitespace.indexOf(s.charAt(i)) != -1)
			 i--;
		  s = s.substring(0, i+1);
	   }
	   return s;
	}
	
	// Trim(string) : Returns a copy of a string without leading or trailing spaces
	function trim(str) {
	   return rtrim(ltrim(str));
	}
	
	//-------------------------------------------------------------------------------
