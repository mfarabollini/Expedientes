<? include('../inc/inicio.inc.php'); ?>
<? include('../class/expediente.class.php'); ?>
<?

DesactivarSSL();
CheckPerfiles('SDO');


switch ($_GET['accion']) {

	case 'generar':

		$_SESSION['PDF-Data'] = $_POST;
		?>
		<script>
			window.open('makePDF_Remito.php');
		</script>
		<?php
	break;
}

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
			serverPage = "checkExpe.php?numero="+number;
			var obj = document.getElementById("exp_response");
			obj.innerHTML = "Verificando...";
			xmlhttp.open("GET", serverPage);
			xmlhttp.onreadystatechange = function() 
			{
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
				{	setTimeout("",10000); 
					if (xmlhttp.responseText == 0)
					{
						obj.innerHTML = "Expediente Existente";
						appendOptionLast(number);
					}
					else
					{
						obj.innerHTML = "Expediente no Existente";
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

function appendOptionLast(num)
{
		var numero = document.getElementById('numero')
		numero.value = "";
		numero.focus();
		var seleccionados = document.getElementsByName('seleccionados[]');
		
		for(i=0 ; i < seleccionados.length ; i ++) {
			if(seleccionados[i].value == num ) {
				alert("El código ingresado ya existe");
				return;
			}
		}

		  var trNew = document.createElement('tr');
		  var td1 = document.createElement('td');
		  td1.className = 'td2';
		  td1.align = 'center';
		  
		  var td2 = td1.cloneNode(true);
		  var td3 = td1.cloneNode(true);

		  var checkbox = document.createElement('input');
		  checkbox.name = "seleccionados[]";
		  checkbox.type = "checkbox";
		  checkbox.value = num;

		  var foja =  document.createElement('input');
		  foja.name = "foja[]";
		  
		  td1.appendChild(checkbox);
		  td2.innerHTML = num;
		  td3.appendChild(foja);
		  
		  trNew.appendChild(td1);
		  trNew.appendChild(td2);
		  trNew.appendChild(td3);
		  
		  var listado = document.getElementById('listado_expedientes').getElementsByTagName('tbody');
		  listado[0].appendChild(trNew);
}

function removeOptionSelected()
{
		var seleccionados = document.getElementsByName('seleccionados[]');
		var removeNode = new Array();

		var i=0;
		while(i < seleccionados.length ) {
			if(seleccionados[i].checked) {
				removeNode[i] = seleccionados[i].parentNode.parentNode;
				removeNode[i].parentNode.removeChild(removeNode[i]);
			} else {
				i++;
			}
		}
}

function ValidarGrabado()
	{
		var errores = "";
		var seleccionados = document.getElementsByName('seleccionados[]');
		var dest = document.getElementById('id_ubicacion_actual_txt').value;

		with (document.form1)
		{
			//if (numero.value == "") {errores += "\nDebe ingresar el número de expediente.";}
			if (fecha.value == "") {errores += "\nDebe ingresar la fecha.";}
			if (seleccionados.length <= 0) {errores += "\nDebe ingresar al menos un expediente.";}
			if(dest == '') {errores += "\nDebe ingresar un destino.";}
			//si lleno todos los campos
			if (errores == "") 
			{
				for(i=0 ; i < seleccionados.length ; i ++) {
					seleccionados[i].checked = true;
				}
				document.form1.action = "?accion=generar";
				document.form1.submit();			
			}
			else
			{
				alert("Tiene los siguientes errores:\n"+errores);
				return false;
			}
		  	document.getElementById('id_ubicacion_actual_txt').value = '';
		  	document.getElementById('listado_expedientes').innerHTML = '';	
		}
	}

</script>
</head>

<body <?=$onload?>>
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
      <table width="350" border="0" cellspacing="0" cellpadding="0">
      <tr id="cont_solapa1">
        <td width="350" align="center" valign="middle"><table width="350" border="0" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
          <tr>
            <td height="25" colspan="2" align="center" valign="middle" class="header2">Remito Expedientes</td>
          </tr>
          <tr>
            <td align="left" class="td1">Fecha</td>
            <td align="left" class="td2"><?=CalendarioCreaInput('fecha', AhoraFecha(), '')?></td>
          </tr>
          <tr>
            <td align="left" class="td1">Destino</td>
            <td align="left" class="td2">
            	<input id="id_ubicacion_actual_txt" name="id_ubicacion_actual_txt" type="text" value="" maxlength="100" />
            	<input id="id_ubicacion_actual" name="id_ubicacion_actual" type="hidden" value="" /> 
            </td>
          </tr>
          <tr>
            <td width="150" height="24" align="left" class="td1">N&uacute;mero de expediente</td>
            <td width="200" align="left" class="td2">
              <input id="numero" name="numero" type="text" value="" maxlength="100" onkeypress="KeyCheck(event);" />            </td>
            
          </tr>
          <tr>
            <td width="150" height="24" align="center" class="td1" colspan="2" >
            	<input type="button" size="30" name="agregar_exp" id="agregar_exp" value="   Agregar Expediente    "  onClick="checkExpediente(document.form1.numero.value)" />
            	<input type="button" size="30" name="remove_exp" id="remove_exp" value="     Borrar Expediente     " onClick="removeOptionSelected();" />
 			</td>
          </tr>
          <tr>
          	<td colspan="2" class="td2" >
				<table width="100%" cellspacing="1" cellpadding="1" border="0" bgcolor="#ffffff" id="listado_expedientes" >
					<thead>
						<tr>
							<td class="header2" align="center" ></td>
							<td class="header2" align="center" >Número expediente</td>
							<td class="header2" align="center" >Foja</td>
						</tr>
					</thead>
					<tbody>
					</tbody>
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

<script type="text/javascript">
/*
	//------------------------------------------------------------------------------------------
	var options2 = {
		script:"../inc/autosuggest/data_destino.php?json=true&",
		varname:"input",
		json:true,
		shownoresults:false,
		maxresults:10,
		minchars: 1,
		timeout:99999,
		cache: false,
		callback: function (obj) { document.getElementById('id_ubicacion_actual').value = obj.id; }
	};
	var as_json2 = new bsn.AutoSuggest('id_ubicacion_actual_txt', options2);
	//------------------------------------------------------------------------------------------
*/	
</script>

</div>
</div>
<input type="hidden" name="hdnAgregados" id="hdnAgregados" value="" />
<input type="hidden" name="accion_anterior" id="accion_anterior" value="<?=$accion?>" />
<input type="hidden" name="arreglo" id="arreglo" value=""/>
<input type="hidden" name="len" id="len" values="" />
</form>
</body>
</html>
