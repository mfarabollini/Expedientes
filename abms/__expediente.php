<? include('../inc/inicio.inc.php'); ?>
<? include('../class/expediente.class.php'); ?>
<?

DesactivarSSL();

$comisionDestino = Array(	'GOBIERNO Y CULTURA',
							'PRESUPUESTO Y HACIENDA',
							'OBRAS PUBLICAS Y SEGURIDAD',
							'SEGURIDAD PUBLICA y CIUDADANA',
							'SALUD Y ACCION SOCIAL',
							'PLANEAMIENTO Y URBANISMO',
							'SERVICIOS PUBLICOS Y CONCEDIDOS',
							'PRODUCCION Y PROMOCION DEL EMPLEO',
							'ECOLOGIA Y MEDIO AMBIENTE',
							'DERECHOS HUMANOS',
							'PRESIDENCIA',
							'PRESUPUESTO, HACIENDA Y OBRAS');

$mensa = '&nbsp;';
$titulo = '';
$numero = '';
$onload = '';
$numero_txt = '';
//$accion = '';


	// Esto es un hack para que ande la carga y actualizacion de los expedientes sin tener que modificar la base de datos
	// ya que se elimino el campo de categoría, de esta manera todos los expedientes van a estar asociados a la categoria
	// de id igual a 1.
	$_POST['id_categoria'] = 1;
	$_POST['id_categoria_txt'] = 'H. CUERPO';


if (isset($_GET['accion'])) 
{
	$expediente = new Expediente();
	$accion = $_GET['accion'];

	if ($accion == 'editar') 
	{
		CheckPerfiles('SDJ');
		$numero = $_GET['numero'];
		$numero_txt = number_format($_GET['numero'], 0, ',', '.');
		$expediente->CargarExpediente($numero);
		if($_SESSION['perfil'] == 'J')
			$onload = 'onload="DisabledInputs();"';
	}


	if ($accion == 'verif_numero') 
	{
		echo $expediente->VerifNumero($_GET['numero']);
		exit;
	}
	

	if ($accion == 'alta') 
	{
		CheckPerfiles('SDO');
		$titulo = 'Alta de expediente';
		$numero = '(Nuevo)';
		$onload = 'onload="ActualizaProyecto('."'".$expediente->tipo."'".');"';
	}


	if ($accion == 'grabar') 
	{
		CheckPerfiles('SDOJ');

		if($_SESSION['perfil'] == 'J')
			$expediente->GrabarTags();
		else
			$expediente->GrabarPost();
			
		Auditoria($expediente->numero, $_POST['accion_anterior']);
		header("Location: confirma.php?numero=".$expediente->numero."&accion=".$_POST['accion_anterior']);
	}
	
}
else
{
	header("Location: ../general/menu_principal.php");
	exit;
}
	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Sistema de Gestión Parlamentaria</title>
<? include('../inc/efecto_transicion.inc.php'); ?>
<link rel="stylesheet" type="text/css" href="../inc/estilo.css">
<script src="../inc/funciones.js"></script>
<? include('../inc/calendario/calendario.inc.php'); ?>

<script type="text/javascript" src="../inc/autosuggest/js/bsn.AutoSuggest_2.1.3.js" charset="utf-8"></script>
<link rel="stylesheet" href="../inc/autosuggest/css/autosuggest_inquisitor.css" type="text/css" media="screen" charset="utf-8" />



