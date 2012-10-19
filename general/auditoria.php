<? include('../inc/inicio.inc.php'); ?>
<?

CheckPerfiles('S');

if (isset($_POST['id_usuario']))
	$id_usuario = $_POST['id_usuario'];
else
	$id_usuario = 'nada';


if (isset($_POST['envio']))
{
	$fec_desde = $_POST['fec_desde'];
	$fec_hasta = $_POST['fec_hasta'];
}
else
{
	$fec_desde = date('Y-m-d');
	$fec_hasta = date('Y-m-d');
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Sistema de Gestión Parlamentaria</title>
<link rel="stylesheet" type="text/css" href="../inc/estilo.css">
<? include('../inc/efecto_transicion.inc.php'); ?>
<? include('../inc/calendario/calendario.inc.php'); ?>

<script language="javascript">

	function VerListado()
	{
		document.form1.submit();
	}

</script>


</head>

<body>
<form id="form1" name="form1" method="post" action="">
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
      <table width="564" border="0" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF">
        <tr>
          <td width="109" align="left" class="td1">Usuario</td>
          <td colspan="3" align="left" class="td2">
          	<? ArmaCombo('id_usuario', 'usuarios', 'nombre', 'style="width:290px"', $id_usuario,  true); ?></td>
        </tr>
        <tr>
          <td width="109" align="left" class="td1">Fecha desde</td>
          <td width="156" align="left" class="td2"><?=CalendarioCreaInput('fec_desde', $fec_desde, '')?></td>
          <td width="121" align="left" class="td1">Fecha hasta</td>
          <td width="173" align="left" class="td2"><?=CalendarioCreaInput('fec_hasta', $fec_hasta, '')?></td>
        </tr>
        <tr>
          <td colspan="4" align="center" class="td1"><br />
            <input type="submit" name="btnListado" id="btnListado" value="Ver listado" style="width:150px" onclick="VerListado();" />
              &nbsp;&nbsp;&nbsp;&nbsp;
              <input type="button" name="btnVolver2" id="btnVolver2" value="Volver" style="width:150px" onclick="window.location = 'menu_principal.php';" />		  </td>
          </tr>
      </table>
      <br />
      <br />
      <?
		if ($id_usuario != 'nada')
		{
			if ($id_usuario != 'null')
				$filtro = " and a.id_usuario=".$id_usuario;
			else
				$filtro = '';
				
			$sql = "select a.fecha, a.numero_exp, a.accion, u.nombre ".
				   "from auditoria a ".
				   "left join usuarios u on u.id_usuario=a.id_usuario ".
				   "where fecha between '$fec_desde"."000000' and '$fec_hasta"."235959' ".
				   $filtro." ".
				   "order by a.fecha desc";
			
			$rs = $conn->Execute($sql);
			Auditoria('null', 'listado auditoria');
			
			if ($rs->EOF) 
			{
				echo "<div align='center'><br><br><br><span class='mensaje'>No se encontraron resultados.</span></div>";
			}
			else
			{
	  ?>
		
      <table width="720" border="0" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
        <tr>
          <td align="center" class="header2">Fecha</td>
          <td align="center" class="header2">Usuario</td>
          <td align="center" class="header2">Nro expediente</td>
          <td align="center" class="header2">Acción</td>
        </tr>
		<?
		$clase = 'td1';
		while (!$rs->EOF) {
			if ($clase == 'td1') $clase = 'td2';
			else {$clase = 'td1';}
		
		?>
		<tr class="<?=$clase?>">
          <td align="left"><?=utf8($rs->fields['fecha'])?></td>
          <td align="left"><?=utf8($rs->fields['nombre'])?></td>
          <td align="left"><?=utf8($rs->fields['numero_exp'])?></td>
          <td align="left"><?=utf8($rs->fields['accion'])?></td>
        </tr>
        <? 
			$rs->MoveNext();
		}
		?>
      </table>
      <? } ?>
   <? } ?>
      </td>
  </tr>
</table>
</div>
<input id="envio" name="envio" type="hidden" value="S" />
</form>
</body>
</html>
