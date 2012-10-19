<? include('../inc/inicio.inc.php'); ?>
<?

$mensa = '';

if (isset($_GET['accion'])) 
{
	if ($_GET['accion'] == 'cambiar_clave') 
	{
		//verifico la clave anterior
		$rs = $conn->Execute("select id_usuario from usuarios where id_usuario=".$_SESSION['id_usuario']." and clave='".md5($_POST['txt_clave_ant'])."'");
		if (!$rs->EOF) 
		{			
			$clave = md5($_POST["txt_clave"]);
			$conn->Execute("update usuarios set clave='".$clave."' where id_usuario=".$_SESSION['id_usuario']);
			Auditoria('null', 'cambio clave');
			header("Location: ../general/menu_principal.php?mensa=Se ha cambiado su clave");
			exit;
		}
		else
			$mensa = 'La clave anterior es incorrecta.';
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Sistema de Gestión Parlamentaria</title>
<link rel="stylesheet" type="text/css" href="../inc/estilo.css">
<? include('../inc/efecto_transicion.inc.php'); ?>

<script language="javascript">

	function Cancelar() 
	{
		if (window.confirm('¿Desea cancelar?')) {
			window.location = '../general/menu_principal.php';
		}		
	}
	
	function Grabar()
	{
		with (document.form1)
		{
			if (txt_clave_ant.value == "")
				alert("Debe ingresar la clave anterior");
				else
					if (txt_clave.value == "")
						alert("Debe ingresar una clave");
					else
						if (txt_clave.value == txt_clave2.value)
							submit();
						else
							alert("La clave y la confirmacion no coinciden");
		}
	}


</script>


</head>

<body onload="document.form1.txt_clave_ant.focus();">
<form id="form1" name="form1" method="post" action="cambio_clave.php?accion=cambiar_clave">
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
    <td height="440" align="center" valign="top" class="contenido">
      <span class="mensaje">
      <br />
      <br />
      <br />
      <br />
      <br />
      <br />
      <br />
      <br />
      <?=$mensa?>
      </span><br />
    
    <table width="290" border="0" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
      <tr>
        <td width="103" align="left" class="td1">Clave anterior</td>
        <td width="171" align="left" class="td2"><input name="txt_clave_ant" type="password" id="txt_clave_ant" maxlength="50" /></td>
      </tr>
      <tr>
        <td width="103" align="left" class="td1">Nueva clave</td>
        <td width="171" align="left" class="td2"><input name="txt_clave" type="password" id="txt_clave" maxlength="50" /></td>
      </tr>
      <tr>
        <td align="left" class="td1">Confirme clave</td>
        <td align="left" class="td2"><input name="txt_clave2" type="password" id="txt_clave2" maxlength="50" /></td>
      </tr>
      <tr>
        <td colspan="2" align="center" class="td1"><br />          
            <input type="button" name="btnCambiar" id="btnCambiar" value="Cambiar clave" onclick="Grabar();" />
            &nbsp;&nbsp; 
            <input type="button" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="Cancelar();" />
            <br />        </td>
        </tr>
    </table></td>
  </tr>
</table>
</div>
</form>
</body>
</html>
