<? 
	session_start();
	include('../inc/conexion.inc.php');
	include('../inc/funciones.inc.php');

	ActivarSSL();

	//OJO CON ESTOOO!!!
	//$conn->Execute("update usuarios set clave='".md5('123')."'");

	if (isset($_GET['accion']))
	{
		if ($_GET['accion'] == 'consultante')
		{
			$_SESSION['id_usuario'] = 1;
			$_SESSION['nombre'] = 'CONSULTANTE';
			$_SESSION['perfil'] = 'C';
			$_SESSION['tiempo_ultimo'] = time();
			$_SESSION["FormatoFecha"] = "dd/mm/yyyy";
			
			header("Location: busqueda.php");
			exit;
		}
	}


	if (isset($_GET['mensa'])) {$mensa = urldecode($_GET['mensa']);}
	else {$mensa = '';}
	
	unset($_SESSION['id_usuario']);
	unset($_SESSION['nombre']);
	unset($_SESSION['perfil']);
	unset($_SESSION['tiempo_ultimo']);
	
	if (isset($_POST['nick'])) {
		
		$nick = strtoupper($_POST['nick']);
	
		//primero por seguridad me fijo si sobrepaso los intentos permitidos
		$rs = $conn->Execute("select intentos from usuarios where upper(nick)='$nick'");
		if ($rs->fields['intentos'] < 5) {
			//ahora verifico la clave
			$rs = $conn->Execute("select nombre, id_usuario, habilitado, perfil from usuarios where upper(nick)='$nick' and clave='".md5($_POST['clave'])."'");
			if (!$rs->EOF) {
				if ($rs->fields['habilitado']=='S') {
					$conn->Execute("update usuarios set intentos=0 where id_usuario=".$rs->fields['id_usuario']);
					$_SESSION['id_usuario'] = $rs->fields['id_usuario'];
					$_SESSION['nombre'] = $rs->fields['nombre'];
					$_SESSION['perfil'] = $rs->fields['perfil'];
					$_SESSION['tiempo_ultimo'] = time();
					$_SESSION["FormatoFecha"] = "dd/mm/yyyy";
					$conn->Execute("update usuarios set intentos=0 where nick='$nick'");
					setcookie("ultimo_usuario",$nick,time()+7776000);

					echo '<html><body><script>window.location = "menu_principal.php";</script></body></html>';
					//header("Location: menu_principal.php?viene_ssl=1");
					exit;
				}
				else {

					$mensa = 'Su usuario ha sido deshabilitado, contacte al administrador del sistema.';
				}
			}
			else {
				$conn->Execute("update usuarios set intentos=intentos+1 where upper(nick)='$nick'");
				$mensa = 'Clave incorrecta!';
				
				setcookie("ultimo_usuario",'',time()+7776000);
			}
		}
		else {
			$mensa = 'Ha sobrepasado el maximo de 5 intentos para poner su clave, su usuario a sido desactivado.';
		}

	}
	
	$ultimo_usuario = '';
	if (isset($_COOKIE['ultimo_usuario'])) {$ultimo_usuario = trim($_COOKIE['ultimo_usuario']);}
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Sistema de Gestión Parlamentaria</title>
<link rel="stylesheet" type="text/css" href="../inc/estilo.css">
<? include('../inc/efecto_transicion.inc.php'); ?>


<script language="javascript">
	
	function Ingreso() {
		if (document.form1.nick.value == '') {
			alert('Debe ingresar su usuario.');
		}
		else {
			if (document.form1.clave.value == '') {
				alert('Debe ingresar su clave.');
			}
			else {
				<? if ($ssl_activo) { ?>
				document.form1.action = 'https://<?=$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']?>';
				<? }
					else { ?>
				document.form1.action = 'http://<?=$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']?>';
				<? } ?>
				
				document.form1.submit();
			}
		}
	}


	function Foco() {
		if (document.form1.nick.value=='') {
			document.form1.nick.focus();
		}
		else {
			document.form1.clave.focus();
		}
	}


	var nav4 = window.Event ? true : false;
	function TeclaPalabra (evt) {
		var key = nav4 ? evt.which : evt.keyCode;
		
		if (key == 13) {document.form1.submit();}
	}

</script>


</head>

<body onLoad="Foco();">
<div align="center">
<table width="800" height="500" border="0" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF">

<tr>
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
<form action="" method="post" name="form1" id="form1">
	<div align="center"><span class="mensaje"><?=$mensa?></span></div>
    <table width="256" border="0" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF">
  <tr>
    <td class="header">Usuario</td>
    <td class="td2">
      <input name="nick" type="text" id="nick" size="18" value="<?=$ultimo_usuario?>">
    </td>
  </tr>
  <tr>
    <td class="header">Clave</td>
    <td class="td2">
      <input name="clave" type="password" id="clave" size="18" onKeyPress="javascript: TeclaPalabra(event);">
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="td1"><br>
      <input type="button" name="btnIngresar" id="btnIngresar" value="Ingresar" onClick="Ingreso();"></td>
    </tr>
</table>
<br>
<a href="login.php?accion=consultante">Ingresar como consultante</a><br>
<br>
<br>
<br>
<br>
</form>
    
    </td>
  </tr>
</table>
</div>
</body>
</html>
