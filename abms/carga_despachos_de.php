<? include('../inc/inicio.inc.php'); ?>
<? include('../class/expediente.class.php'); ?>
<?

DesactivarSSL();
CheckPerfiles('SDO');

function volverBusqueda() {
	header("Location: ../general/busqueda_despachos_de.php");
}

if(!isset($_GET['accion']))
	volverBusqueda();
	

switch($_GET['accion']) {
	case 'buscar':
		$expedientes = new Expediente();
		$rsExpedientes = $expedientes->buscarFormasAprobacion();
		
		if($rs->EOF)
			volverBusqueda();
	break;

	case 'grabar':
		$expedientes = new Expediente();
		$_POST['id_ubicacion_actual_txt'] = "DESPACHO al D.E. " . $_POST['id_ubicacion_actual_txt'];
		$rsExpedientes = $expedientes->guardarMovimientoMasivos();
		header("Location: ../general/menu_principal.php");
	break;
	
	default:
		volverBusqueda();
	
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

function Cancelar() 
{
	//if (window.confirm('¿Desea cancelar?')) {
		window.location = '../general/menu_principal.php';
	//}		
}

function ValidarGrabado() {

	var errores = "";
		
		
	with (document.form1)
	{
		if (fecha.value == "" || fecha.value == 0) {errores += "\nDebe ingresar la fecha.";}
		if (id_ubicacion_actual_txt.value == "") {errores += "\nDebe completar la ubicación actual.";}
		
		
		//si lleno todos los campos
		if (errores != "") {
			alert("Tiene los siguientes errores:\n"+errores);
			return false;
		}
	}

	document.form1.action = "?accion=grabar";
	document.form1.submit();
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
      <table width="730" border="0" cellspacing="0" cellpadding="0">
      <tr id="cont_solapa1">
        <td width="730" align="center" valign="middle"><table width="730" border="0" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
          <tr>
            <td height="25" colspan="6" align="center" valign="middle" class="header2">Despachos D.E.</td>
          </tr>
          <tr>
          	<td width="40" height="24" align="center" class="header2"></td>
            <td width="200" height="24" align="center" class="header2">N&uacute;mero de expediente</td>
            <td width="200" height="24" align="center" class="header2">Fecha de presentación</td>
            <td width="200" height="24" align="center" class="header2">Fecha de aprobación</td>
            <td width="200" height="24" align="center" class="header2">Forma de aprobación</td>
            <td width="200" height="24" align="center" class="header2">Tipo de aprobación</td>
          </tr>
<?php
		while(!$rsExpedientes->EOF) {
?>
          <tr>
          	<td width="40" height="24" align="center" class="td1"><input type="checkbox" name="numero[]" value="<?php echo $rsExpedientes->fields['numero']; ?>" checked="checked" ></td>
            <td width="200" height="24" align="center" class="td1"><?php echo $rsExpedientes->fields['numero']; ?></td>
            <td width="200" height="24" align="center" class="td1"><?php echo $rsExpedientes->fields['fec_presentacion']; ?></td>
            <td width="200" height="24" align="center" class="td1"><?php echo $rsExpedientes->fields['fec_aprobacion']; ?></td>
            <td width="200" height="24" align="center" class="td1"><?php echo $rsExpedientes->fields['forma_aprobacion']; ?></td>
            <td width="200" height="24" align="center" class="td1"><?php echo $rsExpedientes->fields['tipo_aprobacion']; ?></td>
          </tr>
<?php			
			$rsExpedientes->MoveNext();
		}
?>
          
        </table>
        </td>
        </tr>
    </table>
      <br />
      <br />
    <table cellspacing="1" cellpadding="1" border="0" bgcolor="#ffffff" width="350px" >
    	<tr>
    		<td class="td1" height="24" >Fecha de movimiento</td>
    		<td class="td1" height="24" ><?=CalendarioCreaInput('fecha', '', '')?></td>
    	</tr>
    	<tr>
    		<td class="td1" height="24" >Despacho al D.E.</td>
    		<td class="td1" height="24" >
    			<input type="text" value="" name="id_ubicacion_actual_txt" id="id_ubicacion_actual_txt" />
    			<input name="id_ubicacion_actual" id="id_ubicacion_actual" type="hidden" />
    		</td>
    	</tr>
    	
    </table>
      
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

<!-- script type="text/javascript">
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
	
</script-->

</div>
</div>
<input type="hidden" name="hdnAgregados" id="hdnAgregados" value="" />
<input type="hidden" name="accion_anterior" id="accion_anterior" value="<?=$accion?>" />
<input type="hidden" name="arreglo" id="arreglo" value=""/>
<input type="hidden" name="len" id="len" values="" />
</form>
</body>
</html>
