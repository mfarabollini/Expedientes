<? include('../inc/inicio.inc.php'); ?>
<? include('../class/expediente.class.php'); ?>
<?

DesactivarSSL();


$numero = $_GET['numero'];
$expediente = new Expediente();

$existe = $expediente->CargarExpediente($numero);

Auditoria($numero, 'Vista expediente');


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
<form action="expediente.php?accion=grabar" method="post" name="form1" id="form1" onsubmit="return ValidarGrabado();">
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
        <td align="center" class="header2">Número</td>
        <td align="center" class="header2">Letra</td>
        <td align="center" class="header2">Año</td>
      </tr>
      <tr>
        <td align="right" nowrap="nowrap" class="td2"><strong>
          <?
		  
		  	if ($expediente->tipo == 'M')
			{
				echo $expediente->nro_municipal;
			}
			else
			{
	 	    	echo $expediente->NumeroFormateado();
			}
		  ?>
&nbsp;        </strong></td>
        <td nowrap="nowrap" class="td2" align="center"><?=utf8($expediente->letra)?></td>
        <td align="right" nowrap="nowrap" class="td2"><?=utf8($expediente->anio)?>
          &nbsp;</td>
      </tr>
    </table>
    </div>
      <br />
      <table width="505" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="3" align="center" valign="middle"><table width="505" border="0" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
          <tr>
            <td height="25" colspan="2" align="center" valign="middle" class="header2">Información referencial del expediente</td>
          </tr>
          <tr>
            <td width="151" align="left" class="td1">Tipo de expediente</td>
            <td width="342" align="left" class="td2">
                <? if ($expediente->tipo=='I') echo 'INTERNO'; ?>
                <? if ($expediente->tipo=='M') echo 'MUNICIPAL'; ?>
                <? if ($expediente->tipo=='L') echo 'LEGISLATIVO'; ?>            </td>
          </tr>
          <tr>
            <td align="left" class="td1">Causante</td>
            <td align="left" class="td2"><?=utf8($expediente->id_causante_txt)?></td>
          </tr>
          <tr id="tr_num_mensaje">
            <td align="left" class="td1">Número de mensaje</td>
            <td align="left" class="td2"><?=utf8($expediente->num_mensaje)?></td>
          </tr>
          <tr id="tr_tipo_proy">
            <td align="left" class="td1">Tipo de proyecto</td>
            <td align="left" class="td2"><?=utf8($expediente->tipo_proy)?></td>
          </tr>
          <tr>
            <td align="left" class="td1">Fecha de presentación</td>
            <td align="left" class="td2"><?=FormatoFechaNormal($expediente->fec_presentacion)?></td>
          </tr>
          <tr id="tr_fec_sesion">
            <td align="left" class="td1">Fecha de Sesión</td>
            <td align="left" class="td2"><?=FormatoFechaNormal($expediente->fec_sesion)?></td>
          </tr>
          <tr id="tr_com_destino">
            <td align="left" class="td1">Comisión de Destino</td>
            <td align="left" class="td2"><?=utf8($expediente->com_destino_txt)?></td>
          </tr>
          <tr id="tr_fec_aprobacion">
            <td align="left" class="td1">Fecha de aprobación</td>
            <td align="left" class="td2"><?=FormatoFechaNormal($expediente->fec_aprobacion)?></td>
          </tr>
          <tr id="tr_id_aprobacion">
            <td align="left" class="td1">Forma de aprobación</td>
            <td align="left" class="td2"><?=utf8($expediente->id_aprobacion_txt)?></td>
          </tr>
          <tr id="tr_tipo_aprobacion">
            <td align="left" class="td1">Tipo de aprobación</td>
            <td align="left" class="td2"><?=utf8($expediente->tipo_aprobacion)?></td>
          </tr>
          
          <tr id="tr_decretos">
            <td align="left" class="td1">Decretos</td>
            <td align="left" class="td2"><?
            if(trim($expediente->decretos)!=''&&file_exists("../pdf/decretos/".$expediente->decretos.".pdf")) 
            	echo '<a href="../pdf/decretos/'.$expediente->decretos.'.pdf" >' . $expediente->decretos . '</a>';
            else
            	echo $expediente->decretos;
            ?></td>
          </tr>
          <tr id="tr_declaraciones">
            <td align="left" class="td1">Declaraciones</td>
            <td align="left" class="td2"><?
            if(trim($expediente->declaraciones)!=''&&file_exists("../pdf/declaraciones/".$expediente->declaraciones.".pdf")) 
            	echo '<a href="../pdf/declaraciones/'.$expediente->declaraciones.'.pdf" >' . $expediente->declaraciones . '</a>';
            else
            	echo $expediente->declaraciones;
            ?></td>
          </tr>
          <tr id="tr_minutas">
            <td align="left" class="td1">Minutas</td>
            <td align="left" class="td2"><?
            if(trim($expediente->minutas)!=''&&file_exists("../pdf/minutas/".$expediente->minutas.".pdf")) 
            	echo '<a href="../pdf/minutas/'.$expediente->minutas.'.pdf" >' . $expediente->minutas . '</a>';
            else
            	echo $expediente->minutas;
            ?></td>
          </tr>
          <tr id="tr_ordenanzas_y_resoluciones">
            <td align="left" class="td1">Ordenanzas y resoluciones</td>
            <td align="left" class="td2"><?
            if(trim($expediente->ordenanzas_y_resoluciones)!=''&&file_exists("../pdf/ordenanzas_y_resoluciones/".$expediente->ordenanzas_y_resoluciones.".pdf"))
                echo '<a href="../pdf/ordenanzas_y_resoluciones/'.$expediente->ordenanzas_y_resoluciones.'.pdf" >' . $expediente->ordenanzas_y_resoluciones . '</a>';
            else
            	echo $expediente->ordenanzas_y_resoluciones;
            ?></td>
          </tr>          
          
          
          <tr id="tr_caratula">
            <td align="left" class="td1">Carátula</td>
            <td align="left" class="td2">
            <div align="left" style="overflow:auto; height:40px; width:100%;">
				<?=str_replace(chr(13), '<br>', utf8($expediente->caratula))?>
            </div>            </td>
          </tr>
          <tr id="tr_id_ubicacion_actual">
            <td align="left" class="td1">Ubicación actual</td>
            <td align="left" class="td2"><?=utf8($expediente->id_ubicacion_actual_txt)?></td>
          </tr>
          <tr>
            <td align="left" class="td1">Grupo de impresión</td>
            <td align="left" class="td2"><?=utf8($expediente->grupo)?></td>
          </tr>

          <tr>
            <td height="25" colspan="2" align="center" class="header2">Expedientes agregados</td>
            </tr>
          <tr>
            <td colspan="2" align="left" class="td2">
            <div align="left" style="overflow:auto; height:60px; width:100%">
            <?
            	 $agregados = explode('|', $expediente->agregados);
				 
				 for ($i=0; $i < sizeof($agregados); $i++)
				 	if ($agregados[$i] != '')
					{
					 	echo "<a href='ver_expediente.php?numero=".$agregados[$i]."'>Expediente ".$agregados[$i]."</a>";
						if (($i+2) < sizeof($agregados)) {echo ', ';}
					}
					
			?>
            <br><br>
            </div>            </td>
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
                  <td align="center" class="header">Comentario</td>

                </tr>
                <?
			$sql = "select m.fecha, m.comentario, d.destino ". 
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
					$comentario = str_replace("'", "´", $comentario);
					$comentario = str_replace(chr(10), "", $comentario);
					$comentario = str_replace(chr(13), '\n', $comentario);
					
                ?>
                <tr class="<?=$clase?>">
                  <td align="left"><?=FormatoFechaNormal($rs->fields['fecha'])?></td>
                  <td align="left"><?=utf8($rs->fields['destino'])?></td>
           		  <td align="center"><img src="../imagenes/ver.gif" alt="Ver comentario" width="28" height="16" border="0" longdesc="Ver comentario" style="cursor:pointer" onclick="alert('<?=$comentario?>');" /></td>

                </tr>
                <?
                	$rs->MoveNext();
                }
                ?>
				
              </table>
              </div>
              </td>
            </tr>
          <tr>
            <td height="25" colspan="2" align="center" class="header2">Documentos electrónicos</td>
            </tr>
          <tr>
            <td colspan="2" align="center" class="td2">
            <table width="480" border="0" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
              <tr>
                <td align="center" class="header">Proyecto original</td>
                <td align="center" class="header">Norma aprobada</td>
                </tr>
              <?
				
				$sql = "select m.fecha, m.comentario, d.destino ". 
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
					$comentario = str_replace("'", "´", $comentario);
					$comentario = str_replace(chr(10), "", $comentario);
					$comentario = str_replace(chr(13), '\n', $comentario);
					
                ?>
              <?
                	$rs->MoveNext();
                }
                ?>
                
              <tr class="<?=$clase?>">
                <td align="left">
                <?
	                if (file_exists(realpath('../pdf/proy'.$numero.'.pdf')))
						echo '<a target="_blank" href="../pdf/proy'.$numero.'.pdf">proy'.$numero.'.pdf</a>';
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
