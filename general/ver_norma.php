<? include('../inc/inicio.inc.php'); ?>
<? include('../class/normas.class.php'); ?>
<?

DesactivarSSL();


$numero = $_GET['numero'];
$normas = new Normas();

$existe = $normas->CargarNorma($numero);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Sistema de Gestión Parlamentaria</title>
<link rel="stylesheet" type="text/css" href="../inc/estilo.css">

<? include('../inc/efecto_transicion.inc.php'); ?>

<script language="javascript">

	function ActualizaProyecto(tipo)
	{
		var oTR_fec_sesio = document.getElementById('tr_fec_sesion');
		var oTR_caratula = document.getElementById('tr_caratula');
		var oTR_tipo_proy = document.getElementById('tr_tipo_proy');
		var oTR_num_mensaje = document.getElementById('tr_num_mensaje');
		var oTR_com_destino = document.getElementById('tr_com_destino');
		var oTR_id_ubicacion_actual = document.getElementById('tr_id_ubicacion_actual');
		var oTR_tr_tipo_aprobacion = document.getElementById('tr_tipo_aprobacion');
		var oTR_tr_fec_aprobacion = document.getElementById('tr_fec_aprobacion');
		
				 
		oTR_fec_sesio.style.display = 'none';
		oTR_caratula.style.display = 'none';
		oTR_tipo_proy.style.display = 'none';
		oTR_num_mensaje.style.display = 'none';
		oTR_com_destino.style.display = 'none';
		oTR_id_ubicacion_actual.style.display = 'none';
		oTR_tr_tipo_aprobacion.style.display = 'none';
		oTR_tr_fec_aprobacion.style.display = 'none';

		
		if (tipo == 'I')
		{
			oTR_caratula.style.display = '';
			oTR_id_ubicacion_actual.style.display = '';
			oTR_com_destino.style.display = '';
		}

		if (tipo == 'M')
		{
			oTR_caratula.style.display = '';
			oTR_id_ubicacion_actual.style.display = '';
			oTR_com_destino.style.display = '';
		}

		if (tipo == 'L')
		{
			oTR_fec_sesio.style.display = '';
			oTR_tipo_proy.style.display = '';
			oTR_num_mensaje.style.display = '';
			oTR_com_destino.style.display = '';
			oTR_id_ubicacion_actual.style.display = '';
			oTR_caratula.style.display = '';
		}
	}
	
	function TamScreen()
	{
		window.moveTo(0,0);
		window.resizeTo(screen.width,screen.height);
	}

</script>

</head>

