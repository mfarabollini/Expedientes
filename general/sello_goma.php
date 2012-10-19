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
$pdf->selectFont('../inc/pdf/fonts/Helvetica.afm');
$datacreator = array (
				  'Title'=>'Sello de goma',
				  'Author'=>'Sistema de expedientes',
				  'Subject'=>'',
				  'Creator'=>'',
				  'Producer'=>'Sistema de expedientes'
				  );
$pdf->addInfo($datacreator);
$pdf->ezText("");
$pdf->ezText("");
$pdf->ezText("");
$pdf->ezText("CONCEJO MUNICIPAL\nROSARIO\n\nMesa de Entradas\n",10, array('aleft'=>450,'justification'=>'center'));
$pdf->ezText(date('d/m/Y'),10, array('aleft'=>450,'justification'=>'center'));
$pdf->ezText("\n".$expediente->NumeroFormateado(),17, array('aleft'=>450,'justification'=>'center'));
$pdf->setLineStyle(1);
$pdf->rectangle(458,820,115,140);

$pdf->ezStream();

?>