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

function ValidarGrabado() {
	with (document.form1) {
		if(numero.value == '' || palabra.value == '') {
			if(palabra.value == '') {
				if(numero.value == '') {
					alert("Debe ingresar una palabra o número para realizar la busqueda.");
					return false;
				}
			}
		}
		
		document.form1.action = '../abms/carga_despachos_de.php?accion=buscar';
		document.form1.submit();	
	}
}

function Cancelar() 
{
	//if (window.confirm('¿Desea cancelar?')) {
		window.location = '../general/menu_principal.php';
	//}		
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
            <td height="25" colspan="2" align="center" valign="middle" class="header2">Despachos D.E.</td>
          </tr>
          <tr>
            <td width="150" height="24" align="left" class="td1">Palabra</td>
            <td width="200" align="left" class="td2">
              <input id="palabra" name="palabra" type="text" value="" maxlength="100" />            </td>
          </tr>
          <tr>
            <td align="left" class="td1">Número</td>
            <td align="left" class="td2"><input id="numero" name="numero" type="text" value="" maxlength="100" /></td>
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
  <input name="btnGrabar" type="button" id="btnGrabar" value="Buscar" style="width:100px" onClick="javascript: ValidarGrabado();">
  &nbsp;&nbsp;&nbsp;&nbsp;
  <input name="btnCancelar" type="button" id="btnCancelar" value="Salir" style="width:100px" onClick="javascript: Cancelar();">
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
<input type="hidden" name="arreglo" id="arreglo" value=""/>
<input type="hidden" name="len" id="len" values="" />
</form>
</body>
</html>
