<? include('../inc/inicio.inc.php');?>
<? include('../class/expediente.class.php');?>
<? include('../inc/pdf/class.ezpdf.php');?>
<? require_once('../class/clsMsDocGenerator.php'); ?>
<? CheckPerfiles('SEI');
$expediente = new Expediente();
$doc = new clsMsDocGenerator();
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
		$dia = jddayofweek( cal_to_jd(CAL_GREGORIAN, $arrFec[1],$arrFec[0], $arrFec[2]) , 1 ); 
		$mes = jdmonthname( cal_to_jd(CAL_GREGORIAN, $arrFec[1],$arrFec[0], $arrFec[2]) , 1 ); 
		$anio = $arrFec[2];
		$ultimoTit = '';
		$actualTit = '';
		if ($_POST['tipoOrd'] == 'Ord')
			$ordenTipo = 'ORDINARIO';
		else
			$ordenTipo = 'OTROS';
		Auditoria('null', utf8_dec('Reporte Orden del día'));
			$rs_busq = $conn->Execute($expediente->SqlCheckExpOrd($totDat)); 
			if (!$rs_busq)
			{
				print $conn->ErrorMsg();
			}
			else
			{
				$iCount = 0;
				while (!$rs_busq->EOF && $iCount <= ($_POST['len'] - 1))
				{
					//echo $rs_busq->fields['numero'];
					if ($arrDat[$iCount] == $rs_busq->fields['numero'])
					{	
						if ($count == 0)
						{
							$doc->addParagraph($doc->bufferImage('http://www.consultingit.com.ar/expedientes/imagenes/Logo.gif', 600, 310), array('text-align' => 'center'));
							$doc->addParagraph('');
							$doc->addParagraph('');
							$doc->addParagraph('');
							$doc->addParagraph('');					
							$doc->addParagraph(utf8_dec('<i>ORDEN DEL DIA Nro. '.$_POST['ordn'].'</i>'), array('text-align' => 'center', 'font-weight' => 'bold','font-size' => '25'));
							$doc->addParagraph('');
							$doc->addParagraph('');	
							$doc->addParagraph('');
							$doc->addParagraph('');													
							$doc->addParagraph(utf8_dec('<i>SESION DEL DIA ').diaCastellano($dia).' '.$arrFec[0].' DE '.mesCastellano($mes).' DE '.$anio.'</i>', array('text-align' => 'center', 'font-weight' => 'bold','font-size' => '25'));
							$doc->addParagraph('');
							$doc->addParagraph('');
							$doc->addParagraph('');	
							$doc->addParagraph('');	
							$doc->addParagraph('');
							$doc->addParagraph('');	
							$doc->addParagraph(utf8_dec('<i>PRIMER PERIODO '.$ordenTipo.'</i>'), array('text-align' => 'center' , 'font-weight' => 'bold','font-size' => '28'));
							$doc->newPage();		
						}
						if (!$rs_busq->EOF)
						{
							$count+=1;
							//obtengo titulo
							$actualTit = $expediente->CargaTituloExp($rs_busq->fields['destino']);
							if ($ultimoTit != $actualTit)
							{
								$doc->addParagraph('');
								$doc->addParagraph(utf8_dec('<i>'.$actualTit.'</i>'), array('text-align' => 'center' , 'font-weight' => 'bold','font-size' => '12'));
								$ultimoTit = $actualTit;
							}
							$doc->addParagraph('');
							$doc->addParagraph($count.').- Proyecto de: '.$rs_busq->fields['tipo_proy'].' de: '.$rs_busq->fields['causante'].'   '.utf8_dec($rs_busq->fields['caratula']), array('text-align' => 'left', 'font-weight' => 'bold','font-size' => '14'));
							$doc->addParagraph('Expediente Nro.: '.number_format($rs_busq->fields['numero'], 0, ',', '.').'-'.$rs_busq->fields['letra'].'-'.$rs_busq->fields['anio'].'  			('.$rs_busq->fields['destino'].')', array('text-align' => 'left', 'font-weight' => 'bold','font-size' => '14'));
						}
						/*else
						{
							$numExp += $arrDat[$i].";";
						}*/
						$rs_busq->MoveFirst();
						$iCount +=1;
					}
					else
					{
						$rs_busq->MoveNext();
						if ($rs_busq->EOF)
						{
							$rs_busq->MoveFirst();
							$iCount +=1;
						}
					}
				}
			}
		if ($count >= 1)
		{
			$doc->output('Reporte_Ord_Dia_N'.$_POST['ordn'].'_'.$arrFec[0].'_'.mesCastellano($mes).'_'.$anio.'.doc');
		}
		else
		{
		?>
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
                <td width="670" align="left" valign="middle" class="texto_encabezado">Reporte Orden del D&iacute;a</td>
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
                <td height="25" colspan="6" align="center" valign="middle" class="header2">Reporte Orden del D&iacute;a</td>
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
        <?
		}
	}
}
?>