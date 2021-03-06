<? include('../inc/inicio.inc.php'); ?>
<? include('../class/normas.class.php'); ?>
<? include('../inc/pdf/class.ezpdf.php'); ?>
<?

header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0


CheckPerfiles('SDOCEIUTAJ');
//print $_SESSION['perfil'];


$titulo_busq = 'Formulario de Documentaci&oacute;n Legislativa';




$norma = new Normas();
$norma->tipo = '';

$esPostBack = false;


if (isset($_GET['accion'])) 
{
	if ($_GET['accion'] == 'buscar') 
	{
		$rs_busq = $conn->Execute($norma->SqlBuscar());
		$esPostBack = true;
	}

	if ($_GET['accion'] == 'imprimir') 
	{
		header ("content-disposition: attachment;filename=Resultado_busqueda.pdf");

		$rs_busq = $conn->Execute($norma->SqlBuscar());
		
		$pdf =& new Cezpdf('LEGAL', 'landscape');
		$pdf->selectFont('../inc/pdf/fonts/Helvetica.afm');
		$datacreator = array (
						  'Title'=>'Resultado de la busqueda',
						  'Author'=>'Sistema de expedientes',
						  'Subject'=>'',
						  'Creator'=>'',
						  'Producer'=>'Sistema de expentes'
						  );
		$pdf->addInfo($datacreator);
		
		//titulo
		
		$pdf->ezText(utf8_dec("CONCEJO MUNICIPAL DE ROSARIO\n\nSistema de Gesti�n Parlamentaria")."\n",15, array('justification'=>'center'));
		$pdf->ezText("");
		$pdf->ezText(utf8_dec2("Fecha de la consulta: ".date('d/m/Y')."                    Hora de la consulta: ".date('H:i:s')),10, array('justification'=>'center'));
		$pdf->ezText(utf8_dec2("---------------------------------------------------------------------------------------------------------------------------------------------------------"),10, array('justification'=>'center'));
		$pdf->rectangle(170,450,660,140);
		$pdf->ezText("");
		$pdf->ezText("");
		$pdf->ezText("");
		
		$pdf->ezStartPageNumbers(590,20,10,'','{PAGENUM} de {TOTALPAGENUM}',1);
		$pos = 0;
		while (!$rs_busq->EOF)
		{
			$vec[$pos][utf8_dec('Numero')] = number_format($rs_busq->fields['nro_norma'], 0, ',', '.');
			
			switch ($rs_busq->fields['tipo_norma']){
				case 'ORD':
					$vec[$pos][utf8_dec('Tipo de Norma')] = 'Ordenanza';
					break;
				case 'DEC':
					$vec[$pos][utf8_dec('Tipo de Norma')] = 'Decreto';
					break;
				case 'RES':
					$vec[$pos][utf8_dec('Tipo de Norma')] = 'Resoluci&oacute;n';
					break;
				case 'COM':
					$vec[$pos][utf8_dec('Tipo de Norma')] = 'Minuta de Comunicaci&oacute;n';
					break;
				case 'DLA':
					$vec[$pos][utf8_dec('Tipo de Norma')] = 'Declaraci&oacute;n';
					break;
			}
			
			$vec[$pos][utf8_dec('Fecha de Aprobacion')] = utf8(FormatoFechaNormal($rs_busq->fields['fec_aprob']));
			$vec[$pos][utf8_dec('Descripcion')] = utf8($rs_busq->fields['dsc_norma']);

			$pos++;
			$rs_busq->MoveNext();
		}
		

		$opciones_columnas = array(
			utf8_dec('Numero') => array('width'=>50), 
			utf8_dec('Tipo de Norma') => array('width'=>150),
			utf8_dec('Fecha de Aprobacion') => array('width'=>53),
			utf8_dec('Descripcion') => array('width'=>400),
		);
		
		$pdf->ezTable($vec, '', '', array('colGap'=>1 ,'cols'=>$opciones_columnas));
			
		$pdf->ezStream();
		
		exit;
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Sistema de Gesti&oacute;n Parlamentaria</title>
<link rel="stylesheet" type="text/css" href="../inc/estilo.css">
<? include('../inc/efecto_transicion.inc.php'); ?>
<script src="../inc/funciones.js"></script>
<? include('../inc/calendario/calendario.inc.php'); ?>
<script type="text/javascript" src="../inc/autosuggest/js/bsn.AutoSuggest_2.1.3.js" charset="utf-8"></script>
<link rel="stylesheet" href="../inc/autosuggest/css/autosuggest_inquisitor.css" type="text/css" media="screen" charset="utf-8" />


<script language="javascript">
	
	function ValidarBusq()
	{
		with (document.form1)
		{
			if (numero.value == "" && 
				tipo.value == "" && 
				xfec_aprobacion_desde.value == "" &&
				xfec_aprobacion_hasta.value == "" &&
				tags.value == "" )
			{
				alert("Debe elegir al menos un criterio.");
				return false;
			}else{
				return true;
			}	
		}
	}
	
	function Imprimir()
	{
		if (ValidarBusq())
		{
			with (document.form1)
			{
				action = 'busqueda_normas.php?accion=imprimir';
				target = '_blank';
				submit();
			}
		}
	}
	
	function Buscar()
	{
		if (ValidarBusq())
		{
			with (document.form1)
			{
				action = 'busqueda_normas.php?accion=buscar';
				target = '';
				submit();
			}
		}
	}
	
	function Editar(norma,tipo)
	{
		window.location = "../abms/norma.php?accion=editar&numero="+norma+"&tipo="+tipo;
	}
	
	function GenerarPDF(norma)
	{

		with (document.form_pdf)
		{

			numero.value = norma;
			tipo_doc.value = 'norma';
			submit();
		}
	}
	
</script>


</head>

<body>
<form action="busqueda_normas.php?accion=buscar" method="post" name="form1" id="form1" onsubmit="return ValidarBusq();">
<div align="center">
<table width="800" height="500" border="0" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF">
<tr>
        <td height="107" align="left" valign="middle" class="fondo_encabezado">
        <table width="800" height="107" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="130">&nbsp;</td>
            <td width="670" align="left" valign="middle" class="texto_encabezado"><?=$titulo_busq?></td>
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
    <td align="center" valign="top" class="contenido_normas"><br />
        
        <? if (!$esPostBack) { ?>
        
        <table width="740" border="0" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
          <tr>
            <td height="25" colspan="4" align="center" valign="middle" class="header2"><?=$titulo_busq?></td>
          </tr>
          <tr>
            <td width="178" height="24" align="left" class="td1">N&uacute;mero de Norma</td>
            <td width="202" align="left" class="td2"><input type="text" name="numero" id="numero" /></td>
            
            <td width="143" align="left" class="td1">Tipo de norma</td>
            <td width="204" align="left" class="td2">
			  <select name="tipo" id="tipo" style="width:204px;">      
			  	<option value="">- Elegir -</option>    
                <option value="ORD" <? if ($norma->tipo=='ORD') echo 'selected'; ?>>Ordenanza</option>
                <option value="DEC" <? if ($norma->tipo=='DEC') echo 'selected'; ?>>Decreto</option>
                <option value="RES" <? if ($norma->tipo=='RES') echo 'selected'; ?>>Resoluci&oacute;n</option>
                <option value="COM" <? if ($norma->tipo=='COM') echo 'selected'; ?>>Minuta de Comunicaci&oacute;n</option>
                <option value="DLA" <? if ($norma->tipo=='DLA') echo 'selected'; ?>>Declaraci&oacute;n</option>
              </select>
            </td>
          </tr>

          <tr>
            <td align="left" class="td1">Fecha de aprobaci&oacute;n desde</td>
            <td align="left" class="td2"><?=CalendarioCreaInput('fec_aprobacion_desde', $norma->fec_aprobacion_desde, '')?></td>
            <td align="left" class="td1">Fecha de aprobaci&oacute;n hasta</td>
            <td align="left" class="td2"><?=CalendarioCreaInput('fec_aprobacion_hasta', $norma->fec_aprobacion_hasta, '')?></td>
          </tr>
           
          <tr>
            <td align="left" class="td1">Tags</td>
            <td align="left" class="td2" colspan="3" ><input type="text" value="" name="tags" maxlength="200" style="width: 350px;" /></td>
          </tr>

          </table>
      <br />
      <br />
      <br />
      <br />
      <br />
      <input type="button" name="btnBuscar" id="btnBuscar" value="Buscar" style="width:150px" onclick="Buscar();" />
      <? if ($_SESSION['perfil'] != 'C') { ?>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" style="width:150px" onclick="Imprimir();" />
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="button" name="btnVolver" id="btnVolver" value="Volver" style="width:150px" onclick="window.location = 'menu_principal.php';" />
      <? } else { ?>
          <input type="button" name="btnCerrarSesion" id="btnCerrarSesion" value="Cerrar sesion" style="width:150px" onclick="window.location = 'login.php';" />
      <? } ?>
      

      <? }
	  	 else
		 {	  
	  ?>
      <br />
	  <?
      if ($rs_busq->EOF) 
      {
      	echo "<div align='center'><br><br><br><span class='mensaje'>No se encontraron resultados.</span></div>";
	  }
	  else
	  {
      ?>
      <table width="740" border="0" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
        <tr>
          <td align="center" class="header2">N&uacute;mero</td>
          <td align="center" class="header2">Tipo de Norma</td>
          <td align="center" class="header2">Fecha de Aprobaci&oacute;n</td>
          <td align="center" class="header2">Descripci&oacute;n</td>
          <td align="center" class="header2">Estado</td>
          <td align="center" class="header2">Opciones</td>
        </tr>
		<?
			$clase = 'td1';
			while (!$rs_busq->EOF) {
				if ($clase == 'td1') $clase = 'td2';
				else {$clase = 'td1';}
			
			?>
			<tr class="<?=$clase?>">
			  <td align="right">
			  <?
		        	echo $rs_busq->fields['nro_norma'];
			  ?>
              </td>
			  <td align="left">
			  
				<? switch ($rs_busq->fields['tipo_norma']){
					case 'ORD':
						$prefijo = 'O-';
						echo 'Ordenanza';
						break;		
					case 'DEC':
						$prefijo = 'D-';
						echo 'Decreto';
						break;
					case 'RES':
						$prefijo = 'R-';
						echo 'Resoluci&oacute;n';
						break;
					case 'COM':
						$prefijo = 'M-';
						echo 'Minuta de Comunicaci&oacute;n';
						break;
					case 'DLA':
						$prefijo = 'L-';
						echo 'Declaraci&oacute;n';
						break;
				}
				?>

			  
			  </td>
			  <td align="right"><?=FormatoFecha($rs_busq->fields['fec_aprob'])?></td>
			  <td align="left"><?=utf8($rs_busq->fields['dsc_norma'])?></td>
			  <td align="left"><?=utf8($rs_busq->fields['estado'])?></td>
			  <td align="center" nowrap="nowrap">
              <a href="ver_norma.php?numero=<?=$rs_busq->fields['nro_norma']?>&tipo=<?=$rs_busq->fields['tipo_norma']?>" target="_blank"><img src="../imagenes/ver.gif" alt="Ver Norma" width="28" height="16" border="0" /></a>
              <? if ($_SESSION['perfil'] == 'D' || $_SESSION['perfil'] == 'S' || $_SESSION['perfil'] == 'J' || ($_SESSION['perfil'] == 'T' && $rs_busq->fields['tipo'] == 'I' )){ ?>
                  &nbsp;&nbsp;
                  <a href="javascript: Editar('<? echo $rs_busq->fields['nro_norma'] . "','" . $rs_busq->fields['tipo_norma'];?>')"><img src="../imagenes/editar.gif" alt="Editar Norma" width="20" height="16" border="0" /></a>
              <? } ?>
              <? if ($_SESSION['perfil'] != 'C' && $_SESSION['perfil'] != 'U' && $_SESSION['perfil'] != 'E' && $_SESSION['perfil'] != 'T' && $_SESSION['perfil'] != 'A'){ ?>
                  &nbsp;&nbsp;
                  <a href="javascript: GenerarPDF('<?=$rs_busq->fields['nro_norma']?>');"><img src="../imagenes/pdf.gif" alt="Generar PDF" width="16" height="16" border="0" /></a></td>
              <? } ?>
			</tr>
		<? 
					$rs_busq->MoveNext();
				}
		?>
      </table>
      <? } //de si no es EOF ?>
      <br />
      <br />
      <input type="button" name="btnBuscarOtro" id="btnBuscarOtro" value="Buscar otro" style="width:150px" onclick="window.location = 'busqueda_normas.php';" />
      <? if ($_SESSION['perfil'] != 'C') { ?>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" style="width:150px" onclick="Imprimir();" />
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <input type="button" name="btnVolver" id="btnVolver" value="Volver" style="width:150px" onclick="window.location = 'menu_principal.php';" />
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <input type="button" name="btnCarga" id="btnCarga" value="Cargar Nueva Norma" style="width:150px" onclick="window.open('../abms/norma.php?accion=alta','_blank');" />      
      <? } else { ?>
          <input type="button" name="btnCerrarSesion" id="btnCerrarSesion" value="Cerrar sesion" style="width:150px" onclick="window.location = 'login.php';" />
      <? } ?>
      
      
      <input name="numero" id="numero" type="hidden" value="<?=$_POST['numero']?>" />
      <input name="tipo" id="tipo" type="hidden" value="<?=$_POST['tipo']?>" />
      <input name="fec_aprobacion_desde" id="fec_aprobacion_desde" type="hidden" value="<?=$_POST['fec_aprobacion_desde']?>" />
      <input name="fec_aprobacion_hasta" id="fec_aprobacion_hasta" type="hidden" value="<?=$_POST['fec_aprobacion_hasta']?>" />
      <input name="descripcion" id="descripcion" type="hidden" value="<?=$_POST['descripcion']?>" />
      <input name="modifica_txt" id="modifica_txt" type="hidden" value="<?=$_POST['modifica_txt']?>" />
      
      <br />
      <? } ?>
      </td>
  </tr>
</table>
</div>
<script type="text/javascript">
	var options1 = {
		script:"../inc/autosuggest/data_destino.php?json=true&",
		varname:"input",
		json:true,
		shownoresults:false,
		maxresults:10,
		minchars: 1,
		timeout:99999,
		cache: false,
		callback: function (obj) { document.getElementById('com_destino').value = obj.id; }
	};
	var as_json1 = new bsn.AutoSuggest('com_destino_txt', options1);
	//------------------------------------------------------------------------------------------
	var options3 = {
		script:"../inc/autosuggest/data_destino.php?json=true&",
		varname:"input",
		json:true,
		shownoresults:false,
		maxresults:10,
		minchars: 1,
		timeout:99999,
		cache: false,
		callback: function (obj) { document.getElementById('id_ubicacion_actual').value = obj.id; }
	};
	var as_json3 = new bsn.AutoSuggest('id_ubicacion_actual_txt', options3);
	//------------------------------------------------------------------------------------------
	var options4 = {
		script:"../inc/autosuggest/data_numero_exp.php?json=true&",
		varname:"input",
		json:true,
		shownoresults:false,
		maxresults:10,
		minchars: 1,
		timeout:99999,
		cache: false,
		callback: function (obj) { document.getElementById('txt_agregar_id').value = obj.id; }
	};
	var as_json4 = new bsn.AutoSuggest('txt_agregar', options4);
	//------------------------------------------------------------------------------------------
	var options6 = {
		script:"../inc/autosuggest/data_aprobacion.php?json=true&",
		varname:"input",
		json:true,
		shownoresults:false,
		maxresults:10,
		minchars: 1,
		timeout:99999,
		cache: false,
		callback: function (obj) { document.getElementById('id_categoria').value = obj.id; }
	};
	var as_json6 = new bsn.AutoSuggest('id_aprobacion_txt', options6);
	//------------------------------------------------------------------------------------------
	var options7 = {
		script:"../inc/autosuggest/data_causante.php?json=true&",
		varname:"input",
		json:true,
		shownoresults:false,
		maxresults:10,
		minchars: 1,
		timeout:99999,
		cache: false,
		callback: function (obj) { document.getElementById('id_causante').value = obj.id; }
	};
	var as_json7 = new bsn.AutoSuggest('id_causante_txt', options7);
	//------------------------------------------------------------------------------------------


	
</script>

</form>
<div style="display: none">
<form action="generar_pdf.php" method="post" name="form_pdf" id="form_pdf" target="_blank">
	<input id="numero" name="numero" type="hidden" value="" />
	<input id="tipo_doc" name="tipo_doc" type="hidden" value="" />
</form>
</div>
</body>
</html>
