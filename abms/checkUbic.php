<? include('../inc/inicio.inc.php'); ?>
<? include('../class/expediente.class.php'); ?>
<?
$expediente = new Expediente();
$conteoUlt = '';

   if(isset($_GET["forma"]))
	  {
	   if($_GET['forma']!= '')
			{

				$rs_busq = $conn->Execute("select id_destino, destino from destinos where destino = '".$_GET['forma']."'");
				$conteoUlt = $rs_busq->fields['id_destino'];
				if($conteoUlt == '')
				{ 
					//si no existe lo agrego
					$sql = "insert into destinos (destino) ".
							"values ".
							"('".$_GET['forma']."')";
					if ($conn->Execute($sql) === false) echo $conn->ErrorMsg();
					
					$rs_busq = $conn->Execute("select id_destino, destino from destinos where destino = '".$_GET['forma']."'");
					$conteoUlt = $rs_busq->fields['id_destino'];
					if($conteoUlt != '')
					{ 
						//ok lo encontró
						echo $rs_busq->fields['id_destino'];
					}
					else
					{
						echo '0';
					}						
				} 
				else 
				{
					echo $rs_busq->fields['id_destino'];
				}
			}
	}
?> 