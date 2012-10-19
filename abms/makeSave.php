<? include('../inc/inicio.inc.php'); ?>
<? include('../class/expediente.class.php'); ?>
<?

DesactivarSSL();
CheckPerfiles('SD');

if (isset($_GET['accion'])) 
{
	$accion = $_GET['accion'];
	if ($accion == 'grabar') 
	{
		//print_r($_GET);
		$expediente = new Expediente();
		$id_ub_actual = "";
		$id_forma= "";
		$arrDat = split(";",$_GET['lista']);
		$fecha = $expediente->ValorGetFecha('fecha');
		$id_ub_actual = $_GET['destino'];
		$id_forma = $_GET['aprob'];
		$tip_aprb = $_GET['taprob'];
		for ($i = 0; $i <= ($_GET['len'] -1); $i++)
		{
			//inserto el movimiento 
			//$conn->debug = true;
			$numero = $arrDat[$i];
			/*$sql = "insert into movimientos (numero, id_ubicacion_actual, fecha, comentario) ".
				   "values ".
				   "(".$numero.",".$id_ub_actual.",".$fecha.",'Movimiento Prescripcion')";
			if ($conn->Execute($sql) === false) echo $conn->ErrorMsg();
			*/
			//actualizo el expediente
			$sql = "update expedientes set id_ubicacion_actual=".$id_ub_actual.", id_aprobacion=".$id_forma.", tipo_aprobacion='".$tip_aprb."' , fec_aprobacion =".$fecha." where numero=".$numero;
			if ($conn->Execute($sql) === false) echo $conn->ErrorMsg();
			//echo $sql;
			Auditoria($numero, 'Movimiento Masivo Prescrición');
		}
		echo '0'; 
	}
	else
	{
		echo '1';
	}
}
else
{
	echo '1';
}
?>