<script language="javascript">

	var oAjax = nuevoAjax();
	var tipo_expediente = "I";

	function Cancelar() 
	{
		if (window.confirm('¿Desea cancelar?')) {
			window.location = '../general/menu_principal.php';
		}		
	}
	
	function ValidarGrabado()
	{
		var todo_vacio = true;
		
		
		with (document.form1)
		{
			if (tipo.value == "") 
				mensaje_err ="Debe ingresar el tipo de proyecto.";
			else
			{
				var t_interno = tipo.value == "I";
				var t_municipal = tipo.value == "M";
				var t_legislativo = tipo.value == "L";
				
				if (letra.value != "") todo_vacio = false;
				if (anio.value != "") todo_vacio = false;
				if (tipo_proy.value != "") todo_vacio = false;
				if (num_mensaje.value != "") todo_vacio = false;
				if (id_causante_txt.value != "") todo_vacio = false;
				if (caratula.value != "") todo_vacio = false;
				if (xfec_presentacion.value != "") todo_vacio = false;
				if (xfec_sesion.value != "") todo_vacio = false;
				if (com_destino_txt.value != "") todo_vacio = false;
				if (id_ubicacion_actual_txt.value != "") todo_vacio = false;
				if (id_aprobacion_txt.value != "") todo_vacio = false;
				
				if (!todo_vacio)
				{
					if (id_grupo.value != "null" && id_grupo.value != "null")
						return true;
					else
					{
						alert("Debe ingresar el gurpo de impresión");
						return false;
					}
				}
				else
				{
					alert("Debe completar al menos un valor");
					return false;
				}
			}		
		}
	}
	
	function Grabar()
	{
		if (ValidarGrabado())
		{
			var oAgregados = document.getElementById('hdnAgregados');		
			var oCombo = document.getElementById('agregados');
	
			oAgregados.value = '|';
			for (var i=0; i < oCombo.options.length; i++)
				oAgregados.value += oCombo.options[i].value + "|";
	
			document.form1.submit();
		}
	}
	
	function ActualizaProyecto(tipo)
	{ return true;

		var oTR_fec_sesion = document.getElementById('tr_fec_sesion');
		var oTR_caratula = document.getElementById('tr_caratula');
		var oTR_tipo_proy = document.getElementById('tr_tipo_proy');
		var oTR_num_mensaje = document.getElementById('tr_num_mensaje');
		var oTR_com_destino = document.getElementById('tr_com_destino');
		var oTR_id_ubicacion_actual = document.getElementById('tr_id_ubicacion_actual');
		var oTR_tr_tipo_aprobacion = document.getElementById('tr_tipo_aprobacion');
		var oTR_tr_fec_aprobacion = document.getElementById('tr_fec_aprobacion');
		//var oTR_id_categoria = document.getElementById('tr_id_categoria');
		var oTR_id_aprobacion = document.getElementById('tr_id_aprobacion');
		

		oTR_fec_sesion.style.display = 'none';
		oTR_caratula.style.display = 'none';
		oTR_tipo_proy.style.display = 'none';
		oTR_num_mensaje.style.display = 'none';
		oTR_com_destino.style.display = 'none';
		oTR_id_ubicacion_actual.style.display = 'none';
		oTR_tr_tipo_aprobacion.style.display = 'none';
		oTR_tr_fec_aprobacion.style.display = 'none';
		//oTR_id_categoria.style.display = 'none';
		oTR_id_aprobacion.style.display = 'none';

		tipo_expediente = tipo;
		
		if (tipo == 'I')
		{
			oTR_caratula.style.display = '';
			oTR_id_ubicacion_actual.style.display = '';
			oTR_com_destino.style.display = '';
			oTR_tipo_proy.style.display = '';
			document.form1.nro_municipal.style.display = 'none';
		}

		if (tipo == 'M')
		{
			oTR_caratula.style.display = '';
			oTR_id_ubicacion_actual.style.display = '';
			oTR_com_destino.style.display = '';
			oTR_tipo_proy.style.display = '';
			document.form1.nro_municipal.style.display = '';
		}

		if (tipo == 'L')
		{
			oTR_fec_sesion.style.display = '';
			oTR_tipo_proy.style.display = '';
			oTR_num_mensaje.style.display = '';
			oTR_com_destino.style.display = '';
			oTR_id_ubicacion_actual.style.display = '';
			//oTR_id_categoria.style.display = '';
			oTR_caratula.style.display = '';
			document.form1.nro_municipal.style.display = 'none';
		}
	}


	function Estilo(obj, estilo)
	{
		if (obj.className != "solapa3")
			obj.className = estilo;
	}

	
	function EligeSolapa(solapa)
	{
		oSolapa1 = document.getElementById("td_solapa1");
		oSolapa2 = document.getElementById("td_solapa2");
		oContSolapa1 = document.getElementById("cont_solapa1");
		oContSolapa2 = document.getElementById("cont_solapa2");
		
		if (solapa == 1)
		{
			oSolapa1.className = "solapa3";
			oContSolapa1.style.display = "";

			oSolapa2.className = "solapa1";
			oContSolapa2.style.display = "none";
		}

		if (solapa == 2)
		{
			oSolapa2.className = "solapa3";
			oContSolapa2.style.display = "";

			oSolapa1.className = "solapa1";
			oContSolapa1.style.display = "none";
		}
	}
	
	
	function EstaEnCombo(expediente)
	{
		var oCombo = document.getElementById('agregados');
		var existe = false;
		
		for (var i=0; i < oCombo.options.length && !existe; i++)
		{
			if (oCombo.options[i].value == expediente) existe = true;
		}
		
		return existe;
	}
	
	
	function AgregarExpAg()
	{
		var oCombo = document.getElementById('agregados');		
		var oNumero = document.getElementById('numero');		
		var oExpediente = document.getElementById('txt_agregar');		
		
		if (oExpediente.value=='') {
			alert('Debe elegir un expediente.');
		}
		else {
			var oAjax = nuevoAjax();
			var existe_exp = '';
			
			
			if (trim(oExpediente.value)==oNumero.value)
			{
				alert("No puede ingresar como agregado a este mismo expediente.");
			}
			else
			{
				//oAjax.open("GET", "expediente.php?accion=verif_numero&numero="+oExpediente.value,true);
				//oAjax.onreadystatechange=function() {
					//if (oAjax.readyState==4 || oAjax.readyState=="complete") {
						//existe_exp = oAjax.responseText;
	
						//if (existe_exp == 'S')
						//{
							if (!EstaEnCombo(oExpediente.value))
							{
								oCombo.options[oCombo.options.length] = new Option(oExpediente.value, oExpediente.value, false, false);
											
								if (oCombo.options.length > 0) {oCombo.selectedIndex=0;}
								
								sortSelect(oCombo);
								oExpediente.value = '';
							}
							else
								alert("Ya ha ingresado ese expediente agregado");
						//}
						//else
						//{			
							//alert("No esxiste ese número de expediente");
							//oExpediente.focus();
						//}
					//}
				//}
				//oAjax.send(null)
			}		
		}
	}
	
	
	function EliminarSeleccionado()
	{
		var oCombo = document.getElementById('agregados');
		
		if (oCombo.selectedIndex == -1) {
			alert('Debe elegir un expediente a remover');
		}
		else {
			oCombo.remove(oCombo.selectedIndex);			
		}

	}
	
	
	var nav4 = window.Event ? true : false;
	function acceptNumEXP(evt){
		// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57
		var key = nav4 ? evt.which : evt.keyCode;

		if (key == 13)
			AgregarExpAg();
		else
			return (key <= 13 || (key >= 48 && key <= 57) || (key >= 96 && key <= 105) || key == 46);
	}
	
	function DisabledInputs() {
		var inputs = document.getElementsByTagName('input');
		var selects = document.getElementsByTagName('select');
		var textarea =  document.getElementsByTagName('textarea');
		
		for(i=0 ; i < inputs.length ; i++) {
			if(inputs[i].name != 'tags' && inputs[i].name != 'btnGrabar' && inputs[i].name != 'btnCancelar' && inputs[i].name != 'numero' && inputs[i].name != 'accion_anterior')
				inputs[i].disabled = 'disabled';
		}
		
		for(i=0 ; i < selects.length ; i++) {
				selects[i].disabled = 'disabled';
		}
		
		for(i=0 ; i < textarea.length ; i++) {
				textarea[i].disabled = 'disabled';
		}
		
	}
	
		
