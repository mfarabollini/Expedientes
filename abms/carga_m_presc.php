<? include('../inc/inicio.inc.php'); ?>
<? include('../class/expediente.class.php'); ?>
<?
DesactivarSSL();
CheckPerfiles('SDO');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Sistema de Gesti&oacute;n Parlamentaria</title>
<? include('../inc/efecto_transicion.inc.php'); ?>
<link rel="stylesheet" type="text/css" href="../inc/estilo.css">
<script src="../inc/funciones.js"></script>
<? include('../inc/calendario/calendario.inc.php'); ?>

<script type="text/javascript" src="../inc/autosuggest/js/bsn.AutoSuggest_2.1.3.js" charset="utf-8"></script>
<link rel="stylesheet" href="../inc/autosuggest/css/autosuggest_inquisitor.css" type="text/css" media="screen" charset="utf-8" />

<script language="javascript">

//Create a boolean variable to check for a valid Internet Explorer instance.
var xmlhttp = false;
//Check if we are using IE.
try {
//If the javascript version is greater than 5.
xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
} catch (e) {
//If not, then use the older active x object.
	try {
	//If we are using IE.
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (E) {
		//Else we must be using a non-IE browser.
		xmlhttp = false;
		}
	}
//If we are using a non-IE browser, create a javascript instance of the object.
if (!xmlhttp && typeof XMLHttpRequest != 'undefined') 
	{
		xmlhttp = new XMLHttpRequest();
	}

document.onkeyup = KeyCheck;       

function KeyCheck(e,boton)
{
   var KeyID = (window.event) ? event.keyCode : e.keyCode;
   switch(KeyID)
   {
      case 13:
		if (boton == 'numero')
	      checkExpediente(document.form1.numero.value);
		if (boton == 'faprov')
		  FormaAprov(document.form1.faprov.value);
		if (boton == 'uactual')
		  UbicActual(document.form1.uactual.value);
      break; 
   }
}

function Cancelar() 
{
	//if (window.confirm('¿Desea cancelar?')) {
		window.location = '../general/menu_principal.php'; 
	//}		
}

function checkExpediente (number){
//If there is nothing in the box, run Ajax to populate it.

  var leng = trim(number); 
  if (number.length != 0 && leng != '')
  {
  	if (!isNaN(number))
	 {	
		if (document.getElementById("numero").innerHTML.length == 0)
		{
			serverPage = "check_presc.php?numero="+number;
			var obj = document.getElementById("exp_response");
			obj.innerHTML = "Verificando...";
			xmlhttp.open("GET", serverPage);
			xmlhttp.onreadystatechange = function() 
			{
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
				{	setTimeout("",10000); 
					if (xmlhttp.responseText == 0) //implica expediente válido
					{
						obj.innerHTML = "Expediente Existente y Válido";
						appendOptionLast(number);
					}
					else 
					{
						obj.innerHTML = "Expediente no existe o Expediente con Prescripción cargada";
						document.form1.numero.value = "";						
					}					
				}
			}
			xmlhttp.send(null);
		}
	}
	else
	 {
		alert("Ingrese un nro. de expediente válido");
		return;
	 }
  }
  else
   {
  	alert("Ingrese un nro. de expediente válido");
	return;
   }		 
}

function FormaAprov(param)
{
	//verifico que tipo de parametro llega (agregar o buscar)
	var url='checkForma.php?forma='+param;
	document.form1.idaprobacion.value = "";
	var leng = trim(param);
	var insert;

  if (param.length != 0 && leng != '')
	{
		serverPage = url;
		var obj = document.getElementById("exp_response");
		obj.innerHTML = "Verificando Forma ...";
		xmlhttp.open("GET", serverPage);
		xmlhttp.onreadystatechange = function() 
		{
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
			{	setTimeout("",10000); 
				//alert(xmlhttp.responseText);
				if (xmlhttp.responseText != 0) 
				{
					obj.innerHTML = "Forma de Aprobación correcta";
					document.form1.idaprobacion.value = xmlhttp.responseText; 			
				}
				else if (xmlhttp.responseText == 0)
				{
					obj.innerHTML = "Fallo en la búsqueda o inserción, intente nuevamente.";
					document.form1.idaprobacion.value = '';					
				}
				else
				{
				
				}					 
			}
		}
		xmlhttp.send(null);
	}
	else
	{
		alert("Ingrese forma de aprobación válida");
		return;
	}
}

function UbicActual(param)
{
	//verifico que tipo de parametro llega (agregar o buscar)
	var url='checkUbic.php?forma='+param;
	document.form1.destino_id.value = "";
	var leng = trim(param);
	var insert;

  if (param.length != 0 && leng != '')
	{
		serverPage = url;
		var obj = document.getElementById("exp_response");
		obj.innerHTML = "Verificando Ubicación ...";
		xmlhttp.open("GET", serverPage);
		xmlhttp.onreadystatechange = function() 
		{
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
			{	setTimeout("",10000); 
				//alert(xmlhttp.responseText);
				if (xmlhttp.responseText != 0) 
				{
					obj.innerHTML = "Ubicación actual correcta";
					document.form1.destino_id.value = xmlhttp.responseText; 			
				}
				else if (xmlhttp.responseText == 0)
				{
					obj.innerHTML = "Fallo en la búsqueda o inserción, intente nuevamente.";
					document.form1.destino_id.value = '';					
				}
				else
				{
				
				} 
			}
		} 
		xmlhttp.send(null);
	}
	else
	{
		alert("Ingrese ubicación actual válida");
		return;
	}
	//alert(document.form1.destino_id.value);
}

function appendOptionLast(num)
{
		  var elOptNew = document.createElement('option');
		  var count = 0;
		  elOptNew.text = num;
		  elOptNew.value = num;
		  var elSel = document.getElementById('selectX');
		  try {
		  	for (var i = 0; i < elSel.length; i++) 
				{
					if (num == elSel.options[i].value)
						{
							count++;
							break;
						}
				}
			if (count == 0)
				{
					elSel.add(elOptNew, null); // standards compliant; doesn't work in IE
					var replaceTextArray = new Array(elSel.length-1);
					var replaceValueArray = new Array(elSel.length-1);
					for (var i = 0; i < elSel.length; i++) 
					{
						replaceTextArray[i] = elSel.options[i].text;
						replaceTextArray[i-1] = elSel.options[i].text;
						replaceValueArray[i] = elSel.options[i].value;
						replaceValueArray[i-1] = elSel.options[i].value;
					}
				}
				else
				{
					alert("El código ingresado ya existe");
				}
			}
	  	catch(ex) 
		  {
		  	for (var i = 0; i < elSel.length; i++) 
			{
				if (num == elSel.options[i].value)
					{
						count++;
						break;
					}
			}
			if (count == 0)
				{
					elSel.add(elOptNew); // IE only
					var replaceTextArray = new Array(elSel.length-1);
					var replaceValueArray = new Array(elSel.length-1);
					for (var i = 0; i < elSel.length; i++) 
					{
						replaceTextArray[i] = elSel.options[i].text;
						replaceTextArray[i-1] = elSel.options[i].text;
						replaceValueArray[i] = elSel.options[i].value;
						replaceValueArray[i-1] = elSel.options[i].value;
					}
				}
				else
				{
					alert("El código ingresado ya existe");
				}
		}
   document.form1.numero.value = "";
}

function removeOptionSelected()
{
  
  var elSel = document.getElementById('selectX');
  var i;
  if (elSel.length > 0)
  { 
  	  var selected = elSel.selectedIndex;
      if (selected == -1)
	  {
	  	   alert("Debe seleccionar un item para borrar.");
	  }
	  else
	  {
		  for (i = elSel.length - 1; i>=0; i--) {
			if (elSel.options[i].selected) {
			  elSel.remove(i);
			}
		  }
	  }
  }
  else
  	alert("No hay items en la lista para ser borrados");
}

	function ValidarGrabado()
	{
		var errores = "";
		
		var strList = '';
		var lList = document.getElementById('selectX');
		var dest = document.getElementById('destino_id').value;
		var aproba = document.getElementById('idaprobacion').value;
		var taprob = document.getElementById('tipaprov').value;		
		//alert(lList.length);
		if (lList.length > 0)
		{
			for (var i = 0;i < lList.length - 1;i++)
			{
				strList += lList.options[i].value + ';';
			}
			strList += lList.options[lList.length - 1].value;
			//document.form1.arreglo.value = strList;
			//document.form1.len.value = lList.length;
			//alert(strList);
		}
		
  		//alert(document.getElementById('destino_id').value);
		//alert(document.getElementById('faprov').value);
		//alert(document.getElementById('tipaprov').value);
		//alert(document.getElementById('numero').value);
		
		with (document.form1)
		{
			//if (document.getElementById('numero').value == "") {errores += "\nDebe ingresar el número de expediente.";}
			if (fechaapro.value == "") {errores += "\nDebe ingresar la fecha.";}
			if (lList.length <= 0) {errores += "\nDebe ingresar al menos un expediente a mover.";}
			if (document.getElementById('destino_id').value == 0) {errores += "\nDebe ingresar una Ubicación Actual.";}
			if (document.getElementById('faprov').value == 0) {errores += "\nDebe ingresar una forma de aprobación.";}
			if (document.getElementById('tipaprov').value == 0) {errores += "\nDebe ingresar un tipo de aprobación.";}
			
			//si lleno todos los campos
			if (errores == "") 
			{	//alert(fechaapro.value);
				serverPage = "makeSave.php?accion=grabar&fecha=" + fechaapro.value + "&lista=" + strList + "&len=" + lList.length +"&destino=" + dest + "&aprob=" + aproba + "&taprob=" + taprob;
				//alert(serverPage);
				var selected = lList.selectedIndex;
				var obj = document.getElementById("exp_response");
				obj.innerHTML = "Moviendo...";
				xmlhttp.open("GET", serverPage);
				xmlhttp.onreadystatechange = function() 
				{
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
					{	setTimeout("",10000);
					//alert(xmlhttp.responseText);
						if (xmlhttp.responseText == 0) 
						{
							obj.innerHTML = "Movimiento exitoso";						
						}
						else
						{
							obj.innerHTML = "No se pudo realizar la operación";
							//document.form1.numero.value = "";						
						}
					}
				} 
				xmlhttp.send(null);		
				
			}
			else
			{
				alert("Tiene los siguientes errores:\n"+errores);
				return false;
			}
		   document.getElementById('destino_id').value = "";
		   document.getElementById('faprov').value = "";
		   document.getElementById('tipaprov').value = "";
		   document.getElementById('uactual').value = "";			
		   document.form1.numero.value = "";
		   for (i = lList.length - 1; i>=0; i--) {
			  lList.remove(i);
			}		   
		}
	}

</script>

    <!--<link rel="stylesheet" type="text/css" href="../resources/css/ext-all.css" />-->
    <!--<link rel="stylesheet" type="text/css" href="../resources/css/xtheme-gray.css" />-->

    <!-- GC -->
 	<!-- LIBS -->
 	<!--<script type="text/javascript" src="../adapter/ext/ext-base.js"></script>-->
 	<!-- ENDLIBS -->

    <!--<script type="text/javascript" src="../js/ext-all.js"></script>

    <script type="text/javascript" src="../js/states.js"></script>
    <script type="text/javascript" src="../js/combos.js"></script>
    <link rel="stylesheet" type="text/css" href="../resources/css/combos.css" />-->

    <!-- Common Styles for the examples -->
   <!--<link rel="stylesheet" type="text/css" href="../shared/examples.css" />
    <style type="text/css">
        p { width:650px; }
    </style>--> 
    
</head>

<body <?=$onload?>>
<!--<script type="text/javascript" src="../shared/examples.js"></script>-->
<form action="" method="post" name="form1" id="form1">

<div align="center">
<table width="800" height="500" border="0" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF"><tr>
        <td height="107" align="left" valign="middle" class="fondo_encabezado">
        <table width="800" height="107" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="130">&nbsp;</td>
            <td width="670" align="left" valign="middle" class="texto_encabezado">Sistema de Gesti&oacute;n Parlamentaria</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
        </td>
  </tr>
<tr>
    <td height="440" align="center" valign="top" class="contenido"><br />
      <br />
      <table width="440" border="0" cellspacing="0" cellpadding="0">
      <tr id="cont_solapa1">
        <td width="440" align="center" valign="middle">
        <table width="440" border="0" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
          <tr>
            <td height="25" colspan="2" align="center" valign="middle" class="header2">Carga Masiva Prescripci&oacute;n</td>
          </tr>
          <tr>
            <td width="35%" height="24" align="left" class="td1">N&uacute;mero de expediente</td>
            <td width="65%" height="24" align="left" class="td2">
              <input id="numero" name="numero" type="text" value="" maxlength="100" size="20" onkeypress="KeyCheck(event,'numero');" style="height:18px" />            
            </td>
          </tr>
          <tr>
            <td align="left" class="td1">Fecha Aprobaci&oacute;n</td>
            <td align="left" class="td2"><?=CalendarioCreaInput('fechaapro', AhoraFecha(), '')?></td>
	      </tr>
          <tr>
          	<td align="left" class="td1">Forma de Aprobaci&oacute;n</td>
            <td align="left" class="td2">
               <input type="text" id="faprov" size"20" maxlength="50" style="height:18px" onkeypress="KeyCheck(event,'faprov');"/>
               <input type="button" id="addfaprov" name="addfaprov" value="Buscar/Agregar" onclick="FormaAprov(document.form1.faprov.value);"  height="18" />
               <input type="hidden" id="idaprobacion" name="idaprobacion" value=""/>
            </td>
          </tr>
          <tr>
          	<td align="left" class="td1">Tipo Aprobaci&oacute;n</td>
			<td align="left" class="td2"><input id="tipaprov" name="tipaprov" type="text" value="" maxlength="40" style="height:18px"/>            
          </tr>
          <tr>
          	<td align="left" class="td1">Ubicaci&oacute;n Actual</td>
            <td align="left" class="td2">
				 <input type="text" id="uactual" name ="uactual" maxlength="50" style="height:18px" value="Archivo" onkeypress="KeyCheck(event,'uactual');"/>
               	 <input type="button" id="adduactual" name="adduactual" value="Buscar/Agregar" onclick="UbicActual(document.form1.uactual.value);"  height="18" />
                 <input type="hidden" id="destino_id" name="destino_id" value="1"/>                 
            </td>
          </tr>     
          <tr>
          <td colspan="2">
              <table width="440" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
                  <tr>
                    <td align="center" width="70%" class="td1">
                        <select id="selectX" size="10" multiple="multiple" style="width:100%" name="selectX">
                        </select>
                    </td>
                    <td align="center" width="30%" class="td2">
                        <input type="button" size="30" name="agregar_exp" id="agregar_exp" value="   Agregar Expediente    "  onClick="checkExpediente(document.form1.numero.value)" />
                        <input type="button" size="30" name="remove_exp" id="remove_exp" value="     Borrar Expediente     " onClick="removeOptionSelected();" />
                    </td>
                  </tr>
              </table>
          </td>
          </tr>             
        </table>
        </td>
        </tr>
    </table>
      <br />
      <br />
      		  <div id="exp_response"></div>
    <br />
    <br />
<div align="center" class="td1" style="width:730px; height:20px">
  <input name="btnGrabar" type="button" id="btnGrabar" value="Grabar" style="width:100px" onClick="javascript: ValidarGrabado();">
  &nbsp;&nbsp;&nbsp;&nbsp;
  <input name="btnCancelar" type="button" id="btnCancelar" value="Salir" style="width:100px" onClick="javascript: Cancelar();">
</div>
    </td>
  </tr>
</table>
</div>

</div>
</div>
<input type="hidden" name="hdnAgregados" id="hdnAgregados" value="" />
<input type="hidden" name="accion_anterior" id="accion_anterior" value="<?=$accion?>" />
</form>
</body>
</html>

