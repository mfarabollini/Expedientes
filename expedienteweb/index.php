<?php
//session_save_path('/home/glassfish/tmp');
session_start();
include('inc/db_data.php');
include('inc/funciones.inc.php');

// Este valor debe ser mayor o igual que 3
$caracteres_captcha = 6;

$numero = '';
$id_causante_txt = '';
$tipo_aprobacion = '';
$caratula = '';
$fecha_ingreso = '';

$vacio = true;
$filtroSQL = '';
$order = '';
/*
 * Toma los valores del formulario si es que los hay.
*/

	
if($_POST['numero'] != '') {
	$numero = $_POST['numero'];
	$vacio = false;
	$filtroSQL .= " AND numero={$numero} ";
}

if($_POST['id_causante_txt'] != '') {
	$id_causante_txt = $_POST['id_causante_txt'];
	$vacio = false;
	$filtroSQL .= " AND id_causante_txt LIKE '%{$id_causante_txt}%' ";
	$order = "id_causante_txt" ;
}

if($_POST['tipo_aprobacion'] != '') {
	$tipo_aprobacion = $_POST['tipo_aprobacion'];
	$vacio = false;
	$filtroSQL .= " AND tipo_aprobacion LIKE '%{$tipo_aprobacion}%' ";
}

if($_POST['caratula'] != '') {
	$caratula = $_POST['caratula'];
	$vacio = false;
	$filtroSQL .= " AND caratula LIKE '%{$caratula}%' ";
}


if($_POST['fecha_ingreso'] != '') {
	$fecha_ingreso = $_POST['fecha_ingreso'];
	$fecha_ingreso_ = explode("/", $fecha_ingreso);
	$fecha_ingreso_ = $fecha_ingreso_[2] . "-" . $fecha_ingreso_[1] . "-" . $fecha_ingreso_[0];
	$vacio = false;
	$filtroSQL .= " AND fec_alta LIKE '{$fecha_ingreso_} %' ";
}


//Datos del formulario de busqueda de Normas
if($_POST['nroNorma'] != '') {
	$nroNorma = $_POST['nroNorma'];
	$vacio = false;
	$filtroSQLNorma .= " AND nro_norma={$numero} ";
}

if($_POST['tipoNorma'] != '') {
	$tipo_norma = $_POST['tipoNorma'];
	$vacio = false;
	$filtroSQL .= " AND tipo_norma LIKE '%{$tipo_norma}%' ";
}

if($_POST['fecAprob'] != '') {
	$fecha_aprobacion = $_POST['fec_aprob'];
	$fecha_aprobacion_ = explode("/", $fecha_aprobacion);
	$fecha_aprobacion_ = $fecha_aprobacion_[2] . "-" . $fecha_aprobacion_[1] . "-" . $fecha_aprobacion_[0];
	$vacio = false;
	$filtroSQL .= " AND fec_aprob LIKE '{$fecha_aprobacion_} %' ";
}


/*
 * Obtiene la accion
*/
$accion = 'form_busqueda';

if(isset($_GET['accion']))
	$accion = $_GET['accion'];
elseif(isset($_POST['accion']))
	$accion = $_POST['accion'];


if($accion!='buscar' && $accion!='imprimir' && $accion!='buscarNorma' && $accion!='imprimirNorma')	
	$accion = 'form_busqueda';

