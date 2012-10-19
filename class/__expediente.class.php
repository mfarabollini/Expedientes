<?
include('../inc/conexion.inc.php');


class Expediente
{
	public $numero;
	public $tipo; //I=INTERNOS  M=MUNICIPALES  L=LEGISLATIVOS'
	public $letra;
	public $anio;
	public $nro_municipal;
	public $tipo_proy;
	public $num_mensaje;
	public $id_causante;
	public $id_causante_txt;
	public $caratula;
	public $fec_presentacion;
	public $fec_sesion;
	public $flt_solo_sin_imprimir;	
	public $fec_presentacion_desde;
	public $fec_sesion_desde;
	public $fec_presentacion_hasta;
	public $fec_sesion_hasta;
	public $com_destino;
	public $id_ubicacion_actual;
	public $com_destino_txt;
	public $id_ubicacion_actual_txt;
	public $fec_aprobacion;
	public $id_aprobacion;
	public $id_aprobacion_txt;
	public $tipo_aprobacion;
	public $id_usuario_alta;
	public $id_usuario_mod;
	public $fec_alta;
	public $fec_mod;
	public $agregados;
	public $agregados_txt;
	public $id_categoria;
	public $id_categoria_txt;
	public $impreso;
	public $id_grupo;
	public $grupo;
	public $fec_aprobacion_desde;
    public $fec_aprobacion_hasta;
    public $tags;


	public $fec_creacion;
	
	public $tipoOrd;
	
	public $lista1;
	public $lista2;
	public $lista3;
	public $lista4;
	public $lista5;
	public $lista6;
	public $lista7;
	public $lista8;
	public $lista9;
	public $lista10;
	public $lista11;
	//public $lista12;
	
	
	// muestra el numero formateado
	public function NumeroFormateado()
	{
		return number_format($this->numero, 0, ',', '.');
	}
	
