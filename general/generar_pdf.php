<? include('../inc/inicio.inc.php'); ?>
<? include('../class/expediente.class.php'); ?>
<? include('../class/normas.class.php'); ?>
<? include('../inc/pdf/class.ezpdf.php'); ?>
<?

CheckPerfiles('SDOC');

$numero = $_POST['numero'];
$tipo_doc = isset($_POST['tipo_doc'])?$_POST['tipo_doc']:'';

if ($tipo_doc == 'norma'){
	
/// Genero PDF de la Norma
	$normas = new Normas();
	$normas->CargarNorma($numero);
	
	header ("content-disposition: attachment;filename=Recibo_".$numero.".pdf");
	
	$modifica = $normas->modifica;
	
	if ($modifica == '')
		$modifica = 'Ninguno';
	else
		$modifica = str_replace('|', ', ', substr($modifica, 1, strlen($modifica)-1));
	
	
	$pdf =& new Cezpdf('LEGAL');
	$pdf->selectFont('../inc/pdf/fonts/Helvetica.afm');
	$datacreator = array (
			'Title'=>'Norma',
			'Author'=>'Sistema de expedientes',
			'Subject'=>'',
			'Creator'=>'',
			'Producer'=>'Sistema de expedientes'
	);
	$pdf->addInfo($datacreator);
	
	switch ($normas->tipo){
		case 'ORD':
			$tipo_norma =  'Ordenanza: ';
			break;
		case 'DEC':
			$tipo_norma =  'Decreto: ';
			break;
		case 'RES':
			$tipo_norma =  'Resoluci&oacute;n: ';
			break;
		case 'COM':
			$tipo_norma =   'Minuta de Comunicaci&oacute;n: ';
			break;
		case 'DLA':
			$tipo_norma =   'Declaraci&oacute;n: ';
			break;
	}
	$pdf->ezImage("../imagenes/cabecera.jpg");
	$pdf->ezText(utf8_dec("<b> $tipo_norma").utf8_dec2($normas->numero."</b>\n"),20);
	
	$pdf->ezText("\n\n\n",10);
	$pdf->ezText(utf8_dec("<b>Número:</b> ").utf8_dec2($normas->numero),10);
	$pdf->ezText(utf8_dec("\n<b>Fecha de aprobación:</b> ").utf8_dec2(FormatoFecha($normas->fec_aprobacion)),10);
	$pdf->ezText(utf8_dec("\n<b>Descripcion:</b> ").utf8_dec2($normas->descripcion),10);
	$pdf->ezText(utf8_dec("\n<b>Normas que modifica:</b> ").utf8_dec2($modifica),10);

	$pdf->ezText("\n\n\n\n\n<b>Impreso:</b> ".date("d/m/Y H:i:s")."\n\n",10);
	$pdf->ezStream();
	
}else{
	Auditoria($numero, 'genero pdf');
	
	$expediente = new Expediente();
	$expediente->CargarExpediente($numero);
	
	if ($expediente->tipo == 'M')
		header ("content-disposition: attachment;filename=Recibo_".$expediente->nro_municipal.".pdf");
	else
		header ("content-disposition: attachment;filename=Recibo_".$numero.".pdf");
		
	
	$agregados = $expediente->agregados;
	
	if ($agregados == '')
		$agregados = 'Ninguno';
	else
		$agregados = str_replace('|', ', ', substr($agregados, 0, strlen($agregados)-1));
	
	
	$pdf =& new Cezpdf('LEGAL');
	$pdf->selectFont('../inc/pdf/fonts/Helvetica.afm');
	$datacreator = array (
					  'Title'=>'Expediente',
					  'Author'=>'Sistema de expedientes',
					  'Subject'=>'',
					  'Creator'=>'',
					  'Producer'=>'Sistema de expedientes'
					  );
	$pdf->addInfo($datacreator);
	$pdf->ezImage("../imagenes/cabecera.jpg");
	if ($expediente->tipo == 'M')
		$pdf->ezText(utf8_dec("<b>Expediente Nº ").utf8_dec2($expediente->nro_municipal."</b>\n"),20);
	else
		$pdf->ezText(utf8_dec("<b>Expediente Nº ").utf8_dec2($expediente->NumeroFormateado()."</b>\n"),20);
	
	
	
	if ($expediente->tipo == 'I')
	{
		$pdf->ezText("Expediente Interno\n",12);
		$pdf->ezText("\n\n\n",10);
		$pdf->ezText(utf8_dec("<b>Número:</b> ").utf8_dec2($expediente->NumeroFormateado()),10);	
		$pdf->ezText(utf8_dec("\n<b>Letra:</b> ").utf8_dec2($expediente->letra),10);	
		$pdf->ezText(utf8_dec("\n<b>Año:</b> ").utf8_dec2($expediente->anio),10);	
		$pdf->ezText(utf8_dec("\n<b>Fecha de presentación:</b> ").utf8_dec2(FormatoFecha($expediente->fec_presentacion)),10);	
		$pdf->ezText(utf8_dec("\n<b>Causante:</b> ").utf8_dec2($expediente->id_causante_txt),10);	
		$pdf->ezText(utf8_dec("\n<b>Categoría:</b> ").utf8_dec2($expediente->id_categoria_txt),10);	
		$pdf->ezText(utf8_dec("\n<b>Carátula:</b>\n"),10);	
		$pdf->ezText(utf8_dec2($expediente->caratula),10, array('left' =>45));	
		$pdf->ezText(utf8_dec("\n<b>Destino:</b> ").utf8_dec2($expediente->id_ubicacion_actual_txt),10);	
	}
	
	if ($expediente->tipo == 'L')
	{
		$pdf->ezText("Expediente Legislativo\n",12);
		$pdf->ezText("\n\n\n",10);
		$pdf->ezText(utf8_dec("<b>Número:</b> ").utf8_dec2($expediente->NumeroFormateado()),10);	
		$pdf->ezText(utf8_dec("\n<b>Letra:</b> ").utf8_dec2($expediente->letra),10);	
		$pdf->ezText(utf8_dec("\n<b>Año:</b> ").utf8_dec2($expediente->anio),10);	
		$pdf->ezText(utf8_dec("\n<b>Tipo de proyecto:</b> ").utf8_dec2($expediente->tipo_proy),10);	
		$pdf->ezText(utf8_dec("\n<b>Número de mensaje:</b> ").utf8_dec2($expediente->num_mensaje),10);	
		$pdf->ezText(utf8_dec("\n<b>Causante:</b> ").utf8_dec2($expediente->id_causante_txt),10);	
		$pdf->ezText(utf8_dec("\n<b>Categoría:</b> ").utf8_dec2($expediente->id_categoria_txt),10);	
		$pdf->ezText(utf8_dec("\n<b>Fecha de presentación:</b> ").utf8_dec2(FormatoFecha($expediente->fec_presentacion)),10);	
		$pdf->ezText(utf8_dec("\n<b>Fecha de sesión:</b> ").utf8_dec2(FormatoFecha($expediente->fec_sesion)),10);	
		$pdf->ezText(utf8_dec("\n<b>Comisión de destino:</b> ").utf8_dec2($expediente->com_destino_txt),10);	
		$pdf->ezText(utf8_dec("\n<b>Ubicación actual:</b> ").utf8_dec2($expediente->id_ubicacion_actual_txt),10);	
	}
	
	if ($expediente->tipo == 'M')
	{
		$pdf->ezText("Expediente Interno\n",12);
		$pdf->ezText("\n\n\n",10);
		$pdf->ezText(utf8_dec("<b>Número:</b> ").utf8_dec2($expediente->nro_municipal),10);	
		$pdf->ezText(utf8_dec("\n<b>Letra:</b> ").utf8_dec2($expediente->letra),10);	
		$pdf->ezText(utf8_dec("\n<b>Año:</b> ").utf8_dec2($expediente->anio),10);	
		$pdf->ezText(utf8_dec("\n<b>Fecha de presentación:</b> ").utf8_dec2(FormatoFecha($expediente->fec_presentacion)),10);	
		$pdf->ezText(utf8_dec("\n<b>Tipo de proyecto:</b> ").utf8_dec2($expediente->tipo_proy),10);	
		$pdf->ezText(utf8_dec("\n<b>Causante:</b> ").utf8_dec2($expediente->id_causante_txt),10);	
		$pdf->ezText(utf8_dec("\n<b>Categoría:</b> ").utf8_dec2($expediente->id_categoria_txt),10);	
		$pdf->ezText(utf8_dec("\n<b>Carátula:</b>\n"),10);	
		$pdf->ezText(utf8_dec2($expediente->caratula),10, array('left' =>45));	
		$pdf->ezText(utf8_dec("\n<b>Destino:</b> ").utf8_dec2($expediente->id_ubicacion_actual_txt),10);	
		$pdf->ezText(utf8_dec("\n<b>Expedientes agregados:</b> ").utf8_dec2($agregados),10);	
		$pdf->ezText(utf8_dec("\n<b>Ubicación actual:</b> ").utf8_dec2($expediente->id_ubicacion_actual_txt),10);	
	}
	
	
	
	$pdf->ezText("\n\n\n\n\n<b>Impreso:</b> ".date("d/m/Y H:i:s")."\n\n",10);
	
	
	$pdf->ezStream();
}

?>