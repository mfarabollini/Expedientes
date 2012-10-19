<? include('../inc/inicio.inc.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Sistema de Gestión Parlamentaria</title>
<link rel="stylesheet" type="text/css" href="../inc/estilo.css">
<? include('../inc/efecto_transicion.inc.php'); ?>

<script language="javascript">
	var imprimio_recibo = false;
	var accion = '<?=$_GET['accion']?>';
	
	function Volver()
	{
		if (!imprimio_recibo && accion=='alta')
		{
			if (window.confirm("Todavia no ha impreso el recibo.\n¿Desea salir de todas formas?"))
				window.location='../general/menu_principal.php';
		}
		else
			window.location='../general/menu_principal.php';
	}

</script>

</head>

<body>
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
    <td height="440" align="center" valign="middle" class="contenido"><table width="400" height="139" border="0" cellpadding="1" cellspacing="1">
      <tr>
        <td align="center" class="td1">Se grabado correctamente el expediente N&ordm; <?=number_format($_GET['numero'], 0, ',', '.');?>
          <br />

		  <? 
		  if (PerfilAutorizado('SDO') && $_GET['accion']=='alta') 
		  { 
		  
		  ?>
          <br />
          <a href="../general/imprimir_recibo.php?numero=<?=$_GET['numero']?>" target="_blank" onclick="javascript: imprimio_recibo=true;"><font size="3" color="#FFFF00">Imprimir el recibo</font></a><br />
          <br />
          <a href="../general/sello_goma.php?numero=<?=$_GET['numero']?>" target="_blank"><font size="3" color="#FFFF00">Imprimir el sello de goma</font></a><br />
		  <? } ?>

          <br />
          <a href="../general/caratula.php?numero=<?=$_GET['numero']?>" target="_blank"><font size="3" color="#FFFF00">Imprimir caratula</font></a><br />
          <br />
          <input type="button" name="btnVolver" id="btnVolver" value="Volver al men&uacute;" onclick="Volver();" />
          <br /></td>
      </tr>
    </table></td>
  </tr>
</table>
</div>
</body>
</html>
