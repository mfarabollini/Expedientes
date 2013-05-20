<script LANGUAGE="javascript" src="../inc/calendario/calendar.js"></script>
<script LANGUAGE="javascript" src="../inc/calendario/calendar-setup.js"></script>
<script LANGUAGE="javascript" src="../inc/calendario/lang/calendar-sp.js"></script>
<link rel='stylesheet' type='text/css' media='all' href='../inc/calendario/calendar-blue2.css'>

<?

if (!isset($_SESSION["FormatoFecha"])) {$_SESSION["FormatoFecha"] = 'dd/mm/yyyy';}

function CalendarioCreaInput($p_cldNombre, $p_cldValor, $p_tag) {

	$cldFecha = trim(str_replace('-','',$p_cldValor));
	//me fijo si el valor viene en formato oracle, si es asi calculo el formato de $_SESSION["FormatoFecha"]
	if (strlen($cldFecha) == 8) {
		//Guardo la fecha oracle para usar mas adelante en el hidden
		$cldFecha_oracle = $cldFecha;
		
		//Armo la fecha segun $_SESSION["FormatoFecha"]
		$vYYYY = substr($cldFecha, 0, 4);
		$vMM = substr($cldFecha, 4, 2);
		$vDD = substr($cldFecha, 6, 2);
		
		$cldFecha = strtoupper($_SESSION["FormatoFecha"]);
		$cldFecha = str_replace("YYYY", $vYYYY, $cldFecha);
		$cldFecha = str_replace("MM", $vMM, $cldFecha);
		$cldFecha = str_replace("DD", $vDD, $cldFecha);
	}
	else  {//si vino en formato $_SESSION["FormatoFecha"] calculo el formato oracle
		switch ($_SESSION["FormatoFecha"]):
			case "dd/mm/yyyy":
				$cldFecha_oracle = substr($cldFecha, 6, 4) & substr($cldFecha, 3, 2) & substr($cldFecha, 0, 2);
				break;

			case "mm/dd/yyyy":
				$cldFecha_oracle = substr($cldFecha, 6, 4) & substr($cldFecha, 0, 2) & substr($cldFecha, 3, 2);
				break;

			case "yyyy/mm/dd":
				$cldFecha_oracle = substr($cldFecha, 0, 4) & substr($cldFecha, 5, 2) & substr($cldFecha, 8, 2);
				break;

			case "yyyy/dd/mm":
				$cldFecha_oracle = substr($cldFecha, 0, 4) & substr($cldFecha, 8, 2) & substr($cldFecha, 5, 2);
				break;

		endswitch;
	}
	
	
	//Adapto session("FormatoFecha") al formato de fecha del calendario
	$cldFormatoFecha = strtoupper($_SESSION["FormatoFecha"]);
	$cldFormatoFecha = str_replace("DD", "%d", $cldFormatoFecha);
	$cldFormatoFecha = str_replace("MM", "%m", $cldFormatoFecha);
	$cldFormatoFecha = str_replace("YYYY", "%Y", $cldFormatoFecha);
	
	
	//Armo el calendario
	$cldCalendario = 
					 //"<input type='text' size=12 name='x". $p_cldNombre ."' id='x". $p_cldNombre ."' readonly='1' value='". $cldFecha ."' class='xFormsFlatRo' onfocus='".$p_cldNombre."_triger.click();' $p_tag>".
					 "<input type='text' size=12 name='x". $p_cldNombre ."' id='x". $p_cldNombre ."' value='". $cldFecha ."' class='xFormsFlatRo' >".
					 "<input type='hidden' name='". $p_cldNombre ."' id='". $p_cldNombre ."' value='". $cldFecha_oracle ."'>".
					 "&nbsp;<img src='../inc/calendario/img.gif' style='cursor: pointer;' id='". $p_cldNombre ."_triger'>".
					 "&nbsp;<img src='../imagenes/cancelar.gif' style='cursor: pointer;' onclick='document.getElementById(\"x".$p_cldNombre."\").value=\"\"'>".
					 "<script type='text/javascript'>".
					 "    Calendar.setup({".
					 "        inputField     :    'x". $p_cldNombre ."',".
					 "        displayArea    :    '". $p_cldNombre ."',".
					 "        ifFormat       :    '". $cldFormatoFecha ."',".
					 "		 daFormat	     :	 '%Y%m%d',".
					 "		 firstDay	     :    0,".
					 "        button         :    '". $p_cldNombre ."_triger',".
					 "        align          :    'B2',".
					 "        singleClick    :    true,".
					 "		 showOthers      :    false,".
					 "		 step            :    1".
					 "    });".
					 "</script>";

	return $cldCalendario;

}

?>