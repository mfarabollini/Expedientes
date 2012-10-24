<?
include('../inc/inicio.inc.php'); ?>
<?

DesactivarSSL();

if (isset($_GET['mensa']))
	$mensa = $_GET['mensa'];
else
	$mensa = "";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Sistema de Gesti&oacuten Parlamentaria</title>
<link rel="stylesheet" type="text/css" href="../inc/estilo.css">
<? include('../inc/efecto_transicion.inc.php'); ?>

<script language="javascript">

	function Estilo(obj, estilo)
	{
		obj.className = estilo;
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
    <td height="440" align="center" valign="middle" class="contenido">
    <? if ($mensa != "") { ?>
    <span class="mensaje"><?=$mensa?></span><br />
      <br />
    <? } ?>
      <table width="200" border="0" cellspacing="5" cellpadding="2">
      <? //print $_SESSION['perfil'];
	  if (PerfilAutorizado('SDO')) { ?>
      <tr>
        <td height="30" align="center" valign="middle" class="menu1" onmouseout="Estilo(this, 'menu1');" onmouseover="Estilo(this, 'menu2');" onclick="window.location='../abms/expediente.php?accion=alta';">Alta de expediente</td>
      </tr>
      <? } ?>
      
      <? if (PerfilAutorizado('SDOCEIUTAJ')) { ?>
      <tr>
        <td height="30" align="center" valign="middle" class="menu1" onmouseout="Estilo(this, 'menu1');" onmouseover="Estilo(this, 'menu2');" onclick="window.location='busqueda.php';">Buscar expediente</td>
      </tr>
      <? } ?>

      <? if (PerfilAutorizado('SDI')) { ?>
      <tr>
        <td height="30" align="center" valign="middle" class="menu1" onmouseout="Estilo(this, 'menu1');" onmouseover="Estilo(this, 'menu2');" onclick="window.location='reporte_expedientes.php';">Reporte de expedientes</td>
      </tr>
      <? } ?>
      
      <? if (PerfilAutorizado('SDI')) { ?>
      <tr>
        <td height="30" align="center" valign="middle" class="menu1" onmouseout="Estilo(this, 'menu1');" onmouseover="Estilo(this, 'menu2');" onclick="window.location='../abms/movimiento_expediente.php';">Cargar movimiento</td>
      </tr>
      <? } ?>

      <? if (PerfilAutorizado('SEI')) { ?>
      <tr>
        <td height="30" align="center" valign="middle" class="menu1" onmouseout="Estilo(this, 'menu1');" onmouseover="Estilo(this, 'menu2');" onclick="window.location='../general/reporte_ord.php';">Orden del d&iacute;a</td>
      </tr>
      <? } ?>
      
      <?
	  if (PerfilAutorizado('SDOI')) { ?>
      <tr>
        <td height="30" align="center" valign="middle" class="menu1" onmouseout="Estilo(this, 'menu1');" onmouseover="Estilo(this, 'menu2');" onclick="window.location='../abms/norma.php?accion=alta';">Alta de Norma</td>
      </tr>
      <? } ?>
      
      <? if (PerfilAutorizado('SDOCEIUTAJ')) { ?>
      <tr>
        <td height="30" align="center" valign="middle" class="menu1" onmouseout="Estilo(this, 'menu1');" onmouseover="Estilo(this, 'menu2');" onclick="window.location='busqueda_normas.php';">Buscar Norma</td>
      </tr>
      <? } ?>
      
      <? if (PerfilAutorizado('SEI')) { ?>
      <tr>
        <td height="30" align="center" valign="middle" class="menu1" onmouseout="Estilo(this, 'menu1');" onmouseover="Estilo(this, 'menu2');" onclick="window.location='../general/reporte_pre.php';">Preferencias</td>
      </tr>
      <? } ?>
      <? if (PerfilAutorizado('SI')) { ?>
      <tr>
        <td height="30" align="center" valign="middle" class="menu1" onmouseout="Estilo(this, 'menu1');" onmouseover="Estilo(this, 'menu2');" onclick="window.location='../admin/adm_usuarios.php';">Admin. usuarios</td>
      </tr>
      <? } ?>

      <? if (PerfilAutorizado('SIA')) { ?>
      <tr>
        <td height="30" align="center" valign="middle" class="menu1" onmouseout="Estilo(this, 'menu1');" onmouseover="Estilo(this, 'menu2');" onclick="window.location='auditoria.php';">Listado de auditor&iacute;a</td>
      </tr>
      <? } ?>
      
      <? if (PerfilAutorizado('DO')) { ?>
      <tr>
        <td height="30" align="center" valign="middle" class="menu1" onmouseout="Estilo(this, 'menu1');" onmouseover="Estilo(this, 'menu2');" onclick="window.location='../abms/carga_m_pref.php';">Carga Masiva Preferencias</td>
      </tr>    
      <tr>
        <td height="30" align="center" valign="middle" class="menu1" onmouseout="Estilo(this, 'menu1');" onmouseover="Estilo(this, 'menu2');" onclick="window.location='../abms/carga_m_presc.php';">Carga Masiva Prescripci&oacute;n</td>
      </tr>
      <tr>
        <td height="30" align="center" valign="middle" class="menu1" onmouseout="Estilo(this, 'menu1');" onmouseover="Estilo(this, 'menu2');" onclick="window.location='../general/busqueda_despachos_de.php';">Despachos al D.E.</td>
      </tr>
      <tr>
        <td height="30" align="center" valign="middle" class="menu1" onmouseout="Estilo(this, 'menu1');" onmouseover="Estilo(this, 'menu2');" onclick="window.location='../abms/remito_expedientes.php';">Remito Expedientes</td>
      </tr>
      <? } ?>      
      
	  
      <? //if (!PerfilAutorizado('UE')) { ?>
      <tr>
        <td height="30" align="center" valign="middle" class="menu1" onmouseout="Estilo(this, 'menu1');" onmouseover="Estilo(this, 'menu2');" onclick="window.location='../admin/cambio_clave.php';">Cambiar clave</td>
      </tr>
	  <? //} ?>
	  
      <tr>
        <td height="30" align="center" valign="middle" class="menu1" onmouseout="Estilo(this, 'menu1');" onmouseover="Estilo(this, 'menu2');" onclick="window.location='login.php';">Cerrar sesi&oacute;n</td>
      </tr>
    </table>
      <br />
      <br />
      <br />
      Usuario logueado: <font color="#FFFFFF"><?=$_SESSION['nombre']?></font></td>
  </tr>
</table>
</div>
</body>
</html>
