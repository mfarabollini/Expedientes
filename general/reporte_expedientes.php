<? include('../inc/inicio.inc.php'); ?>
<? include('../class/expediente.class.php'); ?>
<? include('../inc/pdf/class.ezpdf.php'); ?>
<?

header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0


CheckPerfiles('SD');

$expediente = new Expediente();
$expediente->flt_solo_sin_imprimir = 'S';

$esPostBack = false;
$fec_presentacion_desde = date('Y-m').'-01';
$fec_presentacion_hasta = AhoraFecha();

$fecha_dia = AhoraFecha();
 	

if (isset($_GET['accion'])) 
{



	$titulo = utf8_dec2($_POST['ddlTitulo']);
	$orden = '';
	
	if ($titulo == 'ASUNTOS ENTRADOS')
		$orden = 'GRUPO';
	else
		$orden = 'NUMERO';

	if ($_GET['accion'] == 'buscar') 
	{
		$rs_busq = $conn->Execute($expediente->SqlReporte($orden));
		$esPostBack = true;
		Auditoria('null', 'Reporte expedientes con filtro:<br>'.$expediente->FiltroReporte());
	}

	if ($_GET['accion'] == 'imprimir') 
	{
		header ("content-disposition: attachment;filename=Reporte_expedientes.pdf");
		$rs_busq = $conn->Execute($expediente->SqlReporte($orden));

		Auditoria('null', 'Imprimio reporte expedientes con filtro:<br>'.$expediente->FiltroReporte());		
		
		
		$pdf =& new Cezpdf('LEGAL');
		$pdf->selectFont('../inc/pdf/fonts/Helvetica.afm');
		$datacreator = array (
						  'Title'=>$titulo,
						  'Author'=>'Sistema de expedientes',
						  'Subject'=>'',
						  'Creator'=>'',
						  'Producer'=>'Sistema de expedientes'
						  );
		$pdf->addInfo($datacreator);
		
		//titulo
		$pdf->ezText(utf8_dec("CONCEJO MUNICIPAL DE ROSARIO\n\nSesion ".utf8_dec2($_POST['ddlSesion'])." del dia: ").$_POST['fecha_dia']."\n",15, array('justification'=>'center'));
		$pdf->ezText("<b>".utf8_dec2($titulo."</b>\n"),15, array('justification'=>'center'));
		$pdf->ezText("");
		
		$pdf->rectangle(22,870,570,120);
		
		$pdf->ezStartPageNumbers(590,20,10,'',utf8_dec('Pagina: ').'{PAGENUM} de {TOTALPAGENUM}',1);


		$grupo_ant = "NOHAY";
		while (!$rs_busq->EOF)
		{
			$imprimir = false;
			
			if (isset($_POST['chk_imp_'.$rs_busq->fields['numero']]))
				$imprimir = $_POST['chk_imp_'.$rs_busq->fields['numero']] == 'S';
			
			if ($imprimir)
			{
			
				$pdf->ezText("");
				$pdf->ezText("");
				$pdf->ezText("");

				//solo para este tipo de listado imprimo los grupos				
				if ($titulo == 'ASUNTOS ENTRADOS')
				{

				
				if ($grupo_ant != $rs_busq->fields['grupo'])
				{
					$grupo_ant = $rs_busq->fields['grupo'];
					$pdf->ezText("<b><u>".utf8_dec2($grupo_ant."</u></b>\n"),18);
				}
				}

			
				$pdf->ezText(utf8_dec("\n").$rs_busq->fields['id_causante_txt'].': '.utf8($rs_busq->fields['tipo_proy']),14, array('leading'=>'10'));
				$pdf->ezText(utf8_dec("\n").$rs_busq->fields['caratula'],10, array('leading'=>'10'));
				$pdf->ezText(utf8_dec("\nComision: ").$rs_busq->fields['com_destino_txt'],10, array('leading'=>'10'));
				$pdf->ezText(utf8_dec("\nExpediente Nro ").number_format($rs_busq->fields['numero'], 0, ',', '.').' - '.$rs_busq->fields['letra'].' - '.$rs_busq->fields['anio'],10, array('leading'=>'10'));

			
				$expediente = new Expediente();
				$expediente->CargarExpediente($rs_busq->fields['numero']);
				$expediente->MarcarImpreso();
			}
			
			$rs_busq->MoveNext();
		}
		
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


<script language="javascript">
	
	function Buscar()
	{
		with (document.form1)
		{
			action = 'reporte_expedientes.php?accion=buscar';
			target = '';
			submit();
		}
	}
	
	
	function ImprimirReporte()
	{
		if (AlgunoElegido())
		{
			with (document.form1)
			{
				action = 'reporte_expedientes.php?accion=imprimir';
				target = '_blank';
				submit();
				
				window.location = "reporte_expedientes.php";
			}
		}
		else
			alert('Debe elegir al menos un expediente para imprimir.');
	}
	
	function AlgunoElegido()
	{
		var alguno = false;
		
        for (var i=0; i < document.forms[0].elements.length; i++)
        {
            sTipo = document.forms[0].elements[i].type

            if (sTipo == "checkbox")
                if (document.forms[0].elements[i].checked)
					alguno = true;
        }
		
		return alguno;
	}

	function SeleccionarTodos()
	{
        for (var i=0; i < document.forms[0].elements.length; i++)
        {
            sTipo = document.forms[0].elements[i].type

            if (sTipo == "checkbox")
                document.forms[0].elements[i].checked = true;
        }
	}
	
	function DesSeleccionarTodos()
	{
        for (var i=0; i < document.forms[0].elements.length; i++)
        {
            sTipo = document.forms[0].elements[i].type

            if (sTipo == "checkbox")
                document.forms[0].elements[i].checked = false;
        }
	}
	
	function InvertirSeleccion()
	{
        for (var i=0; i < document.forms[0].elements.length; i++)
        {
            sTipo = document.forms[0].elements[i].type

            if (sTipo == "checkbox")
                document.forms[0].elements[i].checked = !document.forms[0].elements[i].checked;
        }
	}
	
</script>

</head>

<body>
<form action="reporte_expedientes.php?accion=buscar" method="post" name="form1" id="form1">
<div align="center">
<table width="800" height="500" border="0" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF">
<tr>
        <td height="107" align="left" valign="middle" class="fondo_encabezado">
        <table width="800" height="107" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="130">&nbsp;</td>
            <td width="670" align="left" valign="middle" class="texto_encabezado">Reporte de Expedientes</td>
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
            <td height="25" colspan="4" align="center" valign="middle" class="header2">Reporte de Expedientes</td>
          </tr>
          <tr>
            <td width="178" height="24" align="left" class="td1">N&uacute;mero de expediente</td>
            <td width="202" align="left" class="td2"><input type="text" name="numero" id="numero" /></td>
            
            <td width="143" align="left" class="td1">Fecha de presentación desde</td>
            <td width="204" align="left" class="td2"><?=CalendarioCreaInput('fec_presentacion_desde', $expediente->fec_presentacion_desde, '')?></td>
          </tr>

          <tr>
            <td align="left" class="td1">Solo sin imprimir</td>
            <td align="left" class="td2"><input name="flt_solo_sin_imprimir" type="checkbox" id="flt_solo_sin_imprimir" value="S" <? if ($expediente->flt_solo_sin_imprimir=='S') {echo 'checked';} ?> /></td>
            <td align="left" class="td1">Fecha de presentación hasta</td>
            <td align="left" class="td2"><?=CalendarioCreaInput('fec_presentacion_hasta', $expediente->fec_presentacion_hasta, '')?></td>
          </tr>
          <tr>
            <td align="left" class="td1">Titulo</td>
            <td colspan="3" align="left" class="td2"><select name="ddlTitulo" id="ddlTitulo">
              <option value="ASUNTOS ENTRADOS">ASUNTOS ENTRADOS</option>
              <option value="ASUNTOS ENTRADOS-ORDEN INTERNO (CONTINUACION)">ASUNTOS ENTRADOS-ORDEN INTERNO (CONTINUACION)</option>
              <option value="INGRESO POR RECINTO" selected="selected">INGRESO POR RECINTO</option>
            </select>            </td>
            </tr>
          <tr>
            <td align="left" class="td1">Subtitulo</td>
            <td colspan="3" align="left" class="td2">Sesion 
              <select name="ddlSesion" id="ddlSesion">
                <option value="Ordinaria" selected="selected">Ordinaria</option>
                <option value="Prorroga">Prorroga</option>
                <option value="Extraordinaria">Extraordinaria</option>
              </select> 
              del dia 
              <?=CalendarioCreaInput('fecha_dia', $fecha_dia, '')?></td>
          </tr>

        </table>
      <br />
      <br />
      <br />
      <br />
      <input type="button" name="btnBuscar" id="btnBuscar" value="Buscar" style="width:150px" onclick="Buscar();" />
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="button" name="btnVolver" id="btnVolver" value="Volver" style="width:150px" onclick="window.location = 'menu_principal.php';" />      

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
          <td align="center" class="header2">Imprimir</td>
          <td align="center" class="header2">Número</td>
          <td align="center" class="header2">Tipo de Proyecto</td>
          <td align="center" class="header2">Fecha presentación</td>
          <td align="center" class="header2">Fecha de Aprobación</td>
          <td align="center" class="header2">Tipo de Aprobación</td>
          <td align="center" class="header2">Caratula</td>
          <td align="center" class="header2">Causante</td>
          <td align="center" class="header2">Ubicación actual</td>
          </tr>
		<?
			$clase = 'td1';
			$grupo_ant = "NOHAY";
			while (!$rs_busq->EOF) {
				if ($clase == 'td1') {$clase = 'td2';}
				else {$clase = 'td1';}
			
			?>
            <?
			//solo para ese tipo de listado imprimo el grupo de impresion
			if (($titulo == 'ASUNTOS ENTRADOS') && ($rs_busq->fields['grupo'] != $grupo_ant))

			{
				$grupo_ant = $rs_busq->fields['grupo'];
			?>
			<tr>
			  <td colspan="9" align="left" bgcolor="#7F7F7F"> &nbsp;<font color="#FFFFFF">Grupo de impresión: <strong><?=$grupo_ant?></strong></font></td>
			</tr>
            <? } ?>
			<tr class="<?=$clase?>">
			  <td align="center"><input type="checkbox" name="chk_imp_<?=$rs_busq->fields['numero']?>" id="chk_imp_<?=$rs_busq->fields['numero']?>" value="S" /></td>
			  <td align="left">
			  <?
              	if ($rs_busq->fields['tipo'] == 'M')
		        	echo $rs_busq->fields['nro_municipal'];
				else
		        	echo number_format($rs_busq->fields['numero'], 0, ',', '.');
			  ?>              </td>
			  <td align="left"><?=$rs_busq->fields['tipo_proy']?></td>
			  <td align="left"><?=FormatoFechaNormal($rs_busq->fields['fec_presentacion'])?></td>
			  <td align="right"><?=FormatoFecha($rs_busq->fields['fec_aprobacion'])?></td>
			  <td align="right"><?=utf8($rs_busq->fields['tipo_aprobacion'])?></td>
			  <td align="left"><?=utf8($rs_busq->fields['caratula'])?></td>
			  <td align="left"><?=utf8($rs_busq->fields['id_causante_txt'])?></td>
			  <td align="left"><?=utf8($rs_busq->fields['id_ubicacion_actual_txt'])?></td>
			</tr>
		<? 
					$rs_busq->MoveNext();
				}
		?>
      </table>
      <? } //de si no es EOF ?>
      <br />
      <br />
      <input type="button" name="BtnSelAll" id="BtnSelAll" value="Seleccionar todos" style="width:120px" onclick="SeleccionarTodos();" />
      <input type="button" name="BtnDesSelAll" id="BtnDesSelAll" value="Deseleccionar todos" style="width:120px" onclick="DesSeleccionarTodos();" />
      <input type="button" name="BtnInvertir" id="BtnInvertir" value="Invertir seleccion" style="width:120px" onclick="InvertirSeleccion();" />
      <input type="button" name="BtnImprimirReporte" id="BtnImprimirReporte" value="Imprimir reporte" style="width:120px" onclick="ImprimirReporte();" />
      <input type="button" name="btnBuscarOtro" id="btnBuscarOtro" value="Buscar otro" style="width:120px" onclick="window.location = 'reporte_expedientes.php';" />
      <input type="button" name="btnVolver" id="btnVolver" value="Volver" style="width:120px" onclick="window.location = 'menu_principal.php';" />            
      <br />
      
      <!-- Guardo en hiddens la busqueda anterior para poder luego imprimir el reporte -->
      
      <input name="ddlTitulo" id="ddlTitulo" type="hidden" value="<?=$_POST['ddlTitulo']?>" />
      <input name="numero" id="numero" type="hidden" value="<?=$_POST['numero']?>" />
      <input name="flt_solo_sin_imprimir" id="flt_solo_sin_imprimir" type="hidden" value="<?=$_POST['flt_solo_sin_imprimir']?>" />
      <input name="fec_presentacion_desde" id="fec_presentacion_desde" type="hidden" value="<?=$_POST['fec_presentacion_desde']?>" />
      <input name="fec_presentacion_hasta" id="fec_presentacion_hasta" type="hidden" value="<?=$_POST['fec_presentacion_hasta']?>" />
      <input name="ddlSesion" id="ddlSesion" type="hidden" value="<?=$_POST['ddlSesion']?>" />
      <input name="fecha_dia" id="fecha_dia" type="hidden" value="<?=$_POST['xfecha_dia']?>" />

      <? } //de si es postback ?>
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

	
</script>

</form>
</body>
</html>
