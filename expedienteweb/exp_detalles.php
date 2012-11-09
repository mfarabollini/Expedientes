<?php

include('inc/pdf/class.ezpdf.php');
include('inc/funciones.inc.php');
include('inc/db_data.php');

$action = 'ver';
if($_GET['action']=='pdf')
	$action = 'pdf';

$numero = $_POST['numero'];

//Auditoria($numero, 'genero pdf');

$sql = 	"SELECT " . 
			"numero, ". 
			"letra, ". 
			"anio, ". 
			"num_mensaje, ". 
			"tipo_proy, ".
			"fec_presentacion, ".
			"fec_sesion, ". 
			"fec_aprobacion, ".
			"caratula, ".
			"tipo_aprobacion, ".
			"id_causante, id_causante_txt, ".
			"com_destino, com_destino_txt, ".
			"id_aprobacion, id_aprobacion_txt, ".
			"id_ubicacion_actual, id_ubicacion_actual_txt, ".
			"id_grupo, grupo, ".
			"fec_alta, ".
			"decretos, ".
//			"declaraciones, ".
			"minutas, ordenanzas_y_resoluciones " .
			"FROM expedientes_legislativos WHERE numero=" . $numero;

$link = mysql_connect($server, $user, $pass);

if (!$link)
    die('Could not connect: ' . mysql_error());

$db_selected = mysql_select_db($db_name, $link);
if (!$db_selected)
    die ('Can\'t use foo : ' . mysql_error());

$result = mysql_query($sql, $link);
$expediente = mysql_fetch_assoc($result);

if (!$result)
    die('Invalid query: ' . mysql_error());

//mysql_close($link);