</script>
</head>

<body <?=$onload?>>
<form action="expediente.php?accion=grabar" method="post" name="form1" id="form1" <?php if($_SESSION['perfil'] != 'J') echo 'onsubmit="return ValidarGrabado();"'; ?> >
<div id="wrapper">
<div id="content">

<div align="center">
<table width="800" height="500" border="0" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF"><tr>
        <td height="107" align="left" valign="middle" class="fondo_encabezado">
        <table width="800" height="107" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="130">&nbsp;</td>
            <td width="670" align="left" valign="middle" class="texto_encabezado">Sistema de Gesti&oacute;n Parlamentaria</td>
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
      <table width="505" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td id="td_solapa1" width="181" height="25" align="center" valign="middle" class="solapa3" onmouseout="Estilo(this, 'solapa1');" onmouseover="Estilo(this, 'solapa2');" onclick="EligeSolapa(1);">Datos Expediente</td>
        <td id="td_solapa2" width="181" height="25" align="center" valign="middle" class="solapa1" onmouseout="Estilo(this, 'solapa1');" onmouseover="Estilo(this, 'solapa2');" onclick="EligeSolapa(2);">Expedientes agregados</td>
        <td width="101">&nbsp;</td>
      </tr>
      <tr id="cont_solapa1">
        <td colspan="3" align="center" valign="middle"><table width="505" border="0" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
          <tr>
            <td height="25" colspan="2" align="center" valign="middle" class="header2"><?=$titulo?></td>
          </tr>
          <tr>
            <td width="151" height="24" align="left" class="td1">Número de expediente</td>
            <td width="342" align="left" class="td2"><strong>
              <input id="nro_municipal" name="nro_municipal" type="text" value="<?=$expediente->nro_municipal?>" maxlength="100" style="display:none" />              
	          
			  <? if ($expediente->tipo != 'M') {echo $numero_txt;} ?>
              
              
              <? if ($_GET['accion'] == 'editar') { ?>
              		<input id="numero" name="numero" type="hidden" value="<?=$numero?>" />
              <? } else { ?>
              		<input id="numero" name="numero" type="hidden" value="" />
              <? } ?>
            </strong></td>
          </tr>
          <tr>
            <td width="151" align="left" class="td1">Tipo de expediente</td>
            <td width="342" align="left" class="td2"><select name="tipo" id="tipo" style="width:204px;" onchange="ActualizaProyecto(this.value);">
                <option value="I" <? if ($expediente->tipo=='I') echo 'selected'; ?>>Interno</option>
                <option value="M" <? if ($expediente->tipo=='M') echo 'selected'; ?>>Municipal</option>
                <option value="L" <? if ($expediente->tipo=='L') echo 'selected'; ?>>Legislativo</option>
              </select>
            </td>
          </tr>
          <tr>
            <td align="left" class="td1">Letra</td>
            <td align="left" class="td2"><input id="letra" name="letra" type="text" style="width:10px;" maxlength="1" value="<?=utf8($expediente->letra)?>" /></td>
          </tr>
          <tr>
            <td align="left" class="td1">Año</td>
            <td align="left" class="td2"><input id="anio" name="anio" type="text" style="width:30px;" maxlength="4" onkeypress="return acceptNum(event)" value="<?=utf8($expediente->anio)?>" /></td>
          </tr>
          <tr>
             <td align="left" class="td1">Fecha de presentación</td>
            <td align="left" class="td2"><?=CalendarioCreaInput('fec_presentacion', $expediente->fec_presentacion, '')?></td>
          </tr>
          <tr id="tr_fec_sesion">
            <td align="left" class="td1">Fecha de Sesión</td>
            <td align="left" class="td2"><?=CalendarioCreaInput('fec_sesion', $expediente->fec_sesion, '')?></td>
          </tr>
          <tr>
            <td align="left" class="td1">Causante</td>
            <td align="left" class="td2">
            <input id="id_causante_txt" name="id_causante_txt" type="text" style="width:200px;" maxlength="50" value="<?=utf8($expediente->id_causante_txt)?>" />
            <input type="hidden" id="id_causante" name="id_causante" value="<?=utf8($expediente->id_causante)?>" />
            </td>
          </tr>
          <tr id="tr_caratula">
            <td align="left" class="td1">Carátula</td>
            <td align="left" class="td2"><textarea name="caratula" id="caratula" style="width:200px;" rows="5"><?=utf8($expediente->caratula)?></textarea></td>
          </tr>
          <tr id="tr_tipo_proy">
            <td align="left" class="td1">Tipo de proyecto</td>
            <td align="left" class="td2"><input id="tipo_proy" name="tipo_proy" type="text" style="width:200px;" maxlength="35" value="<?=utf8($expediente->tipo_proy)?>" /></td>
          </tr>
          <tr id="tr_num_mensaje">
            <td align="left" class="td1">Número de mensaje</td>
            <td align="left" class="td2"><input id="num_mensaje" name="num_mensaje" type="text" style="width:200px;" maxlength="11" value="<?=utf8($expediente->num_mensaje)?>" /></td>
          </tr>
          <tr id="tr_com_destino">
            <td align="left" class="td1">Comisión de Destino</td>
             <td align="left" class="td2">
	            <input id="com_destino_txt" name="com_destino_txt" type="text" style="width:200px;" maxlength="150" value="<?=utf8($expediente->com_destino_txt)?>" />
	            <input id="com_destino" name="com_destino" type="hidden" style="width:200px;" maxlength="150" value="<?=$expediente->com_destino; ?>" />

