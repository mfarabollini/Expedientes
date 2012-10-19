<? include('../inc/inicio.inc.php'); ?>
<? include('../class/expediente.class.php'); ?>
<? include('../inc/pdf/class.ezpdf.php'); ?>
<?

CheckPerfiles('SDO');

$numero = $_GET['numero'];

Auditoria($numero, 'genero pdf');

$expediente = new Expediente();
$expediente->CargarExpediente($numero);


if ($expediente->tipo == 'M')
	header ("content-disposition: attachment;filename=Recibo_".$expediente->nro_municipal.".pdf");
else
	header ("content-disposition: attachment;filename=Recibo_".$numero.".pdf");



$pdf =& new Cezpdf('LEGAL');
$pdf->selectFont('../inc/pdf/fonts/Times-Roman.afm');
$datacreator = array (
				  'Title'=>'Recibo',
				  'Author'=>'Sistema de expedientes',
				  'Subject'=>'',
				  'Creator'=>'',
				  'Producer'=>'Sistema de expedientes'
				  );
$pdf->addInfo($datacreator);


$pdf->ezText(utf8_dec("<b>RECIBO</b>"),17, array('justification'=>'center'));
$pdf->ezText("",20);
$pdf->ezText("",20);
$pdf->ezText(utf8_dec("<b>CONCEJO MUNICIPAL DE ROSARIO</b>"),15);
$pdf->ezText(utf8_dec("<b>DIRECCION GRAL DE MESA DE ENTRADAS</b>"),8);
$pdf->ezText(utf8_dec("<b>CORDOBA 501 - 4106298</b>"),8);
$pdf->ezText("",20);
$pdf->ezText("",20);


if ($expediente->tipo == 'M')
{
	$pdf->ezText(utf8_dec("EXPEDIENTE Nº: <b>").utf8_dec2($expediente->nro_municipal."    ".$expediente->letra."     ".$expediente->anio."</b>"),12);
}
else
{
	$pdf->ezText(utf8_dec("EXPEDIENTE Nº: <b>").utf8_dec2($expediente->NumeroFormateado()."    ".$expediente->letra."     ".$expediente->anio."</b>"),12);
}

$pdf->ezText(utf8_dec("\nCAUSANTE: <b>").utf8_dec2($expediente->id_causante_txt."</b>"),12);	
$pdf->ezText(utf8_dec("\nCARATULA: <b>").utf8_dec2($expediente->caratula."</b>"),12);	
$pdf->ezText(utf8_dec("\nFECHA DE PRESENTACIÓN: <b>").utf8_dec2(FormatoFecha($expediente->fec_presentacion)."</b>"),12);	

//$pdf->rectangle(20,480,570,500);

//$pdf->ezText("\n\n\n\n\n\n\n\n\n<b>Impreso:</b> ".date("d/m/Y H:i:s")."\n\n",10);



$pdf->ezStream();

?>