if($accion != 'form_busqueda') {
	if ($vacio) {
		$error = "Debe llenar al menos un campo.";
		$accion = 'form_busqueda';
	}

	if ($_SESSION["security_code"]!=$_POST["captcha"]) {
		if(!isset($_POST['volver'])) {
			$error = "El código de seguridad es incorrecto.";
			$accion = 'form_busqueda';
		}
	}
}
// Si es true, significa que se busco algo
if($accion == 'buscar' || $accion == 'imprimir') {
	$link = mysql_connect($server, $user, $pass);

	// La funcion de sincronizar debe estar si o si despues de hacer la conexion a MySQL
	if(!sincronizar_tabla()) {
		echo "Error fatal: \"No se puede sincronizar la tabla de expedientes.\"";
	}

	if (!$link)
	    die('Could not connect: ' . mysql_error());

	$db_selected = mysql_select_db($db_name, $link);
	if (!$db_selected)
	    die ('Can\'t use foo : ' . mysql_error());

	$sqlBusqueda = 	"SELECT " . 
			"numero, ". 
			"id_causante, id_causante_txt, ".
			"tipo_aprobacion, ".
			"caratula, ".
			"fec_alta " .
			"FROM expedientes_legislativos WHERE 1=1 " . $filtroSQL;

	if($order!='')
		$sqlBusqueda .= " ORDER BY " . $order;

	$result = mysql_query($sqlBusqueda, $link);

	if (!$result)
	    die('Invalid query: ' . mysql_error());

	if ($accion == 'imprimir') 
	{
		header ("content-disposition: attachment;filename=Resultado_busqueda.pdf");

		include('inc/pdf/class.ezpdf.php');
		//Auditoria('null', 'Imprimio busqueda con filtro:<br>'.$expediente->FiltroBuscar());

		$pdf =& new Cezpdf('LEGAL', 'landscape');
		$pdf->selectFont('inc/pdf/fonts/Helvetica.afm');
		$datacreator = array (
						  'Title'=>'Resultado de la busqueda',
						  'Author'=>'Sistema de expedientes',
						  'Subject'=>'',
						  'Creator'=>'',
						  'Producer'=>'Sistema de expedientes'
						  );
		$pdf->addInfo($datacreator);
		
		//titulo
		$pdf->ezText(utf8_dec("CONCEJO MUNICIPAL DE ROSARIO\n\nSistema de Gestión Parlamentaria")."\n",15, array('justification'=>'center'));
		$pdf->ezText("");

		$pdf->ezText(utf8_dec2("Fecha de la consulta: ".date('d/m/Y') . "                                                            "),10, array('justification'=>'right'));
		$pdf->ezText(utf8_dec2("Hora de la consulta: ".date('H:i:s') . "                                                            "),10, array('justification'=>'right'));
		$pdf->ezText(utf8_dec2("---------------------------------------------------------------------------------------------------------------------------------------------------------"),10, array('justification'=>'center'));
		$pdf->rectangle(170,450,660,140);
		$pdf->ezText("");
		$pdf->ezText("");
		$pdf->ezText("");
		
		$pdf->ezStartPageNumbers(590,20,10,'','{PAGENUM} de {TOTALPAGENUM}',1);
		$pos = 0;

		while ($row = mysql_fetch_assoc($result))
		{
			$vec[$pos]['numero'] = number_format($row['numero'], 0, ',', '.');
			$vec[$pos]['causante'] = utf8_dec2($row['id_causante_txt']);
			$vec[$pos]['tipo_de_aprobacion'] = utf8_dec2($row['tipo_aprobacion']);
			$vec[$pos]['caratula'] = utf8_dec2($row['caratula']);
			$vec[$pos]['fecha_de_alta'] = FormatoFechaNormal($row['fec_alta']);
			$pos++;
		}

		$opciones_columnas = array(
			'numero' => array('width'=>50), 
			'causante' => array('width'=>220),
			'tipo_de_aprobacion' => array('width'=>135),
			'caratula' => array('width'=>220),
			'fecha_de_alta' => array('width'=>53),
		);
		
		$pdf->ezTable(	$vec, 
				Array(
					'numero' => utf8_dec("Número"),
                		        'causante' => utf8_dec("Autor/es del proyecto o expediente"),
		                        'tipo_de_aprobacion' => utf8_dec("Tipo de aprobación"),
                		        'caratula' => utf8_dec("Carátula"),
		                        'fecha_de_alta' => utf8_dec("Fecha de alta")), 
				'',
				array('colGap'=>1 ,'cols'=>$opciones_columnas)
				);
		$pdf->ezStream();
		mysql_close($link);
		exit;
	}
}elseif ($accion=='buscarNorma' || $accion == 'imprimirNorma'){
	//Busqueda de Normas
	$link_normas = mysql_connect($server, $user, $pass);
	
	// La funcion de sincronizar debe estar si o si despues de hacer la conexion a MySQL
	if(!sincronizar_tabla_normas()) {
		echo "Error fatal: \"No se puede sincronizar la tabla de normas.\"";
	}
	
	if (!$link_normas)
		die('Could not connect: ' . mysql_error());
	
	$db_selected = mysql_select_db($db_name, $link_normas);
	if (!$db_selected)
		die ('Can\'t use foo : ' . mysql_error());
	
	$sqlBusqueda = 	"SELECT * " .
			"FROM normas WHERE 1=1 " . $filtroSQL;
	
	if($order!='')
		$sqlBusqueda .= " ORDER BY " . $order;
	
	$result = mysql_query($sqlBusqueda, $link_normas);
	
	if (!$result)
		die('Invalid query: ' . mysql_error());
	
	if ($accion == 'imprimirNorma')
	{
		header ("content-disposition: attachment;filename=Resultado_busqueda.pdf");
	
		include('inc/pdf/class.ezpdf.php');
		//Auditoria('null', 'Imprimio busqueda con filtro:<br>'.$expediente->FiltroBuscar());
	
		$pdf =& new Cezpdf('LEGAL', 'landscape');
		$pdf->selectFont('inc/pdf/fonts/Helvetica.afm');
		$datacreator = array (
				'Title'=>'Resultado de la busqueda',
				'Author'=>'Sistema de expedientes',
				'Subject'=>'',
				'Creator'=>'',
				'Producer'=>'Sistema de expedientes'
		);
		$pdf->addInfo($datacreator);
	
		//titulo
		$pdf->ezText(utf8_dec("CONCEJO MUNICIPAL DE ROSARIO\n\nSistema de Gestión Parlamentaria")."\n",15, array('justification'=>'center'));
		$pdf->ezText("");
	
		$pdf->ezText(utf8_dec2("Fecha de la consulta: ".date('d/m/Y') . "                                                            "),10, array('justification'=>'right'));
		$pdf->ezText(utf8_dec2("Hora de la consulta: ".date('H:i:s') . "                                                            "),10, array('justification'=>'right'));
		$pdf->ezText(utf8_dec2("---------------------------------------------------------------------------------------------------------------------------------------------------------"),10, array('justification'=>'center'));
		$pdf->rectangle(170,450,660,140);
		$pdf->ezText("");
		$pdf->ezText("");
		$pdf->ezText("");
	
		$pdf->ezStartPageNumbers(590,20,10,'','{PAGENUM} de {TOTALPAGENUM}',1);
		$pos = 0;
	
		while ($row = mysql_fetch_assoc($result))
		{
			$vec[$pos]['nro_norma'] = $row['nro_norma'];
			$vec[$pos]['tipo_norma'] = utf8_dec2($row['tipo_norma']);
			$vec[$pos]['dsc_norma'] = utf8_dec2($row['tipo_norma']);
			$vec[$pos]['fec_aprob'] = FormatoFechaNormal($row['fec_aprob']);
			$pos++;
		}
	
		$opciones_columnas = array(
				'nro_norma' => array('width'=>50),
				'tipo_norma' => array('width'=>100),
				'dsc_norma' => array('width'=>220),
				'fec_aprob' => array('width'=>53),
		);
	
		$pdf->ezTable(	$vec,
				Array(
						'nro_norma' => utf8_dec("Número"),
						'tipo_norma' => utf8_dec("Tipo de Norma"),
						'dsc_norma' => utf8_dec("Desripcion"),
						'fec_aprob' => utf8_dec("Fecha de aprobacion")),
				'',
				array('colGap'=>1 ,'cols'=>$opciones_columnas)
		);
		$pdf->ezStream();
		mysql_close($link_normas);
		exit;
	}
};