<body onload="TamScreen();">
<form action="norma.php?accion=grabar" method="post" name="form1" id="form1" onsubmit="return ValidarGrabado();">
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
    <td height="440" align="center" valign="top" class="contenido">
    <?
    
	if (!$existe)
	{
		echo '<br><br><br><br><br><span class="texto_encabezado">El expediente no existe en la base de datos</span><br><br><br>';
	}
	else
	{
	?>
    <div align="left">
    <table border="0" cellpadding="2" cellspacing="1" bgcolor="#FFFFFF">
      <tr>
        <td align="center" class="header2">N&uacute;mero</td>
      </tr>
      <tr>
        <td align="right" nowrap="nowrap" class="td2"><strong>
          <?	  
	 	    echo $normas->NumeroFormateado();
		  ?>
&nbsp;        </strong></td>

      </tr>
    </table>
    </div>
      <br />
      <table width="505" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="3" align="center" valign="middle"><table width="505" border="0" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
          <tr>
            <td height="25" colspan="2" align="center" valign="middle" class="header2">Informaci&oacute;n referencial de la Norma</td>
          </tr>
          <tr>
            <td width="151" align="left" class="td1">Tipo de Norma</td>
            <td width="342" align="left" class="td2">
            <? switch ($normas->tipo){
					case 'ORD':
						echo 'Ordenanza';
						break;		
					case 'DEC':
						echo 'Decreto';
						break;
					case 'RES':
						echo 'Resoluci&oacute;n';
						break;
					case 'COM':
						echo 'Minuta de Comunicaci&oacute;n';
						break;
					case 'DLA':
						echo 'Declaraci&oacute;n';
						break;
				}

			?>
			</td>
          </tr>
          <tr id="tr_fec_aprobacion">
            <td align="left" class="td1">Fecha de aprobaci&oacute;n</td>
            <td align="left" class="td2"><?=FormatoFechaNormal($normas->fec_aprobacion)?></td>
          </tr>
          <tr id="tr_caratula">
            <td align="left" class="td1">Descripci&oacute;n</td>
            <td align="left" class="td2">
            <div align="left" style="overflow:auto; height:40px; width:100%;">
				<?=str_replace(chr(13), '<br>', utf8($normas->descripcion)) ?>
            </div>            </td>
          </tr>
     
          <tr>
            <td height="25" colspan="2" align="center" class="header2">Normas que modifica</td>
            </tr>
          <tr>
            <td colspan="2" align="left" class="td2">
            <div align="left" style="overflow:auto; height:60px; width:100%">
            <?
            	 $modifica = explode('|', $normas->modifica);
				 
				 for ($i=0; $i < sizeof($modifica); $i++)
				 	if ($modifica[$i] != '')
					{
					 	echo "<a href='ver_norma.php?numero=".$modifica[$i]."'>Norma ".$modifica[$i]."</a>";
						if (($i+2) < sizeof($modifica)) {echo ', ';}
					}
					
			?>
            <br><br>
            </div>            </td>
            </tr>
         <tr>
            <td height="25" colspan="2" align="center" class="header2">Documentos electr&oacute;nicos</td>
            </tr>
          <tr>
            <td colspan="2" align="center" class="td2">
            <table width="480" border="0" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
              <tr>
                <td align="center" class="header">Norma</td>
                <td align="center" class="header">Normas que modifica</td>
                </tr>
                
              <tr class="td1">
                <td align="left">
                <?
	                if (file_exists(realpath('../pdf/'.$numero.'.pdf')))
						echo '<a target="_blank" href="../pdf/'.$numero.'.pdf">'.$numero.'.pdf</a>';
				?>
                </td>
                <td align="left">
                  <?
            	$tipo_aprobacion = utf8($expediente->tipo_aprobacion);
				$vec_aprob = array();
				
				$indice = -1;				
				$llenando = false;
				for ($i=0; $i < strlen($tipo_aprobacion); $i++)
				{
					if (!(strpos("0123456789.,", $tipo_aprobacion[$i]) === false))
					{
						if (!$llenando)
						{
							$llenando = true;
							$indice++;
							$vec_aprob[$indice] = '';
						}
						
						if (!(strpos("0123456789", $tipo_aprobacion[$i]) === false))
							$vec_aprob[$indice] .= $tipo_aprobacion[$i];
					}
					else
					{
						$llenando = false;
					}
				}
				
				
				$documentos = '';
				if (sizeof($vec_aprob) > 0)
				{
					foreach ($vec_aprob as $value)
					{
						if (file_exists(realpath('../pdf/'.$value.'.pdf')))
							$documentos .= '<a target="_blank" href="../pdf/'.$value.'.pdf">'.$value.'.pdf</a>, ';
						else
							$documentos .= '<a href="javascript: alert('."'".'No se ha digitalizado todavia el documento'."'".');">'.$value.'.pdf</a>, ';
					}
				}
				
				if ($documentos != '')
					echo substr($documentos, 0, strlen($documentos)-2);
			?>
                </td>
                </tr>
            </table>
            </td>
            </tr>
        </table></td>
        </tr>
    </table>
      <br />


    <? } //del if de si existe el expediente ?>
    <br />
<div align="center" class="td1" style="width:730px; height:20px">
  <input name="btnCerrar" type="button" id="btnCerrar" value="Cerrar" style="width:100px" onClick="javascript: window.close();">
</div>    </td>
  </tr>
</table>
</div>

</div>
</div>
<br />
</form>
</body>
</html>
