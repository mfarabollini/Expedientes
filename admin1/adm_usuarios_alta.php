<? include('../inc/inicio.inc.php'); ?>
<?

CheckPerfiles('SI');

ActivarSSL();


$nombre = '';
$nick = '';
$direccion = '';
$telefono = '';
$celular = '';
$email = '';
$habilitado = '';
$perfil = 'C';

if (isset($_GET['accion'])) {

	//para que cargue el iframe vacio
	if ($_GET['accion'] == 'nada') {
		echo 'Nada';
		exit;
	}
	

	if ($_GET['accion'] == 'alta') {
		$nombre = trim($_POST['nombre']);
		$nick = strtolower(trim($_POST['nick']));
		$clave = md5(trim($_POST['clave']));
		$direccion = trim($_POST['direccion']);
		$telefono = trim($_POST['telefono']);
		$celular = trim($_POST['celular']);
		$email = trim($_POST['email']);
		$habilitado = $_POST['habilitado'];
		$perfil = $_POST['perfil'];
		
		//primero me fijo si existe
		$rs = $conn->Execute("select count(*) as cant from usuarios where upper(nick)='".trim(strtoupper($nick))."'");
		if ($rs->fields['cant']==0) {
			
			$conn->StartTrans();
			$conn->Execute("insert into usuarios ".
						   "(nombre,nick,clave,habilitado,intentos,direccion,telefono,celular,email,perfil) ".
						   "values ".
						   "('$nombre','$nick','$clave','$habilitado',0,'$direccion','$telefono','$celular','$email','$perfil')");
			
			
			if ($conn->CompleteTrans(true)) {
				Auditoria('null', 'alta usuario '.$conn->Insert_ID());
				echo '<html><body><script>parent.AltaOk();</script></body></html>';
				exit;
			}
			else {
				echo '<html><body><script>parent.AltaError("'.$conn->ErrorMsg().'");</script></body></html>';
				exit;
			}
			
		}
		else {
			echo '<html><body><script>parent.AltaNickDuplicado();</script></body></html>';
			exit;
		}
	}

}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Sistema de Gestión Parlamentaria</title>
<link rel="stylesheet" type="text/css" href="../inc/estilo.css">
<? include('../inc/efecto_transicion.inc.php'); ?>
<script language="javascript">

	function AltaOk() {
		window.location = 'adm_usuarios.php';
	}
	
	
	function AltaError(pError) {
		alert('Ocurrio un error al dar de alta el usuario!\n'+pError);
	}
	
	
	function AltaNickDuplicado() {
		alert('El nick elegido ya existe, por favor elija otro.');
		document.form1.nick.select();
	}
	

	function Grabar() {
		var sError = '';
		var todoOk = true;
		
		with (document.form1) {
			if (nombre.value=='') {sError += '\nNombre';}
			if (nick.value=='') {sError += '\nNick';}
			if (clave.value=='') {sError += '\nClave';}
			
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
				target = "iframe_data";
				action = "adm_usuarios_alta.php?accion=alta";
				submit();			
			}
		}
	}
	
	
	function Cancelar() {
		if (window.confirm('¿Desea cancelar la alta del usuario?')) {
			window.location = 'adm_usuarios.php';
		}		
	}


	function Chequear(pObj) {
		var obj = document.getElementById(pObj);

		obj.checked = !obj.checked;
	}
	
</script>
</head>

<body onLoad="document.form1.nombre.focus();">
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
    
<font size="1"><br></font>
<table width="593" border="0" align="center" cellpadding="2" cellspacing="1" bgcolor="#000099">
	<tr>
		<td colspan="2" align="center" class="header2">Alta de Usuario </td>
	</tr>
	<tr>
		<td width="117" align="left" class="td1">Nombre</td>
		<td width="465" align="left" class="td2"><input name="nombre" type="text" id="nombre" value="<?=$nombre?>" maxlength="255" style="width:235px;"></td>
	</tr>
	<tr>
		<td width="117" align="left" class="td1">Nick</td>
		<td width="465" align="left" class="td2"><input name="nick" type="text" id="nick" value="<?=$nick?>" maxlength="255" style="width:235px;"></td>
	</tr>
	<tr>
		<td width="117" align="left" class="td1">Direccion</td>
		<td width="465" align="left" class="td2"><input name="direccion" type="text" id="direccion" value="<?=$direccion?>" maxlength="255" style="width:235px;"></td>
	</tr>
	<tr>
		<td width="117" align="left" class="td1">Telefono</td>
		<td width="465" align="left" class="td2"><input name="telefono" type="text" id="telefono" value="<?=$telefono?>" maxlength="200" style="width:235px;"></td>
	</tr>
	<tr>
		<td width="117" align="left" class="td1">Celular</td>
		<td width="465" align="left" class="td2"><input name="celular" type="text" id="celular" value="<?=$celular?>" maxlength="200" style="width:235px;"></td>
	</tr>
	<tr>
		<td width="117" align="left" class="td1">E-Mail</td>
		<td width="465" align="left" class="td2"><input name="email" type="text" id="email" value="<?=$email?>" maxlength="255" style="width:235px;"></td>
	</tr>
	<tr>
		<td width="117" align="left" class="td1">Clave</td>
	  <td width="465" align="left" class="td2"><input name="clave" type="password" id="clave" value="" maxlength="255" style="width:235px;"></td>
	</tr>
	<tr>
		<td width="117" align="left" class="td1">Confirmar clave</td>
		<td width="465" align="left" class="td2"><input name="clave2" type="password" id="clave2" value="" maxlength="255" style="width:235px;"></td>
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
		<td width="465" align="left" class="td2">
        <select name="perfil" id="perfil">
		  <option value="C" <? if ($perfil=='C') {echo 'selected';} ?>>Consultante</option>
		  <option value="E" <? if ($perfil=='E') {echo 'selected';} ?>>Consultante Despacho</option>
		  <option value="U" <? if ($perfil=='U') {echo 'selected';} ?>>Consultante Contadur&iacute;a</option>
		  <option value="A" <? if ($perfil=='A') {echo 'selected';} ?>>Consultante Auditor</option>
		  <option value="D" <? if ($perfil=='D') {echo 'selected';} ?>>Director</option>
		  <option value="I" <? if ($perfil=='I') {echo 'Inform&aacute;tica';}?>>Inform&aacute;tica</option>		  
		  <option value="O" <? if ($perfil=='O') {echo 'selected';} ?>>Operador</option>
		  <option value="T" <? if ($perfil=='T') {echo 'selected';} ?>>Operador Contadur&iacute;a </option>		  
		  <option value="S" <? if ($perfil=='S') {echo 'selected';} ?>>Supervisor</option>
        </select>
        </td>
	</tr>
</table>
<font size="1"><br>
<br>
<br>
<br></font><font size="1"><br>
</font>
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
<? if ($ssl_activo) { ?>
<iframe id="iframe_data" name="iframe_data" src="https://<?=$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']?>?accion=nada" width="0" height="0" frameborder="0"></iframe>
<? } 
	else { ?>
<iframe id="iframe_data" name="iframe_data" src="http://<?=$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']?>?accion=nada" width="0" height="0" frameborder="0"></iframe>
<? } ?>
</body>
</html>