/*
 * Aqui comienza la web.
*/
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<link src="favicon.ico" rel="shortcut icon" href="/resources/favicon.ico"/>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Concejo Municipal de la Ciudad de Rosario</title>
	<link type="text/css" rel="stylesheet" href="/resources/style.css"></link>
	<link type="text/css" rel="stylesheet" href="/resources/style3Columnas.css"></link>
	<style>
		form#busqueda {
			display: block;
			margin: 30px auto;
			width: 400px;
		}
		form#busqueda label {
			display: block;
			width: auto;
		}
		form#busqueda input {
			margin: 0 0 10px 0;
		}
		form#busquedaNorma {
			display: none;
			margin: 30px auto;
			width: 400px;
		}
		form#busquedaNorma label {
			display: block;
			width: auto;
		}
		form#busquedaNorma input {
			margin: 0 0 10px 0;
		}
		div.error {
			margin: 3px 0 0;
			height: 35px;
			color: red;
			font-size: 10pt;
		}
		table {
			font-size: 10pt;
		}
		#radio{
			min-width: 400px;
			margin: 30px 40%;
		}
		

	</style>
	<script>
		function GenerarPDF(expediente)
		{
			var form_pdf = document.getElementById('form_pdf');
			form_pdf.action = "exp_detalles.php?action=pdf";
			form_pdf.numero.value = expediente;
			form_pdf.submit();
		}

		function Ver(expediente)
		{
			var form_pdf = document.getElementById('form_pdf');
			form_pdf.action = "exp_detalles.php?action=ver";
			form_pdf.numero.value = expediente;
			form_pdf.submit();
		}

		function Select(sel){
			var formExp = document.busqueda;
			var formNormas    = document.busquedaNorma;

			
			if (sel == 'normas')
			{
				formExp.style.display = "none";
				formNormas.style.display = "block";
			}

			if (sel == 'expedientes')
			{
				formExp.style.display = "block";
				formNormas.style.display = "none";
			}
		}

	</script>
