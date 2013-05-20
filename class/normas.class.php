<?
include('../inc/conexion.inc.php');


class Normas
{
	public $numero;
	public $numero_ext;
	public $tipo; 
	public $fec_aprobacion;
	public $descripcion;
	public $estado;	
	public $modifica;
	public $modifica_txt;
	
	public $numero_old;
	public $tipo_old;
	
    public $tags;
	
	
	// verifica que exista o no ese numero de norma
	public function VerifNumero($pNumero,$pTipo)
	{

		if ($pNumero != '')
		{
			global $conn;
			$sql;
			$rs;
			
			$sql = "select nro_norma from normas where nro_norma='$pNumero' and tipo_norma='$pTipo'";
			if (!($rs = $conn->Execute($sql)))
			{
				return 'N';
			}
			else
			{
				if ($rs->EOF)
					return 'N';
				else
					return 'S';
			}
		}
		else
			return 'N';
	}


	private function InicializarValores()
	{
		$this->numero = '';
		$this->numero_ext = '';
		$this->tipo = 'null';
		$this->descripcion = '';
		$this->estado = '';
		$this->fec_aprobacion = '';
		$this->modifica = '';		
		$this->modifica_txt = '';		
	}


	public function __construct()
	{
		$this->InicializarValores();
	}


	public function CargarNorma($nro_norma, $tipoNorma)
	{


			global $conn;
			$sql;
			$rs;
		
			$sql = "SELECT n.*, t.tags as tags ".
			   		"FROM normas n ".
			   		"LEFT JOIN tags_normas t ON t.nro_norma = n.nro_norma AND t.tipo_norma = n.tipo_norma ".
			   		"WHERE n.nro_norma='$nro_norma' and n.tipo_norma='$tipoNorma'";
			   
		$rs = $conn->Execute($sql);
		
		if ($rs->EOF)
		{
			return false;
		}
		else
		{
			$this->numero = $rs->fields['nro_norma'];
			$this->tipo = $rs->fields['tipo_norma'];
			$this->descripcion = $rs->fields['dsc_norma'];
			$this->estado = $rs->fields['estado'];
			$this->fec_aprobacion = $rs->fields['fec_aprob'];
			$this->modifica = $rs->fields['modifica'];
			$this->tags = $rs->fields['tags'];
			
			return true;
		}
	}

	
	public function FiltroBuscar()
	{				
		$filtro = '';


		$this->LeerPost();
				
		
		if ($this->fec_presentacion_desde=='null') $this->fec_presentacion_desde='';
		if ($this->fec_sesion_desde=='null') $this->fec_sesion_desde='';
		if ($this->fec_presentacion_hasta=='null') $this->fec_presentacion_hasta='';
		if ($this->fec_sesion_hasta=='null') $this->fec_sesion_hasta='';
		if ($this->fec_aprobacion_desde=='null') $this->fec_aprobacion_desde='';
        if ($this->fec_aprobacion_hasta=='null') $this->fec_aprobacion_hasta='';


						
		if ($this->TieneValor($this->numero))
			$filtro .= '<br>Numero: '.$this->numero;
			
		if ($this->TieneValor($this->tipo))
			$filtro .= '<br>Tipo: '.$this->tipo;
			
		if ($this->TieneValor($this->letra))
			$filtro .= '<br>Letra: '.$this->letra;
			
		if ($this->TieneValor($this->anio))
			$filtro .= '<br>A�o: '.$this->anio;
			
		if ($this->TieneValor($this->tipo_proy))
			$filtro .= '<br>Tipo proy.: '.$this->tipo_proy;
			
		if ($this->TieneValor($this->num_mensaje))
			$filtro .= '<br>Num. mensaje: '.$this->num_mensaje;
			
		if ($this->TieneValor($this->id_causante_txt))
			$filtro .= '<br>Causante: '.$this->id_causante_txt;
			
		if ($this->TieneValor($this->caratula))
			$filtro .= '<br>Caratula: '.$this->caratula;
			
		if ($this->TieneValor($this->fec_presentacion_desde))
			$filtro .= '<br>Fec. presentacion desde: '.$this->fec_presentacion_desde;
			
		if ($this->TieneValor($this->fec_presentacion_hasta))
			$filtro .= '<br>Fec. presentacion hasta: '.$this->fec_presentacion_hasta;
			
		if ($this->TieneValor($this->fec_sesion_desde))
			$filtro .= '<br>Fec. sesion desde: '.$this->fec_sesion_desde;
			
		if ($this->TieneValor($this->fec_sesion_hasta))
			$filtro .= '<br>Fec. sesion hasta: '.$this->fec_sesion_hasta;
						
		if ($this->TieneValor($this->com_destino_txt))
			$filtro .= '<br>Comision destino: '.$this->com_destino_txt;
			
		if ($this->TieneValor($this->id_ubicacion_actual_txt))
			$filtro .= '<br>Destino actual: '.$this->id_ubicacion_actual_txt;

		if ($this->TieneValor($this->id_categoria_txt))
			$filtro .= '<br>Categoria: '.$this->id_categoria_txt;
			
		if ($this->TieneValor($this->id_aprobacion_txt))
			$filtro .= '<br>Forma de aprobacion: '.$this->id_aprobacion_txt;
			
		if ($this->TieneValor($this->tipo_aprobacion))
			$filtro .= '<br>Tipo aprobacion: '.$this->tipo_aprobacion;
			
		if ($this->TieneValor($this->agregados_txt))
			$filtro .= '<br>Exp. agregado: '.$this->agregados_txt;
		
		if ($this->TieneValor($this->fec_aprobacion_desde))
			$filtro .= '<br>Fec. aprobacion desde: '.$this->fec_aprobacion_desde;
			
		if ($this->TieneValor($this->fec_aprobacion_hasta))
			$filtro .= '<br>Fec. aprobacion hasta: '.$this->fec_aprobacion_hasta;


		return $filtro;
	}

	
	public function cambiarFormatoFecha($fechaa){
	    list($anioa,$mesa,$diaa)=explode("-",$fechaa);
	    return $anioa."-".$mesa."-".$diaa;
	}

	
	public function SqlBuscar()
	{				
		$sql = '';
		global $conn;
		$filtro = '';


		$this->LeerPost();
				
		
		if ($this->fec_aprobacion_desde=='null') $this->fec_aprobacion_desde='';
		if ($this->fec_aprobacion_hasta=='null') $this->fec_aprobacion_hasta='';

						
		if ($this->TieneValor($this->numero))
			$filtro .= " and n.nro_norma='".$this->numero."'";
			
		if ($this->TieneValor($this->tipo))
			$filtro .= " and n.tipo_norma='".$this->tipo."'";
			
		if ($this->TieneValor($this->descripcion))
			$filtro .= " and n.dsc_norma like '%".$this->descripcion."%'";

		if ($this->TieneValor($this->estado))
			$filtro .= " and n.estado like '%".$this->estado."%'";
			
			
		if ($this->TieneValor($this->fec_aprobacion_desde))
			$filtro .= " and n.fec_aprob >= '".$this->fec_aprobacion_desde."'";
			
		if ($this->TieneValor($this->fec_aprobacion_hasta))
			$filtro .= " and n.fec_aprob <= '".$this->fec_aprobacion_hasta."'";
								
		if ($this->TieneValor($this->tags)){
			$tags = explode(';',$this->tags);
			
			if(count($tags) > 1 ) {
				$index = 0;
				$filtro .= "and (";
				foreach($tags as $tag) {
					if ($index > 0){
						$filtro .= "or t.tags LIKE '%{$tag}%' ";
					}else{	
						$filtro .= "t.tags LIKE '%{$tag}%' ";
					}
					$index++;
				}	
				$filtro .= ")";
			}elseif (count($tags) == 1 ){
				$filtro .= " and t.tags LIKE '%{$tags[0]}%' ";					
			}
		}
	
			
			//$filtro .= " and MATCH (t.tags) AGAINST ('{$this->tags}' IN boolean mode) ";
			//$filtro .= " and t.tags LIKE '%{$this->tags}%' ";
		
		$sql = "select n.*, t.tags as tags ".
			   "from normas n ".
			   "left join tags_normas t on t.nro_norma = n.nro_norma and t.tipo_norma = n.tipo_norma ".
			   "where 1=1 ".$filtro;

		return $sql;
	}


