<?php

include('../inc/inicio.inc.php');

echo "<h1>Script de inicializaci&oacute;n de la tabla `tags`</h1>";

$error = false;
$sql = "SELECT numero FROM expedientes WHERE numero NOT IN (SELECT numero_expediente FROM tags)";
$rs = $conn->Execute($sql);


if(count($rs) > 0 && !$rs->EOF) {

	while (!$rs->EOF) {
	
		$rows = "";
	
		$rows .= "({$rs->fields['numero']}, '')";
		$rs->MoveNext();

		for($i = 0 ; $i < 1000 && !$rs->EOF ; $i++) {
			$rows .= ", ({$rs->fields['numero']}, NULL)";
			$rs->MoveNext();
		}
		insertRows($rows);			
	}
	
	if($error)
		echo "<br><br><strong><span style=\"color: red\" >Hubo un error en una de las consultas.</span></strong>";
	else
		echo "<br><br>La inicializaci&oacute;n de la tabla 'tags' se realiz&oacute; con &eacute;xito!!";
}


function insertRows($rows) {
	global $conn, $error;
	
	if($rows!='') {
		$sql = "INSERT INTO tags (numero_expediente, tags) VALUES " . $rows;
		$conn->Execute($sql);
	
		if($conn->ErrorNo()) {
			echo "<strong><span style=\"color: red\" >Se produjo un error en la consulta: </span></strong> " . $conn->ErrorMsg() . "</br>";
			$error = true;
		} else
			echo "La consulta se realizo <strong>OK</strong></br>";
	}
}

?>