</head>
<body class="thrColFixHdr">
<div id="container">
	<a href="/home.jsp">
	<div id="header">
	    <div id="Hoy">
		
		<?php
			$dias = Array(1=>"lunes",2=>"martes",3=>"miercoles",4=>"jueves",5=>"viernes",6=>"sabado",7=>"domingo");

			$meses = Array(1=>"enero",2=>"febrero",3=>"marzo",4=>"abril",5=>"mayo",6=>"junio",7=>"julio",8=>"agosto",9=>"septiembre",10=>"octubre",11=>"noviembre",12=>"diciembre");
			$dia = date(N);
			$diaNum = date(j);
			$mes = date(n);
			$anio = date(Y);
			echo "Rosario, " . $dias[$dia] . " " . $diaNum . " de " . $meses[$mes] . " del " . $anio;

		?>
	 </div>
	</div>
	</a>

<?php
if($accion == 'buscar') {

?>
	<table border="1" width="780" >
		<tr>
			<td align="center" width="60" >Número</td>
			<td align="center" width="200" >Autor/es del proyecto o expediente</td>
			<td align="center" width="155" >Tipo de Aprobación</td>
			<td align="center" width="200" >Caratula</td>
			<td align="center" width="100" >Fecha de Ingreso</td>
			<td align="center" width="20" ></td>
		</tr>
<?php

	if(mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_assoc($result)) {
?>
		<tr>
			<td align="center" ><?php echo number_format($row['numero'], 0, ',', '.'); ?></td>
			<td align="center" ><div style="width: 200px" ><?php echo utf8(str_replace(',',', ', $row["id_causante_txt"])); ?></div></td>
			<td align="center" ><div style="width: 155px" ><?php echo utf8($row["tipo_aprobacion"]); ?></div></td>
			<td align="center" ><div style="width: 200px" ><?php echo utf8($row["caratula"]); ?></div></td>
			<td align="center" ><?php echo FormatoFechaNormal($row["fec_alta"]); //substr ?></td>
			<td>
				<a href="javascript: Ver(<?php echo $row["numero"] ?>);">
					<img src="imagenes/ver.gif" alt="Ver detalle" width="16" height="16" border="0" />
				</a>
				<a href="javascript: GenerarPDF(<?php echo $row["numero"] ?>);">
					<img src="imagenes/pdf.gif" alt="Generar PDF" width="16" height="16" border="0" />
				</a>
			</td>
		</tr>
<?php
		}
	} else {
?>
		<tr>
			<td align="center" colspan="8" >No se encontraron resultados para la busqueda realizada.</td>
		</tr>

<?php
	}
?>
	</table>
