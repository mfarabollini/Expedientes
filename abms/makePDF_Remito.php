<?php
		include('../inc/inicio.inc.php');
		include('../class/expediente.class.php');
		
		if(!isset($_SESSION['PDF-Data'])) {
			echo "Error al generar PDF.";
			exit;
		}
		
		$_POST = $_SESSION['PDF-Data'];
		$_POST['numero'] = $_POST['seleccionados'];

		$foja = Array();
		foreach($_POST['numero'] as $key => $numero)
			$foja[$numero] = $_POST['foja'][$key]; 
		
		$expediente = new Expediente();
		$expediente->guardarMovimientoMasivos();
		$expediente->guardarUbicacionActualMasivos();
		
		include('../inc/pdf/class.ezpdf.php');
		// SQL para armar el pdf.
		$lista_expedientes = implode(",",$_POST['numero']);
		$sql = "SELECT numero, letra, anio FROM expedientes WHERE numero IN ({$lista_expedientes})";
		$rsExpedientes = $conn->Execute($sql);
		
		$fecha = $_POST['fecha'];
		$destino = $_POST['id_ubicacion_actual_txt'];

		$meses['01'] = "enero";
		$meses['02'] = "febrero";
		$meses['03'] = "marzo";
		$meses['04'] = "abril";
		$meses['05'] = "mayo";
		$meses['06'] = "junio";
		$meses['07'] = "julio";
		$meses['08'] = "agosto";
		$meses['09'] = "septiembre";
		$meses['10'] = "octubre";
		$meses['11'] = "noviembre";
		$meses['12'] = "diciembre";

		$año = substr($fecha, 0, 4);
		$mes = $meses[substr($fecha, 4, 2)];
		$dia = substr($fecha, 6, 2);
		
		$fecha = $dia . " de " . $mes . " de " . $año;  
		
		header ("content-disposition: attachment;filename=Remito_".$fecha.".pdf");

		$pdf =& new Cezpdf('LEGAL');
		$pdf->selectFont('../inc/pdf/fonts/Times-Roman.afm');
		$datacreator = array (
				  'Title'=>'Remito de expedientes',
				  'Author'=>'Sistema de expedientes',
				  'Subject'=>'',
				  'Creator'=>'',
				  'Producer'=>'Sistema de expedientes'
				  );
		$pdf->addInfo($datacreator);

		$pdf->addJpegFromFile("../imagenes/concejo_municipal_rosario.jpg",30,900,350);
//		$pdf->ezText(utf8_dec("<b>CONCEJO MUNICIPAL DE ROSARIO</b>"),17, array('justification'=>'center'));
		$pdf->ezText("",80);
		$pdf->ezText(utf8_dec("Rosario, ".$fecha.".-"),12, array('justification'=>'right'));
		$pdf->ezText("",20);
		$pdf->ezText(utf8_dec("Remisión de expedientes de Dirección General de Mesa de Entradas a:  " . strtoupper($destino)),12, array('justification'=>'left'));
		$pdf->ezText("",20);
		$pdf->ezText("",20);

		$data = Array();
		$i = 0;
		while(!$rsExpedientes->EOF) {
				$data[$i]['numero'] = $rsExpedientes->fields['numero'];
				$data[$i]['letra'] = $rsExpedientes->fields['letra'];
				$data[$i]['anio'] = $rsExpedientes->fields['anio'];
				$data[$i]['foja'] = $foja[$rsExpedientes->fields['numero']];
				$i++;
				$rsExpedientes->MoveNext();
		}

		$cols = array('numero' => 'Numero', 'letra' => 'Letra', 'anio' => utf8_dec('Año'), 'foja' => 'Foja');
	
		$coslOptions = array(	'numero' => array('justification'=>'right'), 
								'letra' => array ('justification' => 'center'),
								'anio' => array ('justification' => 'center'),
								'foja' => array ('justification' => 'center'));								
								
		$pdf->ezTable($data, $cols, '', array( 'width' => '550', 'fontSize' => '14', 'options' => $coslOptions ));
		$pdf->ezText("",20);
		$pdf->ezText("",20);		
		
		$footer[0][] = utf8_dec('Firma y Aclaración');
		$footer[0][] = utf8_dec('Firma y Aclaración');
		$footer[0][] = utf8_dec('Hora');
		
		$footer[1][] = utf8_dec('Oficina remitente');
		$footer[1][] = utf8_dec('Oficina receptora');
		$footer[1][] = ' ';
		
		$pdf->ezTable($footer, array(' ',' ',' '), '', array( 'width' => '550', 'fontSize' => '12', 'showLines' => '0', 'shaded' => '0' ));
		$pdf->ezStream();
		
		unset($_SESSION['PDF-Data']);
		exit;
?>