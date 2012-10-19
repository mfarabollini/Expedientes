<? include('../inc/inicio.inc.php'); ?>
<? include('../class/expediente.class.php'); ?>
<? include('../inc/pdf/class.ezpdf.php'); ?>
<?
error_reporting(1);

CheckPerfiles('SDOC');

$numero = $_GET['numero'];

Auditoria($numero, 'genero pdf');

//header ('Content-Type: application/pdf; charset=utf-8');
//header('Content-Type: application/octet-stream');
//header("Content-Type: application/force-download");
//header("Content-Type: application/octet-stream");
//header("Content-Type: application/download");  
//header ("Content-Disposition: attachment; filename='Expediente_".$numero.".pdf'");


$expediente = new Expediente();
$expediente->CargarExpediente($numero);

$agregados = $expediente->agregados;

if ($agregados == '')
	$agregados = 'Ninguno';
else
	$agregados = str_replace('|', ', ', substr($agregados, 0, strlen($agregados)-1));


$pdf =& new Cezpdf('LEGAL');
$pdf->selectFont('../inc/pdf/fonts/Times-Roman.afm');
$datacreator = array (
				  'Title'=>utf8_dec2('Expediente Nº '.$expediente->numero),
				  'Author'=>'Sistema de expedientes',
				  'Subject'=>'',
				  'Creator'=>'',
				  'Producer'=>'Sistema de expedientes'
				  );
$pdf->addInfo($datacreator);

$pdf->rectangle(20,20,575,970);
$pdf->ezText("",10);
$pdf->ezText("",10);
$pdf->ezSetMargins(0,0,0,0);
$pdf->ezImage(realpath('../imagenes').'\logo_concejo.png',0, 250, 'none','center','');
$pdf->ezSetMargins(30,30,30,30);
$pdf->ezText("",10);



if ($expediente->tipo != 'M')
{
	$pdf->ezText(utf8_dec("\n\nEXPTE Nº: <b>").utf8_dec2($expediente->NumeroFormateado()."    ".$expediente->letra."     ".$expediente->anio."</b>"),24);
	

	$pdf->ezText(utf8_dec("\n\nCAUSANTE: <b>").utf8_dec2($expediente->id_causante_txt."</b>"),24);	
	$pdf->ezText(utf8_dec("\n\nTIPO DE PROYECTO: <b>").utf8_dec2($expediente->tipo_proy."</b>"),24);
	$pdf->ezText(utf8_dec("\n\nCARATULA: <b>").utf8_dec2($expediente->caratula."</b>"),24);	
	$pdf->ezText(utf8_dec("\n\nCOMISIÓN DE DESTINO: <b>").utf8_dec2($expediente->com_destino_txt."</b>"),24);
	$pdf->ezText(utf8_dec("\n\nEXPEDIENTES AGREGADOS: <b>").utf8_dec2($agregados."</b>"),24);
	$pdf->ezText(utf8_dec("\n\nFECHA DE INGRESO A SESIÓN: <b>").utf8_dec2(FormatoFecha($expediente->fec_sesion)."</b>"),24);
}


if ($expediente->tipo == 'M')
{
	$pdf->ezText("Los expedientes municipales no llevan caratula.\n",24);
}



/*
if ($expediente->tipo == 'I')
{
	$pdf->ezText(utf8_dec("<b>EXPEDIENTE N°:</b> ").utf8_dec2($expediente->numero),15);	
	$pdf->ezText(utf8_dec("\n<b>LETRA:</b> ").utf8_dec2($expediente->letra),10);	
	$pdf->ezText(utf8_dec("\n<b>AÑO:</b> ").utf8_dec2($expediente->anio),10);	
	$pdf->ezText("",10);	
	$pdf->ezText(utf8_dec(utf8_dec2($expediente->id_causante_txt),13));	
	$pdf->ezText(utf8_dec(utf8_dec2($expediente->tipo_proy),10));	
	$pdf->ezText(utf8_dec("\n<b>CARÁTULA:</b>\n"),10);	
	$pdf->ezText(utf8_dec2($expediente->caratula),10, array('left' =>45));	
	$pdf->ezText(utf8_dec("\n<b>DESTINO:</b> ").utf8_dec2($expediente->id_ubicacion_actual_txt),13);
	$pdf->ezText(utf8_dec("\n<b>EXPEDIENTES AGREGADOS:</b> ").utf8_dec2($agregados),10);
}

if ($expediente->tipo == 'L')
{	
	$pdf->ezText(utf8_dec("<b>EXPEDIENTE N°:</b> ").utf8_dec2($expediente->numero),15);	
	$pdf->ezText(utf8_dec("\n<b>LETRA:</b> ").utf8_dec2($expediente->letra),10);	
	$pdf->ezText(utf8_dec("\n<b>AÑO:</b> ").utf8_dec2($expediente->anio),10);	
	$pdf->ezText("",10);	
	$pdf->ezText(utf8_dec(utf8_dec2($expediente->id_causante_txt),13));	
	$pdf->ezText(utf8_dec(utf8_dec2($expediente->tipo_proy),10));	
	$pdf->ezText(utf8_dec("\n<b>CARÁTULA:</b>\n"),10);	
	$pdf->ezText(utf8_dec2($expediente->caratula),10, array('left' =>45));	
	$pdf->ezText(utf8_dec("\n<b>COMISIÓN DE DESTINO:</b> ").utf8_dec2($expediente->com_destino_txt),13);
	$pdf->ezText(utf8_dec("\n<b>EXPEDIENTES AGREGADOS:</b> ").utf8_dec2($agregados),10);
	$pdf->ezText(utf8_dec("\n<b>FECHA DE INGRESO A SESIÓN:</b> ").utf8_dec2(FormatoFecha($expediente->fec_sesion)),10);
	
	$pdf->ezSetY(785);
	$pdf->ezText(utf8_dec("<b>ORDEN DEL DÍA:</b>\n"),10, array('aleft'=>350));
	$pdf->ezText(utf8_dec("<b>ASUNTO:</b>\n"),10, array('aleft'=>350));
	$pdf->ezText(utf8_dec("<b>AÑO:</b>"),10, array('aleft'=>350));

}
*/

$pdf->output();
$pdf->ezStream();

?>