<br/>
<?php 
	echo "Cantidad de registros encontrados: " . mysql_num_rows($result);
	mysql_close($link);
?>
<br/><br/>
	<form method="POST" action="" name="form_pdf" >
		<input type="hidden" name="numero" value="<?php echo $numero; ?>" />
		<input type="hidden" name="id_causante_txt" value="<?php echo $id_causante_txt; ?>" />
		<input type="hidden" name="tipo_aprobacion" value="<?php echo $tipo_aprobacion; ?>" />
		<input type="hidden" name="caratula" value="<?php echo $caratula; ?>" />
		<input type="hidden" name="fecha_ingreso" value="<?php echo $fecha_ingreso; ?>" />
		<input type="hidden" value="form_busqueda" name="accion" />
		<input type="hidden" value="volver" name="volver" />
		<input type="button" value="Volver" onclick="javascript:with (document.form_pdf){action = ''; target = '_self'; submit();}" />
		<input type="button" value="Imprimir" onclick="javascript:with (document.form_pdf){action = '?accion=imprimir';target = '_blank';submit();}" />
	</form>

<?php

} else {

?>
	<div id="radio" >
		<input type="radio" name="group" value="normas" onclick="Select(this.value)">Normas
		<input type="radio" name="group" value="expedientes" checked onclick="Select(this.value)">Expedientes<br>
	</div>
	
	<form method="POST" action="" name="busqueda" id="busqueda" >
		<label>Número de expediente</label>
			<input name="numero" value="<?php echo $numero; ?>" />
		<label>Autor/es del proyecto o expediente</label>
			<input name="id_causante_txt" value="<?php echo $id_causante_txt; ?>" />

		<label>Tipo de Aprobación</label>
			<input name="tipo_aprobacion" value="<?php echo $tipo_aprobacion; ?>" />

		<label>Carátula</label>
			<input name="caratula" value="<?php echo $caratula; ?>" />

		<label>Fecha de Ingreso</label>
			<input name="fecha_ingreso" value="<?php echo $fecha_ingreso; ?>" /> dd/mm/aaaa
		<div class="captcha" >
			<img src="captcha.php?characters=<?php echo $caracteres_captcha; ?>" alt="captcha image">
			<input type="text" name="captcha" size="<?php echo $caracteres_captcha; ?>" maxlength="<?php echo $caracteres_captcha; ?>">
		</div>
		<div class="error" >
			<?php echo $error; ?>
		</div>
		<input type="hidden" value="buscar" name="accion" />
		<input type="submit" value="Buscar" name="buscar" />
	</form>

	<form method="POST" action="" name="busquedaNorma" id="busquedaNorma" >
		<label>N&uacute;mero norma</label>
			<input name="nroNorma" value="<?php echo $numero; ?>" />
		<label>Tipo norma</label>
			<input name="tipoNorma" value="<?php echo $id_causante_txt; ?>" />

		<label>Fecha de Aprobaci&oacute;n</label>
			<input name="fecAprob" value="<?php echo $fecha_ingreso; ?>" /> dd/mm/aaaa
		<div class="captcha" >
			<img src="captcha.php?characters=<?php echo $caracteres_captcha; ?>" alt="captcha image">
			<input type="text" name="captcha" size="<?php echo $caracteres_captcha; ?>" maxlength="<?php echo $caracteres_captcha; ?>">
		</div>
		<div class="error" >
			<?php echo $error; ?>
		</div>
		<input type="hidden" value="buscarNorma" name="accion" />
		<input type="submit" value="Buscar" name="buscarNorma" />
	</form>

<?php

}

?>
	<div id="footer">
	    <img border="0" usemap="#Map" src="/resources/pie.jpg"/>
	    <map id="Map" name="Map">
		<area href="mailto:info@concejorosario.gov.ar" coords="234,43,391,62" shape="rect"/>
	    </map>
	</div>
</div>
<form action="exp_detalles.php" method="post" name="form_pdf" id="form_pdf" target="_blank">
	<input id="numero" name="numero" type="hidden" value="" />
</form>
</body>
</html>


<?php

