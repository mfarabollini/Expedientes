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
				$rs_busq = $conn->Execute("SELECT A.numero FROM expedientes A WHERE A.numero = ".$expd);
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