	// verifica que exista o no ese numero de expediente
	public function VerifNumero($pNumero)
	{
		$pNumero = trim($pNumero);
		$pNumero = str_replace('.', '', $pNumero);
		$pNumero = str_replace(',', '', $pNumero);
		$pNumero = str_replace('-', '', $pNumero);

		if ($pNumero != '')
		{
			global $conn;
			$sql;
			$rs;
			
			$sql = "select numero from expedientes where numero=$pNumero";
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
		$this->tipo = 'L';
		$this->letra = '';
		$this->anio = '';
		$this->nro_municipal = '';		
		$this->tipo_proy = '';
		$this->num_mensaje = '';
		$this->id_causante = '';
		$this->id_causante_txt = '';
		$this->caratula = '';
		$this->fec_presentacion = '';
		$this->fec_sesion = '';
		$this->fec_presentacion_desde = '';
		$this->fec_sesion_desde = '';
		$this->fec_presentacion_hasta = '';
		$this->fec_sesion_hasta = '';
		$this->com_destino = '';
		$this->id_ubicacion_actual = '';
		$this->com_destino_txt = '';
		$this->id_ubicacion_actual_txt = '';
		$this->fec_aprobacion = '';
		$this->id_aprobacion = '';
		$this->id_aprobacion_txt = '';
		$this->tipo_aprobacion = '';
		$this->id_usuario_alta = '';
		$this->id_usuario_mod = '';
		$this->fec_alta = '';
		$this->fec_mod = '';
		$this->agregados = '';		
		$this->agregados_txt = '';		
		$this->id_categoria = '';		
		$this->id_categoria_txt = '';		
		$this->impreso = '';
		$this->flt_solo_sin_imprimir = '';
		$this->id_grupo = '';
		$this->grupo = '';
		$this->fec_creacion = '';
		$this->fec_aprobacion_desde = '';
		$this->fec_aprobacion_hasta = '';

		
		$this->tipoOrd = 'Ord';

		$this->lista1 = 'GOBIERNO Y CULTURA';
		$this->lista2 = 'PRESUPUESTO Y HACIENDA';
		$this->lista3 = 'OBRAS PUBLICAS Y SEGURIDAD';
		$this->lista4 = 'SEGURIDAD PUBLICA y CIUDADANA';
		$this->lista5 = 'SALUD Y ACCION SOCIAL';
		$this->lista6 = 'PLANEAMIENTO Y URBANISMO';
		$this->lista7 = 'SERVICIOS PUBLICOS Y CONCEDIDOS';
		$this->lista8 = 'PRODUCCION Y PROMOCION DEL EMPLEO';
		$this->lista9 = 'ECOLOGIA Y MEDIO AMBIENTE';
		$this->lista10 = 'DERECHOS HUMANOS';	
		$this->lista11 = 'PRESIDENCIA';		
		//$this->lista12 = 'PRESUPUESTO, HACIENDA Y OBRAS';	


	}


	public function __construct()
	{
		$this->InicializarValores();
	}


	//Funci�n que de acuerdo a la primer palabra de la comisi�n ,obtenida desde mysql,
	//asigna un valor de t�tulo que corresponda
	public function CargaTituloExp($comisionBD)
	{

		$arrCom = substr($comisionBD,0,5);
		$arrC1 = substr($this->lista1,0,5);
		$arrC2 = substr($this->lista2,0,5);
		$arrC3 = substr($this->lista3,0,5);				
		$arrC4 = substr($this->lista4,0,5);
		$arrC5 = substr($this->lista5,0,5);
		$arrC6 = substr($this->lista6,0,5);
		$arrC7 = substr($this->lista7,0,5);
		$arrC8 = substr($this->lista8,0,5);
		$arrC9 = substr($this->lista9,0,5);
		$arrC10 = substr($this->lista10,0,5);
  		$arrC11 = substr($this->lista11,0,5);
																
	
		//$arrC1 = split(" ",$this->lista1);
		//$arrC2 = split(" ",$this->lista2);
		//$arrC3 = split(" ",$this->lista3);
		//$arrC4 = split(" ",$this->lista4);
		//$arrC5 = split(" ",$this->lista5);
		//$arrC6 = split(" ",$this->lista6);
		//$arrC7 = split(" ",$this->lista7);
		//$arrC8 = split(" ",$this->lista8);
		//$arrC9 = split(" ",$this->lista9);
		//$arrC10 = split(" ",$this->lista10);
		//$arrC11 = split(" ",$this->lista11);
		//$arrC12 = split(" ",$this->lista12);
		
			if (strcmp(strtoupper($arrCom),strtoupper($arrC1)) == 0)
				$titulo = $this->lista1;
			elseif (strcmp(strtoupper($arrCom),strtoupper($arrC2)) == 0)	
				$titulo = $this->lista2;
			elseif (strcmp(strtoupper($arrCom),strtoupper($arrC3)) == 0)	
				$titulo = $this->lista3;
			elseif (strcmp(strtoupper($arrCom),strtoupper($arrC4)) == 0)	
				$titulo = $this->lista4;
			elseif (strcmp(strtoupper($arrCom),strtoupper($arrC5)) == 0)	
				$titulo = $this->lista5;
			elseif (strcmp(strtoupper($arrCom),strtoupper($arrC6)) == 0)	
				$titulo = $this->lista6;
			elseif (strcmp(strtoupper($arrCom),strtoupper($arrC7)) == 0)	
				$titulo = $this->lista7;
			elseif (strcmp(strtoupper($arrCom),strtoupper($arrC8)) == 0)	
				$titulo = $this->lista8;
			elseif (strcmp(strtoupper($arrCom),strtoupper($arrC9)) == 0)	
				$titulo = $this->lista9;
			elseif (strcmp(strtoupper($arrCom),strtoupper($arrC10)) == 0)	
				$titulo = $this->lista10;
			elseif (strcmp(strtoupper($arrCom),strtoupper($arrC11)) == 0)	
				$titulo = $this->lista11;
			//elseif (strcmp(strtoupper($arrCom),strtoupper($arrC12)) == 0)	
				//$titulo = $this->lista12;
			else
				$titulo = "Sin Titulo";																							

		return $titulo;
	}


	public function CargarExpediente($nro_exp)
	{
		if (is_numeric($nro_exp))

		{
			global $conn;
			$sql;
			$rs;
		
			$sql = "select e.*, cat.categoria, d1.destino as com_destino_txt, d2.destino as id_ubicacion_actual_txt, ap.aprobacion as id_aprobacion_txt, g.grupo, cau.causante as id_causante_txt, t.tags as tags ".
			   "from expedientes e ".
			   "left join destinos d1 on d1.id_destino=e.com_destino ".
			   "left join destinos d2 on d2.id_destino=e.id_ubicacion_actual ".
			   "left join categorias cat on cat.id_categoria=e.id_categoria ".
			   "left join formas_aprobacion ap on ap.id_aprobacion=e.id_aprobacion ".
			   "left join causantes cau on cau.id_causante=e.id_causante ".
			   "left join grupos_impresion g on g.id_grupo=e.id_grupo ".
			   "left join tags t on t.numero_expediente=e.numero ".
			   "where e.numero=$nro_exp";
			   
		$rs = $conn->Execute($sql);
		
		if ($rs->EOF)
		{
			return false;
		}
		else
		{
			$this->numero = $rs->fields['numero'];
			$this->tipo = $rs->fields['tipo'];
			$this->letra = $rs->fields['letra'];
			$this->anio = $rs->fields['anio'];
			$this->nro_municipal = $rs->fields['nro_municipal'];
			$this->tipo_proy = $rs->fields['tipo_proy'];
			$this->num_mensaje = $rs->fields['num_mensaje'];
			$this->id_causante = $rs->fields['id_causante'];
			$this->id_causante_txt = $rs->fields['id_causante_txt'];
			$this->caratula = $rs->fields['caratula'];
			$this->fec_presentacion = $rs->fields['fec_presentacion'];
			$this->fec_sesion = $rs->fields['fec_sesion'];
			$this->com_destino = $rs->fields['com_destino'];
			$this->id_ubicacion_actual = $rs->fields['id_ubicacion_actual'];
			$this->com_destino_txt = $rs->fields['com_destino_txt'];
			$this->id_ubicacion_actual_txt = $rs->fields['id_ubicacion_actual_txt'];
			$this->fec_aprobacion = $rs->fields['fec_aprobacion'];
			$this->id_aprobacion = $rs->fields['id_aprobacion'];
			$this->id_aprobacion_txt = $rs->fields['id_aprobacion_txt'];
			$this->tipo_aprobacion = $rs->fields['tipo_aprobacion'];
			$this->agregados = $rs->fields['agregados'];
			$this->id_usuario_alta = $rs->fields['id_usuario_alta'];
			$this->id_usuario_alta = $rs->fields['id_usuario_mod'];
			$this->fec_alta = $rs->fields['fec_alta'];
			$this->fec_mod = $rs->fields['fec_mod'];
			$this->id_categoria_txt = $rs->fields['categoria'];
			$this->id_categoria = $rs->fields['id_categoria'];
			$this->impreso = $rs->fields['impreso'];
			$this->id_grupo = $rs->fields['id_grupo'];
			$this->grupo = $rs->fields['grupo'];
			$this->tags = $rs->fields['tags'];
			$this->decretos = $rs->fields['decretos'];
			$this->declaraciones = $rs->fields['declaraciones'];
			$this->minutas = $rs->fields['minutas'];
			$this->ordenanzas_y_resoluciones = $rs->fields['ordenanzas_y_resoluciones'];
			
			return true;
		}
	}
	
		else
			return false;
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


	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	//+ Mdificaci�n Reporte Preferencias                                  +
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	//+ Verifico que los expedientes cargados existan y sean legislativos  +
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	public function SqlCheckExpPref($exp)
	{
		$sql = '';
		global $conn;
		//echo $exp;
		$sql = "select A.tipo_proy, B.causante, A.numero, A.letra, A.anio, C.destino, A.caratula ".
				"from expedientes A, causantes B, destinos C, lista_preferencias D ".
				"where A.id_causante = B.id_causante ".
				"and A.com_destino = C.id_destino ".
				
	           // "and (SUBSTRING(C.destino,1,8) = SUBSTRING(D.desc_lista,1,8) OR ".
				//		"SUBSTRING(C.destino,1,5)=SUBSTRING(D.desc_lista,1,5) OR ".
					//	"SUBSTRING(C.destino,1,12)=SUBSTRING(D.desc_lista,1,12) OR ".
					//	"SUBSTRING(C.destino,1,12)=SUBSTRING(D.desc_lista,1,6) OR ".
					//	"SUBSTRING(C.destino,1,12)=SUBSTRING(D.desc_lista,1,11) OR ".
					//	"SUBSTRING(C.destino,1,12)=SUBSTRING(D.desc_lista,1,13) OR ".																		
						//"SUBSTRING(C.destino,1,5)=SUBSTRING(D.desc_lista,1,5) OR ".
						//"SUBSTRING(C.destino,1,12)=SUBSTRING(D.desc_lista,1,12) OR ".
					//	"SUBSTRING(C.destino,1,9)=SUBSTRING(D.desc_lista,1,9) OR ".
					//	"SUBSTRING(C.destino,1,10)=SUBSTRING(D.desc_lista,1,10) OR ".
						//"SUBSTRING(C.destino,1,8)=SUBSTRING(D.desc_lista,1,8) OR ".
						//"SUBSTRING(C.destino,1,8)=SUBSTRING(D.desc_lista,1,8) ".	
					//	"SUBSTRING(C.destino,1,12)=SUBSTRING(D.desc_lista,1,12) ".					
				"and (SUBSTRING(C.destino,1,7) = SUBSTRING(D.desc_lista,1,7) ".
				        // OR ".
						//"SUBSTRING(C.destino,1,5)=SUBSTRING(D.desc_lista,1,5) OR ".
						//"SUBSTRING(C.destino,1,12)=SUBSTRING(D.desc_lista,1,12) OR ".
						//"SUBSTRING(C.destino,1,12)=SUBSTRING(D.desc_lista,1,6) OR ".
						//"SUBSTRING(C.destino,1,12)=SUBSTRING(D.desc_lista,1,11) OR ".
						//"SUBSTRING(C.destino,1,12)=SUBSTRING(D.desc_lista,1,13) OR ".																		
						//"SUBSTRING(C.destino,1,5)=SUBSTRING(D.desc_lista,1,5) OR ".
						//"SUBSTRING(C.destino,1,12)=SUBSTRING(D.desc_lista,1,12) OR ".
						//"SUBSTRING(C.destino,1,9)=SUBSTRING(D.desc_lista,1,9) OR ".
						//"SUBSTRING(C.destino,1,10)=SUBSTRING(D.desc_lista,1,10) OR ".
						//"SUBSTRING(C.destino,1,8)=SUBSTRING(D.desc_lista,1,8) OR ".
						//"SUBSTRING(C.destino,1,8)=SUBSTRING(D.desc_lista,1,8) ".	
						//"SUBSTRING(C.destino,1,12)=SUBSTRING(D.desc_lista,1,12) ".									
				") ".				
				"and A.tipo = 'L' ".
				"and A.numero in (".$exp.") ".
				"GROUP BY A.numero ".
				"ORDER BY D.id_lista, A.numero";
				
		//print $sql;		
		return $sql;
	}
	
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	//+ Mdificaci�n Reporte Preferencias                                  +
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	//+ Verifico que los expedientes cargados existan y sean legislativos  +
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	public function SqlCheckExpOrd($exp)
	{
		$sql = '';
		global $conn;
		$sql = "select A.tipo_proy, B.causante, A.numero, A.letra, A.anio, C.destino, A.caratula ".
				"from expedientes A, causantes B, destinos C ".
				"where A.id_causante = B.id_causante ".
				"and A.com_destino = C.id_destino ".
				"and A.tipo = 'L' ".
				"and A.numero in (".$exp.")".
				" and  (".
						"SUBSTRING(C.destino,1,5)='GOBIE' OR ".
						"SUBSTRING(C.destino,1,5)='PRESU' OR ".
						"SUBSTRING(C.destino,1,5)='OBRAS' OR ".
						"SUBSTRING(C.destino,1,5)='SEGUR' OR ".						
						"SUBSTRING(C.destino,1,5)='SALUD' OR ".												
						"SUBSTRING(C.destino,1,5)='PLANE' OR ".												
						"SUBSTRING(C.destino,1,5)='SERVI' OR ".												
						"SUBSTRING(C.destino,1,5)='PRODU' OR ".												
						"SUBSTRING(C.destino,1,5)='ECOLO' OR ".												
						"SUBSTRING(C.destino,1,5)='DEREC' OR ".												
						"SUBSTRING(C.destino,1,5)='PRESI')"; 
						
					//	"SUBSTRING(C.destino,1,9)='GOBIERNO,' OR ".
					//	"SUBSTRING(C.destino,1,5)='SALUD' OR ".
					//	"SUBSTRING(C.destino,1,6)='SALUD,' OR ".
					//	"SUBSTRING(C.destino,1,11)='PRESUPUESTO' OR ".
					//	"SUBSTRING(C.destino,1,12)='PRESUPUESTO,' OR ".
					//	"SUBSTRING(C.destino,1,9)='SEGURIDAD' OR ".	
					//	"SUBSTRING(C.destino,1,10)='SEGURIDAD,' OR ".						
					//	"SUBSTRING(C.destino,1,5)='OBRAS' OR ".
					//	"SUBSTRING(C.destino,1,6)='OBRAS,' OR ".
					//	"SUBSTRING(C.destino,1,12)='PLANEAMIENTO' OR ".
					//	"SUBSTRING(C.destino,1,13)='PLANEAMIENTO,' OR ".						
					//	"SUBSTRING(C.destino,1,9)='SERVICIOS' OR ".
					//	"SUBSTRING(C.destino,1,10)='SERVICIOS,' OR ".
					//	"SUBSTRING(C.destino,1,10)='PRODUCCION' OR ".
					//	"SUBSTRING(C.destino,1,11)='PRODUCCION,' OR ".
					//	"SUBSTRING(C.destino,1,8)='ECOLOGIA' OR ".
					//	"SUBSTRING(C.destino,1,9)='ECOLOGIA,' OR ".						
					//	"SUBSTRING(C.destino,1,8)='DERECHOS' OR ".
					//	"SUBSTRING(C.destino,1,9)='DERECHOS,' OR ".						
     				//	"SUBSTRING(C.destino,1,11)='PRESIDENCIA')".
					//	"SUBSTRING(C.destino,1,12)='PRESIDENCIA,')"; 
		//print $sql;		
		return $sql;
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
				
		
		if ($this->fec_presentacion_desde=='null') $this->fec_presentacion_desde='';
		if ($this->fec_sesion_desde=='null') $this->fec_sesion_desde='';
		if ($this->fec_presentacion_hasta=='null') $this->fec_presentacion_hasta='';
		if ($this->fec_sesion_hasta=='null') $this->fec_sesion_hasta='';
		if ($this->fec_aprobacion_desde=='null') $this->fec_aprobacion_desde='';
		if ($this->fec_aprobacion_hasta=='null') $this->fec_aprobacion_hasta='';

						
		if ($this->TieneValor($this->numero))
			$filtro .= " and ((numero=".$this->numero." and tipo<>'M') or (nro_municipal='".$this->numero."' and tipo='M'))";
			
		if ($this->TieneValor($this->tipo))
			$filtro .= " and tipo='".$this->tipo."'";
			
		if ($this->TieneValor($this->letra))
			$filtro .= " and letra='".$this->letra."'";
			
		if ($this->TieneValor($this->anio))
			$filtro .= " and anio='".$this->anio."'";
			
		if ($this->TieneValor($this->tipo_proy))
			$filtro .= " and tipo_proy='".$this->tipo_proy."'";
			
		if ($this->TieneValor($this->num_mensaje))
			$filtro .= " and num_mensaje='".$this->num_mensaje."'";
			
		if ($this->TieneValor($this->id_causante_txt))
			$filtro .= " and cau.causante like '%".$this->id_causante_txt."%'";
			
		if ($this->TieneValor($this->caratula))
			$filtro .= " and caratula like '%".$this->caratula."%'";
			
		if ($this->TieneValor($this->fec_presentacion_desde))
			$filtro .= " and fec_presentacion >= '".$this->fec_presentacion_desde."'";
			
		if ($this->TieneValor($this->fec_presentacion_hasta))
			$filtro .= " and fec_presentacion <= '".$this->fec_presentacion_hasta."'";
			
		if ($this->TieneValor($this->fec_sesion_desde))
			$filtro .= " and fec_sesion >= '".$this->fec_sesion_desde."'";
			
		if ($this->TieneValor($this->fec_sesion_hasta))
			$filtro .= " and fec_sesion <= '".$this->fec_sesion_hasta."'";
			
		if ($this->TieneValor($this->fec_aprobacion_desde))
			$filtro .= " and fec_aprobacion >= '".$this->fec_aprobacion_desde."'";
			
		if ($this->TieneValor($this->fec_aprobacion_hasta))
			$filtro .= " and fec_aprobacion <= '".$this->fec_aprobacion_hasta."'";
								
		if ($this->TieneValor($this->com_destino_txt))
			$filtro .= " and d1.destino like '".$this->com_destino_txt."%'";
			
		if ($this->TieneValor($this->id_ubicacion_actual_txt))
			$filtro .= " and d2.destino like '".$this->id_ubicacion_actual_txt."%'";

		if ($this->TieneValor($this->id_categoria_txt))
			$filtro .= " and cat.categoria like '".$this->id_categoria_txt."%'";
			
		if ($this->TieneValor($this->id_aprobacion_txt))
			$filtro .= " and ap.aprobacion like '%".$this->id_aprobacion_txt."%'";
			
		if ($this->TieneValor($this->tipo_aprobacion))
			$filtro .= " and tipo_aprobacion like '%".$this->tipo_aprobacion."%'";
			
		if ($this->TieneValor($this->agregados_txt))
			$filtro .= " and agregados like '%|".$this->agregados_txt."|%'";

		if ($this->TieneValor($this->tags))
			$filtro .= " and MATCH (tags) AGAINST ('{$this->tags}') ";
		
		$sql = "select e.*, cat.categoria, d1.destino as com_destino_txt, d2.destino as id_ubicacion_actual_txt, ap.aprobacion as id_aprobacion_txt, g.grupo, cau.causante as id_causante_txt ".
			   "from expedientes e ".
			   "left join destinos d1 on d1.id_destino=e.com_destino ".
			   "left join destinos d2 on d2.id_destino=e.id_ubicacion_actual ".
			   "left join categorias cat on cat.id_categoria=e.id_categoria ".
			   "left join formas_aprobacion ap on ap.id_aprobacion=e.id_aprobacion ".		
			   "left join causantes cau on cau.id_causante=e.id_causante ".			   	   
			   "left join grupos_impresion g on g.id_grupo=e.id_grupo ".
			   "left join tags t on t.numero_expediente=e.numero ".
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

	//public function SqlReporte($orden)
	public function SqlReporte()
	{				
		$sql = '';
		global $conn;
		$filtro = '';
		//$ordenar_por = '';
		
		
		//switch (strtoupper(trim($orden)))
		//{
		//	case 'GRUPO':
		//			$ordenar_por = 'order by g.orden, e.fec_presentacion, e.numero';
		//			break;

		//	case 'NUMERO':
		//			$ordenar_por = 'order by e.numero';
		//			break;
					
		//	default:
		//			$ordenar_por = 'order by e.numero';
		//			break;
		//}


		$this->LeerPost();
				
		
		if ($this->fec_presentacion_desde=='null') $this->fec_presentacion_desde='';
		if ($this->fec_presentacion_hasta=='null') $this->fec_presentacion_hasta='';

						
		if ($this->TieneValor($this->numero))
			$filtro .= " and ((numero=".$this->numero." and tipo<>'M') or (nro_municipal='".$this->numero."' and tipo='M'))";
			
		if ($this->flt_solo_sin_imprimir == 'S')
			$filtro .= " and (impreso <> 'S' or impreso is null)";
			
			
		if ($this->TieneValor($this->fec_presentacion_desde))
			$filtro .= " and fec_presentacion >= '".$this->fec_presentacion_desde."'";
			
		if ($this->TieneValor($this->fec_presentacion_hasta))
			$filtro .= " and fec_presentacion <= '".$this->fec_presentacion_hasta."'";
				
		
		
		$sql = "select e.*, cat.categoria, d1.destino as com_destino_txt, d2.destino as id_ubicacion_actual_txt, ap.aprobacion as id_aprobacion_txt, g.grupo, cau.causante as id_causante_txt ".
			   "from expedientes e ".
			   "left join destinos d1 on d1.id_destino=e.com_destino ".
			   "left join destinos d2 on d2.id_destino=e.id_ubicacion_actual ".
			   "left join categorias cat on cat.id_categoria=e.id_categoria ".
			   "left join formas_aprobacion ap on ap.id_aprobacion=e.id_aprobacion ".
			   "left join causantes cau on cau.id_causante=e.id_causante ".			   
			   "inner join grupos_impresion g on g.id_grupo=e.id_grupo ".
			   "where 1=1 ".$filtro." and e.tipo<>'I' ".
			   "order by g.orden, e.fec_presentacion, e.numero";
		
		
		return $sql;
	}


	private function LeerPost()
	{				
		$this->numero = str_replace('.', '', str_replace(',', '', $this->ValorPostNull('numero')));
		$this->tipo = $this->ValorPost('tipo');
		$this->letra = $this->ValorPost('letra');
		$this->anio = $this->ValorPost('anio');
		$this->nro_municipal = $this->ValorPost('nro_municipal');
		$this->tipo_proy = $this->ValorPost('tipo_proy');
		$this->num_mensaje = $this->ValorPost('num_mensaje');
		$this->id_causante_txt = $this->ValorPost('id_causante_txt');
		$this->id_causante = $this->ValorCausante('id_causante');
		$this->caratula = substr($this->ValorPost('caratula'), 0, 250);
		$this->fec_presentacion = $this->ValorPostFecha('fec_presentacion');
		$this->fec_sesion = $this->ValorPostFecha('fec_sesion');
		$this->fec_presentacion_desde = $this->ValorPostFecha('fec_presentacion_desde');
		$this->fec_sesion_desde = $this->ValorPostFecha('fec_sesion_desde');
		$this->fec_presentacion_hasta = $this->ValorPostFecha('fec_presentacion_hasta');
		$this->fec_sesion_hasta = $this->ValorPostFecha('fec_sesion_hasta');
		$this->com_destino = $this->ValorDestino('com_destino');
		$this->id_ubicacion_actual = $this->ValorDestino('id_ubicacion_actual');
		$this->com_destino_txt = $this->ValorPost('com_destino_txt');
		$this->id_ubicacion_actual_txt = $this->ValorPost('id_ubicacion_actual_txt');
		$this->id_categoria_txt = $this->ValorPost('id_categoria_txt');
		$this->id_categoria = $this->ValorCategoria('id_categoria');
		$this->id_aprobacion_txt = $this->ValorPost('id_aprobacion_txt');
		$this->id_aprobacion = $this->ValorAprobacion('id_aprobacion');
		$this->fec_aprobacion = $this->ValorPostFecha('fec_aprobacion');
		$this->fec_aprobacion_desde = $this->ValorPostFecha('fec_aprobacion_desde');
		$this->fec_aprobacion_hasta = $this->ValorPostFecha('fec_aprobacion_hasta');
		$this->tipo_aprobacion = $this->ValorPost('tipo_aprobacion');
		$this->agregados = $this->ValorPost('hdnAgregados');
		$this->agregados_txt = $this->ValorPost('agregados_txt');
		$this->impreso = $this->ValorPost('impreso');
		$this->flt_solo_sin_imprimir = $this->ValorPost('flt_solo_sin_imprimir');
		$this->id_grupo = $this->ValorPost('id_grupo');
		$this->tags =  $this->ValorPost('tags');
		$this->decretos =  $this->ValorPost('decretos');
		$this->declaraciones =  $this->ValorPost('declaraciones');
		$this->minutas =  $this->ValorPost('minutas');
		$this->ordenanzas_y_resoluciones =  $this->ValorPost('ordenanzas_y_resoluciones');

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
		if ($this->numero == '' || $this->numero == 'null')
		{
			$this->fec_alta = "'".date('Y-m-d H:i:s')."'";

			$sql = "INSERT INTO expedientes ".
				   "(tipo, letra, anio, nro_municipal, tipo_proy, num_mensaje, id_causante, caratula, fec_presentacion, ".
				   "fec_sesion, com_destino, id_ubicacion_actual, ".
				   "fec_aprobacion, id_aprobacion, tipo_aprobacion, id_usuario_alta, fec_alta, fec_mod, agregados, id_categoria, impreso, id_grupo, ".
				   "decretos, declaraciones, minutas, ordenanzas_y_resoluciones) " .
				   "VALUES ".
				   "('$this->tipo', '$this->letra', '$this->anio', '$this->nro_municipal', '$this->tipo_proy', '$this->num_mensaje', $this->id_causante, '$this->caratula', $this->fec_presentacion, ".
				   "$this->fec_sesion, $this->com_destino, $this->id_ubicacion_actual, ".
				   "$this->fec_aprobacion, $this->id_aprobacion, '$this->tipo_aprobacion', '$this->id_usuario_alta', $this->fec_alta, $this->fec_mod, '$this->agregados', $this->id_categoria, '$this->impreso', $this->id_grupo, ".
				   "'$this->decretos', '$this->declaraciones', '$this->minutas', '$this->ordenanzas_y_resoluciones')";

			if (!$conn->Execute($sql))
			{
				echo $conn->ErrorMsg();
			}
			$this->numero = $conn->Insert_ID();
		}
		else
		{
			//guardo una copia en historico		
			$sql = "INSERT INTO expedientes_historico ".
				   "(numero, tipo, letra, anio, nro_municipal, tipo_proy, num_mensaje, id_causante, caratula, fec_presentacion, ".
				   "fec_sesion, com_destino, id_ubicacion_actual, ".
				   "fec_aprobacion, id_aprobacion, tipo_aprobacion, id_usuario_alta, fec_alta, fec_mod, agregados, id_usuario_mod, id_categoria, impreso, id_grupo, " . 
				   "decretos, declaraciones, minutas, ordenanzas_y_resoluciones) ".
				   "SELECT ".
				   "numero, tipo, letra, anio, nro_municipal, tipo_proy, num_mensaje, id_causante, caratula, fec_presentacion, ".
				   "fec_sesion, com_destino, id_ubicacion_actual, ".
				   "fec_aprobacion, id_aprobacion, tipo_aprobacion, id_usuario_alta, fec_alta, fec_mod, agregados, id_usuario_mod, id_categoria, impreso, id_grupo, ".
				   "decretos, declaraciones, minutas, ordenanzas_y_resoluciones " . 
				   "from expedientes where numero=$this->numero";

			$conn->Execute($sql);
			$this->fec_mod = "'".date('Y-m-d H:i:s')."'";
			$sql = "UPDATE expedientes SET ".
						   "tipo='$this->tipo', ".
						   "letra='$this->letra', ".
						   "anio='$this->anio', ".
						   "nro_municipal='$this->nro_municipal', ".
						   "tipo_proy='$this->tipo_proy', ".
						   "num_mensaje='$this->num_mensaje', ".
						   "id_causante=$this->id_causante, ".
						   "caratula='$this->caratula', ".
						   "fec_presentacion=$this->fec_presentacion, ".
						   "fec_sesion=$this->fec_sesion, ".
						   "com_destino=$this->com_destino, ".
						   "id_ubicacion_actual=$this->id_ubicacion_actual, ".
						   "id_categoria=$this->id_categoria, ".						   
						   "fec_aprobacion=$this->fec_aprobacion, ".
						   "id_aprobacion=$this->id_aprobacion, ".
						   "tipo_aprobacion='$this->tipo_aprobacion', ".
						   "impreso='$this->impreso', ".
						   "id_grupo=$this->id_grupo, ".
						   "id_usuario_alta=$this->id_usuario_alta, ".
						   "id_usuario_mod=$this->id_usuario_mod, ".
						   "fec_mod=$this->fec_mod, ".
						   "agregados='$this->agregados', ".
						   "decretos='$this->decretos', ".
						   "declaraciones='$this->declaraciones', ".
						   "minutas='$this->minutas', ".
						   "ordenanzas_y_resoluciones='$this->ordenanzas_y_resoluciones' ".
						   "where numero=$this->numero";
			
			$registro_nuevo = 0;
			$conn->Execute($sql);
			
		}
		
		if($this->tipo == 'L') {
			$mod_time = time();
			
//			$id_causante_txt = '';
//			if($this->id_causante != '') {
//				$rs = $conn->Execute("SELECT causante FROM causantes WHERE id_causante=" . $this->id_causante);
//				if(!$rs->EOF)
//					$id_causante_txt = $rs->fields['causante']; 
//			}
//			
//			$id_ubicacion_actual_txt = '';
//			if($this->id_ubicacion_actual != '') {
//				$rs = $conn->Execute("SELECT destino FROM destinos WHERE id_destino=" . $this->id_ubicacion_actual);
//				if(!$rs->EOF)
//					$id_ubicacion_actual_txt = $rs->fields['destino']; 
//			}

			$this->CargarExpediente($this->numero);
			
//			if($registro_nuevo == 1) {
//				$sqlTemp = "INSERT INTO expedientes_legislativos ".
//				   "(numero, tipo_proy, fec_presentacion, fec_aprobacion, tipo_aprobacion, " .
//				   "caratula, id_causante, id_causante_txt, id_ubicacion_actual, id_ubicacion_actual_txt" .
//				   ", fec_alta) ".
//				   "VALUES ".
//				   "($this->numero, '$this->tipo_proy', $this->fec_presentacion, $this->fec_aprobacion, '$this->tipo_aprobacion', " .
//				   "'$this->caratula', $this->id_causante, '$id_causante_txt', $this->id_ubicacion_actual, '$id_ubicacion_actual_txt' " .
//					", $this->fec_alta)";
//			} else {
//				$sqlTemp = "UPDATE expedientes_legislativos SET ".
//						   "tipo_proy='$this->tipo_proy', ".
//						   "fec_presentacion='$this->fec_presentacion', ".
//						   "fec_aprobacion='$this->fec_aprobacion', ".
//						   "tipo_aprobacion='$this->tipo_aprobacion', ".
//						   "caratula='$this->caratula', ".
//						   "id_causante='$this->id_causante', ".
//						   "id_causante_txt='$id_causante_txt', ".
//						   "id_ubicacion_actual='$this->id_ubicacion_actual', ".
//						   "id_ubicacion_actual_txt='$id_ubicacion_actual_txt' ".
//						   "WHERE numero=$this->numero";
//			}

			if($registro_nuevo == 1) {
				$sqlTemp = "INSERT INTO expedientes_legislativos ".
					"(`numero`, `letra`, `anio`, `num_mensaje`, `tipo_proy`, `fec_presentacion`, `fec_sesion`, `fec_aprobacion`, " .
					"`caratula`, `tipo_aprobacion`, `id_causante`, `id_causante_txt`, `com_destino`, `com_destino_txt`, `id_aprobacion`, " .
					"`id_aprobacion_txt`, `id_ubicacion_actual`, `id_ubicacion_actual_txt`, `id_grupo`, `grupo`, `fec_alta`," . 
				    "decretos, declaraciones, minutas, ordenanzas_y_resoluciones) ".
				   "VALUES ".
				   "($this->numero, '$this->letra', '$this->anio', '$this->num_mensaje', '$this->tipo_proy', '$this->fec_presentacion', '$this->fec_sesion', '$this->fec_aprobacion', " .    
					"'$this->caratula', '$this->tipo_aprobacion', '$this->id_causante', '$this->id_causante_txt', '$this->com_destino', '$this->com_destino_txt', '$this->id_aprobacion_txt', " .
					"'$this->id_aprobacion_txt', '$this->id_ubicacion_actual', '$this->id_ubicacion_actual_txt', '$this->id_grupo', '$this->grupo', '$this->fec_alta', " .
					"'$this->decretos', '$this->declaraciones', '$this->minutas', '$this->ordenanzas_y_resoluciones')";
			} else {
				$sqlTemp = "UPDATE expedientes_legislativos SET ".
						   "letra='$this->letra', " .
						   "anio='$this->anio', " .
						   "num_mensaje='$this->num_mensaje', " . 
						   "tipo_proy='$this->tipo_proy', ".
						   "fec_presentacion='$this->fec_presentacion', ".
						   "fec_sesion='$this->fec_sesion', ".
						   "fec_aprobacion='$this->fec_aprobacion', ".
						   "caratula='$this->caratula', ".
						   "tipo_aprobacion='$this->tipo_aprobacion', ".
						   "id_causante='$this->id_causante', ".
						   "id_causante_txt='$this->id_causante_txt', ".
						   "com_destino='$this->com_destino', ".
						   "com_destino_txt='$this->com_destino_txt', ".
						   "id_aprobacion='$this->id_aprobacion', ".
						   "id_aprobacion_txt='$this->id_aprobacion_txt', ".
						   "id_ubicacion_actual='$this->id_ubicacion_actual', ".
						   "id_ubicacion_actual_txt='$this->id_ubicacion_actual_txt', ".
						   "id_grupo='$this->id_grupo', ".
						   "grupo='$this->grupo', ".
						   "decretos='$this->decretos', ".
						   "declaraciones='$this->declaraciones', ".
						   "minutas='$this->minutas', ".
						   "ordenanzas_y_resoluciones='$this->ordenanzas_y_resoluciones' ".
						   "WHERE numero=$this->numero";
			}
			
			$conn2 =& ADONewConnection('mysql');

			$error = true;
			if($conn2->Connect($conn_web_server, $conn_web_user, $conn_web_pass, $conn_web_bd)) {
				 if($conn2->Execute($sqlTemp))
					$error = false;				 	
			}
			$conn2->debug=0;

			if ($error) {
				// Si hay un error, guarda los cambios en una tabla temporal.
				$sql = 	"INSERT INTO expedientes_temporal " .
					"(registro_nuevo, `numero`, `letra`, `anio`, `num_mensaje`, `tipo_proy`, `fec_presentacion`, `fec_sesion`, `fec_aprobacion`, " .
					"`caratula`, `tipo_aprobacion`, `id_causante`, `id_causante_txt`, `com_destino`, `com_destino_txt`, `id_aprobacion`, " .
					"`id_aprobacion_txt`, `id_ubicacion_actual`, `id_ubicacion_actual_txt`, `id_grupo`, `grupo`, `fec_alta`, " . 
					"decretos, declaraciones, minutas, ordenanzas_y_resoluciones) ".
				   "VALUES ".
				   "($registro_nuevo, $this->numero, '$this->letra', '$this->anio', '$this->num_mensaje', '$this->tipo_proy', '$this->fec_presentacion', '$this->fec_sesion', '$this->fec_aprobacion', " .    
					"'$this->caratula', '$this->tipo_aprobacion', '$this->id_causante', '$this->id_causante_txt', '$this->com_destino', '$this->com_destino_txt', '$this->id_aprobacion_txt', " .
					"'$this->id_aprobacion_txt', '$this->id_ubicacion_actual', '$this->id_ubicacion_actual_txt', '$this->id_grupo', '$this->grupo', '$this->fec_alta', " .
					"'$this->decretos', '$this->declaraciones', '$this->minutas', '$this->ordenanzas_y_resoluciones')";
				$conn->Execute($sql);
				//echo $conn->ErrorMsg();
			
			}

		}
		$this->RelacionarAgregados();
		$conn->CompleteTrans(true);
	}
	
	public function GrabarTags() {
		$sql;
		global $conn;
		
		$this->LeerPost();
		
		$conn->StartTrans();
		//hago un insert o un update dependiendo de si tengo cargado uno o no
		//guardo una copia en historico		
		$sql = "INSERT INTO expedientes_historico ".
			   "(numero, tipo, letra, anio, nro_municipal, tipo_proy, num_mensaje, id_causante, caratula, fec_presentacion, ".
			   "fec_sesion, com_destino, id_ubicacion_actual, ".
			   "fec_aprobacion, id_aprobacion, tipo_aprobacion, id_usuario_alta, fec_alta, fec_mod, agregados, id_usuario_mod, id_categoria, impreso, id_grupo) ".
			   "SELECT ".
			   "numero, tipo, letra, anio, nro_municipal, tipo_proy, num_mensaje, id_causante, caratula, fec_presentacion, ".
			   "fec_sesion, com_destino, id_ubicacion_actual, ".
			   "fec_aprobacion, id_aprobacion, tipo_aprobacion, id_usuario_alta, fec_alta, fec_mod, agregados, id_usuario_mod, id_categoria, impreso, id_grupo ".
			   "from expedientes where numero=$this->numero";

		$conn->Execute($sql);

		$this->fec_mod = "'".date('Y-m-d H:i:s')."'";
		$sql = "UPDATE expedientes SET ".
						   "fec_mod=$this->fec_mod, ".
						   "where numero=$this->numero";
		$conn->Execute($sql);

		$this->guardarTags();
		$conn->CompleteTrans(true);
	}
	
	
	private function RelacionarAgregados()
	{
		global $conn;
		
		//primero desrelaciono todos de este
		$sql = "update expedientes set agregados=replace(agregados, '|".$this->numero."|', '|') where agregados like '%|".$this->numero."|%'";
		$conn->Execute($sql);
		
	
		//luego relaciono los que van
		if ($this->agregados != '' && $this->agregados != '|')
		{
			$vec = explode('|', $this->agregados);
			for ($i=0; $i <= sizeof($vec); $i++)
			{
				if ($vec[$i] != '' && $vec[$i] != 0)
				{
				
					$sql = "update expedientes set agregados= concat(if (agregados is null, '', agregados),'|".$this->numero."|') where numero=".$vec[$i];
					$conn->Execute($sql);			
				}
			}
		}
		
		//arreglo por si me quedo '||'
		$sql = "update expedientes set agregados=replace(agregados, '||', '|')";
		$conn->Execute($sql);

		//arreglo por si me quedo solo un '|'
		$sql = "update expedientes set agregados='' where agregados='|'";
		$conn->Execute($sql);


		
	}
	
	public function MarcarImpreso()
	{
		global $conn;
		
		$conn->Execute("update expedientes set impreso='S' where numero=".$this->numero);
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

	//public function ValorGetFecha($valor)
	//{
	//	if (isset($_GET[$valor]))
	//		if ($_GET[$valor]=='' || $_GET[$valor]=="0")
	//			return 'null';
	//		else
	//			return utf8_dec($_GET[$valor]);
	//	else
	//		return 'null';
	//}
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
	
	public function ValorDestino($valor)
	{
		global $conn;
		
		if (isset($_POST[$valor]))
		{
			$valor_id = utf8_decode($_POST[$valor]);
			$valor_txt = str_replace("'", "''", strtoupper(utf8_dec(trim($_POST[$valor.'_txt']))));
			
			if ($valor_txt == '')
				return 'null';
			else
			{
				//me fijo si lo encuentro, si no lo doy de alta
				$rs = $conn->Execute("select id_destino from destinos where upper(destino)='".strtoupper($valor_txt)."'");
				if (!$rs->EOF)
					return $rs->fields['id_destino'];
				else
				{
					$conn->Execute("insert into destinos (destino) values ('".$valor_txt."')");
					return $conn->Insert_ID();
				}
			}
		}
		else
			return '';
	}
	
	public function buscarFormasAprobacion() {
		
		global $conn;
		
		if(trim($_POST['palabra']) == '' && trim($_POST['numero']) == '')
			return false;
		
		$numero = strtoupper($_POST['numero']);
		$palabra = strtoupper($_POST['palabra']);
		
		$sql = 	"SELECT a.numero AS numero, " .  
				"a.fec_aprobacion AS fec_aprobacion, " .  
				"a.fec_presentacion AS fec_presentacion,  " . 
				"a.tipo_aprobacion AS tipo_aprobacion, " . 
				"a.tipo_aprobacion AS forma_aprobacion " . 
				"FROM expedientes AS a  " . 
				//"LEFT JOIN formas_aprobacion AS b ON b.id_aprobacion = a.id_aprobacion " .
				"WHERE a.tipo_aprobacion LIKE '%{$numero}%' " .
				"AND a.tipo_aprobacion LIKE '%{$palabra}%' ";
		
		$rs = $conn->Execute($sql);
	
		return $rs;
		
	}
	
	public function guardarMovimientoMasivos() {
		global $conn;
		
		if(!isset($_POST['numero']))
			return;

		$fecha = $this->ValorPostFecha('fecha');
		$comentarios = $this->ValorPost('comentarios');
		$id_ubicacion_actual = $this->ValorDestino('id_ubicacion_actual');

		if($id_ubicacion_actual!='') {

			$sql = Array();	
			
			foreach($_POST['numero'] as $numero) {
				$numero = trim($numero);
				$numero = str_replace('.', '', $numero);
				$numero = str_replace(',', '', $numero);
				$numero = str_replace('-', '', $numero);

				$sql[] = "($numero, $id_ubicacion_actual, $fecha, '$comentarios')";
			}

			$sql = implode(",",$sql);
			$sql = "INSERT INTO movimientos (numero, id_ubicacion_actual, fecha, comentario) ".
				   "VALUES " . $sql;
			
			$conn->Execute($sql);	
		}			
	}
	
	public function guardarUbicacionActualMasivos() {
		global $conn;
		
		if(!isset($_POST['numero']))
			return;
			
		$id_ubicacion_actual = $this->ValorDestino('id_ubicacion_actual');

		if($id_ubicacion_actual!='') {

			$sql = Array();	
			
			foreach($_POST['numero'] as $numero) {
				$numero = trim($numero);
				$numero = str_replace('.', '', $numero);
				$numero = str_replace(',', '', $numero);
				$numero = str_replace('-', '', $numero);

				$sql[] = $numero;
			}

			$sql = implode(",",$sql);
			$sql = "UPDATE expedientes SET id_ubicacion_actual={$id_ubicacion_actual} WHERE numero IN ({$sql}) ";
				
			$conn->Execute($sql);
			
			return true;
		}
		
	}
	
	private function ValorCategoria($valor)
	{
		global $conn;
		
		
		if (isset($_POST[$valor]))
		{
			$valor_id = utf8_decode($_POST[$valor]);
			$valor_txt = str_replace("'", "''", strtoupper(utf8_dec(trim($_POST[$valor.'_txt']))));
			
			if ($valor_txt == '')
				return 'null';
			else
			{
				//me fijo si lo encuentro, si no lo doy de alta
				$rs = $conn->Execute("select id_categoria from categorias where upper(categoria)='".strtoupper($valor_txt)."'");
				if (!$rs->EOF)
					return $rs->fields['id_categoria'];
				else
				{
					$conn->Execute("insert into categorias (categoria) values ('".$valor_txt."')");
					return $conn->Insert_ID();
				}
			}
		}
		else
			return '';
	}


	private function ValorAprobacion($valor)
	{
		global $conn;
		
		if (isset($_POST[$valor]))
		{
			$valor_id = utf8_decode($_POST[$valor]);
			$valor_txt = str_replace("'", "''", strtoupper(utf8_dec(trim($_POST[$valor.'_txt']))));
			
			if ($valor_txt == '')
				return 'null';
			else
			{
				//me fijo si lo encuentro, si no lo doy de alta
				$rs = $conn->Execute("select id_aprobacion from formas_aprobacion where upper(aprobacion)='".strtoupper($valor_txt)."'");
				if (!$rs->EOF)
					return $rs->fields['id_aprobacion'];
				else
				{
					$conn->Execute("insert into formas_aprobacion (aprobacion) values ('".$valor_txt."')");
					return $conn->Insert_ID();
				}
			}
		}
		else
			return '';
	}


	private function ValorCausante($valor)
	{
		global $conn;
		
		if (isset($_POST[$valor]))
		{
			$valor_id = utf8_decode($_POST[$valor]);
			$valor_txt = str_replace("'", "''", strtoupper(utf8_dec(trim($_POST[$valor.'_txt']))));
			
			if ($valor_txt == '')
				return 'null';
			else
			{
				//me fijo si lo encuentro, si no lo doy de alta
				$rs = $conn->Execute("select id_causante from causantes where upper(causante)='".strtoupper($valor_txt)."'");
				if (!$rs->EOF)
					return $rs->fields['id_causante'];
				else
				{
					$conn->Execute("insert into causantes (causante) values ('".$valor_txt."')");
					return $conn->Insert_ID();
				}
			}
		}
		else
			return '';
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
		
		// Primero debe borrar los tags anteriores.
		$rs = $conn->Execute("DELETE FROM `tags` WHERE `numero_expediente`= {$this->numero}");
		
		$tags = explode(' ',$this->tags);
		
		if(count($tags) > 0 ) {
			foreach($tags as $tag) {
				if(strlen($tag) > 3) 
					$tagSave .= $tag." ";
			}
			
			if(strlen($tagSave)>0) {
				$sql = "INSERT INTO `tags` (`numero_expediente`, `tags`) VALUES ({$this->numero}, '{$tagSave}')";
				$rs = $conn->Execute($sql);
			}
		}
	}
}

?>