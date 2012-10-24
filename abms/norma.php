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
	$norma = new Normas();
	$accion = $_GET['accion'];

	if ($accion == 'editar') 
	{
		CheckPerfiles('SDJI');
		$numero = $_GET['numero'];
		$numero_txt = number_format($_GET['numero'], 0, ',', '.');
		$norma->CargarNorma($numero);
		if($_SESSION['perfil'] == 'J')
			$onload = 'onload="DisabledInputs();"';
	}


	if ($accion == 'verif_numero') 
	{
		echo $norma->VerifNumero($_GET['numero']);
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

		if($_SESSION['perfil'] == 'J')
			$norma->GrabarTags();
		else
			$norma->GrabarPost();
			
		Auditoria($norma->numero, $_POST['accion_anterior']);
		header("Location: confirma.php?numero=".$norma->numero."&accion=".$_POST['accion_anterior']);
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
			if (tipo.value != "") todo_vacio = false;
			if (descripcion.value != "") todo_vacio = false;
			if (xfec_aprobacion.value != "") todo_vacio = false;
		
			if (todo_vacio)
			{
				alert("Debe completar al menos un valor");
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
	
	
	function EstaEnCombo(norma)
	{
		var oCombo = document.getElementById('modifica');
		var existe = false;
		
		for (var i=0; i < oCombo.options.length && !existe; i++)
		{
			if (oCombo.options[i].value == norma) existe = true;
		}
		
		return existe;
	}
	
	
	function AgregarNormaMod()
	{
		var oCombo = document.getElementById('modifica');		
		var oNumero = document.getElementById('numero');		
		var oNorma = document.getElementById('txt_agregar');		
		
		if (oNorma.value=='') {
			alert('Debe elegir una norma.');
		}
		else {
			var existe_norma = '';
			
			if (trim(oNorma.value)==oNumero.value)
			{
				alert("No puede ingresar como agregado a este misma norma.");
			}
			else
			{
				if (!EstaEnCombo(oNorma.value))
				{
					oCombo.options[oCombo.options.length] = new Option(oNorma.value, oNorma.value, false, false);
								
					if (oCombo.options.length > 0) {oCombo.selectedIndex=0;}
					
					sortSelect(oCombo);
					oNorma.value = '';
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
<form action="norma.php?accion=grabar" method="post" name="form1" id="form1" <?php if($_SESSION['perfil'] != 'J') echo 'onsubmit="return ValidarGrabado();"'; ?> >
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
        <td id="td_solapa1" width="181" height="25" align="center" valign="middle" class="solapa3" onmouseout="Estilo(this, 'solapa1');" onmouseover="Estilo(this, 'solapa2');" onclick="EligeSolapa(1);">Datos norma</td>
        <td id="td_solapa2" width="181" height="25" align="center" valign="middle" class="solapa1" onmouseout="Estilo(this, 'solapa1');" onmouseover="Estilo(this, 'solapa2');" onclick="EligeSolapa(2);">Normas que modifica</td>
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
              		<input id="numero" name="numero" type="hidden" value="<?=$numero?>" />
              <? } else { ?>
              		<input id="numero" name="numero" type="hidden" value="" />
              <? } ?>
            </strong></td>
          </tr>
          <tr>
            <td width="151" align="left" class="td1">Tipo de Norma</td>
            <td width="342" align="left" class="td2"><select name="tipo" id="tipo" style="width:204px;">
                <option value="Dec" <? if ($norma->tipo=='Dec') echo 'selected'; ?>>Decreto</option>
                <option value="Ord" <? if ($norma->tipo=='Ord') echo 'selected'; ?>>Ordenanza</option>
                <option value="Res" <? if ($norma->tipo=='Res') echo 'selected'; ?>>Resoluci&oacute;n</option>
              </select>
            </td>
          </tr>
          
        <tr id="tr_caratula">
            <td align="left" class="td1">Descripci&oacute;n</td>
            <td align="left" class="td2"><textarea name="descripcion" id="descripcion" style="width:200px;" rows="5"><?=utf8($norma->descripcion)?></textarea></td>
          </tr>

          <tr id="tr_fec_aprobacion">
            <td align="left" class="td1">Fecha de aprobaci&oacute;n</td>
            <td align="left" class="td2"><?=CalendarioCreaInput('fec_aprobacion', $norma->fec_aprobacion, '')?></td>
          </tr>

          
          <td align="left" class="td1" valign="top" >Tags</td>
            <td align="left" class="td2">
            	<input type="text" value="<?php echo $norma->tags; ?>" name="tags" maxlength="200" style="width: 200px;" <?php if($_SESSION['perfil'] != 'J') echo "disabled"; ?> />
            	<div class="tags" >
	            	Los tags se deben:
	            	<ul>
	            		<li>Estar separados por un espacio.</li>
	            		<li>Tener una longitud m&iacute;nima de 4 caracteres, de lo contrario no seran tenidos en cuenta.</li>
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
	  
	  links
<?php }?>

   
        </table></td>
        </tr>
      <tr id="cont_solapa2" style="display:none">
        <td colspan="3" align="center" valign="middle"><table width="505" border="0" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
          <tr>
            <td height="25" colspan="2" align="center" valign="middle" class="header2">Normas que modifica</td>
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
<input type="hidden" name="hdnModifica" id="hdnModifica" value="" />
<input type="hidden" name="accion_anterior" id="accion_anterior" value="<?=$accion?>" />
</form>
</body>
</html>