<? /*$expediente->com_destino?>
                <? echo $expediente->com_destino; ?>
                <select id="com_destino_txt" name="com_destino_txt" >
                	<option>Seleccione una comisión ...</option>
                <?php
                	$selected = false; 
                	foreach($comisionDestino as $comision) { 
                ?>
                	<option value="<?php echo $comision; ?>" <?php if($comision==$expediente->com_destino) { echo "SELECTED"; $selected=true; }?> >
                		<?php echo $comision; ?>
                	</option>
                <?php } ?>
                </select>
                <?php 
                	if($comision!='' && !$selected) {
                		echo "<span style=\"color:red\" >Comisión incorrecta: " . $comision . "</span>";
                	}
                ?>
*/?>                
            </td>
         </tr>
          <tr id="tr_id_aprobacion">
            <td align="left" class="td1">Forma de aprobación</td>
            <td align="left" class="td2">
            <input id="id_aprobacion_txt" name="id_aprobacion_txt" type="text" style="width:200px;" maxlength="150" value="<?=utf8($expediente->id_aprobacion_txt)?>" />
                <input type="hidden" id="id_aprobacion" name="id_aprobacion" value="<?=utf8($expediente->id_aprobacion)?>" />
            </td>
          </tr>
      
          <tr id="tr_tipo_aprobacion">
            <td align="left" class="td1">Tipo de aprobación</td>
            <td align="left" class="td2"><input id="tipo_aprobacion" name="tipo_aprobacion" type="text" style="width:200px;" maxlength="40" value="<?=utf8($expediente->tipo_aprobacion)?>" /></td>
          </tr>

         <tr id="tr_decretos">
            <td align="left" class="td1">Decretos</td>
            <td align="left" class="td2"><input id="decretos" name="decretos" type="text" style="width:200px;" maxlength="40" value="<?=utf8($expediente->decretos)?>" /></td>
          </tr>
         <tr id="tr_declaraciones">
            <td align="left" class="td1">Declaraciones</td>
            <td align="left" class="td2"><input id="declaraciones" name="declaraciones" type="text" style="width:200px;" maxlength="40" value="<?=utf8($expediente->declaraciones)?>" /></td>
          </tr>
         <tr id="tr_minutas">
            <td align="left" class="td1">Minutas</td>
            <td align="left" class="td2"><input id="minutas" name="minutas" type="text" style="width:200px;" maxlength="40" value="<?=utf8($expediente->minutas)?>" /></td>
          </tr>
         <tr id="tr_ordenanzas_y_resoluciones">
            <td align="left" class="td1">Ordenanzas y Resoluciones</td>
            <td align="left" class="td2"><input id="ordenanzas_y_resoluciones" name="ordenanzas_y_resoluciones" type="text" style="width:200px;" maxlength="40" value="<?=utf8($expediente->ordenanzas_y_resoluciones)?>" /></td>
          </tr>


          <tr id="tr_fec_aprobacion">
            <td align="left" class="td1">Fecha de aprobación</td>
            <td align="left" class="td2"><?=CalendarioCreaInput('fec_aprobacion', $expediente->fec_aprobacion, '')?></td>
          </tr>
          <tr id="tr_id_ubicacion_actual">
            <td align="left" class="td1">Ubicación actual</td>
            <td align="left" class="td2">
   			<?
            /*
		jony: Comente esto para que deje editar el "ubicacion actual"
            
            
            	if ($accion=='alta')
					$carga_ubicacion = '';
				else
					$carga_ubicacion = ' readonly onkeypress="javascript: alert('."'Para cambiar la ubicación actual debe cargar un movimiento desde el menú principal.'".')" ';
		*/	
	$carga_ubicacion = '';

			?>
            	<input id="id_ubicacion_actual_txt" name="id_ubicacion_actual_txt" type="text" style="width:200px;" maxlength="150" value="<?=utf8($expediente->id_ubicacion_actual_txt)?>" <?=$carga_ubicacion?> />
                <input type="hidden" id="id_ubicacion_actual" name="id_ubicacion_actual" value="<?=utf8($expediente->id_ubicacion_actual)?>" />
            </td>
          </tr>          
          <tr>
            <td align="left" class="td1">Grupo de impresión</td>
            <td align="left" class="td2"><? ArmaCombo('id_grupo', 'grupos_impresion', 'grupo', 'style="width:200px;" ', $expediente->id_grupo, true); ?></td>
          </tr>    
          <tr>
            <td align="left" class="td1" valign="top" >Tags</td>
            <td align="left" class="td2">
            	<input type="text" value="<?php echo $expediente->tags; ?>" name="tags" maxlength="200" style="width: 200px;" <?php if($_SESSION['perfil'] != 'J') echo "disabled"; ?> />
            	<div class="tags" >
	            	Los tags se deben:
	            	<ul>
	            		<li>Estar separados por un espacio.</li>
	            		<li>Tener una longitud mínima de 4 caracteres, de lo contrario no seran tenidos en cuenta.</li>
	            	</ul>
            	</div>
            </td>
          </tr>     