function sincronizar_tabla() {
	global $serverTbOrig, $userTbOrig, $passTbOrig, $db_nameTbOrig, $link, $db_name;

	$link2 = mysql_connect($serverTbOrig, $userTbOrig, $passTbOrig);

	if (!$link2)
	    die('No se pueden sincronizar los expedientes: ' . mysql_error($link2));

	$db_selected = mysql_select_db($db_nameTbOrig, $link2);
	if (!$db_selected)
	    die ('No se pueden sincronizar los expedientes : ' . mysql_error($link2));

	$sqlBusqueda = 	"SELECT " .
			"registro_nuevo, ". 
			"numero, ". 
			"letra, ". 
			"anio, ". 
			"num_mensaje, ". 
			"tipo_proy, ".
			"fec_presentacion, ".
			"fec_sesion, ".
			"fec_aprobacion, ".
			"caratula, ".
			"tipo_aprobacion, ".
			"id_causante, id_causante_txt, ".
			"com_destino, com_destino_txt, ".
			"id_aprobacion, id_aprobacion_txt, ".
			"id_ubicacion_actual, id_ubicacion_actual_txt, ".
			"id_grupo, grupo, ".
			"fec_alta, " .
			"decretos, declaraciones, minutas, ordenanzas_y_resoluciones ".
			"FROM expedientes_temporal";


	$result = mysql_query($sqlBusqueda, $link2);
	if (!$result)
	    die('No se pueden sincronizar los expedientes: ' . mysql_error($link2));

	$db_selected2 = mysql_select_db($db_name, $link);

	while ($row = mysql_fetch_assoc($result)) {

		if($row['registro_nuevo'] == 1) {
			$sql = 	"INSERT INTO expedientes_legislativos " .
			"(`numero`, `letra`, `anio`, `num_mensaje`, " . 
			"`tipo_proy`, `fec_presentacion`, `fec_sesion`, `fec_aprobacion`," .
			"`caratula`, `tipo_aprobacion`, `id_causante`, `id_causante_txt`, " . 
			"`com_destino`, `com_destino_txt`, `id_aprobacion`, " .
			"`id_aprobacion_txt`, `id_ubicacion_actual`, " .
			"`id_ubicacion_actual_txt`, `id_grupo`, `grupo`, `fec_alta`, " .
			"`decretos`, `declaraciones`, `minutas`, `ordenanzas_y_resoluciones`) ".
			"VALUES (".
			$row['numero'] . ", ".
			"'" . $row['letra'] . "', ".
			"'" . $row['anio'] . "', " .
			"'" . $row['num_mensaje'] . "', " .
			"'" . $row['tipo_proy'] . "', " .
			"'" . $row['fec_presentacion'] . "', " .
			"'" . $row['fec_sesion'] . "', " .
			"'" . $row['fec_aprobacion'] . "', " .
			"'" . $row['caratula'] . "', ".
			"'" . $row['tipo_aprobacion'] . "', ".
			"'" . $row['id_causante'] . "', ".
			"'" . $row['id_causante_txt'] . "', ".
			"'" . $row['com_destino'] . "', ".
			"'" . $row['com_destino_txt'] . "', ".
			"'" . $row['id_aprobacion'] . "', ".
			"'" . $row['id_aprobacion_txt'] . "', ".
			"'" . $row['id_ubicacion_actual'] . "', ".
			"'" . $row['id_ubicacion_actual_txt'] . "', " .
			"'" . $row['id_grupo'] . "', ".
			"'" . $row['grupo'] . "', ".
			"'" . $row['fec_alta'] . "', " .
			"'" . $row['decretos'] . "', " .
			"'" . $row['declaraciones'] . "', " .
			"'" . $row['minutas'] . "', " .
			"'" . $row['ordenanzas_y_resoluciones'] . "') ";
			

			$r = mysql_query($sql, $link);
			if (!$r && mysql_errno($link)!=1062) {
			    die('Invalid query: ' . mysql_errno($link) . " - " . mysql_error($link));
			}
		} else {
			$sql = 	"UPDATE expedientes_legislativos SET " .
			"letra='" . $row['letra'] . "', ".
			"anio='" . $row['anio'] . "', ".
			"num_mensaje='" . $row['num_mensaje'] . "', ".
			"tipo_proy='" . $row['tipo_proy'] . "', ".
			"fec_presentacion='" . $row['fec_presentacion'] . "', ".
			"fec_sesion='" . $row['fec_sesion'] . "', ".
			"fec_aprobacion='" . $row['fec_aprobacion'] . "', ".
			"caratula='" . $row['caratula'] . "', ".
			"tipo_aprobacion='" . $row['tipo_aprobacion'] . "', ".
			"id_causante='" . $row['id_causante'] . "', ".
			"id_causante_txt='" . $row['id_causante_txt'] . "', ".
			"com_destino='" . $row['com_destino'] . "', ".
			"com_destino_txt='" . $row['com_destino_txt'] . "', ".
			"id_aprobacion='" . $row['id_aprobacion'] . "', ".
			"id_aprobacion_txt='" . $row['id_aprobacion_txt'] . "', ".
			"id_ubicacion_actual='" . $row['id_ubicacion_actual'] . "', ".
			"id_ubicacion_actual_txt='" . $row['id_ubicacion_actual_txt'] . "', ".
			"id_grupo='" . $row['id_grupo'] . "', ".
			"grupo='" . $row['grupo'] . "', ".
			"fec_alta='" . $row['fec_alta'] . "', ".
			"decretos='" . $row['decretos'] . "', " .
			"declaraciones='" . $row['declaraciones'] . "', " .
			"minutas='" . $row['minutas'] . "', " .
			"ordenanzas_y_resoluciones='" . $row['ordenanzas_y_resoluciones'] . "' " .
			" WHERE numero=" . $row['numero'];
			$r = mysql_query($sql, $link);
			if (!$r)
			    die('Invalid query: ' . mysql_error($link));
		}
	}

	$r = mysql_query("DELETE FROM expedientes_temporal", $link2);

	if (!$r)
	    die('Fatal error: Puede haber problemas en la sincronizaciones futuras, comunicarlo al administrador.');

	mysql_close($link2);

	return true;
}


