<?php
include('../inc/inicio.inc.php');

if($_POST['accion_lista'] != '') {
	
	switch ($_POST['accion_lista']) {
		case 'resetear':
			resetarLista();
		break;
	}
	
}

$sql = "SELECT expediente FROM lista_preferencia_temp";
$rs = $conn->Execute($sql);

$lista_expedientes = Array();

if(count($rs)>0 && !$rs->EOF) {
	while(!$rs->EOF) {
		$lista_expedientes[] = $rs->fields['expediente'];
		$rs->MoveNext();
	}
}


// Declaracion de funciones

function resetarLista() {
	global $conn;
	$sql = "DELETE FROM lista_preferencia_temp";
	$conn->Execute($sql);
}


?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Sistema de Gesti&oacute;n Parlamentaria</title>
<link rel="stylesheet" type="text/css" href="../inc/estilo.css">
<? include('../inc/efecto_transicion.inc.php'); ?>
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

function KeyCheck(e)
{
   var KeyID = (window.event) ? event.keyCode : e.keyCode;
   switch(KeyID)
   {
      case 13:
      checkExpediente(document.form1.numero.value);
      break; 
   }
}

	function Crear()
	{
		with (document.form1)
		{ 
			//verfico que se haya ingresodo la fecha
			if (xfec_creacion.value == "")
				{
					alert("Ingrese la fecha del Reporte");
				}
				else
				{
					//pregunto si se cargo el nro de orden
					/*if (ordn.value == "" || isNaN(ordn.value))
					{
						alert("Ingrese un nmero de orden vlido");
					}
					else
					{*/
						//alert(xfec_creacion.value);		
						var strList = '';
						var lList = document.getElementById('selectX');
						if (lList.length > 1)
						{
							for (var i = 0;i < lList.length - 1;i++)
							{
								strList += lList.options[i].value + ';';
							}
							strList += lList.options[lList.length - 1].value;
							document.form1.arreglo.value = strList;
							document.form1.len.value = lList.length;
							action = 'reporte_pre_exec.php?accion=crear';
							/*&arreglo=' + strList + '&len=' + lList.length + '&fec=' + xfec_creacion.value + '&ord=' + ordn.value + '&ordT=' + lOrdT.value;*/
							target = '_blank';
							submit();
						}
						else
						{
							if (lList.length == 1)
								{
									strList = lList.options[lList.length - 1].value;
									document.form1.arreglo.value = strList;
									document.form1.len.value = lList.length;
									action = 'reporte_pre_exec.php?accion=crear';/*&arreglo=' + strList + '&len=' + lList.length + '&fec=' + xfec_creacion.value + '&ord=' + ordn.value + '&ordT=' + lOrdT.value;*/
									target = '_blank';
									submit();
								}
							else
								alert("No hay expedientes para procesar");
						}
					//}
				}
		}
	}
	
//aca comienza lo nuevo
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
				_i = i;
				var obj = document.getElementById("exp_response");
				obj.innerHTML = "Eliminando expediente " + elSel.options[i].value + "...";
				xmlhttp.open("GET", "borrarExpPrefTemp.php?numero="+elSel.options[i].value);
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						if (xmlhttp.responseText == 0) {
							obj.innerHTML = "Expedientes(s) eliminado(s)";
			  				elSel.remove(_i);
						} else if (xmlhttp.responseText == 1) {
							obj.innerHTML = "No existe(n) expediente(s)";
						} else {
							obj.innerHTML = "Error al eliminar expediente";
						}
					}
				}
				xmlhttp.send(null);
			}
		  }
	  }
  }
  else
  	alert("No hay items en la lista para ser borrados");
}

function trim(str)
{
    if(!str || typeof str != 'string')
        return null;

    return str.replace(/^[\s]+/,'').replace(/[\s]+$/,'').replace(/[\s]{2,}/,' ');
}
function refrescarLista(result){
	var selectObj = document.getElementById('selectX');
	selectObj.options.length = 0;
	selectObj.selectedIndex = -1;

	for (var i = 1; i <= result.cant; i++) 
	{
		selectObj.options[i-1] = new Option(result[i], result[i]);

	}
}