<!-- Agregado por Jon -->
<?php if ($accion == 'editar') { ?>
	  <tr>
		<td colspan="2" height="20px" style="background-color: #B6B6B6;">
		</td>
	  </tr>

          <tr>
            <td height="25" colspan="2" align="center" class="header2">Movimientos del expediente</td>
            </tr>
          <tr>
            <td colspan="2" align="left" class="td2">
            <div align="center" style="overflow:auto; height:120px; width:100%">
            <table width="480" border="0" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
                <tr>
                  <td align="center" class="header">Fecha</td>
                  <td align="center" class="header">Destino</td>
<?php // Jon: le elimine la palabra comentario dentro del td ?>
                  <td align="center" class="header"><!-- Comentario --></td>

                </tr>
                <?
//	Jon: 	Le cambie el sql para que traiga tambien el id de movimiento
//		SQL viejo:	$sql = "select m.fecha, m.comentario, d.destino ". 
//					   "from movimientos m ".
//					   "left join destinos d on d.id_destino=m.id_ubicacion_actual ".
//					   "where m.numero=$numero ".
//					   "order by  m.fecha desc";
//
//		SQL Nuevo:
				$sql = "select m.id_movimiento, m.fecha, m.comentario, d.destino ". 
					   "from movimientos m ".
					   "left join destinos d on d.id_destino=m.id_ubicacion_actual ".
					   "where m.numero=$numero ".
					   "order by  m.fecha desc";

				$rs = $conn->Execute($sql);
				
				$clase = 'td1';
				while (!$rs->EOF) 
				{                
					if ($clase == 'td1') $clase = 'td2';
					else {$clase = 'td1';}
					
					$comentario = utf8($rs->fields['comentario']);
					$comentario = str_replace("'", "Â´", $comentario);
					$comentario = str_replace(chr(10), "", $comentario);
					$comentario = str_replace(chr(13), '\n', $comentario);
					
                ?>
                <tr class="<?=$clase?>">
                  <td align="left"><?=FormatoFechaNormal($rs->fields['fecha'])?></td>
                  <td align="left"><?=utf8($rs->fields['destino'])?></td>
                  <td align="center">
<?php
	// Jon: Le reemplace la imagen del ojo por la de editar y le agrege un link a la ediciÃ³n de movimientos.
?>
		<img src="../imagenes/ver.gif" alt="Ver comentario" width="28" height="16" border="0" longdesc="Ver comentario" style="cursor:pointer" onclick="alert('<?=$comentario?>');" />

<?php
		if($_SESSION['perfil'] != 'J') {
?>
	    	<a href="../abms/movimiento_expediente_editar.php?accion=editar&id=<?php echo $rs->fields['id_movimiento']; ?>" alt="Editar moviminto" >
	       		<img src="../imagenes/editar.gif" width="20" height="16" border="0" longdesc="Ver comentario" style="cursor:pointer" />
               	</a>
<?php
		}
?>


		   </td>
                </tr>
                <?
                	$rs->MoveNext();
                }
                ?>
				
              </table>
              </div>
              </td>
            </tr>
<!-- Fin - Agregado por Jon -->
<?php } ?>


        



                
        </table></td>
        </tr>
      <tr id="cont_solapa2" style="display:none">
        <td colspan="3" align="center" valign="middle"><table width="505" border="0" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
          <tr>
            <td height="25" colspan="2" align="center" valign="middle" class="header2">Expedientes agregados</td>
          </tr>
          <tr>
            <td width="50%" align="center" valign="middle" class="td2">
            <select name="agregados" size="5" id="agregados" style="width:240px;">
            <?
            	 $agregados = explode('|', $expediente->agregados);
				 
				 for ($i=0; $i < sizeof($agregados); $i++)
				 	if ($agregados[$i] != '')
					 	echo "<option value='".$agregados[$i]."'>".$agregados[$i]."</option>"
			?>
              </select>            </td>
            <td width="50%" align="left" valign="middle" class="td2">
            	<input type="text" name="txt_agregar" id="txt_agregar" style="width:120px;" value=""  />
                <input type="button" name="btnAgregar" id="btnAgregar" value="Agregar" onclick="AgregarExpAg();" />
                <input type="hidden" name="txt_agregar_id" id="txt_agregar_id" value="" />
                <br />
                <br />
                <input type="button" name="btnEliminar" id="btnEliminar" value="Eliminar seleccionado" style="width:190px;" onclick="EliminarSeleccionado();" />
                <br /></td>
          </tr>
        </table></td>
      </tr>
    </table>
      <br />
    <br />
    <br />