	public function FiltroReporte()
	{				
		$filtro = '';

		$this->LeerPost();				
		
		if ($this->fec_presentacion_desde=='null') $this->fec_presentacion_desde='';
		if ($this->fec_presentacion_hasta=='null') $this->fec_presentacion_hasta='';
		if ($this->flt_solo_sin_imprimir=='S') 
			$txt_flt_solo_sin_imprimir='Si';
		else
			$txt_flt_solo_sin_imprimir='No';
		
						
		if ($this->TieneValor($this->numero))
			$filtro .= '<br>Numero: '.$this->numero;
			
		$filtro .= '<br>Solo sin imprimir: '.$txt_flt_solo_sin_imprimir;
						
		if ($this->TieneValor($this->fec_presentacion_desde))
			$filtro .= '<br>Fec. presentacion desde: '.$this->fec_presentacion_desde;
			
		if ($this->TieneValor($this->fec_presentacion_hasta))
			$filtro .= '<br>Fec. presentacion hasta: '.$this->fec_presentacion_hasta;
			
		return $filtro;
	}

	private function LeerPost()
	{				
		$this->numero = str_replace('.', '', str_replace(',', '', $this->ValorPostNull('numero')));
		$this->tipo = $this->ValorPost('tipo');

		$this->descripcion = substr($this->ValorPost('descripcion'), 0, 500);
		$this->estado = substr($this->ValorPost('estado'), 0, 100);
		$this->fec_aprobacion = $this->ValorPostFecha('fec_aprobacion');
		$this->fec_aprobacion_desde = $this->ValorPostFecha('fec_aprobacion_desde');
		$this->fec_aprobacion_hasta = $this->ValorPostFecha('fec_aprobacion_hasta');

		$this->modifica = $this->ValorPost('hdnModifica');
		$this->modifica_txt = $this->ValorPost('modifica_txt');

		$this->tags =  $this->ValorPost('tags');
	}
	
	
	public function GrabarPost()
	{
		$sql;
		global $conn, $conn_web_server, $conn_web_user, $conn_web_pass, $conn_web_bd;
		
		$this->LeerPost();
		$this->id_usuario_alta = $_SESSION['id_usuario'];
		$this->id_usuario_mod = $_SESSION['id_usuario'];
		$this->fec_mod = 'null';

		$conn->StartTrans();
		$registro_nuevo = 1;	
		//hago un insert o un update dependiendo de si tengo cargado uno o no
		if ($_POST['accion_anterior']!='editar')
		{
			$this->fec_alta = "'".date('Y-m-d H:i:s')."'";

			$sql = "INSERT INTO normas ".
				   "(nro_norma, tipo_norma, fec_aprob, dsc_norma, estado, modifica, id_usuario_alta, fec_alta) ".
				   "VALUES ".
				   "('$this->numero','$this->tipo', $this->fec_aprobacion, '$this->descripcion','$this->estado','$this->modifica', '$this->id_usuario_alta', $this->fec_alta)";

			if (!$conn->Execute($sql))
			{
				echo $conn->ErrorMsg();
			}
		}
		else
		{
			$this->fec_mod = "'".date('Y-m-d H:i:s')."'";
			$this->numero_old = $_POST['numero_old'];
			$this->tipo_old   = $_POST['tipo_old'];
			if (($_POST['numero'] != $_POST['numero_old'])||($_POST['tipo'] != $_POST['tipo_old'])){
				//Borro el registro
				$sql = "DELETE FROM normas WHERE nro_norma='$this->numero_old' and tipo_norma='$this->tipo_old'";
				$conn->Execute($sql);

								
				//Inserto el Nuevo
				$sql = "INSERT INTO normas ".
					   "(nro_norma, tipo_norma, fec_aprob, dsc_norma, estado, modifica, id_usuario_mod, fec_mod) ".
					   "VALUES ".
					   "('$this->numero','$this->tipo', $this->fec_aprobacion, '$this->descripcion','$this->estado','$this->modifica', '$this->id_usuario_mod', $this->fec_mod)";
				
				$registro_nuevo = 0;
				$conn->Execute($sql);
				
			}else{
			
				$sql = "UPDATE normas SET ".
						   "fec_aprob=$this->fec_aprobacion, ".
						   "dsc_norma='$this->descripcion', ".
						   "estado='$this->estado', ".
						   "modifica='$this->modifica', ".						   
						   "id_usuario_mod=$this->id_usuario_mod, ".
						   "fec_mod=$this->fec_mod ".
						   "where nro_norma='$this->numero' AND ".
						   "tipo_norma='$this->tipo'";
	
				$registro_nuevo = 0;
				$conn->Execute($sql);
			}
		}
		
		$conn->CompleteTrans(true);
	}
	
