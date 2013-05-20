<? include('../inc/inicio.inc.php'); ?>
<? include('../class/normas.class.php'); ?>
<?

DesactivarSSL();


$numero = $_GET['numero'];
$tipo = $_GET['tipo'];
$normas = new Normas();

$existe = $normas->CargarNorma($numero,$tipo);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Sistema de Gesti&oacute;n Parlamentaria</title>
<link rel="stylesheet" type="text/css" href="../inc/estilo.css">

<? include('../inc/efecto_transicion.inc.php'); ?>

<script language="javascript">
	
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
		echo '<br><br><br><br><br><span class="texto_encabezado">La Norma no existe en la base de datos</span><br><br><br>';
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
	 	   echo $normas->numero;
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
						$prefijo = 'O';
						echo 'Ordenanza';
						break;		
					case 'DEC':
						$prefijo = 'D';
						echo 'Decreto';
						break;
					case 'RES':
						$prefijo = 'R';
						echo 'Resoluci&oacute;n';
						break;
					case 'COM':
						$prefijo = 'M';
						echo 'Minuta de Comunicaci&oacute;n';
						break;
					case 'DLA':
						$prefijo = 'L';
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
     		<tr id="tr_estado">
            <td align="left" class="td1">Estado</td>
            <td align="left" class="td2">
            <div align="left" style="overflow:auto; height:20px; width:100%;">
				<?=str_replace(chr(13), '<br>', utf8($normas->estado)) ?>
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
					 	switch (substr($modifica[$i],0,1)){
						case 'O':	
							echo "<a href='ver_norma.php?numero=".substr($modifica[$i],1)."&tipo=ORD"."'>Ordenanza ".substr($modifica[$i],1)."</a>";
							if (($i+2) < sizeof($modifica)) {echo ', ';}
							break;
						case 'D':	
							echo "<a href='ver_norma.php?numero=".substr($modifica[$i],1)."&tipo=DEC"."'>Decreto ".substr($modifica[$i],1)."</a>";
							if (($i+2) < sizeof($modifica)) {echo ', ';}
							break;
						case 'M':	
							echo "<a href='ver_norma.php?numero=".substr($modifica[$i],1)."&tipo=COM"."'>Minuta Comunicaci&oacuten ".substr($modifica[$i],1)."</a>";
							if (($i+2) < sizeof($modifica)) {echo ', ';}
							break;
						case 'R':	
							echo "<a href='ver_norma.php?numero=".substr($modifica[$i],1)."&tipo=RES"."'>Resoluci&oacute;n ".substr($modifica[$i],1)."</a>";
							if (($i+2) < sizeof($modifica)) {echo ', ';}
							break;
						case 'L':	
							echo "<a href='ver_norma.php?numero=".substr($modifica[$i],1)."&tipo=DCL"."'>Declaracion ".substr($modifica[$i],1)."</a>";
							if (($i+2) < sizeof($modifica)) {echo ', ';}
							break;							
																												
					 	}
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
	                if (file_exists(realpath('../pdf/'.$prefijo.$numero.'.pdf')))
						echo '<a target="_blank" href="../pdf/'.$prefijo.$numero.'.pdf">'.$prefijo.$numero.'.pdf</a>';
				?>
                </td>
                <td align="left">
                  <?
                  
                  for ($i=0; $i < sizeof($modifica); $i++){
                  	if ($modifica[$i] != ''){
						
                  		if (file_exists(realpath('../pdf/'.$modifica[$i].'.pdf'))){
							echo '<a target="_blank" href="../pdf/'.$modifica[$i].'.pdf">'.$modifica[$i].'.pdf</a>';	
							if (($i+2) < sizeof($modifica)) {
								echo ', ';
							}					
                  		}
                  	}	

                  }
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
