<? include('../inc/inicio.inc.php'); ?>
<? include('../class/normas.class.php'); ?>
<?

DesactivarSSL();

$mensa = '&nbsp;';
$titulo = '';
$numero = '';
$onload = '';
$numero_txt = '';
//$accion = '';


	// Esto es un hack para que ande la carga y actualizacion de los expedientes sin tener que modificar la base de datos
	// ya que se elimino el campo de categorÃ­a, de esta manera todos los expedientes van a estar asociados a la categoria
	// de id igual a 1.
	$_POST['id_categoria'] = 1;
	$_POST['id_categoria_txt'] = 'H. CUERPO';


if (isset($_GET['accion'])) 
{
	$accion = $_GET['accion'];
	
	$norma = new Normas();

	if ($accion == 'editar') 
	{
		CheckPerfiles('SDJI');
		$numero = $_GET['numero'];
		$tipoNorma = $_GET['tipo'];
		$norma->numero_old = $numero;
		$norma->tipo_old = $tipoNorma;
		$numero_txt = $_GET['numero'];
		$norma->CargarNorma($numero,$tipoNorma);
		if($_SESSION['perfil'] == 'J')
			$onload = 'onload="DisabledInputs();"';
	}


	if ($accion == 'verif_numero') 
	{
		echo $norma->VerifNumero($_GET['numero'],$_GET['tipo']);
		exit;
	}
	

	if ($accion == 'alta') 
	{
		CheckPerfiles('SDOI');
		$titulo = 'Alta de Norma';
		$numero = '(Nuevo)';
	}


	if ($accion == 'grabar') 
	{
		CheckPerfiles('SDOJI');

		if($_SESSION['perfil'] == 'I' ||
		   $_SESSION['perfil'] == 'S'){
			$norma->GrabarPost();
		   	$norma->GrabarTags();

		}	
		header("Location: confirma_norma.php?numero=".$norma->numero."&accion=".$_POST['accion_anterior']);
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
<title>Sistema de Gesti&oacute;n Parlamentaria</title>
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
			if (tipo.value == "null"){
				alert("Debe completar el tipo de Norma");
				return false;
			}	

			if (descripcion.value != "") todo_vacio = false;
			if (xfec_aprobacion.value != "") todo_vacio = false;
		
			if (todo_vacio)
			{
				alert("Debe completar o la Descripcion o la Fecha de Aprobacion");
				return false;
			}else{
				return true;
			}
		}
				
	}


	function Grabar()
	{
		if (ValidarGrabado())
		{

			var oAjax = nuevoAjax();
			var oNorma = document.getElementById('numero');		
			var oTipo = document.getElementById('tipo');
			var ret;
			
			oAjax.open("GET", "norma.php?accion=verif_numero&numero="+oNorma.value+"&tipo="+oTipo.value,true);
			oAjax.onreadystatechange=function() {
				if (oAjax.readyState=='4' || oAjax.readyState=="complete") {
					ret =  oAjax.responseText;
					if (ret=='N'){
						var oAgregados = document.getElementById('hdnModifica');		
						var oCombo = document.getElementById('modifica');
				
						oAgregados.value = '|';
						for (var i=0; i < oCombo.options.length; i++)
							oAgregados.value += oCombo.options[i].value + "|";
			
						document.form1.submit();
					}else{
						alert('Ya existe una Norma con ese Número y Tipo');
					}
				}	
			}
			oAjax.send(null);
		}
	}

	function GrabarModificado()
	{
		if (ValidarGrabado())
		{
			var oAgregados = document.getElementById('hdnModifica');		
			var oCombo = document.getElementById('modifica');
	
			oAgregados.value = '|';
			for (var i=0; i < oCombo.options.length; i++)
				oAgregados.value += oCombo.options[i].value + "|";

			document.form1.submit();
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
	
	
	function EstaEnCombo(norma,tipo)
	{
		var oCombo = document.getElementById('modifica');
		var existe = false;
		switch(tipo){
		case 'ORD':  
			norma = 'O'+norma;
			break;
		case 'DEC':  
			norma = 'D'+norma;
			break;
		case 'RES':  
			norma = 'R'+norma;
			break;
		case 'COM':  
			norma = 'M'+norma;
			break;
		case 'DCL':  
			norma = 'L'+norma;
			break;										
	}
		for (var i=0; i < oCombo.options.length && !existe; i++)
		{
			if (oCombo.options[i].value == norma) existe = true;
		}
		
		return existe;
	}
	


	function verificarNro(perfil){

		var oNorma = document.getElementById('numero');	
		var oNorma_old = document.getElementById('numero_old');			
		var oTipo = document.getElementById('tipo'); 	
		var oTipo_old = document.getElementById('tipo_old'); 

		if (oNorma.value=='') {
			alert('Debe elegir una norma.');
		}
		if (oTipo.value == 'null') {
			alert('Debe elegir un tipo de norma');
		}
		else {
			
			if ((oNorma_old.value!=oNorma.value)||(oTipo_old.value!=oTipo.value)){

					var oAjax = nuevoAjax();
					var ret;
					
					oAjax.open("GET", "norma.php?accion=verif_numero&numero="+oNorma.value+"&tipo="+oTipo.value,true);
					oAjax.onreadystatechange=function() {
						if (oAjax.readyState=='4' || oAjax.readyState=="complete") {
							ret = oAjax.responseText;
							if(ret != 'N'){
								alert("Ya existe una Norma con ese Numero y Tipo");	
								oNorma.value = oNorma_old.value;
								oTipo.value = oTipo_old.value;		
							}else{
								if(perfil != 'J'){
									GrabarModificado();
								}else{
									document.form1.submit();
								}
							}
						}
					}
					oAjax.send(null)
			}else{
				if(perfil != 'J'){
					GrabarModificado();
				}else{
					document.form1.submit();
				}
			}
		}		
	}

	function AgregarNormaMod()
	{
		var oCombo = document.getElementById('modifica');
		var oTipo = document.getElementById('tipoNormaModif'); 		
		var oNumero = document.getElementById('numero');
		var oTipoOri = document.getElementById('tipo');		
		var oNorma = document.getElementById('txt_agregar');	

		if (oNorma.value=='') {
			alert('Debe elegir una norma.');
		}
		if (oTipo.value == 'null') {
			alert('Debe elegir un tipo de norma');
		}
		else {
			
			if ((trim(oNorma.value)==oNumero.value)&&(trim(oTipo.value)==oTipoOri.value))
			{
				alert("No puede ingresar como agregado a este misma norma.");
			}
			else
			{

				if (!EstaEnCombo(oNorma.value,oTipo.value))
				{
					var oAjax = nuevoAjax();
					var ret;
					
					oAjax.open("GET", "norma.php?accion=verif_numero&numero="+oNorma.value+"&tipo="+oTipo.value,true);
					oAjax.onreadystatechange=function() {
						if (oAjax.readyState=='4' || oAjax.readyState=="complete") {
							ret = oAjax.responseText;
							if(ret == 'N'){
								alert("Nro de Norma inexistente");					
							}else{
								switch(oTipo.value){
									case 'ORD':  
										normaAdd = 'O'+oNorma.value;
										break;
									case 'DEC':  
										normaAdd = 'D'+oNorma.value;
										break;
									case 'RES':  
										normaAdd = 'R'+oNorma.value;
										break;
									case 'COM':  
										normaAdd = 'M'+oNorma.value;
										break;
									case 'DCL':  
										normaAdd = 'L'+oNorma.value;
										break;										
								}
								oCombo.options[oCombo.options.length] = new Option(normaAdd, normaAdd, false, false);
								
								if (oCombo.options.length > 0) {oCombo.selectedIndex=0;}
								
								sortSelect(oCombo);
								oNorma.value = '';
								oTipo.value = 'null';
							}
						}
					}
					oAjax.send(null);

				}
				else
					alert("Ya ha ingresado esa norma agregado");
			}		
		}
	}
	
	
	function EliminarSeleccionado()
	{
		var oCombo = document.getElementById('modifica');
		
		if (oCombo.selectedIndex == -1) {
			alert('Debe elegir una norma a remover');
		}
		else {
			oCombo.remove(oCombo.selectedIndex);			
		}

	}
	
	
	function DisabledInputs() {
		var inputs = document.getElementsByTagName('input');
		var selects = document.getElementsByTagName('select');
		var textarea =  document.getElementsByTagName('textarea');
		
		for(i=0 ; i < inputs.length ; i++) {
			if(inputs[i].name != 'btnGrabar' && inputs[i].name != 'btnCancelar' && inputs[i].name != 'numero' && inputs[i].name != 'accion_anterior')
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
<form action="norma.php?accion=grabar" method="post" name="form1" id="form1" <?php if($_SESSION['perfil'] != 'J') echo 'onsubmit="return ValidarGrabado();"'; ?> >
<div id="wrapper">
<div id="content">

<div align="center">
<table width="800" height="500" border="0" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF"><tr>
        <td height="107" align="left" valign="middle" class="fondo_encabezado">
        <table width="800" height="107" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="130">&nbsp;</td>
            <td width="670" align="left" valign="middle" class="texto_encabezado">Sistema de Documentaci&oacute;n Legislativa</td>
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
    <td height="440" align="center" valign="top" class="contenido_normas"><br />
      <table width="505" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td id="td_solapa1" width="181" height="25" align="center" valign="middle" class="solapa3" onmouseout="Estilo(this, 'solapa1');" onmouseover="Estilo(this, 'solapa2');" onclick="EligeSolapa(1);">Datos norma</td>
        <td id="td_solapa2" width="181" height="25" align="center" valign="middle" class="solapa1" onmouseout="Estilo(this, 'solapa1');" onmouseover="Estilo(this, 'solapa2');" onclick="EligeSolapa(2);">Normativas Relacionadas</td>
        <td width="101">&nbsp;</td>
      </tr>
      <tr id="cont_solapa1">
        <td colspan="3" align="center" valign="middle"><table width="505" border="0" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
          <tr>
            <td height="25" colspan="2" align="center" valign="middle" class="header2"><?=$titulo?></td>
          </tr>
          <tr>
            <td width="151" height="24" align="left" class="td1">N&uacute;mero de norma</td>
            <td width="342" align="left" class="td2"><strong>          
              <? if ($_GET['accion'] == 'editar') { ?>
              		<input id="numero_old" name="numero_old" type="hidden" value="<?=$numero?>" />
              		<input id="numero" name="numero" type="text" value="<?=$numero?>" />
              <? } else { ?>
              		<input id="numero" name="numero" type="text" value="" />
              <? } ?>
            </strong></td>
          </tr>
          <tr>
            <td width="151" align="left" class="td1">Tipo de Norma</td>
            <td width="342" align="left" class="td2">		  
			  <input name="tipo_old" id="tipo_old" type="hidden" value="<?php echo $norma->tipo?>"/>
			  <select name="tipo" id="tipo" style="width:204px;">          
			  	<option value="null">[Elegir...]</option>';  
                <option value="ORD" <? if ($norma->tipo=='ORD') echo 'selected'; ?>>Ordenanza</option>
                <option value="DEC" <? if ($norma->tipo=='DEC') echo 'selected'; ?>>Decreto</option>
                <option value="RES" <? if ($norma->tipo=='RES') echo 'selected'; ?>>Resoluci&oacute;n</option>
                <option value="COM" <? if ($norma->tipo=='COM') echo 'selected'; ?>>Minuta de Comunicaci&oacute;n</option>
                <option value="DLA" <? if ($norma->tipo=='DLA') echo 'selected'; ?>>Declaraci&oacute;n</option>
              </select>
            </td>
          </tr>
          
        <tr id="tr_caratula">
            <td align="left" class="td1">Descripci&oacute;n</td>
            <td align="left" class="td2"><textarea name="descripcion" id="descripcion" style="width:347px;" rows="10"><?=utf8($norma->descripcion)?></textarea></td>
          </tr>

        <tr id="tr_estado">
            <td align="left" class="td1">Estado</td>
            <td align="left" class="td2"><textarea name="estado" id="estado" style="width:347px;" rows="3"><?=utf8($norma->estado)?></textarea></td>
          </tr>
        
          <tr id="tr_fec_aprobacion">
            <td align="left" class="td1">Fecha de aprobaci&oacute;n</td>
            <td align="left" class="td2"><?=CalendarioCreaInputNorma('fec_aprobacion', $norma->fec_aprobacion, '')?></td>
          </tr>

          
          <td align="left" class="td1" valign="top" >Tags</td>
            <td align="left" class="td2">
            	<textarea name="tags" id="tags" style="width:347px;" rows="3"><?php echo $norma->tags; ?></textarea>
            	<div class="tags" >
	            	Los tags se deben:
	            	<ul>
	            		<li>Estar separados por un punto y coma (;).</li>
	            		<li>Tener una longitud m&iacute;nima de 3 caracteres, de lo contrario no seran tenidos en cuenta.</li>
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
	  
<?php }?>

   
        </table></td>
        </tr>
      <tr id="cont_solapa2" style="display:none">
        <td colspan="3" align="center" valign="middle"><table width="505" border="0" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
          <tr>
            <td height="25" colspan="2" align="center" valign="middle" class="header2">Normativas Relacionadas</td>
          </tr>
          <tr>
            <td width="50%" align="center" valign="middle" class="td2">
            <select name="modifica" size="5" id="modifica" style="width:240px;">
            <?
            	 $modifica = explode('|', $norma->modifica) ;
				 
				 for ($i=0; $i < sizeof($modifica); $i++)
				 	if ($modifica[$i] != '')
					 	echo "<option value='".$modifica[$i]."'>".$modifica[$i]."</option>"
			?>
              </select>            </td>
            <td width="50%" align="left" valign="middle" class="td2">
            	<select name="tipoNormaModif" id="tipoNormaModif" style="width:186px;">          
				  	<option value="null">[Elegir...]</option>';  
	                <option value="ORD">Ordenanza</option>
	                <option value="DEC">Decreto</option>
	                <option value="RES">Resoluci&oacute;n</option>
	                <option value="COM">Minuta de Comunicaci&oacute;n</option>
	                <option value="DLA">Declaraci&oacute;n</option>
              	</select>
            	<input type="text" name="txt_agregar" id="txt_agregar" style="width:120px;" value=""  />
                <input type="button" name="btnAgregar" id="btnAgregar" value="Agregar" onclick="AgregarNormaMod();" />
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

<?php 	if ($accion=='editar'){ ?>	
			<input name="btnGrabar" type="button" id="btnGrabar" value="Grabar" style="width:100px" onClick="verificarNro('<?php echo $_SESSION['perfil'] ?>')" />
<?php 	}else{
			if ($_SESSION['perfil']!='J'){ ?>
			<input name="btnGrabar" type="button" id="btnGrabar" value="Grabar" style="width:100px" onClick="Grabar()" />			
<?php 		}else{ ?>
			<input name="btnGrabar" type="button" id="btnGrabar" value="Grabar" style="width:100px" onClick="document.form1.submit();" />		
<?php 		}
		}?>  
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
<input type="hidden" name="hdnModifica" id="hdnModifica" value="" />
<input type="hidden" name="accion_anterior" id="accion_anterior" value="<?=$accion?>" />
</form>
</body>
</html>