<div align="center" class="td1" style="width:730px; height:20px">

  <input name="btnGrabar" type="button" id="btnGrabar" value="Grabar" style="width:100px" <?php 

if($_SESSION['perfil'] != 'J') 
	echo 'onClick="javascript: Grabar();"'; 
else 
	echo 'onClick="javascript: document.form1.submit();"'; 
?> >
  &nbsp;&nbsp;&nbsp;&nbsp;
  <input name="btnCancelar" type="button" id="btnCancelar" value="Cancelar" style="width:100px" onClick="javascript: Cancelar();">
</div>
    </td>
  </tr>
</table>
</div>

<!--
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
	<? if ($accion=='alta' || $accion=='editar' ) { ?>
	var options2 = {
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
	var as_json2 = new bsn.AutoSuggest('id_ubicacion_actual_txt', options2);
	<? } ?>
    
	//------------------------------------------------------------------------------------------
	var options3 = {
		script:"../inc/autosuggest/data_categoria.php?json=true&",
		varname:"input",
		json:true,
		shownoresults:false,
		maxresults:10,
		minchars: 1,
		timeout:99999,
		cache: false,
		callback: function (obj) { document.getElementById('id_categoria').value = obj.id; }
	};
	var as_json3 = new bsn.AutoSuggest('id_categoria_txt', options3);
	//------------------------------------------------------------------------------------------
	
	var options_xml = {
		script: function (input) { return "../inc/autosuggest/data_numero_exp.php?input="+input+"&testid="+document.getElementById('txt_agregar_id').value; },
		varname:"input",
		shownoresults: false,
		timeout:99999,
		cache: false,
		maxresults:10,
		minchars: 1		
	};
	var as_xml = new bsn.AutoSuggest('txt_agregar', options_xml);
	
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
-->
</div>
</div>
<input type="hidden" name="hdnAgregados" id="hdnAgregados" value="" />
<input type="hidden" name="accion_anterior" id="accion_anterior" value="<?=$accion?>" />
</form>
</body>
</html>
