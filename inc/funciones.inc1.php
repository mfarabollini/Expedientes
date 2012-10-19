<?
$ssl_activo = false;
$usar_utf8 = true;


//--------------------------------------------------------------------------------------------------------------------------------

function FormatoFechaNormal($pfecha) { //yyyy-mm-dd => dd/mm/yyyy
	if ($pfecha != '') {
		return substr($pfecha, 8, 2)."/".substr($pfecha, 5, 2)."/".substr($pfecha, 0, 4);
	}
	else {
		return '';
	}
}

//--------------------------------------------------------------------------------------------------------------------------------

function utf8_dec2($texto)
{
	global $usar_utf8;
	if (!$usar_utf8)
		return utf8_decode($texto);
	else
		return $texto;
}

//--------------------------------------------------------------------------------------------------------------------------------

function utf8_dec($texto)
{
	global $usar_utf8;
	if ($usar_utf8)
		return utf8_decode($texto);
	else
		return $texto;
}

//--------------------------------------------------------------------------------------------------------------------------------

function utf8($texto)
{
	global $usar_utf8;
	if ($usar_utf8)
		return utf8_encode($texto);
	else
		return $texto;
}

//--------------------------------------------------------------------------------------------------------------------------------

function FormatoFecha($fecha)
{
	if ($fecha == '')
		return '';
	else
	{
		$vecFecha = split('-', $fecha);
		return $vecFecha[2].'/'.$vecFecha[1].'/'.$vecFecha[0];
	}	
}

//--------------------------------------------------------------------------------------------------------------------------------

function MandarMail($destino, $asunto, $mensaje)
{
	$eol="\r\n";
	$mime_boundary=md5(time());
	
	$fromname = 'Sistema OCMI';
	$fromaddress = 'sistemaocmi@gmail.com';
	
	# Common Headers
	$headers .= "From: ".$fromname."<".$fromaddress.">".$eol;
	$headers .= "Reply-To: ".$fromname."<".$fromaddress.">".$eol;
	$headers .= "Return-Path: ".$fromname."<".$fromaddress.">".$eol; 
	$headers .= "Message-ID: <".time()."-".$fromaddress.">".$eol;
	$headers .= "X-Mailer: PHP v".phpversion().$eol;  // These two to help avoid spam-filters
	
	mail($destino, $asunto, $mensaje, $headers);

	return true;
}

//--------------------------------------------------------------------------------------------------------------------------------

function CorrigeComillas($cadena)
{
	return str_replace("'", "´", str_replace('"', "´", $cadena));
}

//--------------------------------------------------------------------------------------------------------------------------------

function ActivarSSL() {
	global $ssl_activo;
	
	if ($ssl_activo)
	{
		if (isset($_SERVER['QUERY_STRING'])) {$variables = '?'.$_SERVER['QUERY_STRING'];}
		else {$variables = '';}
	
		if ($_SERVER['HTTPS']!="on") {
				header ('Location: https://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].$variables);
				exit;
		}
	}
}

//--------------------------------------------------------------------------------------------------------------------------------

function DesActivarSSL() {
	global $ssl_activo;
	
	if ($ssl_activo)
	{
		if (isset($_SERVER['QUERY_STRING'])) {$variables = '?'.$_SERVER['QUERY_STRING'];}
		else {$variables = '';}
	
		if ($_SERVER['HTTPS']=="on") {
				header ('Location: http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].$variables);
				exit;
		}
	}
}

//--------------------------------------------------------------------------------------------------------------------------------

function QuitaCaraceresFeos($pCadena) {
	$cad = trim($pCadena);
	$cad = str_replace("'", "´", $cad);
	$cad = str_replace('"', '´', $cad);
	$cad = str_replace('/', '|', $cad);
	$cad = str_replace('\\', '|', $cad);

	return $cad;
}

//--------------------------------------------------------------------------------------------------------------------------------

function ArmaCombo($p_id, $p_tabla, $p_campo_desc, $p_tag, $p_valor, $p_con_elegir) {
	global $conn;
	
	echo "<select name='$p_id' id='$p_id' $p_tag>";
    
	if ($p_con_elegir) {
		echo '<option value="null">[Elegir...]</option>';
	}

	$rs_combo = $conn->Execute("select $p_id, $p_campo_desc from $p_tabla order by upper($p_campo_desc)");
	
	while (!$rs_combo->EOF) {
		if ($rs_combo->fields[$p_id]==$p_valor) {$sel = 'selected ';}
		else {$sel = '';}
		
		echo '<option '.$sel.'value="'.$rs_combo->fields[$p_id].'">'.$rs_combo->fields[$p_campo_desc].'</option>';
		$rs_combo->MoveNext();
	}
	
	echo '</select>';
}

//--------------------------------------------------------------------------------------------------------------------------------

function ArmaComboWhere($p_id, $p_tabla, $p_campo_desc, $p_tag, $p_valor, $p_cond_where, $p_con_elegir) {
	global $conn;
	
	echo "<select name='$p_id' id='$p_id' $p_tag>";
    
	if ($p_con_elegir) {
		echo '<option value="null">[Elegir...]</option>';
	}

	$rs_combo = $conn->Execute("select $p_id, $p_campo_desc from $p_tabla where $p_cond_where order by upper($p_campo_desc)");
	
	while (!$rs_combo->EOF) {
		if ($rs_combo->fields[$p_id]==$p_valor) {$sel = 'selected ';}
		else {$sel = '';}
		
		echo '<option '.$sel.'value="'.$rs_combo->fields[$p_id].'">'.$rs_combo->fields[$p_campo_desc].'</option>';
		$rs_combo->MoveNext();
	}
	
	echo '</select>';
}

//--------------------------------------------------------------------------------------------------------------------------------

function AhoraFechaHora() {
	return date('Y-m-d H:i:s');
}

//--------------------------------------------------------------------------------------------------------------------------------

function AhoraFecha() {
	return date('Y-m-d');
}

//--------------------------------------------------------------------------------------------------------------------------------

?>