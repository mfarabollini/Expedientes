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
				$rs_busq = $conn->Execute("SELECT A.numero FROM expedientes A WHERE A.numero = ".$expd." AND (A.fec_aprobacion IS NULL OR A.fec_aprobacion = '' OR A.fec_aprobacion = '00000000') AND (A.id_aprobacion IS NULL OR A.id_aprobacion = '') AND (A.tipo_aprobacion IS NULL OR A.tipo_aprobacion = '') AND (A.id_aprobacion IS NULL OR A.id_aprobacion = '' OR A.id_aprobacion = 0)");
				$conteoUlt = $rs_busq->fields['numero'];
				if($conteoUlt == $expd)
				{ 
					echo '0';
				} else 
				{
					echo '1';
				}
			}

	}
?> 