if($action=='pdf') {

	$pdf =& new Cezpdf('LEGAL');
	$pdf->selectFont('./inc/pdf/fonts/Helvetica.afm');
	$datacreator = array (
					  'Title'=>'Expediente',
					  'Author'=>'Sistema de expedientes',
					  'Subject'=>'',
					  'Creator'=>'',
					  'Producer'=>'Sistema de expedientes'
					  );




	$pdf->addInfo($datacreator);

	//$pdf->ezText(utf8("<b>Expediente Nº ").utf82($expediente->NumeroFormateado()."</b>\n"),20);
// 780 157
// 750 150
	$pdf->ezImage("imagenes/cabecera.jpg");

	$fec_sesion = FormatoFechaNormal($expediente['fec_sesion']);
	$fec_sesion = ($fec_sesion=='00/00/0000')?'':$fec_sesion;

	$pdf->ezText("SISTEMA DE SEGUIMIENTO DE EXPEDIENTES",12);
//	$pdf->ezText("Expediente Legislativo\n",12);
	$pdf->ezText("\n\n\n",10);
	$pdf->ezText(utf8_dec("<b>Número:</b> ").number_format($expediente['numero'], 0, ',', '.'),10);	
	$pdf->ezText(utf8_dec("<b>Letra:</b> ").$expediente["letra"],10);
	$pdf->ezText(utf8_dec("<b>Año:</b> ").$expediente['anio'],10);	
	$pdf->ezText(utf8_dec("\n<b>Autor/es del proyecto o expediente:</b> ").$expediente['id_causante_txt'],10);
	$pdf->ezText(utf8_dec("<b>Número de mensaje:</b> ").$expediente['num_mensaje'],10);	
	$pdf->ezText(utf8_dec("\n<b>Tipo de proyecto:</b> ").$expediente['tipo_proy'],10);
	$pdf->ezText(utf8_dec("\n<b>Fecha de presentación:</b> ").FormatoFechaNormal($expediente['fec_presentacion']),10);	
	$pdf->ezText(utf8_dec("\n<b>Fecha de Sesión:</b> ").$fec_sesion,10);	
	$pdf->ezText(utf8_dec("\n<b>Comisión de destino:</b> ").$expediente['com_destino_txt'],10);
	$pdf->ezText(utf8_dec("\n<b>Fecha de Aprobación:</b> ").FormatoFechaNormal($expediente['fec_aprobacion']),10);	
	$pdf->ezText(utf8_dec("\n<b>Forma de aprobación:</b> ").$expediente['id_aprobacion_txt'],10);
	$pdf->ezText(utf8_dec("\n<b>Tipo de Aprobación:</b> ").$expediente['tipo_aprobacion'],10);
	$pdf->ezText(utf8_dec("\n<b>Carátula:</b> ").$expediente['caratula'],10);
	$pdf->ezText(utf8_dec("\n<b>Ubicación actual:</b> ").$expediente['id_ubicacion_actual_txt'],10);
//	$pdf->ezText(utf8_dec("\n<b>Grupo de impresión:</b> ").$expediente['grupo'],10);

	$pdf->ezText("\n\n\n\n\n",10);
	$pdf->ezText("<b>Impreso:</b> ".date("d/m/Y H:i:s")."\n\n",10,Array('justification'=>'right'));

	$pdf->ezStream();
	mysql_close($link);
	exit;
} else {
?>
<html>
<head>
	<link src="favicon.ico" rel="shortcut icon" href="/resources/favicon.ico"/>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Concejo Municipal de la Ciudad de Rosario</title>
	<link type="text/css" rel="stylesheet" href="http://www.concejorosario.gov.ar/resources/style.css"></link>
	<link type="text/css" rel="stylesheet" href="http://www.concejorosario.gov.ar/resources/style3Columnas.css"></link>
</head>
<body class="thrColFixHdr">
<div id="container">
	<a href="/home.jsp">
	<div id="header">
	    <div id="Hoy">
		<?php
			$dias = Array(1=>"lunes",2=>"martes",3=>"miercoles",4=>"jueves",5=>"viernes",6=>"sabado",7=>"domingo");

			$meses = Array(1=>"enero",2=>"febrero",3=>"marzo",4=>"abril",5=>"mayo",6=>"junio",7=>"julio",8=>"agosto",9=>"septiembre",10=>"octubre",11=>"noviembre",12=>"diciembre");
			$dia = date(N);
			$diaNum = date(j);
			$mes = date(n);
			$anio = date(Y);
			echo "Rosario, " . $dias[$dia] . " " . $diaNum . " de " . $meses[$mes] . " del " . $anio;

		?>
	    </div>
	</div>
	</a>
	<div style="width: 600px; margin: 30px 0 20px 90px;" >
		<div style="font-size:12pt; margin: 0 0 20px 0px;" >Detalle de expediente número: <strong><?php echo number_format($expediente["numero"], 0, ',', '.'); ?></strong></div>
		<table border="1" width="600" style="margin:0 auto;" >
			<tr>
				<td align="left" width="200" >Letra</td>
				<td width="400" ><?php echo $expediente["letra"]; ?></td>
			</tr>
			<tr>
				<td align="left" >Año</td>
				<td><?php echo $expediente["anio"]; ?></td>
			</tr>
			<tr>
				<td align="left" >Autor/es del proyecto o expediente</td>
				<td><?php echo utf8($expediente["id_causante_txt"]); ?></td>
			</tr>
			<tr>
				<td align="left" >Número de mensaje</td>
				<td><?php echo utf8($expediente["num_mensaje"]); ?></td>
			</tr>
			<tr>
				<td align="left" >Tipo de proyecto</td>
				<td><?php echo utf8($expediente["tipo_proy"]); ?></td>
			</tr>
			<tr>
				<td align="left" >Fecha de presentación</td>
				<td><?php echo FormatoFechaNormal($expediente["fec_presentacion"]); ?></td>
			</tr>
			<tr>
				<td align="left" >Fecha de sesión</td>
				<td>
				<?php 
					$fec_sesion = FormatoFechaNormal($expediente['fec_sesion']);
					$fec_sesion = ($fec_sesion=='00/00/0000')?'':$fec_sesion;
					echo $fec_sesion;
				?></td>
			</tr>
			<tr>
				<td align="left" >Comision de destino</td>
				<td><?php echo utf8($expediente["com_destino_txt"]); ?></td>
			</tr>
			<tr>
				<td align="left" >Fecha de Aprobación</td>
				<td><?php echo FormatoFechaNormal($expediente["fec_aprobacion"]); ?></td>
			</tr>
			<tr>
				<td align="left" >Forma de Aprobación</td>
				<td><?php echo utf8($expediente["id_aprobacion_txt"]); ?></td>
			</tr>
			<tr>
				<td align="left" >Tipo de Aprobación</td>
				<td><?php echo utf8($expediente["tipo_aprobacion"]); ?></td>
			</tr>
			
			
          <tr>
            <td align="left">Decretos</td>
            <td align="left"><?
            if(trim($expediente['decretos'])!=''&&file_exists("pdf/decretos/" . $expediente['decretos'] . ".pdf")) 
            	echo '<a href="pdf/decretos/' . $expediente['decretos'] . '.pdf" >' . $expediente['decretos'] . '</a>';
            else
            	echo $expediente['decretos'];
            ?></td>
          </tr>
		<?php /*
          <tr>
            <td align="left">Declaraciones</td>
            <td align="left"><?
            if(trim($expediente['declaraciones'])!=''&&file_exists("pdf/declaraciones/".$expediente['declaraciones'].".pdf")) 
            	echo '<a href="pdf/declaraciones/'.$expediente['declaraciones'].'.pdf" >' . $expediente['declaraciones'] . '</a>';
            else
            	echo $expediente['declaraciones'];
            ?></td>
          </tr>
		*/  ?>
          <tr>
            <td align="left">Minutas</td>
            <td align="left"><?
            if(trim($expediente['minutas'])!=''&&file_exists("pdf/minutas/".$expediente['minutas'].".pdf")) 
            	echo '<a href="pdf/minutas/'.$expediente['minutas'].'.pdf" >' . $expediente['minutas'] . '</a>';
            else
            	echo $expediente['minutas'];
            ?></td>
          </tr>
          <tr>
            <td align="left">Ordenanzas y resoluciones</td>
            <td align="left"><?
            if(trim($expediente['ordenanzas_y_resoluciones'])!=''&&file_exists("pdf/ordenanzas_y_resoluciones/".$expediente['ordenanzas_y_resoluciones'].".pdf"))
                echo '<a href="pdf/ordenanzas_y_resoluciones/'.$expediente->ordenanzas_y_resoluciones.'.pdf" >' . $expediente['ordenanzas_y_resoluciones'] . '</a>';
            else
            	echo $expediente['ordenanzas_y_resoluciones'];
            ?></td>
          </tr>    			
			
			
			
			
			
			
			
			<tr>
				<td align="left" >Caratula</td>
				<td><?php echo utf8($expediente["caratula"]); ?></td>
			</tr>
			<tr>
				<td align="left" >Ubicación actual</td>
				<td><?php echo utf8($expediente["id_ubicacion_actual_txt"]); ?></td>
			</tr>
<?php
/*
			<tr>
				<td align="left" >Grupo de impresión</td>
				<td><?php echo utf8($expediente["grupo"]); ?></td>
			</tr>
*/
?>

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
						<tr class="">
		                	<td align="left">
		                <?
			                if (file_exists(realpath('pdf/proy'.$numero.'.pdf')))
								echo '<a target="_blank" href="pdf/proy'.$numero.'.pdf">proy'.$numero.'.pdf</a>';
						?>
							</td>
							<td align="left">
		                  <?
		            	$tipo_aprobacion = utf8($expediente["tipo_aprobacion"]);
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
								if (file_exists(realpath('pdf/'.$value.'.pdf')))
									$documentos .= '<a target="_blank" href="pdf/'.$value.'.pdf">'.$value.'.pdf</a>, ';
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
		</table>
		<br/>
		<form action="exp_detalles.php?action=pdf" method="post" target="_blank">
			<input name="numero" type="hidden" value="<?php echo $expediente["numero"]; ?>" />
			<input type="submit" value="Imprimir" />
		</form>
	</div>
	<br/>
	<div id="footer">
	    <img border="0" usemap="#Map" src="http://www.concejorosario.gov.ar/resources/pie.jpg"/>
	    <map id="Map" name="Map">
		<area href="mailto:info@concejorosario.gov.ar" coords="234,43,391,62" shape="rect"/>
	    </map>
	</div>
</div>
</body>
</html>






























<?php
}
mysql_close($link);
