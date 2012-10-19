<? 
	session_start();
	include('../inc/conexion.inc.php');
	include('../inc/funciones.inc.php');
	
	$conn->Execute("update expedientes set impreso=null where id_grupo is not null");
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Reset</title>
<style type="text/css">
<!--
.Estilo1 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: small;
	color: #0000FF;
}
-->
</style>
</head>

<body>
<span class="Estilo1">Marcados como no impresos (lote de datos).</span>
</body>
</html>
