<? include('../inc/inicio.inc.php'); ?>
<? include('../class/expediente.class.php'); ?>
<? include('../inc/pdf/class.ezpdf.php'); ?>
<? header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");

CheckPerfiles('SEI');
$expediente = new Expediente();
$pdf =& new Cezpdf('LEGAL');
$pdf->selectFont('../inc/pdf/fonts/Helvetica.afm');
$fec_creacion = AhoraFecha();
if (isset($_GET['accion'])) 
{	
	if ($_GET['accion'] == 'crear') 
	{
		//recorro el arreglo de  verificando que sean válidos 
		//desgloso el arreglo enviado por parametro
		$count = 0;
		/*print "POST ";
		print_r($_POST);
		print "GET ";		
		print_r($_GET);*/
		$totDat = "";
		$arrDat = array($_POST['len']);
		$arrDat = split(";",$_POST['arreglo']);
		for ($i = 0; $i <= ($_POST['len'] -2); $i++)
				$totDat .= $arrDat[$i].",";
		$totDat .= $arrDat[$_POST['len'] -1];
		$numExp = ""; 
		$arrFec = split("/",$_POST['xfec_creacion']);
		$jd = gregoriantojd($arrFec[0],$arrFec[1],$arrFec[2]);
		$dia = jddayofweek($jd,1);
		$mes = jdmonthname($jd,1);	
		$anio = $arrFec[2];
		$ultimoTit = '';
		$actualTit = '';
		if ($_POST['tipoOrd'] == 'Ord')
			$ordenTipo = 'ORDINARIO';
		else
			$ordenTipo = 'OTROS';
		Auditoria('null', utf8_dec('Reporte Preferencias'));
			$rs_busq = $conn->Execute($expediente->SqlCheckExpPref($totDat)); 
			if (!$rs_busq)
			{
				echo $conn->ErrorMsg();
			}
			else
			{
				while (!$rs_busq->EOF)
				{
					if ($count == 0)
					{
						$titulo = utf8_dec2('Reporte Preferencia');
						$datacreator = array (
							  'Title'=>$titulo,
							  'Author'=>'Sistema de expedientes',
							  'Subject'=>'',
							  'Creator'=>'',
							  'Producer'=>'Sistema de expedientes'
							  );
						$pdf->addInfo($datacreator);
						//$pdf->rectangle(20,20,575,970);
						$pdf->ezStartPageNumbers(590,20,10,'','{PAGENUM} de {TOTALPAGENUM}',1);
						$pdf->ezText("",10);
						$pdf->ezText("",10);
						$pdf->ezSetMargins(0,0,0,0);
						$pdf->ezImage(realpath('../imagenes').'\Logo.png',0, 250, 'none','center','');
						$pdf->ezSetMargins(30,30,30,30);
						$pdf->ezText("",10);
						$pdf->ezText(utf8_dec('NOMINA DE EXPEDIENTES SOLICITADOS PARA SER TRATADOS COMO PREFERENCIA EN LA SESION DEL DIA '.$arrFec[0].'-'.$arrFec[1].'-'.$arrFec[2]),15, array('justification'=>'center'));	
					}
						$count+=1;
						//obtengo titulo
						$actualTit = $expediente->CargaTituloExp($rs_busq->fields['destino']);
						//printf ("Titulo : %s",$actualTit);
						if ($ultimoTit != $actualTit)
						{
							$pdf->ezText("");
							$pdf->ezText('<i><u>'.$actualTit.'</u></i>',15, array('justification'=>'center'));
							$pdf->ezText("");
							$ultimoTit = $actualTit;
						}
						$pdf->ezText("");
						$pdf->ezText(utf8_dec2($count.').-'.strtoupper($rs_busq->fields['causante']).' - '.strtoupper($rs_busq->fields['tipo_proy']).' - '.$rs_busq->fields['caratula']),10,array('leading'=>'10'));
						$pdf->ezText("");
						$pdf->ezText(utf8_dec2('	('.strtoupper($rs_busq->fields['destino']).')'),10,array('leading'=>'10'));
						$pdf->ezText("");
						$pdf->ezText(utf8_dec2('	Expediente Nro.: '.number_format($rs_busq->fields['numero'], 0, ',', '.').'-'.$rs_busq->fields['letra'].'-'.$rs_busq->fields['anio']),10,array('leading'=>'10'));					
					$rs_busq->MoveNext();
				}
		    }
		if ($count >= 1)
		{
			header ("content-disposition: attachment;filename=Reporte_expedientes_Preferencias.pdf");
			$pdf->output();
			$pdf->ezStream();
			exit;
		}
		else
		{ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Sistema de Gestión Parlamentaria</title>
<link rel="stylesheet" type="text/css" href="../inc/estilo.css">
<? include('../inc/efecto_transicion.inc.php'); ?>
<script src="../inc/funciones.js"></script>
<? include('../inc/calendario/calendario.inc.php'); ?>
<script type="text/javascript" src="../inc/autosuggest/js/bsn.AutoSuggest_2.1.3.js" charset="utf-8"></script>
<link rel="stylesheet" href="../inc/autosuggest/css/autosuggest_inquisitor.css" type="text/css" media="screen" charset="utf-8" />
</head>
<body>
    <div align="center">
    <table width="800" height="500" border="0" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF">
    <tr>
            <td height="107" align="left" valign="middle" class="fondo_encabezado">
            <table width="800" height="107" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="130">&nbsp;</td>
                <td width="670" align="left" valign="middle" class="texto_encabezado">Reporte Preferencias</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
            </table>
            </td>
      </tr>
    <tr>
        <td height="440" align="center" valign="top" class="contenido"><br />
            <table width="740" border="0" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
              <tr>
                <td height="25" colspan="6" align="center" valign="middle" class="header2">Reporte Preferencias</td>
              </tr>
              <tr>
              	<td height="100" colspan="6" align="center" valign="middle" class="td2">
                	<div align='center'><span class='mensaje'>No se encontraron resultados.</span></div>
                </td>
              </tr>
                <tr>
                    <td olspan="6" align="center" valign="middle" class="td1">
                        <input type="button" name="btnCerrar" id="btnCerrar" value="Cerrar" style="width:150px" onclick="javascript:window.close();" />
                    </td>
                </tr>              
            </table>
        </td>
    </tr>
    </table>
    </div>    
 </body>
</html>
			
	<?	}
	}
}
?>


