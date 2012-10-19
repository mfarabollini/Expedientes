<? include('../inc/inicio.inc.php'); ?>
<?

CheckPerfiles('SI');

ActivarSSL();

$id_usuario = $_POST['id_usuario'];
$rs = $conn->Execute("select nombre, nick, direccion, telefono, celular, email, habilitado, perfil from usuarios where id_usuario=".$id_usuario);

$nombre = $rs->fields['nombre'];
$nick = $rs->fields['nick'];
$direccion = $rs->fields['direccion'];
$telefono = $rs->fields['telefono'];
$celular = $rs->fields['celular'];
$email = $rs->fields['email'];
$habilitado = $rs->fields['habilitado'];
$perfil = $rs->fields['perfil'];


if (isset($_GET['accion'])) {
	
	if ($_GET['accion']=='modi') {	

		$nombre = trim($_POST['nombre']);
		$clave = trim($_POST['clave']);
		$direccion = trim($_POST['direccion']);
		$telefono = trim($_POST['telefono']);
		$celular = trim($_POST['celular']);
		$email = trim($_POST['email']);
		$habilitado = $_POST['habilitado'];
		$perfil = $_POST['perfil'];
		
		
		$conn->StartTrans();
		
		if ($clave == '') {
			$sql = "update usuarios set ".
				   "nombre='$nombre', ".
				   "direccion='$direccion', ".
				   "telefono='$telefono', ".
				   "celular='$celular', ".
				   "email='$email', ".
				   "perfil='$perfil', ".
				   "habilitado='$habilitado' ".
				   "where id_usuario=".$id_usuario;
		}
		else {
			$sql = "update usuarios set ".
				   "nombre='$nombre', ".
				   "direccion='$direccion', ".
				   "telefono='$telefono', ".
				   "celular='$celular', ".
				   "email='$email', ".
				   "perfil='$perfil', ".
				   "habilitado='$habilitado', ".
				   "clave='".md5($clave)."' ".
				   "where id_usuario=".$id_usuario;
		}
		

		$conn->Execute($sql);
				
		$conn->CompleteTrans(true);
		Auditoria('null', 'modifico usuario '.$id_usuario);
		header('Location: adm_usuarios.php');
		exit;
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

	function Grabar() {
		var sError = '';
		var todoOk = true;
		
		with (document.form1) {
			if (nombre.value=='') {sError += '\nNombre';}
			
			if (sError != '') {
				alert('Debe completar los siguientes campos:\n----------------------------------------------\n'+sError);
				todoOk = false;
			}
			else {
				if (clave.value != '' && clave.value != clave2.value) {
					alert('La clave y la confirmación no coinciden.');
					todoOk = false;
				}
			}
			
			if (todoOk) {
				action = "adm_usuarios_abm.php?accion=modi";
				submit();			
			}
		}
	}
	
	
	function Cancelar() {
		if (window.confirm('¿Desea cancelar la modificacion del usuario?')) {
			window.location = 'adm_usuarios.php';
		}		
	}


	function Chequear(pObj) {
		var obj = document.getElementById(pObj);

		obj.checked = !obj.checked;
	}
	
</script>

</head>

<body onload="document.form1.nombre.focus();">
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
    <td height="440" align="center" valign="middle" class="contenido">
<font size="1"><br />
<br />
<br></font>
<table width="593" border="0" align="center" cellpadding="2" cellspacing="1" bgcolor="#000099">
	<tr>
		<td colspan="2" align="center" class="header2">Modificaci&oacute;n de Usuario </td>
	</tr>
	<tr>
		<td width="117" align="left" class="td1">Nombre</td>
		<td width="465" align="left" class="td2"><input name="nombre" type="text" id="nombre" tabindex="1" value="<?=$nombre?>" maxlength="255" style="width:235px;"></td>
	</tr>
	<tr>
		<td width="117" align="left" class="td1">Nick</td>
		<td width="465" align="left" class="td2"><strong><?=$nick?></strong></td>
	</tr>
	<tr>
		<td width="117" align="left" class="td1">Direccion</td>
		<td width="465" align="left" class="td2"><input name="direccion" type="text" id="direccion" tabindex="1" value="<?=$direccion?>" maxlength="255" style="width:235px;"></td>
	</tr>
	<tr>
		<td width="117" align="left" class="td1">Telefono</td>
		<td width="465" align="left" class="td2"><input name="telefono" type="text" id="telefono" tabindex="1" value="<?=$telefono?>" maxlength="200" style="width:235px;"></td>
	</tr>
	<tr>
		<td width="117" align="left" class="td1">Celular</td>
		<td width="465" align="left" class="td2"><input name="celular" type="text" id="celular" tabindex="1" value="<?=$celular?>" maxlength="200" style="width:235px;"></td>
	</tr>
	<tr>
		<td width="117" align="left" class="td1">E-Mail</td>
		<td width="465" align="left" class="td2"><input name="email" type="text" id="email" tabindex="1" value="<?=$email?>" maxlength="255" style="width:235px;"></td>
	</tr>
	<tr>
		<td width="117" align="left" class="td1">Clave</td>
		<td width="465" align="left" class="td2"><input name="clave" type="password" id="clave" tabindex="1" value="" maxlength="255" style="width:235px;"> 
			(en blanco para no cambiar) </td>
	</tr>
	<tr>
		<td width="117" align="left" class="td1">Confirmar clave</td>
		<td width="465" align="left" class="td2"><input name="clave2" type="password" id="clave2" tabindex="1" value="" maxlength="255" style="width:235px;"></td>
	</tr>
	<tr>
		<td width="117" align="left" class="td1">Habilitado</td>
		<td width="465" align="left" class="td2">
			<select name="habilitado" id="habilitado">
				  <option value="S" <? if ($habilitado=='S') {echo 'selected';} ?>>S&iacute;</option>
				  <option value="N" <? if ($habilitado=='N') {echo 'selected';} ?> class="resaltado1">No</option>
			</select>		</td>
	</tr>
	<tr>
		<td width="117" align="left" class="td1">Perfil</td>
		<td width="465" align="left" class="td2"><select name="perfil" id="perfil">
		  <option value="C" <? if ($perfil=='C') {echo 'selected';} ?>>Consultante</option>
		  <option value="E" <? if ($perfil=='E') {echo 'selected';} ?>>Consultante Despacho</option>
		  <option value="O" <? if ($perfil=='U') {echo 'selected';} ?>>Consultante Contadur&iacute;a</option>
		  <option value="A" <? if ($perfil=='A') {echo 'selected';} ?>>Consultante Auditor</option>
		  <option value="D" <? if ($perfil=='D') {echo 'selected';} ?>>Director</option>
		  <option value="I" <? if ($perfil=='I') {echo 'Inform&aacute;tica';}?>>Inform&aacute;tica</option>
		  <option value="O" <? if ($perfil=='O') {echo 'selected';} ?>>Operador</option>
		  <option value="T" <? if ($perfil=='T') {echo 'selected';} ?>>Operador Contadur&iacute;a </option>		  
		  <option value="S" <? if ($perfil=='S') {echo 'selected';} ?>>Supervisor</option>		  
        </select></td>
	</tr>
</table>
<font size="1"><br></font><br />
<br />
<br />
<br>
<div align="center" class="td1" style="width:730px; height:20px">
  <input name="btnGrabar" type="button" id="btnGrabar" value="Grabar" style="width:100px" onClick="javascript: Grabar();">
  &nbsp;&nbsp;&nbsp;&nbsp;
  <input name="btnCancelar" type="button" id="btnCancelar" value="Cancelar" style="width:100px" onClick="javascript: Cancelar();">
</div>
	<input name="id_usuario" id="id_usuario" type="hidden" value="<?=$id_usuario?>">
    
    </td>
  </tr>
</table>
</div>
</form>
</body>
</html>
