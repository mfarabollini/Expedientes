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
				$rs = $conn->Execute("DELETE FROM lista_preferencia_temp WHERE expediente = ".$expd);
				if($conn->Affected_Rows() === 0)
					echo "1";
				else
					echo "0";
				exit;
			}
	} 
	echo "-1";
?> 
