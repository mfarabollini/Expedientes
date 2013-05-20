<? include('../inc/inicio.inc.php'); ?>
<? include('../class/expediente.class.php'); ?>
<?
$expediente = new Expediente();
$conteoUlt = 0;

   if(isset($_GET["numero"]))
	  {
	   if($_GET['numero']!='')
			{

				$expd = $_GET['numero'];
				$rs_busq = $conn->Execute("SELECT A.numero FROM expedientes A LEFT JOIN  destinos AS b ON b.id_destino = A.id_ubicacion_actual WHERE b.destino != 'MESA DE ENTRADAS' AND A.tipo = 'L' AND A.numero = ".$expd);
				$conteoUlt = $rs_busq->fields['numero'];
				if($conteoUlt == $expd)
				{
					$conn->Execute("INSERT INTO orden_dia_temp (expediente) VALUES ({$expd})");
					echo '0';
				} else 
				{
					$rs_busq = $conn->Execute("SELECT A.numero FROM expedientes A WHERE A.tipo <> 'L' AND A.numero = ".$expd);
					if (!$rs_busq->EOF)
					{
						echo '1';
					}
					else
					{
						$rs_busq = $conn->Execute("SELECT A.numero FROM expedientes A JOIN LEFT destinos AS b ON b.id_destino = A.id_ubicacion_actual WHERE b.destino = 'MESA DE ENTRADAS' AND A.numero = ".$expd);
						if (!$rs_busq->EOF)
						{
							echo '2';
						} else {
							echo '-1';
						}
					}
				}
			}

	}
?> 
