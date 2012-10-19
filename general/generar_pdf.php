<? include('../inc/inicio.inc.php'); ?>
<? include('../class/expediente.class.php'); ?>
<? include('../inc/pdf/class.ezpdf.php'); ?>
<?

CheckPerfiles('SDOC');

$numero = $_POST['numero'];

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

?>