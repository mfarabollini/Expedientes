<? include('../inc/inicio.inc.php'); ?>
<? include('../class/expediente.class.php'); ?>
<? include('../inc/pdf/class.ezpdf.php'); ?>
<?

header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0


CheckPerfiles('SDOCEIUTAJ');
//print $_SESSION['perfil'];

if ($_SESSION['perfil'] != 'C' && $_SESSION['perfil'] != 'E' && $_SESSION['perfil'] != 'U' && $_SESSION['perfil'] != 'T' && $_SESSION['perfil'] != 'A')

	$titulo_busq = 'Formulario de busqueda de expedientes';
else
	$titulo_busq = 'Formulario de busqueda de expedientes Legislativos';



$expediente = new Expediente();
$expediente->tipo = '';

$esPostBack = false;


if (isset($_GET['accion'])) 
{
	if ($_GET['accion'] == 'buscar') 
	{
		$rs_busq = $conn->Execute($expediente->SqlBuscarAvanzado());
	
		$esPostBack = true;
		Auditoria('null', 'Busqueda con filtro:<br>'.$expediente->FiltroBuscar());
	}

	if ($_GET['accion'] == 'imprimir') 
	{
		header ("content-disposition: attachment;filename=Resultado_busqueda.pdf");

		$rs_busq = $conn->Execute($expediente->SqlBuscarAvanzado());
		
		Auditoria('null', 'Imprimio busqueda con filtro:<br>'.$expediente->FiltroBuscar());
		
		
		$pdf =& new Cezpdf('LEGAL', 'landscape');
		$pdf->selectFont('../inc/pdf/fonts/Helvetica.afm');
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
			$vec[$pos][utf8_dec('Número')] = number_format($rs_busq->fields['numero'], 0, ',', '.');
			$vec[$pos][utf8_dec('Tipo de Proyecto')] = utf8($rs_busq->fields['tipo_proy']);
			$vec[$pos][utf8_dec('Fecha presentacion')] = utf8(FormatoFechaNormal($rs_busq->fields['fec_presentacion']));
			$vec[$pos][utf8_dec('Fecha de Aprobación')] = utf8(FormatoFechaNormal($rs_busq->fields['fec_aprobacion']));
			$vec[$pos][utf8_dec('Tipo de Aprobación')] = utf8($rs_busq->fields['tipo_aprobacion']);
			$vec[$pos][utf8_dec('Caratula')] = utf8($rs_busq->fields['caratula']);
			$vec[$pos][utf8_dec('Causante')] = utf8($rs_busq->fields['id_causante_txt']);
			$vec[$pos][utf8_dec('Ubicación actual')] = utf8($rs_busq->fields['id_ubicacion_actual_txt']);

			$pos++;
			$rs_busq->MoveNext();
		}
		

		$opciones_columnas = array(
			utf8_dec('Número') => array('width'=>50), 
			utf8_dec('Tipo de Proyecto') => array('width'=>70),
			utf8_dec('Fecha ingreso') => array('width'=>53),
			utf8_dec('Fecha de Aprobación') => array('width'=>53),
			utf8_dec('Tipo de Aprobación') => array('width'=>135),
			utf8_dec('Caratula') => array('width'=>220),
			utf8_dec('Causante') => array('width'=>220),
			utf8_dec('Ubicación actual') => array('width'=>110)
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
<title>Sistema de Gestión Parlamentaria</title>
<link rel="stylesheet" type="text/css" href="../inc/estilo.css">
<? include('../inc/efecto_transicion.inc.php'); ?>
<script src="../inc/funciones.js"></script>
<? include('../inc/calendario/calendario.inc.php'); ?>
<script type="text/javascript" src="../inc/autosuggest/js/bsn.AutoSuggest_2.1.3.js" charset="utf-8"></script>
<link rel="stylesheet" href="../inc/autosuggest/css/autosuggest_inquisitor.css" type="text/css" media="screen" charset="utf-8" />

<style>
.input_disabled {
	background-color: #555;
}
</style>

<script language="javascript">
	window.onload = function () {
		with (document.form1) {
				
				if(opcion_busqueda[0].value == 'general') 
					opcion_busqueda[0].checked = true;
				else {
					opcion_busqueda[1].checked = true;
					opcion_busqueda[2].checked = true;
				}
				
				select_busqueda_campo.disabled = true;
				input_busqueda_campo.disabled = true;
				numero.disabled = true;
				select_busqueda_campo.className = "input_disabled";
				input_busqueda_campo.className = "input_disabled";
				numero.className = "input_disabled";
				
				input_busqueda_general.disabled = false;
		}
	}
	
	
	function ValidarBusq()
	{
		with (document.form1)
		{
			if (input_busqueda_general.value == "" &&
				input_busqueda_campo.value == "" &&
				numero.value == "" && 
				tipo_proy.value == "" && 
				tipo.value == "" && 
				anio.value == "" && 
				xfec_presentacion_desde.value == "" &&
				xfec_presentacion_hasta.value == "" &&
				xfec_sesion_desde.value == "" &&
				xfec_sesion_hasta.value == "" &&
				xfec_aprobacion_desde.value == "" &&
				xfec_aprobacion_hasta.value == ""
//				id_causante_txt.value == "" && caratula.value == "" &&
//				num_mensaje.value == "" &&
//				id_ubicacion_actual_txt.value == "" && com_destino_txt.value == "" &&
//				id_ubicacion_actual_txt.value == "" &&
//				id_aprobacion.value == "" && id_aprobacion_txt.value == "" &&
//				tipo_aprobacion.value == "" && agregados_txt.value == "" && tags.value == ""
			)
			{
				alert("Debe elegir al menos un criterio.");
				return false;
			}
			else
				return true;
				
		}
	}

	function seleccion_opcion_busqueda(e)
	{
		with (document.form1) {
			input_busqueda_general.disabled = true;
			input_busqueda_general.className = "input_disabled";

			select_busqueda_campo.disabled = false;
			select_busqueda_campo.className = "input_disabled";
			input_busqueda_campo.disabled = true;
			input_busqueda_campo.className = "input_disabled";

			numero.disabled = true;
			numero.className = "input_disabled";

			if(e.value == 'general') {
				input_busqueda_general.disabled = false;
				input_busqueda_general.className = "";
			} else if(e.value == 'campo') {
				select_busqueda_campo.disabled = false;
				select_busqueda_campo.className = "";
				input_busqueda_campo.disabled = false;
				input_busqueda_campo.className = "";
			} else if(e.value == 'numero') {
				numero.disabled = false;
				numero.className = "";
			}
		}
	}
	
	function Imprimir()
	{
		if (ValidarBusq())
		{
			with (document.form1)
			{
				action = 'busquedaAvanzada.php?accion=imprimir';
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
				action = 'busquedaAvanzada.php?accion=buscar';
				target = '';
				submit();
			}
		}
	}
	
	function Editar(expediente)
	{
		window.location = "../abms/expediente.php?accion=editar&numero="+expediente;
	}
	
	function GenerarPDF(expediente)
	{
		with (document.form_pdf)
		{
			numero.value = expediente;
			submit();
		}
	}
	
</script>


</head>

<body>
<form action="busquedaAvanzada.php?accion=buscar" method="post" name="form1" id="form1" onsubmit="return ValidarBusq();">
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
    <td height="440" align="center" valign="top" class="contenido"><br />
        
        

        <? if (!$esPostBack) { ?>        
        
        <table width="740" border="0" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
		  <tr>
		  	<td height="25" colspan="5" align="center" valign="middle" class="header2">Busqueda general</td>
		  </tr>
          <tr>
			<td class="td1"  width="25">
				<input type="radio" name="opcion_busqueda" value="general" onclick="seleccion_opcion_busqueda(this)" />
			</td>          	
            <td align="left" class="td1" width="200">
            	Ingrese palabras claves a buscar
            </td>
            <td align="left" class="td2" colspan="3" >
            	<input type="text" 	maxlength="200" style="width: 350px;" name="input_busqueda_general" />
            </td>
          </tr>
        </table>

		<br/>

        <table width="740" border="0" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
		  <tr>
		  	<td height="25" colspan="5" align="center" valign="middle" class="header2">Busqueda por campo</td>
		  </tr>
          <tr>
			<td class="td1" width="25">
				<input type="radio" name="opcion_busqueda" value="campo"  onclick="seleccion_opcion_busqueda(this)" />
			</td>
            <td align="left" class="td1" width="200">
            		<select name="select_busqueda_campo" >
						<option value="id_causante_txt" 		>Causante</option>
						<option value="caratula" 				>Car&aacute;tula</option>
						<option value="id_ubicacion_actual_txt" >Ubicaci&oacute;n actual</option>
						<option value="num_mensaje" 			>N&uacute;mero de mensaje</option>
						<option value="com_destino_txt" 		>Comisi&oacute;n de Destino</option>
						<option value="id_aprobacion_txt" 		>Forma de aprobación</option>
						<option value="tipo_aprobacion" 		>Tipo de aprobación</option>
						<option value="agregados_txt" 			>Exp. agregado</option>
					</select>
            </td>
            <td align="left" class="td2" colspan="3" ><input type="text" maxlength="200" style="width: 350px;"  name="input_busqueda_campo" /></td>
          </tr>
        </table>

      <br />

        <table width="740" border="0" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
		  <tr>
		  	<td height="25" colspan="5" align="center" valign="middle" class="header2">Busqueda por Número de expediente</td>
		  </tr>
          <tr>
			<td class="td1"  width="25">
				<input type="radio" name="opcion_busqueda" value="numero" onclick="seleccion_opcion_busqueda(this)" />
			</td>          	
	        <td width="200" height="24" align="left" class="td1">N&uacute;mero de expediente</td>
            <td width="202" align="left" class="td2" colspan="3" >
            	<input type="text" name="numero" id="numero" />
            </td>
          </tr>
        </table>

      <br />

        
        <table width="740" border="0" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
          <tr>
            <td height="25" colspan="4" align="center" valign="middle" class="header2"><?=$titulo_busq?></td>
          </tr>
          <tr>
<!--             
            <td width="178" height="24" align="left" class="td1">N&uacute;mero de expediente</td>
            <td width="202" align="left" class="td2"><input type="text" name="numero" id="numero" /></td>
-->            
            <? if ($_SESSION['perfil'] != 'C') { ?>
            <td width="143" align="left" class="td1">Tipo de expediente</td>
            <td width="204" align="left" class="td2" colspan="3" >
            <select name="tipo" id="tipo" style="width:204px;">              
			  <? if ($_SESSION['perfil'] == 'E') { ?>
			      <option value="O" <? if ($expediente->tipo=='O') echo 'selected'; ?>>Orden del d&iacute;a</option>
			      <option value="P" <? if ($expediente->tipo=='P') echo 'selected'; ?>>Preferencias</option>				    
			  <? } elseif (($_SESSION['perfil'] == 'U') || ($_SESSION['perfil'] == 'T') || ($_SESSION['perfil'] == 'A')) { ?>
	              <option value="I" <? if ($expediente->tipo=='I') echo 'selected'; ?>>Interno</option>

        	      <option value="L" <? if ($expediente->tipo=='L') echo 'selected'; ?>>Legislativo</option>
			  <? } else { ?>
	              <option value="">- Elegir -</option>
	              <option value="I" <? if ($expediente->tipo=='I') echo 'selected'; ?>>Interno</option>
    	          <option value="M" <? if ($expediente->tipo=='M') echo 'selected'; ?>>Municipal</option>
        	      <option value="L" <? if ($expediente->tipo=='L') echo 'selected'; ?>>Legislativo</option>
			  <? } ?>
            </select></td>

            <? } else { ?>
			<td width="143" align="left" class="td1" colspan="2">&nbsp;<input id="tipo" name="tipo" type="hidden" value="L" /></td>
            <? } ?>
          </tr>

          <tr>
            <td align="left" class="td1">A&ntilde;o</td>
            <td align="left" class="td2">
            	<!-- input id="anio" name="anio" type="text" style="width:30px;" maxlength="4" onkeypress="return acceptNum(event)" value="" / -->
            	<input id="anio" name="anio" type="text" style="width:30px;" maxlength="4" value="" />
            </td>
            <td align="left" class="td1">Tipo de proyecto</td>
            <td align="left" class="td2"><input id="tipo_proy" name="tipo_proy" type="text" style="width:200px;" maxlength="35" /></td>
          </tr>

          <tr>
            <td align="left" class="td1">Fecha de presentaci&oacute;n desde</td>
            <td align="left" class="td2"><?=CalendarioCreaInput('fec_presentacion_desde', $expediente->fec_presentacion_desde, '')?></td>
           <td align="left" class="td1">Fecha de presentaci&oacute;n hasta</td>
            <td align="left" class="td2"><?=CalendarioCreaInput('fec_presentacion_hasta', $expediente->fec_presentacion_hasta, '')?></td>
             
          </tr>
          <tr id="tr_fec_sesion">
            <td align="left" class="td1">Fecha de Sesi&oacute;n desde</td>
            <td align="left" class="td2"><?=CalendarioCreaInput('fec_sesion_desde', $expediente->fec_sesion_desde, '')?></td>
            <td align="left" class="td1">Fecha de Sesi&oacute;n hasta</td>
            <td align="left" class="td2"><?=CalendarioCreaInput('fec_sesion_hasta', $expediente->fec_sesion_hasta, '')?></td>
          </tr>
          <tr>
            <td align="left" class="td1">Fecha de aprobación desde</td>
            <td align="left" class="td2"><?=CalendarioCreaInput('fec_aprobacion_desde', $expediente->fec_aprobacion_desde, '')?></td>
            <td align="left" class="td1">Fecha de aprobación hasta</td>
            <td align="left" class="td2"><?=CalendarioCreaInput('fec_aprobacion_hasta', $expediente->fec_aprobacion_hasta, '')?></td>
           </tr>
           
          <!-- tr>
            <td align="left" class="td1">Causante</td>
            <td align="left" class="td2">
            <input id="id_causante_txt" name="id_causante_txt" type="text" style="width:200px;" maxlength="50" value="<?=utf8($expediente->id_causante_txt)?>" />
            <input type="hidden" id="id_causante" name="id_causante" value="<?=utf8($expediente->id_causante)?>" />
            </td>
            <td align="left" class="td1">Car&aacute;tula</td>
            <td align="left" class="td2"><input name="caratula" type="text" id="caratula" style="width:200px;" /></td>
          </tr>
          <tr id="tr_tipo_proy">
            <td align="left" class="td1">Ubicaci&oacute;n actual</td>
            <td align="left" class="td2"><input id="id_ubicacion_actual_txt" name="id_ubicacion_actual_txt" type="text" style="width:200px;" maxlength="150" />
              <input type="hidden" id="id_ubicacion_actual" name="id_ubicacion_actual" value="<?=$expediente->id_ubicacion_actual?>" /></td>
            <td align="left" class="td1">N&uacute;mero de mensaje</td>
            <td align="left" class="td2"><input id="num_mensaje" name="num_mensaje" type="text" style="width:200px;" maxlength="11" /></td>
          </tr>
          <tr>
            <td align="left" class="td1">Forma de aprobación</td>
            <td align="left" class="td2"><input id="id_aprobacion_txt" name="id_aprobacion_txt" type="text" style="width:200px;" maxlength="150" />
              <input type="hidden" id="id_aprobacion" name="id_aprobacion" value="<?=$expediente->id_aprobacion?>" /></td>
            <td align="left" class="td1">Comisi&oacute;n de Destino</td>
            <td align="left" class="td2"><input id="com_destino_txt" name="com_destino_txt" type="text" style="width:200px;" maxlength="150" />
              <input type="hidden" id="com_destino" name="com_destino" value="<?=$expediente->com_destino?>" /></td>
          </tr>
          <tr>
            <td align="left" class="td1">Tipo de aprobación</td>
            <td align="left" class="td2"><input id="tipo_aprobacion" name="tipo_aprobacion" type="text" style="width:200px;" maxlength="40" /></td>
            <td align="left" class="td1">Exp. agregado</td>
            <td align="left" class="td2"><input name="agregados_txt" type="text" id="agregados_txt" style="width:200px;" /></td>
          </tr>
          <tr>
            <td align="left" class="td1">Tags</td>
            <td align="left" class="td2" colspan="3" ><input type="text" value="" name="tags" maxlength="200" style="width: 350px;" /></td>
          </tr-->
          
        </table>

      <br />
      <br />
      <? if ($_SESSION['perfil'] == 'C') { ?>
      	<br><a href="busqueda.php">Volver a Busqueda Normal</a><br>
      <?php }?>
<?
//	Causante
//	Car&aacute;tula
//	Ubicaci&oacute;n actual
//	N&uacute;mero de mensaje
//	Forma de aprobación
//	Comisi&oacute;n de Destino
//	Tipo de aprobación
//	Exp. agregado
 ?>
        
        
        
        
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
      if ($rs_empty) 
      {
      	echo "<div align='center'><br><br><br><span class='mensaje'>No se encontraron resultados.</span></div>";
	  }
	  else
	  {
      ?>
      <table width="740" border="0" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
        <tr>
          <td align="center" class="header2">Número</td>
          <td align="center" class="header2">Tipo de Proyecto</td>
          <td align="center" class="header2">Fecha presentación</td>
          <td align="center" class="header2">Fecha de Aprobación</td>
          <td align="center" class="header2">Tipo de Aprobación</td>
          <td align="center" class="header2">Caratula</td>
          <td align="center" class="header2">Causante</td>
          <td align="center" class="header2">Ubicación actual</td>
          <td align="center" class="header2">Opciones</td>
        </tr>
		<?
			$clase = 'td1';
			$countExp=0;
			while (!$rs_busq->EOF) {
				$countExp++;
				if ($clase == 'td1') $clase = 'td2';
				else {$clase = 'td1';}
			
			?>
			<tr class="<?=$clase?>">
			  <td align="right">
			  <?
              	if ($rs_busq->fields['tipo'] == 'M')
		        	echo $rs_busq->fields['nro_municipal'];
				else
		        	echo number_format($rs_busq->fields['numero'], 0, ',', '.');
			  ?>
              </td>
			  <td align="left"><?=$rs_busq->fields['tipo_proy']?></td>
			  <td align="left"><?=FormatoFechaNormal($rs_busq->fields['fec_presentacion'])?></td>
			  <td align="right"><?=FormatoFecha($rs_busq->fields['fec_aprobacion'])?></td>
			  <td align="right"><?=utf8($rs_busq->fields['tipo_aprobacion'])?></td>
			  <td align="left"><?=utf8($rs_busq->fields['caratula'])?></td>
			  <td align="left"><?=utf8($rs_busq->fields['id_causante_txt'])?></td>
			  <td align="left"><?=utf8($rs_busq->fields['id_ubicacion_actual_txt'])?></td>
			  <td align="center" nowrap="nowrap">
              <a href="ver_expediente.php?numero=<?=$rs_busq->fields['numero']?>" target="_blank"><img src="../imagenes/ver.gif" alt="Ver expediente" width="28" height="16" border="0" /></a>
              <? if ($_SESSION['perfil'] == 'D' || $_SESSION['perfil'] == 'S' || $_SESSION['perfil'] == 'J' || ($_SESSION['perfil'] == 'T' && $rs_busq->fields['tipo'] == 'I' )){ ?>
                  &nbsp;&nbsp;
                  <a href="javascript: Editar(<?=$rs_busq->fields['numero']?>);"><img src="../imagenes/editar.gif" alt="Editar expediente" width="20" height="16" border="0" /></a>
              <? } ?>
              <? if ($_SESSION['perfil'] != 'C' && $_SESSION['perfil'] != 'U' && $_SESSION['perfil'] != 'E' && $_SESSION['perfil'] != 'T' && $_SESSION['perfil'] != 'A'){ ?>
                  &nbsp;&nbsp;
                  <a href="javascript: GenerarPDF(<?=$rs_busq->fields['numero']?>);"><img src="../imagenes/pdf.gif" alt="Generar PDF" width="16" height="16" border="0" /></a></td>
              <? } ?>
			</tr>
		<? 
					$rs_busq->MoveNext();
				}

		?>
      </table>
<?php echo "Cantidad de registros encontrados: " . $countExp; ?>
      <? } //de si no es EOF ?>
      <br />
      <br />
      <input type="button" name="btnBuscarOtro" id="btnBuscarOtro" value="Buscar otro" style="width:150px" onclick="window.location = 'busquedaAvanzada.php';" />
      <? if ($_SESSION['perfil'] != 'C') { ?>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" style="width:150px" onclick="Imprimir();" />
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <input type="button" name="btnVolver" id="btnVolver" value="Volver" style="width:150px" onclick="window.location = 'menu_principal.php';" />      
      <? } else { ?>
          <input type="button" name="btnCerrarSesion" id="btnCerrarSesion" value="Cerrar sesion" style="width:150px" onclick="window.location = 'login.php';" />
      <? } ?>
      
      
      <input name="numero" id="numero" type="hidden" value="<?=$_POST['numero']?>" />
      <input name="tipo" id="tipo" type="hidden" value="<?=$_POST['tipo']?>" />
      <input name="tipo_proy" id="tipo_proy" type="hidden" value="<?=$_POST['tipo_proy']?>" />
      <input name="anio" id="anio" type="hidden" value="<?=$_POST['anio']?>" />
      <input name="fec_presentacion_desde" id="fec_presentacion_desde" type="hidden" value="<?=$_POST['fec_presentacion_desde']?>" />
      <input name="fec_presentacion_hasta" id="fec_presentacion_hasta" type="hidden" value="<?=$_POST['fec_presentacion_hasta']?>" />
      <input name="fec_sesion_desde" id="fec_sesion_desde" type="hidden" value="<?=$_POST['fec_sesion_desde']?>" />
      <input name="fec_sesion_hasta" id="fec_sesion_hasta" type="hidden" value="<?=$_POST['fec_sesion_hasta']?>" />
      <input name="fec_aprobacion_desde" id="fec_aprobacion_desde" type="hidden" value="<?=$_POST['fec_aprobacion_desde']?>" />
      <input name="fec_aprobacion_hasta" id="fec_aprobacion_hasta" type="hidden" value="<?=$_POST['fec_aprobacion_hasta']?>" />
      <input name="id_causante_txt" id="id_causante_txt" type="hidden" value="<?=$_POST['id_causante_txt']?>" />
      <input name="id_ubicacion_actual_txt" id="id_ubicacion_actual_txt" type="hidden" value="<?=$_POST['id_ubicacion_actual_txt']?>" />
      <input name="id_ubicacion_actual" id="id_ubicacion_actual" type="hidden" value="<?=$_POST['id_ubicacion_actual']?>" />
      <input name="id_aprobacion_txt" id="id_aprobacion_txt" type="hidden" value="<?=$_POST['id_aprobacion_txt']?>" />
      <input name="id_aprobacion" id="id_aprobacion" type="hidden" value="<?=$_POST['id_aprobacion']?>" />
      <input name="tipo_aprobacion" id="tipo_aprobacion" type="hidden" value="<?=$_POST['tipo_aprobacion']?>" />
      <input name="caratula" id="caratula" type="hidden" value="<?=$_POST['caratula']?>" />
      <input name="num_mensaje" id="num_mensaje" type="hidden" value="<?=$_POST['num_mensaje']?>" />
      <input name="com_destino_txt" id="com_destino_txt" type="hidden" value="<?=$_POST['com_destino_txt']?>" />
      <input name="com_destino" id="com_destino" type="hidden" value="<?=$_POST['com_destino']?>" />
      <input name="xfec_presentacion_desde" id="xfec_presentacion_desde" type="hidden" value="<?=$_POST['xfec_presentacion_desde']?>" />
      <input name="xfec_sesion_desde" id="xfec_sesion_desde" type="hidden" value="<?=$_POST['xfec_sesion_desde']?>" />
      <input name="xfec_presentacion_hasta" id="xfec_presentacion_hasta" type="hidden" value="<?=$_POST['xfec_presentacion_hasta']?>" />
      <input name="xfec_sesion_hasta" id="xfec_sesion_hasta" type="hidden" value="<?=$_POST['xfec_sesion_hasta']?>" />
      <input name="agregados_txt" id="agregados_txt" type="hidden" value="<?=$_POST['agregados_txt']?>" />
      
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
</form>
</div>
</body>
</html>