	public function GrabarTags() {
		$sql;
		global $conn;
		
		$this->LeerPost();
		
		$conn->StartTrans();
		if ($_POST['accion_anterior']!='editar'){
			$this->fec_mod = "'".date('Y-m-d H:i:s')."'";
			$this->id_usuario_mod = $_SESSION['id_usuario'];
			$sql = "UPDATE normas SET ".
							   "id_usuario_mod=$this->id_usuario_mod, ".
							   "fec_mod=$this->fec_mod ".
							   "where nro_norma=$this->numero AND ".
							   "tipo_norma='$this->tipo'";
			$conn->Execute($sql);
		}
		$this->guardarTags();
		$conn->CompleteTrans(true);
	}
	
	
	public function ValorPost($valor)
	{
		if (isset($_POST[$valor]))
			return str_replace('"', "''", str_replace("'", "''", strtoupper(utf8_dec(trim($_POST[$valor])))));
		else
			return '';
	}

	public function ValorPostNull($valor)
	{
		if (isset($_POST[$valor]))
			return strtoupper(utf8_dec($_POST[$valor]));
		else
			return 'null';
	}

	public function ValorPostFecha($valor)
	{
		if (isset($_POST[$valor]))
			if ($_POST[$valor]=='' || $_POST[$valor]=="0")
				return 'null';
			else
				return utf8_dec($_POST[$valor]);
		else
			return 'null';
	}