function sincronizar_tabla_normas() {
	global $serverTbOrig, $userTbOrig, $passTbOrig, $db_nameTbOrig, $link, $db_name;

	$link2 = mysql_connect($serverTbOrig, $userTbOrig, $passTbOrig);

	if (!$link2)
		die('No se pueden sincronizar los expedientes: ' . mysql_error($link2));

	$db_selected = mysql_select_db($db_nameTbOrig, $link2);
	if (!$db_selected)
		die ('No se pueden sincronizar los expedientes : ' . mysql_error($link2));

	$sqlBusqueda = 	"SELECT " .
			"registro_nuevo, ".
			"numero, ".
			"letra, ".
			"anio, ".
			"num_mensaje, ".
			"tipo_proy, ".
			"fec_presentacion, ".
			"fec_sesion, ".
			"fec_aprobacion, ".
			"caratula, ".
			"tipo_aprobacion, ".
			"id_causante, id_causante_txt, ".
			"com_destino, com_destino_txt, ".
			"id_aprobacion, id_aprobacion_txt, ".
			"id_ubicacion_actual, id_ubicacion_actual_txt, ".
			"id_grupo, grupo, ".
			"fec_alta, " .
			"decretos, declaraciones, minutas, ordenanzas_y_resoluciones ".
			"FROM expedientes_temporal";


	$result = mysql_query($sqlBusqueda, $link2);
	if (!$result)
		die('No se pueden sincronizar los expedientes: ' . mysql_error($link2));

	$db_selected2 = mysql_select_db($db_name, $link);

	while ($row = mysql_fetch_assoc($result)) {

		if($row['registro_nuevo'] == 1) {
			$sql = 	"INSERT INTO expedientes_legislativos " .
					"(`numero`, `letra`, `anio`, `num_mensaje`, " .
					"`tipo_proy`, `fec_presentacion`, `fec_sesion`, `fec_aprobacion`," .
					"`caratula`, `tipo_aprobacion`, `id_causante`, `id_causante_txt`, " .
					"`com_destino`, `com_destino_txt`, `id_aprobacion`, " .
					"`id_aprobacion_txt`, `id_ubicacion_actual`, " .
					"`id_ubicacion_actual_txt`, `id_grupo`, `grupo`, `fec_alta`, " .
					"`decretos`, `declaraciones`, `minutas`, `ordenanzas_y_resoluciones`) ".
					"VALUES (".
					$row['numero'] . ", ".
					"'" . $row['letra'] . "', ".
					"'" . $row['anio'] . "', " .
					"'" . $row['num_mensaje'] . "', " .
					"'" . $row['tipo_proy'] . "', " .
					"'" . $row['fec_presentacion'] . "', " .
					"'" . $row['fec_sesion'] . "', " .
					"'" . $row['fec_aprobacion'] . "', " .
					"'" . $row['caratula'] . "', ".
					"'" . $row['tipo_aprobacion'] . "', ".
					"'" . $row['id_causante'] . "', ".
					"'" . $row['id_causante_txt'] . "', ".
					"'" . $row['com_destino'] . "', ".
					"'" . $row['com_destino_txt'] . "', ".
					"'" . $row['id_aprobacion'] . "', ".
					"'" . $row['id_aprobacion_txt'] . "', ".
					"'" . $row['id_ubicacion_actual'] . "', ".
					"'" . $row['id_ubicacion_actual_txt'] . "', " .
					"'" . $row['id_grupo'] . "', ".
					"'" . $row['grupo'] . "', ".
					"'" . $row['fec_alta'] . "', " .
					"'" . $row['decretos'] . "', " .
					"'" . $row['declaraciones'] . "', " .
					"'" . $row['minutas'] . "', " .
					"'" . $row['ordenanzas_y_resoluciones'] . "') ";
				

			$r = mysql_query($sql, $link);
			if (!$r && mysql_errno($link)!=1062) {
				die('Invalid query: ' . mysql_errno($link) . " - " . mysql_error($link));
			}
		} else {
			$sql = 	"UPDATE expedientes_legislativos SET " .
					"letra='" . $row['letra'] . "', ".
					"anio='" . $row['anio'] . "', ".
					"num_mensaje='" . $row['num_mensaje'] . "', ".
					"tipo_proy='" . $row['tipo_proy'] . "', ".
					"fec_presentacion='" . $row['fec_presentacion'] . "', ".
					"fec_sesion='" . $row['fec_sesion'] . "', ".
					"fec_aprobacion='" . $row['fec_aprobacion'] . "', ".
					"caratula='" . $row['caratula'] . "', ".
					"tipo_aprobacion='" . $row['tipo_aprobacion'] . "', ".
					"id_causante='" . $row['id_causante'] . "', ".
					"id_causante_txt='" . $row['id_causante_txt'] . "', ".
					"com_destino='" . $row['com_destino'] . "', ".
					"com_destino_txt='" . $row['com_destino_txt'] . "', ".
					"id_aprobacion='" . $row['id_aprobacion'] . "', ".
					"id_aprobacion_txt='" . $row['id_aprobacion_txt'] . "', ".
					"id_ubicacion_actual='" . $row['id_ubicacion_actual'] . "', ".
					"id_ubicacion_actual_txt='" . $row['id_ubicacion_actual_txt'] . "', ".
					"id_grupo='" . $row['id_grupo'] . "', ".
					"grupo='" . $row['grupo'] . "', ".
					"fec_alta='" . $row['fec_alta'] . "', ".
					"decretos='" . $row['decretos'] . "', " .
					"declaraciones='" . $row['declaraciones'] . "', " .
					"minutas='" . $row['minutas'] . "', " .
					"ordenanzas_y_resoluciones='" . $row['ordenanzas_y_resoluciones'] . "' " .
					" WHERE numero=" . $row['numero'];
			$r = mysql_query($sql, $link);
			if (!$r)
				die('Invalid query: ' . mysql_error($link));
		}
	}

	$r = mysql_query("DELETE FROM expedientes_temporal", $link2);

	if (!$r)
		die('Fatal error: Puede haber problemas en la sincronizaciones futuras, comunicarlo al administrador.');

	mysql_close($link2);

	return true;
}
