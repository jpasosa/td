<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Trabajos_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->config('estados');
	}

	public function validarNuevo($trabajo)
	{
		$errors = false;

		$valid_files = array('application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
							'application/vnd.oasis.opendocument.text', 'application/pdf');

		// VALIDO POR LA EXTENSION DEL ARCHIVO SUBIDO -- ARCHIVO PRIVADO
		if ( isset($_FILES['archivo_privado']['type']) && $_FILES['archivo_privado']['type'] != '')
		{
			if (!in_array($_FILES['archivo_privado']['type'], $valid_files)) {
				$errors['valid_file_privado'] = 'El archivo privado debe ser formato doc, pdf, odt';
			}
		}
		// VALIDO POR LA EXTENSION DEL ARCHIVO SUBIDO -- ARCHIVO PUBLICO
		if ( isset($_FILES['archivo_publico']['type']) && $_FILES['archivo_publico']['type'] != '')
		{
			if (!in_array($_FILES['archivo_publico']['type'], $valid_files)) {
				$errors['valid_file_privado'] = 'El archivo publico debe ser formato doc, pdf, odt';
			}
		}
		// VALIDO POR LA EXTENSION DEL ARCHIVO SUBIDO -- ARCHIVO VISTA PREVIA
		if ( isset($_FILES['archivo_vista_previa']['type']) && $_FILES['archivo_vista_previa']['type'] != '')
		{
			if (!in_array($_FILES['archivo_vista_previa']['type'], $valid_files)) {
				$errors['valid_file_privado'] = 'El archivo de vista previa debe ser formato doc, pdf, odt';
			}
		}



		if(!isset($trabajo['titulo'])) {
			$errors['titulo'] = 'El titulo es obligatorio';
		}

		if(!isset($trabajo['texto'])) {
			$errors['texto'] = 'El texto es obligatorio';
		}

		if(!isset($trabajo['resumen'])) {
			$errors['resumen'] = 'El resumen es obligatorio';
		}

		if(!isset($trabajo['idCategorias_parentId'])){
			$errors['idCategorias_parentId'] = 'Debe elegir una categoría';
		}

		if (isset($trabajo['texto']))
		{
			$cant_palabras_texto = str_word_count($trabajo['texto']);
			if($cant_palabras_texto < 70) {
				$errors['texto_minimo'] = 'La descripción debe tener como mínimo 70 palabras';
			}
		}

		if (isset($trabajo['resumen']))
		{
			$cant_palabras_resumen = str_word_count($trabajo['resumen']);
			if($cant_palabras_resumen < 70) {
				$errors['resumen_minimo'] = 'El resumen debe tener como mínimo 70 palabras';
			}
		}




		return $errors;
	}

	public function validarEditar($trabajo)
	{
		$errors = false;


		$valid_files = $this->config->item('format_validations');
		// VALIDO POR LA EXTENSION DEL ARCHIVO SUBIDO
		if ( isset($_FILES['archivo_privado']['type']) && $_FILES['archivo_privado']['type'] != '')
		{
			if (!in_array($_FILES['archivo_privado']['type'], $valid_files)) {
				$errors['valid_file_privado'] = 'El archivo privado debe ser formato doc, pdf, odt';
			}
		}
		// VALIDO POR LA EXTENSION DEL ARCHIVO SUBIDO
		if ( isset($_FILES['archivo_publico']['type']) && $_FILES['archivo_publico']['type'] != '')
		{
			if (!in_array($_FILES['archivo_publico']['type'], $valid_files)) {
				$errors['valid_file_privado'] = 'El archivo publico debe ser formato doc, pdf, odt';
			}
		}
		// VALIDO POR LA EXTENSION DEL ARCHIVO SUBIDO -- ARCHIVO VISTA PREVIA
		if ( isset($_FILES['archivo_vista_previa']['type']) && $_FILES['archivo_vista_previa']['type'] != '')
		{
			if (!in_array($_FILES['archivo_vista_previa']['type'], $valid_files)) {
				$errors['valid_file_privado'] = 'El archivo de vista previa debe ser formato doc, pdf, odt';
			}
		}


		if(!isset($trabajo['titulo'])){
			$errors['titulo'] = 'El titulo es obligatorio';
		}

		if(!isset($trabajo['texto'])) {
			$errors['texto'] = 'El texto es obligatorio';
		}

		if(!isset($trabajo['idCategorias_parentId'])){
			$errors['idCategorias_parentId'] = 'Debe elegir una categoría';
		}

		return $errors;
	}

	public function nuevo($trabajo)
	{
		try
		{

			if(checkRol('administrador', $this->session)){
				if(!isset($trabajo['estado'])){
					$trabajo['estado'] = 1;
				}

				if(!isset($trabajo['precio_sin_derecho'])){
					$trabajo['precio_sin_derecho'] = 0;
				}
				if(!isset($trabajo['precio_con_derecho'])){
					$trabajo['precio_con_derecho'] = 0;
				}
				if(!isset($trabajo['monto_por_venta'])){
					$trabajo['monto_por_venta'] = 0;
				}
			}else{ // es un USER comun. NO es ADMINISTRADOR, después va a cargar el admin estos precios.
				$trabajo['monto_por_venta']		= 0;
				$trabajo['precio_sin_derecho']	= 0;
				$trabajo['precio_con_derecho']	= 0;
				$trabajo['idEstados']				= 1; // estado PENDIENTE
			}

			$this->db->trans_begin();

			$data = array(
					'idUsuarios' 				=> $trabajo['idUsuarios'],
					'texto' 					=> $trabajo['texto'],
					'titulo'  					=> $trabajo['titulo'],
					'resumen' 				=> $trabajo['resumen'],
					'fecha' 					=> date('Y-m-d',strtotime($trabajo['fecha'])),
					'palabrasClave' 			=> $trabajo['palabrasClave'],
					'monto_por_venta'  		=> $trabajo['monto_por_venta'],
					'precio_sin_derecho' 	=> $trabajo['precio_sin_derecho'],
					'precio_con_derecho' 	=> $trabajo['precio_con_derecho'],
					'destacado' 				=> $trabajo['destacado'],
					'idCategorias_parentId' 	=> $trabajo['idCategorias_parentId'],
					'cantidadPalabras' 		=> $trabajo['cantidadPalabras'],
					'indice' 					=> $trabajo['indice'],
					'foto' 					=> $this->guardarFoto($trabajo['foto']),
					'archivo_publico' 		=> $this->guardarArchivo('archivo_publico',$trabajo['archivo_publico']),
					'archivo_privado' 		=> $this->guardarArchivo('archivo_privado',$trabajo['archivo_privado']),
					'archivo_vista_previa' 	=> $this->guardarArchivo('archivo_vista_previa',$trabajo['archivo_vista_previa']),
					'idEstados' 				=> $trabajo['idEstados'],
					'idPrecios' 				=> $trabajo['idPrecios']
					);
			$this->db->insert('Trabajos',$data);



			$trabajo['idTrabajos'] = $this->db->insert_id();
			if(isset($trabajo['idTrabajos']) && $trabajo['idTrabajos'] > 0) {
				if(isset($trabajo['idCategorias']) && sizeof($trabajo['idCategorias']) > 0) {
					foreach($trabajo['idCategorias'] as $idCategorias) {
						$data = array(
								'idCategorias' => $idCategorias,
								'idTrabajos' => $trabajo['idTrabajos']
								);
						$this->db->insert('TrabajosCategorias',$data);
					}
				}
			}

			if($this->db->trans_status()) {
				$this->db->trans_commit();
				return true;
			} else {
				$this->db->trans_rollback();
				return false;
			}

		} catch (Exception $e) {
			$this->db->trans_rollback();
			return false;
		}
	}

	public function editar($trabajo)
	{

		try {

			$this->db->trans_begin();

			// $indice = isset($trabajo['indice']) ? $trabajo['indice']  : "";


			// if(isset($trabajo['removeIndice']) && !empty($trabajo['removeIndice'])){
			// 	$this->eliminarIndice($trabajo['removeIndice']);
			// 	$indice = "";
			// 	$this->db->set('indice',"");
			// }

			// if(isset($trabajo['indice']) && !empty($trabajo['indice'])){
			// 	if(isset($trabajo['ori_indice'])){
			// 		$this->eliminarIndice($trabajo['ori_indice']);
			// 	}
			// 	$indice = $this->guardarIndice($trabajo['indice']);
			// 	$this->db->set('indice',$indice);
			// } elseif(isset($trabajo['ori_indice'])){
			// 	//$this->eliminarIndice($trabajo['ori_indice']);
			// 	//$indice = "";
			// 	//$this->db->set('indice',"");
			// }

			$foto = isset($trabajo['foto']) ? $trabajo['foto'] : "";
			if(isset($trabajo['removeFoto']) && !empty($trabajo['removeFoto'])){
				$this->eliminarFoto($trabajo['removeFoto']);
				//$foto = "";
				$this->db->set('foto',"");
			}

			if(isset($trabajo['foto']) && !empty($trabajo['foto'])){
				if(isset($trabajo['ori_foto'])){
					$this->eliminarFoto($trabajo['ori_foto']);
				}
				$foto = $this->guardarFoto($trabajo['foto']);
				$this->db->set('foto',$foto);
			} elseif(isset($trabajo['ori_foto'])){
				//$this->eliminarFoto($trabajo['ori_foto']);
				//$foto = "";
				//$this->db->set('foto',"");
			}


			// ARCHIVO PUBLICO
			$archivo_publico = isset($trabajo['archivo_publico']) ? $trabajo['archivo_publico'] : "";
			if(isset($trabajo['removearchivo_publico']) && !empty($trabajo['removearchivo_publico'])){
				$this->eliminarArchivo($trabajo['removearchivo_publico']);
				//$archivo_publico = "";
				$this->db->set('archivo_publico',"");
			}
			if(isset($trabajo['archivo_publico']) && !empty($trabajo['archivo_publico'])){
				if(isset($trabajo['ori_archivo_publico'])){
					$this->eliminarArchivo($trabajo['ori_archivo_publico']);
				}
				$archivo_publico = $this->guardarArchivo('archivo_publico',$trabajo['archivo_publico']);
				$this->db->set('archivo_publico',$archivo_publico);
			} elseif(isset($trabajo['ori_archivo_publico'])){
				//$this->eliminarArchivo($trabajo['ori_archivo_publico']);
				//$archivo_publico = "";
				//$this->db->set('archivo_publico',"");
			}


			// ARCHIVO VISTA PREVIA
			$archivo_vista_previa = isset($trabajo['archivo_vista_previa']) ? $trabajo['archivo_vista_previa'] : "";
			if(isset($trabajo['removearchivo_vista_previa']) && !empty($trabajo['removearchivo_vista_previa'])) {
				$this->eliminarArchivo($trabajo['removearchivo_vista_previa']);
				//$archivo_publico = "";
				$this->db->set('archivo_vista_previa',"");
			}
			if(isset($trabajo['archivo_vista_previa']) && !empty($trabajo['archivo_vista_previa'])){
				if(isset($trabajo['ori_archivo_vista_previa'])){
					$this->eliminarArchivo($trabajo['ori_archivo_vista_previa']);
				}
				$archivo_vista_previa = $this->guardarArchivo('archivo_vista_previa',$trabajo['archivo_vista_previa']);
				$this->db->set('archivo_vista_previa',$archivo_vista_previa);
			} elseif(isset($trabajo['ori_archivo_vista_previa'])){
				//$this->eliminarArchivo($trabajo['ori_archivo_publico']);
				//$archivo_publico = "";
				//$this->db->set('archivo_publico',"");
			}


			// ARCHIVO PRIVADO
			$archivo_privado = isset($trabajo['archivo_privado']) ? $trabajo['archivo_privado'] : "";
			if(isset($trabajo['removearchivo_privado']) && !empty($trabajo['removearchivo_privado'])){
				$this->eliminarArchivo($trabajo['removearchivo_privado']);
				$this->db->set('archivo_privado',"");
			}

			if(isset($trabajo['archivo_privado']) && !empty($trabajo['archivo_privado'])){
				if(isset($trabajo['ori_archivo_privado'])){
					$this->eliminarArchivo($trabajo['ori_archivo_privado']);
				}
				$archivo_privado = $this->guardarArchivo('archivo_privado',$trabajo['archivo_privado']);
				$this->db->set('archivo_privado',$archivo_privado);
			} elseif(isset($trabajo['ori_archivo_privado'])){
				//$this->eliminarArchivo($trabajo['ori_archivo_privado']);
				//$archivo_privado = "";
				//$this->db->set('archivo_privado',$archivo_privado);
			}

			if(checkRol('administrador', $this->session)) {
				if(!isset($trabajo['estado'])){
					$trabajo['estado'] = 1;
				}

				if(!isset($trabajo['precio_sin_derecho'])){
					$trabajo['precio_sin_derecho'] = 0;
				}
				if(!isset($trabajo['precio_con_derecho'])){
					$trabajo['precio_con_derecho'] = 0;
				}
				if(!isset($trabajo['monto_por_venta'])){
					$trabajo['monto_por_venta'] = 0;
				}
			}

			if(!isset($trabajo['indice'])) {
				$trabajo['indice'] = '';
			}

			if(checkRol('administrador', $this->session)) // ADMINISTRADOR
			{
				$data = array(
						'idUsuarios' 			=> $trabajo['idUsuarios'],
						'texto' 				=> $trabajo['texto'],
						'titulo'  				=> $trabajo['titulo'],
						'resumen' 			=> $trabajo['resumen'],
						'fecha'	 			=> date('Y-m-d',strtotime($trabajo['fecha'])),
						'palabrasClave' 		=> $trabajo['palabrasClave'],
						'monto_por_venta'  => $trabajo['monto_por_venta'],
						'precio_sin_derecho' => $trabajo['precio_sin_derecho'],
						'precio_con_derecho' => $trabajo['precio_con_derecho'],
						'destacado' 			=> $trabajo['destacado'],
						'idCategorias_parentId' => $trabajo['idCategorias_parentId'],
						'cantidadPalabras' 	=> $trabajo['cantidadPalabras'],
						'indice' 				=> $trabajo['indice'],
						//'archivo_publico' => $archivo_publico,
						//'archivo_privado' => $archivo_privado,
						//'foto' => $foto,
						'idEstados' 			=> $trabajo['idEstados'],
						'notificado' 			=> $trabajo['notificado']
						// 'idPrecios' 			=> $trabajo['idPrecios']
				);
			}else{ 	// USER COMUN
				$data = array(
						// 'idUsuarios' => $trabajo['idUsuarios'],
						'texto' 				=> $trabajo['texto'],
						'titulo'  				=> $trabajo['titulo'],
						'resumen' 			=> $trabajo['resumen'],
						'fecha' 				=> date('Y-m-d',strtotime($trabajo['fecha'])),
						// 'palabrasClave' => $trabajo['palabrasClave'],
						'destacado' 			=> $trabajo['destacado'],
						'idCategorias_parentId' => $trabajo['idCategorias_parentId'],
						'cantidadPalabras' 	=> $trabajo['cantidadPalabras'],
						'indice' 				=> $trabajo['indice'],
						'archivo_publico' 	=> $archivo_publico,
						//'archivo_privado' => $archivo_privado,
						'foto' 				=> $foto,
						// 'idPrecios' 			=> $trabajo['idPrecios']
				);
			}

			$this->db->where('idTrabajos',$trabajo['idTrabajos']);
			$this->db->update('Trabajos', $data);

			if(isset($trabajo['idCategorias']) and sizeof($trabajo['idCategorias']) > 0) {
				$this->eliminarCategorias($trabajo);
				foreach($trabajo['idCategorias'] as $idCategorias){
					$data = array(
							'idCategorias' => $idCategorias,
							'idTrabajos' => $trabajo['idTrabajos']
					);
					$this->db->insert('TrabajosCategorias',$data);
				}
			} else {
				$this->eliminarCategorias($trabajo);
			}


			if($this->db->trans_status()){
				$this->db->trans_commit();
				return true;
			} else {
				$this->db->trans_rollback();
				return false;
			}

		} catch (Exception $e) {
			$this->db->trans_rollback();
			return false;
		}
	}

	// public function guardarIndice($indice)
	// {
	// 	try {
	// 		if(isset($_FILES['indice']) && !empty($indice)) {
	// 			if(move_uploaded_file($_FILES['indice']['tmp_name'], UPLOADS . "trabajos/indices/$indice")){
	// 				return $indice;
	// 			} else {
	// 				return "";
	// 			}
	// 		} else {
	// 			return "";
	// 		}

	// 	} catch (Exception $e) {
	// 		return "";
	// 	}
	// }

	// public function eliminarIndice($indice)
	// {
	// 	try {
	// 		if(file_exists(UPLOADS . "trabajos/indices/$indice") && is_file(UPLOADS. "trabajos/indices/$indice")){
	// 			unlink(UPLOADS. "trabajos/indices/$indice");
	// 		}
	// 	} catch (Exception $e) {

	// 	}
	// }

	public function guardarFoto($foto)
	{
		try {
			if(isset($_FILES['foto']) && !empty($foto)){
				if(move_uploaded_file($_FILES['foto']['tmp_name'], "web/uploads/trabajos/portadas/$foto")){
					// FOTO PORTADA
					$config = array();
					$config['image_library'] 	= 'gd2';
					// $config['source_image']	= UPLOADS . "web/uploads/trabajos/portadas/$foto";
					$config['source_image']	= "web/uploads/trabajos/portadas/$foto";
					// $config['new_image'] 	= "web/uploads/categorias/iconos/thumbs/$image";
					$config['create_thumb'] = FALSE;
					$config['maintain_ratio'] = TRUE;
					$config['width']	 		= 146;
					$config['height']			= 146;
					// $this->load->library('image_lib');
					// $this->load->library('image_lib', $config);
					$this->image_lib->initialize($config);
					// $this->image_lib->clear();
					$foto_portada = $this->image_lib->resize();

					if($foto_portada){
						return $foto;
					} else{
						return $foto; // TODO: ver esto . .. debe retornar vacio
					}

				} else {
					return $foto; // TODO: ver esto . .. debe retornar vacio
				}
			} else {
				return $foto; // TODO: ver esto . .. debe retornar vacio
			}

		} catch (Exception $e) {
			return "";
		}
	}

	public function eliminarFoto($foto)
	{
		try {
			if(file_exists(UPLOADS . "trabajos/portadas/$foto") && is_file(UPLOADS. "trabajos/portadas/$foto")){
				unlink(UPLOADS. "trabajos/portadas/$foto");
			}
		} catch (Exception $e) {

		}
	}


	public function guardarArchivo ($tipo, $archivo)
	{
		try {
			// ARCHIVOS PRIVADOS
			if( isset($_FILES[$tipo]) && $tipo == 'archivo_privado' && !empty($archivo) ) {
				if(move_uploaded_file($_FILES[$tipo]['tmp_name'], "web/uploads/trabajos/archivos_privado/$archivo")) {
					// print_r($_FILES[$tipo]);
					return $archivo;
				} else {
					return "";
				}
			// ARCHIVOS PUBLICOS
			}elseif( isset($_FILES[$tipo]) && $tipo == 'archivo_publico' && !empty($archivo) ) {
				if(move_uploaded_file($_FILES[$tipo]['tmp_name'], "web/uploads/trabajos/archivos_publico/$archivo")) {
					// print_r($_FILES[$tipo]);
					return $archivo;
				} else {
					return "";
				}
			// ARCHIVOS VISTA PREVIA
			}elseif( isset($_FILES[$tipo]) && $tipo == 'archivo_vista_previa' && !empty($archivo) ) {
				if(move_uploaded_file($_FILES[$tipo]['tmp_name'], "web/uploads/trabajos/archivos_vista_previa/$archivo")) {
					// print_r($_FILES[$tipo]);
					return $archivo;
				} else {
					return "";
				}

			} else {
				return "";
			}

		} catch (Exception $e) {
			return "";
		}
	}



	public function eliminarArchivo($archivo)
	{
		try {
			if(file_exists(UPLOADS . "trabajos/archivos/$archivo") && is_file(UPLOADS. "../trabajos/archivos/$archivo")){
				unlink(UPLOADS. "../trabajos/archivos/$archivo");
			}
		} catch (Exception $e) {

		}
	}

	public function get($trabajo)
	{
		try {
			$sql = "SELECT * FROM Trabajos T,EstadosTrabajos ET WHERE
			T.idEstados = ET.idEstados AND
			T.idTrabajos = ".(int)$trabajo['idTrabajos'];

			$query = $this->db->query($sql);
			$trabajo = $query->row_array();
			$trabajo['categorias'] = $this->getCategorias($trabajo);
			return $trabajo;
		} catch (Exception $e) {
			return array();
		}
	}

	protected function eliminarCategorias($trabajo)
	{
		try {
			$this->db->where('idTrabajos',$trabajo['idTrabajos']);
			$this->db->delete('TrabajosCategorias');
			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	public function getCategorias($trabajo)
	{
		try {
			$sql = "SELECT TC.*,C.* FROM TrabajosCategorias TC, Trabajos T,Categorias C WHERE
			TC.idTrabajos = T.idTrabajos AND
			C.idCategorias = TC.idCategorias AND
			T.idTrabajos = ".(int)$trabajo['idTrabajos'];
			$query = $this->db->query($sql);
			return $query->result_array();
		} catch (Exception $e) {
			return array();
		}
	}

	public function listar($filter = NULL)
	{
		try {
			$sql = '';
			$condicion= '';
			$estado = '';
			if(isset($filter['idTrabajos'])) {
				$condicion = " AND T.idTrabajos = ".(int)$filter['idTrabajos']. " ";
			}
			elseif(isset($filter['titulo'])) {
				$condicion = " AND T.titulo like '%" . trim($filter['titulo']) ."%'";
			} elseif(isset($filter['userName'])) {
				$condicion = " AND U.email like '%" . trim($filter['userName']) ."%'";
			}

			if(isset($filter['idUsuarios'])){
				$condicion .= " AND U.idUsuarios = ".(int)$filter['idUsuarios'];
			}

			$sql = 'SELECT * FROM Trabajos T,Usuarios U,EstadosTrabajos ET WHERE T.idEstados = ET.idEstados AND
			U.idUsuarios = T.idUsuarios '. $condicion .' ORDER BY T.fecha DESC,T.idTrabajos DESC ' . $filter['limit'];

			$query = $this->db->query($sql);
			$trabajos = $query->result_array();
			if(sizeof($trabajos) == 0){
				return array();
			}
			else{
				return $trabajos;
			}

		} catch (Exception $e) {
			return array();
		}
	}


	public function pendientes($filter = NULL){
		try {
			$sql = '';
			$condicion= '';
			$estado = '';
			if(isset($filter['idTrabajos'])) {
				$condicion = " AND T.idTrabajos = ".(int)$filter['idTrabajos']. " ";
			}
			elseif(isset($filter['titulo'])) {
				$condicion = " AND T.titulo like '%" . trim($filter['titulo']) ."%'";
			} elseif(isset($filter['userName'])) {
				$condicion = " AND U.email like '%" . trim($filter['userName']) ."%'";
			}


			$sql = 'SELECT *,T.fecha as \'fecha\' FROM Trabajos T,Usuarios U,EstadosTrabajos ET WHERE T.idEstados = ET.idEstados AND
			U.idUsuarios = T.idUsuarios AND ET.idEstados = '. $this->config->item('estado_trabajo_pendiente')  . " " . $condicion .' ORDER BY T.fecha DESC,T.idTrabajos DESC ' . $filter['limit'];


			$query = $this->db->query($sql);
			$trabajos = $query->result_array();
			if(sizeof($trabajos) == 0){
				return array();
			}
			else{
				return $trabajos;
			}

		} catch (Exception $e) {
			return array();
		}
	}

	public function contar($filter = NULL){
		try
		{
			$sql = '';
			$condicion = "";
			if(isset($filter['idTrabajos'])) {
				$condicion = " AND T.idTrabajos = ".(int)$filter['idTrabajos']. " ";
			}
			elseif(isset($filter['titulo'])) {
				$condicion = " AND T.titulo like '%" . trim($filter['titulo']) ."%'";
			} elseif(isset($filter['userName'])) {
				$condicion = " AND U.email like '%" . trim($filter['userName']) ."%'";
			}
			if(isset($filter['estadoPendiente'])){
				$condicion .= ' AND ET.idEstados = '.$this->config->item('estado_trabajo_pendiente');
			}

			if(isset($filter['idUsuarios'])){
				$condicion .= " AND U.idUsuarios = ".(int)$filter['idUsuarios'];
			}

			$sql = "SELECT COUNT(T.idTrabajos) as 'max'
					FROM Trabajos T ,Usuarios U,EstadosTrabajos ET
					WHERE T.idEstados = ET.idEstados
							AND U.idUsuarios = T.idUsuarios ".$condicion;
			$query = $this->db->query($sql);
			$rows = $query->row_array();
			if($rows['max'] <= 0 ){
				return 0;
			}
			else{
				return $rows['max'];
			}

		} catch (Exception $e) {
			//throw new Exception($e->getMessage());
			return 0;
		}
	}


	public function eliminar($trabajo)
	{
		try {
			$this->db->trans_begin();

			$trabajo = $this->get($trabajo);

			$is_in_pedidos = $this->isInPedidos($trabajo['idTrabajos']);
			if ($is_in_pedidos) {  // ESTA EN PEDIDOS
				return false;
			}

			$is_in_favoritos = $this->isInFavoritos($trabajo['idTrabajos']);
			if ($is_in_favoritos) {
				$this->eraseFavoritos($trabajo['idTrabajos']);
			}

			// if(!empty($trabajo['indice'])){
			// 	$this->eliminarIndice($trabajo['indice']);
			// }

			if(!empty($trabajo['foto'])){
				$this->eliminarFoto($trabajo['foto']);
			}
			if(isset($trabajo['archivo_publico']) && !empty($trabajo['archivo_publico'])){
				$this->eliminarArchivo($trabajo['archivo_publico']);
			}
			if(isset($trabajo['archivo_privado']) && !empty($trabajo['archivo_privado'])){
				$this->eliminarArchivo($trabajo['archivo_privado']);
			}

			$this->eliminarCategorias($trabajo);
			$this->db->where('idTrabajos',$trabajo['idTrabajos']);
			$this->db->delete('Trabajos');

			if($this->db->trans_status()){
				$this->db->trans_commit();
				return true;
			} else{
				$this->db->trans_rollback();
				return false;
			}
		} catch (Exception $e) {
			$this->db->trans_rollback();
			return false;
		}
	}

	public function aprobar($trabajo)
	{
		try {


			$this->eliminarCategorias($trabajo);
			$this->db->where('idTrabajos',$trabajo['idTrabajos']);
			$data = array('idEstados' => $this->config->item('estado_trabajo_aprobado'));
			$this->db->update('Trabajos',$data);

			return true;
		} catch (Exception $e) {
			return false;
		}
	}


	public function getTrabajosById($id)
	{
        try {
            $sql = "
                SELECT *
                FROM Trabajos J
                WHERE J.idTrabajos = $id
                ";
            $query = $this->db->query($sql);
            $trabajo = $query->result_array();
            $trabajo = $trabajo[0];
            return $trabajo;

        } catch (Exception $e) {
            return false;
        }
    }

	public function isInPedidos($idTrabajos)
	{
		try {

			$sql = "SELECT *
		      	          FROM Pedidos P
		            	    WHERE P.idTrabajos = $idTrabajos
			                ";
	            $query = $this->db->query($sql);
	            $trabajo = $query->result_array();
	            if (count($trabajo) > 0) {
	            	return true;
	            } else {
	            	return false;
	            }

		} catch (Exception $e) {
			return true;
		}
	}

	public function isInFavoritos($idTrabajos)
	{
		try {
			$sql = "SELECT *
	      	          		FROM Favoritos F
	            	    		WHERE F.idTrabajos = $idTrabajos
			                ";
	            $query 		= $this->db->query($sql);
	            $trabajo 	= $query->result_array();
	            if (count($trabajo) > 0) {
	            	return true;
	            } else {
	            	return false;
	            }

		} catch (Exception $e) {
			return true;
		}
	}

	protected function eraseFavoritos($idTrabajos)
	{
		try {
			$this->db->where('idTrabajos',$idTrabajos);
			$this->db->delete('Favoritos');

			return true;

		} catch (Exception $e) {
			return true;
		}
	}



}

?>