	public function ValorGetFecha($valor)
	{
		if (isset($_GET[$valor]))
		{
			if ($_GET[$valor]=='' || $_GET[$valor]=="0")
			{	
				return 'null';
			}
			else
			{
				return utf8_dec($_GET[$valor]);
			}
		}
		else
			return 'null';
	}
	
	
	private function TieneValor($valor)
	{
		if ($valor != '' && $valor != 'null')
			return true;
		else
			return false;
	}

	
	private function guardarTags() {

		global $conn;
		$this->numero_old = $_POST['numero_old'];
		$this->tipo_old   = $_POST['tipo_old'];
		if ($_POST['accion_anterior']!='editar'){
			// Primero debe borrar los tags anteriores.
			$rs = $conn->Execute("DELETE FROM `tags_normas` WHERE `nro_norma`= {$this->numero} and `tipo_norma`= '{$this->tipo}'");
			
			$tags = explode(';',$this->tags);
			
			if(count($tags) > 0 ) {
				foreach($tags as $tag) {
					if(strlen($tag) > 2) 
						$tagSave .= $tag.";";
				}
				
				if(strlen($tagSave)>0) {
					$sql = "INSERT INTO `tags_normas` (`nro_norma`,`tipo_norma`, `tags`) VALUES ({$this->numero}, '{$this->tipo}', '{$tagSave}')";
					$rs = $conn->Execute($sql);
				}
			}
		}else{
					// Primero debe borrar los tags anteriores.
			$rs = $conn->Execute("DELETE FROM `tags_normas` WHERE `nro_norma`= {$this->numero_old} and `tipo_norma`= '{$this->tipo_old}'");
			
			$tags = explode(';',$this->tags);
			
			if(count($tags) > 0 ) {
				foreach($tags as $tag) {
					if(strlen($tag) > 2) 
						$tagSave .= $tag.";";
				}
				
				if(strlen($tagSave)>0) {
					$sql = "INSERT INTO `tags_normas` (`nro_norma`,`tipo_norma`, `tags`) VALUES ({$this->numero}, '{$this->tipo}', '{$tagSave}')";
					$rs = $conn->Execute($sql);
				}
			}			
			
		}
	}
	
}

?>