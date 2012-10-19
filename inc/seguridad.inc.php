<?

// ---------------------------- CONTROL BASICO DE SEGURIDAD ----------------------------

	if (!isset($_SESSION['id_usuario'])) {
		header("Location: ./../general/login.php?mensa=".urlencode("No se encuentra logueado al sistema."));
		exit;
	}
	
	//para que la sesion venza en N segundos (en este caso 1 hora)
	if (time() >  $_SESSION['tiempo_ultimo']+3600) {
		header("Location: ./../general/login.php?mensa=".urlencode("Ha sobrepasado el tiempo maximo de inactividad, vuelva a escribir su clave."));
		exit;
	}
	else {$_SESSION['tiempo_ultimo'] = time();}
	
	
	function CheckPerfiles($perfiles)
	{
		if (strpos($perfiles, $_SESSION['perfil']) === false)
		{
			header("Location: ./../general/login.php?mensa=".urlencode("Ha intentado ingresar en un modulo donde no tiene permisos asignados."));
			exit;
		}
		
	}
	
	
	function PerfilAutorizado($perfiles)
	{
		if (strpos($perfiles, $_SESSION['perfil']) === false)
			return false;
		else
			return true;
		
	}	


	function Auditoria($p_id_exp, $p_detalle) {
		global $conn;
		
		$fecha_alta = AhoraFechaHora();
		$conn->Execute("insert into auditoria (id_usuario, fecha, numero_exp, accion) values ".
					   "(".$_SESSION['id_usuario'].",'$fecha_alta',$p_id_exp,'$p_detalle')");
	}
	
?>