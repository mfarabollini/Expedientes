<? include('../inc/inicio.inc.php'); ?>
<?

CheckPerfiles('SI');

ActivarSSL();

$mensa = '&nbsp;';

if (isset($_GET['accion'])) {

	if ($_GET['accion'] == 'borrar') {
		$id_usuario = $_POST['id_usuario'];
		
		if ($conn->Execute("delete from usuarios where id_usuario=$id_usuario")) {
			$mensa = 'Se ha borrado el usuario.';
			Auditoria('null', 'borro usuario '.$id_usuario);
			//si es el mismo usuario el que se autoborro se desloguea enseguida
			if ($id_usuario == $_SESSION['id_usuario']) {
				header('Location: ../general/login.php');
				exit;
			}
		}
		else {
			$mensa = 'ERROR: No se ha podido borrar el usuario ya que hay informacion cargada con el mismo.';
		}
	}
	
	if ($_GET['accion'] == 'desbloquear') {
		$id_usuario = $_POST['id_usuario'];
		$conn->Execute("update usuarios set intentos=0 where id_usuario=$id_usuario");
		$mensa = 'Se ha desbloqueado el usuario.';
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
	
	function Borrar(pId, pDescrip) {
		if (window.confirm('¿Desea eliminar al usuario "'+pDescrip+'"?')) {
			with (document.form1) {
				id_usuario.value=pId;
				action = 'adm_usuarios.php?accion=borrar';
				submit();
			}
		}
	}
	
	
	function Elegir(pId) {
		with (document.form1) {
			id_usuario.value=pId;
			action = 'adm_usuarios_abm.php';
			submit();
		}		
	}
	
	
	function Desbloquear(pId) {
		if (window.confirm('El usuario se ha bloqueado por haber ingresado mal su clave mas de 5 veces.\n¿Desea desbloquear el usuario?')) {
			with (document.form1) {
				id_usuario.value=pId;
				action = 'adm_usuarios.php?accion=desbloquear';
				submit();
			}
		}
	}
	
</script>


</head>

<body>
<div align="center">
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
<table width="750" height="500" border="0" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF"><tr>
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
  <div align="center">
    <span class="mensaje"><?=$mensa?></span><br>
  </div>
  <div id="divFichas" style="width:718px; height:425px; overflow:auto;">
  <?
  	$rs = $conn->Execute("select id_usuario, nombre, celular, intentos, habilitado, nick, perfil from usuarios where id_usuario <> 1 order by upper(nombre), nick");
	
	if ($rs->EOF) {echo 'No hay uuarios cargados.';}
	else {
		$clase = 'td1';
  ?>
  <table width="670" border="0" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
    <tr align="center">
      <td height="23" colspan="7" class="header2">Administraci&oacute;n de Usuarios </td>
    </tr>
    <tr align="center">
      <td nowrap class="header">Nombre y Apellido</td>
      <td nowrap class="header">Nick</td>
      <td nowrap class="header">Habilitado</td>
      <td nowrap class="header">Estado</td>
      <td nowrap class="header">Perfil</td>
      <td nowrap class="header">Celular</td>
      <td nowrap class="header">Opciones</td>
    </tr>
    <? while (!$rs->EOF) { ?>
	<tr class="<?=$clase?>">
      <td align="left" nowrap><?=$rs->fields['nombre']?></td>
      <td align="left" nowrap><?=$rs->fields['nick']?></td>
      <td align="left" nowrap>
	  <?
	  	if ($rs->fields['habilitado'] == 'S') {
			echo 'Habilitado';
		}
		else {
			echo '<span class="resaltado1">&nbsp;Deshabilitado&nbsp;</span></td>';
		}
	  ?>	  </td>
      <td align="left" nowrap>
	  <?
	  	if ($rs->fields['intentos'] >= 5) {
			echo '<a href="javascript: Desbloquear('.$rs->fields['id_usuario'].');"><span class="resaltado1">&nbsp;Bloqueado&nbsp;</span></a></td>';
		}
		else {
			echo 'Activo';
		}
	  ?>
      <td align="left" nowrap>
		<? 
            if ($rs->fields['perfil']=='C') echo 'Consultante';
		  	if ($rs->fields['perfil']=='E') echo 'Consultante Despacho';
		 	if ($rs->fields['perfil']=='U') echo 'Consultante Contadur&iacute;a';
		    if ($rs->fields['perfil']=='A') echo 'Consultante Auditor';			
            if ($rs->fields['perfil']=='D') echo 'Director';
			if ($rs->fields['perfil']=='I') echo 'Inform&aacute;tica';
            if ($rs->fields['perfil']=='O') echo 'Operador';
			if ($rs->fields['perfil']=='T') echo 'Operador Contadur&iacute;a';			
            if ($rs->fields['perfil']=='S') echo 'Supervisor';
        ?>      </td>
      <td align="left" nowrap><?=$rs->fields['celular']?></td>
      <td align="center" nowrap><a href="javascript: Elegir(<?=$rs->fields['id_usuario']?>)"><img src="../imagenes/editar.gif" alt="Editar usuario" border="0"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: Borrar(<?=$rs->fields['id_usuario']?>, '<?=QuitaCaraceresFeos($rs->fields['nombre'])?>')"><img src="../imagenes/tachito.gif" alt="Eliminar usuario" border="0"></a></td>
    </tr>
	<? 
			if ($clase=='td1') {$clase='td2';}
			else {$clase='td1';}
			
			$rs->MoveNext();
		} ?>
  </table>
  <? } ?>
  </div>
	<div align="center" id="divBotones" class="td1" style="width:730px; height:40px">
    <br>
	<input name="btnNuevo" type="button" id="btnNuevo" value="Nuevo Usuario" onClick="javascript: window.location='adm_usuarios_alta.php';" style="width:100px">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    
<input name="btnVolver" type="button" id="btnVolver" value="Volver" onClick="javascript: window.location='../general/menu_principal.php';" style="width:100px">
  </div>
  <input name="id_usuario" id="id_usuario" type="hidden" value="">
    
    </td>
  </tr>
</table>
</div>
</form>
</body>
</html>
