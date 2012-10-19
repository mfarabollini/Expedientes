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
		$expediente = new Expediente();
		$id_ub_actual = "";
		$arrDat = split(";",$_GET['lista']);
		$fecha = $expediente->ValorGetFecha('fecha');
		$ub_actual = $_GET['destino'];
		for ($i = 0; $i <= ($_GET['len'] -1); $i++)
		{
			//inserto el movimiento
			//$conn->debug = true;
			//b�sco el destino cargado, si no lo encuentro lo grabo y me traigo el nuevo id
			$rs_busq = $conn->Execute("select id_destino, destino from destinos where destino = '".$_GET['destino']."'");
			$conteoUlt = $rs_busq->fields['id_destino'];
			//print ("select id_destino, destino from destinos where destino = '".$_GET['destino']."'");
			//print $rs_busq->fields['id_destino'];

			if($conteoUlt == '')
			{ 
					$sql = "insert into destinos (destino) ".
							"values ".
							"('".$_GET['destino']."')";
					if ($conn->Execute($sql) === false) echo $conn->ErrorMsg();
	
					$rs_busq = $conn->Execute("select id_destino, destino from destinos where destino = '".$_GET['destino']."'");
					$conteoUlt = $rs_busq->fields['id_destino'];
					if($conteoUlt != '')
					{  
						//ok lo encontr�
						$id_ub_actual = $rs_busq->fields['id_destino'];
					}
					else
					{
						$id_ub_actual = 1103;
					}
			}
			else
			{
				$id_ub_actual = $rs_busq->fields['id_destino'];
			}
			$numero = $arrDat[$i];			
			$sql = "insert into movimientos (numero, id_ubicacion_actual, fecha, comentario) ".
				   "values ".
				   "(".$numero.",".$id_ub_actual.",".$fecha.",'Movimiento')"; 
			if ($conn->Execute($sql) === false) echo $conn->ErrorMsg();
			
			//actualizo el expediente
			/*$sql = "update expedientes set id_ubicacion_actual=".$ub_actual." where numero=".$numero;
			if ($conn->Execute($sql) === false) echo $conn->ErrorMsg();
			*/
			Auditoria($numero, 'Movimiento Masivo Preferencia');
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