function actualizarLista(){
	serverPage = "checkExp.php?accion=actualizar";
	var obj = document.getElementById("exp_response");
	obj.innerHTML = "Actualizando...";
	xmlhttp.open("GET", serverPage);
	xmlhttp.onreadystatechange = function() 
	{
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
		{	setTimeout("",10000); 
			result = JSON.parse(xmlhttp.responseText);
			refrescarLista(result);
		}
	};
	xmlhttp.send(null);
	obj.innerHTML = "";
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
			serverPage = "checkExp.php?numero="+number;
			var obj = document.getElementById("exp_response");
			obj.innerHTML = "Verificando...";
			xmlhttp.open("GET", serverPage);
			xmlhttp.onreadystatechange = function() 
			{
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
				{	setTimeout("",10000); 
					if (xmlhttp.responseText == 0)
					{
						obj.innerHTML = "Expediente Existente y Legislativo";
						appendOptionLast(number);
					}
					else
					{
						if (xmlhttp.responseText == 1)
						{
							obj.innerHTML = "Expediente no Legislativo";
							document.form1.numero.value = "";						
						}
						else if (xmlhttp.responseText == 2)
						{
							obj.innerHTML = "Expediente en MESAS DE ENTRADA";
							document.form1.numero.value = "";
						}
						else if (xmlhttp.responseText == 3)
						{
							obj.innerHTML = "Expediente ya Aprobado";
							document.form1.numero.value = "";
						}
						else 
						{
							obj.innerHTML = "Expediente no Existente";
							document.form1.numero.value = "";
						}
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
<?php
//function MoveUp(listField)
//{
//   if ( listField.length == -1) {  // If the list is empty
//      alert("No hay valores para mover.");
//   } else {
//      var selected = listField.selectedIndex;
//      if (selected == -1) {
//         alert("Debe seleccionar un item para mover.");
//      } else {  // Something is selected 
//         if ( listField.length == 0 ) {  // If there's only one in the list
//            alert("Hay un solo item, no se puede mover.");
//         } else {  // There's more than one in the list, rearrange the list order
//            if ( selected == 0 ) {
//               alert("El primer item no se puede mover.");
//            } else {
//               // Get the text/value of the one directly above the hightlighted entry as
//               // well as the highlighted entry; then flip them
//               var moveText1 = listField[selected-1].text;
//               var moveText2 = listField[selected].text;
//               var moveValue1 = listField[selected-1].value;
//               var moveValue2 = listField[selected].value;
//               listField[selected].text = moveText1;
//               listField[selected].value = moveValue1;
//               listField[selected-1].text = moveText2;
//               listField[selected-1].value = moveValue2;
//               listField.selectedIndex = selected-1; // Select the one that was selected before
//            }  // Ends the check for selecting one which can be moved
//         }  // Ends the check for there only being one in the list to begin with
//      }  // Ends the check for there being something selected
//   }	
//}
//
//function MoveDn(listField)
//{
//   if ( listField.length == -1) {  // If the list is empty
//      alert("No hay valores para mover.");
//   } else {
//      var selected = listField.selectedIndex;
//      if (selected == -1) {
//         alert("Debe seleccionar un item para mover.");
//      } else {  // Something is selected 
//         if ( listField.length == 0 ) {  // If there's only one in the list
//            alert("Hay un solo item, no se puede mover.");
//         } else {  // There's more than one in the list, rearrange the list order
//            if ( selected == (listField.length -1) ) {
//               alert("El ltimo item no se puede mover.");
//            } else {
//               // Get the text/value of the one directly above the hightlighted entry as
//               // well as the highlighted entry; then flip them
//               var moveText1 = listField[selected+1].text;
//               var moveText2 = listField[selected].text;
//               var moveValue1 = listField[selected+1].value;
//               var moveValue2 = listField[selected].value;
//               listField[selected].text = moveText1;
//               listField[selected].value = moveValue1;
//               listField[selected+1].text = moveText2;
//               listField[selected+1].value = moveValue2;
//               listField.selectedIndex = selected+1; // Select the one that was selected before
//			   
//            }  // Ends the check for selecting one which can be moved
//         }  // Ends the check for there only being one in the list to begin with
//      }  // Ends the check for there being something selected
//   }
//}

	//aca termina lo nuevo

//function guardarLista() { 
//	
//	with (document.form1) {
//		action = '';
//		document.form1.accion_lista.value = 'guardar';
//				
//		var strList = '';
//		var lList = document.getElementById('selectX');
//		if (lList.length > 1) {
//			for (var i = 0;i < lList.length - 1;i++) {
//				strList += lList.options[i].value + ';';
//			}
//			strList += lList.options[lList.length - 1].value;
//			document.form1.arreglo.value = strList;
//			document.form1.len.value = lList.length;
//		} else {
//			if (lList.length == 1) {
//				strList = lList.options[lList.length - 1].value;
//				document.form1.arreglo.value = strList;
//				document.form1.len.value = lList.length;
//			}
//		}
//		submit();
//	}	
//}
 ?>
 
 function resetaerrLista() {
	with (document.form1) {		
		action = '';
		document.form1.accion_lista.value = 'resetear';
		submit();
	}	
}
 
	
</script>
</head>
<body>
<form action="reporte_pre_exec.php?accion=crear" method="post" name="form1" id="form1">
<div align="center">
<table width="800" height="500" border="0" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF">
<tr>
        <td height="107" align="left" valign="middle" class="fondo_encabezado">
        <table width="800" height="107" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="130">&nbsp;</td>
            <td width="670" align="left" valign="middle" class="texto_encabezado">Reporte Preferencias</td>
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
        <table width="740" border="0" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
          <tr>
            <td height="25" colspan="6" align="center" valign="middle" class="header2">Reporte Preferencias</td>
          </tr>
          <tr>
            <td width="130" height="24" align="left" class="td1">Nro. Expediente</td>
			<td width="65" height="24" align="left" class="td2">
				<input type="text" name="numero" id="numero" maxlength="20" width="60" onkeypress="KeyCheck(event);"/>
			</td>
            <td width="143" align="left" class="td1">Fecha de creaci&oacute;n</td>
            <td width="150" align="left" class="td2"><?=CalendarioCreaInput('fec_creacion', $expediente->fec_creacion, '')?></td>
          </tr>
          <tr>
            <td colspan="6" align="center" class="td2">
				<table width="100%">
					<tr>
						<td width="10%">&nbsp;</td>
						<td width="50%" align="center" >
							<select id="selectX" size="10" multiple="multiple" style="width:100%" name="selectX">
							<?php
								foreach($lista_expedientes as $expediente) {
									echo '<option value="' . $expediente .'" >' . $expediente .'</option>';
								}
							?>
							</select>
						</td>
						<td width="15%" align="left">
							<table border="0" cellpadding="0" cellspacing="0" width="100%"> 
							<tr>
								<td width="50%"><br /></td>
							</tr>
							<tr>
								<td width="50%"><br /></td>
							</tr>
							<tr>
								<td width="50%">&nbsp;</td>
								<td align="center" width="50%" height="10">
									<input type="button" size="30" name="agregar_exp" id="agregar_exp" value="   Agregar Expediente    " onClick="checkExpediente(document.form1.numero.value);"/>
								</td>
							</tr>
							<tr>
								<td width="50%">&nbsp;</td>
								<td align="center" width="50%" height="10">
									<input type="button" size="30" name="remove_exp" id="remove_exp" value="     Borrar Expediente     " onClick="removeOptionSelected();" />
								</td>
							</tr>
							<tr>
								<td width="50%">&nbsp;</td>
								<td align="center" width="50%" height="10">
									<input type="button" size="30" name="update" id="update" value="             Actualizar            " onClick="actualizarLista();" />
								</td>
							</tr>							
							<?php /*
							<tr>
								<td width="50%">&nbsp;</td>
								<td align="center" width="50%" height="10">
									<input type="button" size="30" name="moveup_exp" id="moveup_exp" value="      Mover Exp. Arriba      " onClick="MoveUp(document.getElementById('selectX'));" />
								</td>
							</tr>
							<tr>
								<td width="50%">&nbsp;</td>
								<td align="center" width="50%" height="10">
									<input type="button" size="30" name="movedown_exp" id="movedown_exp" value="      Mover Exp. Abajo      " onClick="MoveDn(document.getElementById('selectX'));" />
								</td>
							</tr>
							*/?>
							</table>
						</td>
						<td width="15%">&nbsp;</td>
					</tr>
				</table>
	        </td>
           </tr>
		   <tr>
			  <td class="td2" colspan="4" align="right" style="padding: 5px;" >
			  		<input type="hidden" name="accion_lista" value="" /> 
		  			<input type="button" name="resetear_lista" value="Resetear Lista" onclick="resetaerrLista()" />
					<!-- input type="button" name="guardar_lista" value="Guardar Lista" onclick="guardarLista()" /-->
			  </td>
		   </tr>
        </table>
      <br />
      <br />
	  <div id="exp_response"></div>
      <br />
      <br />
      <input type="button" name="btnCrear" id="btnCrear" value="Crear Informe" style="width:150px" onclick="Crear();" />
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="button" name="btnVolver" id="btnVolver" value="Volver" style="width:150px" onclick="window.location = 'menu_principal.php';" />  
	  <input type="hidden" name="arreglo" id="arreglo" value=""/>
	  <input type="hidden" name="len" id="len" values="" />
	  <input type="hidden" name="resConsul" id="resConsul" value="" />

      
      </td>
  </tr>
</table>
</div>
<script type="text/javascript">
	var options1 = {
		script:"../inc/autosuggest/data_destino.php?json=true&",
		varname:"input",
		json:true,
		shownoresults:false,
		maxresults:10,
		minchars: 1,
		timeout:99999,
		cache: false,
		callback: function (obj) { document.getElementById('numero').value = obj.id; }
	};
	var as_json1 = new bsn.AutoSuggest('numero', options1);

</script>

</form>
<div style="display: none">
<form action="reporte_pre_exec.php" method="post" name="form_pdf" id="form_pdf" target="_blank">
</form>
</div>
</body>
</html>
