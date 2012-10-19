<? include('../inc/inicio.inc.php'); ?>
<? include('../class/expediente.class.php'); ?>
<?

DesactivarSSL();

$mensa = '&nbsp;';
$titulo = '';
$numero = '';
$onload = '';
$numero_txt = '';

CheckPerfiles('SD');

if (isset($_GET['accion'])) 
{
	$expediente = new Expediente();
	$accion = $_GET['accion'];

	if ($accion == 'grabar') 
	{

		if(!isset($_POST['id_movimiento'])) {
			die("No trae número de movimiento");
		}
		
		$fecha = $expediente->ValorPostFecha('fecha');
		$id_ubicacion_actual = $expediente->ValorDestino('id_ubicacion_actual');
		$comentario = $expediente->ValorPost('comentario');

		$numero = trim($_POST['numero']);
		$numero = str_replace('.', '', $numero);
		$numero = str_replace(',', '', $numero);
		$numero = str_replace('-', '', $numero);
		

		$sql = "UPDATE movimientos SET comentario='{$comentario}', fecha={$fecha}, id_ubicacion_actual={$id_ubicacion_actual} WHERE id_movimiento={$_POST['id_movimiento']}";
		$conn->Execute($sql);

		header("Location: expediente.php?accion=editar&numero=" . $numero);
		//inserto elmovimiento
		
	}  else if ($accion == 'editar') {
		$id_movimiento = $_GET['id'];
		$sql = "SELECT * FROM movimientos WHERE id_movimiento=" . $id_movimiento;
		$rs = $conn->Execute($sql);
		
		if($rs->EOF)
			die("No existe el movimiento.");

		$sql = "SELECT * FROM destinos WHERE id_destino=" . $rs->fields['id_ubicacion_actual'];
		$rsUbicacion = $conn->Execute($sql);
			
		$id_movimiento = $rs->fields['id_movimiento']; 
		$numero = $rs->fields['numero'];
		$fecha = $rs->fields['fecha'];
		$comentario = $rs->fields['comentario'];
		$id_ubicacion_actual = $rsUbicacion->fields['id_destino'];
		$id_ubicacion_actual_txt = $rsUbicacion->fields['destino'];
	}
	
}

	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Sistema de Gestión Parlamentaria</title>
<? include('../inc/efecto_transicion.inc.php'); ?>
<link rel="stylesheet" type="text/css" href="../inc/estilo.css">
<script src="../inc/funciones.js"></script>
<? include('../inc/calendario/calendario.inc.php'); ?>

<script type="text/javascript" src="../inc/autosuggest/js/bsn.AutoSuggest_2.1.3.js" charset="utf-8"></script>
<link rel="stylesheet" href="../inc/autosuggest/css/autosuggest_inquisitor.css" type="text/css" media="screen" charset="utf-8" />



<script language="javascript">

	var oAjax = nuevoAjax();

	function Cancelar() 
	{
		if (window.confirm('¿Desea cancelar?')) {
			window.location = '../general/menu_principal.php';
		}		
	}
	
	function ValidarGrabado()
	{
		var errores = "";

		
		with (document.form1)
		{
			if (numero.value == "") {errores += "\nDebe ingresar el número de expediente.";}
			if (fecha.value == "") {errores += "\nDebe ingresar la fecha.";}
			if (id_ubicacion_actual_txt.value == "") {errores += "\nDebe ingresar la ubicacion actual.";}
			
			//si lleno todos los campos
			if (errores != "")
			{
				alert("Tiene los siguientes errores:\n"+errores);
				return false;
			}
		}
		document.form1.submit();
	}
	

	var nav4 = window.Event ? true : false;
	function acceptNumEXP(evt){
		// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57
		var key = nav4 ? evt.which : evt.keyCode;

		if (key == 13)
			AgregarExpAg();
		else
			return (key <= 13 || (key >= 48 && key <= 57) || (key >= 96 && key <= 105) || key == 46);
	}


</script>
</head>

<body <?=$onload?>>
<form action="movimiento_expediente_editar.php?accion=grabar" method="post" name="form1" id="form1" onsubmit="return ValidarGrabado();">
<div id="wrapper">
<div id="content">

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
      <table width="505" border="0" cellspacing="0" cellpadding="0">
      <tr id="cont_solapa1">
        <td width="463" align="center" valign="middle"><table width="505" border="0" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
          <tr>
            <td height="25" colspan="2" align="center" valign="middle" class="header2">Movimiento de expediente</td>
          </tr>
          <tr>
            <td width="151" height="24" align="left" class="td1">Número de expediente</td>
            <td width="342" align="left" class="td2">
              <input id="numero_txt" name="numero_txt" type="text" value="<?php echo $numero ?>" maxlength="100" disable />
	      <input id="numero" name="numero" type="hidden" value="<?php echo $numero ?>" />
	      <input id="id_movimiento" name="id_movimiento" type="hidden" value="<?php echo $id_movimiento ?>" />
	    </td>
          </tr>
          <tr>
            <td align="left" class="td1">Fecha</td>
            <td align="left" class="td2"><?=CalendarioCreaInput('fecha', $fecha, '')?></td>
          </tr>
          <tr>
            <td align="left" class="td1">Comentario</td>
            <td align="left" class="td2"><textarea name="comentario" id="comentario" style="width:200px;" rows="5"><?php echo $comentario; ?></textarea></td>
          </tr>
          <tr id="tr_id_ubicacion_actual">
            <td align="left" class="td1">Destino</td>
            <td align="left" class="td2">
            	<input id="id_ubicacion_actual_txt" name="id_ubicacion_actual_txt" type="text" style="width:200px;" maxlength="150" value="<?php echo $id_ubicacion_actual_txt; ?>" />
                <input type="hidden" id="id_ubicacion_actual" name="id_ubicacion_actual" value="<?php echo $id_ubicacion_actual; ?>" />
            </td>
          </tr>          
        </table></td>
        </tr>
    </table>
      <br />
      <br />
    <br />
    <br />
<div align="center" class="td1" style="width:730px; height:20px">
  <input name="btnGrabar" type="button" id="btnGrabar" value="Grabar" style="width:100px" onClick="javascript: ValidarGrabado();">
  &nbsp;&nbsp;&nbsp;&nbsp;
  <input name="btnCancelar" type="button" id="btnCancelar" value="Cancelar" style="width:100px" onClick="javascript: Cancelar();">
</div>
    </td>
  </tr>
</table>
</div>

<script type="text/javascript">
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
	
</script>

</div>
</div>
<input type="hidden" name="hdnAgregados" id="hdnAgregados" value="" />
<input type="hidden" name="accion_anterior" id="accion_anterior" value="<?=$accion?>" />
</form>
</body>
</html>
