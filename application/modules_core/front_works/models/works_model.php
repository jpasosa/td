<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Works_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		// $this->load->config('estados');
	}




	public function add($work)
	{
		try
		{

			$this->db->trans_begin();

			$data = array(
					'idUsuarios' 			=> $work['idUsuarios'],
					'texto' 					=> $work['texto'],
					'titulo'  					=> $work['titulo'],
					'resumen' 				=> $work['resumen'],
					'fecha' 					=> date('Y-m-d',strtotime($work['fecha'])),
					'palabrasClave' 			=> $work['palabrasClave'],
					'monto_por_venta'  	=> $work['monto_por_venta'],
					'precio_sin_derecho' 	=> $work['precio_sin_derecho'],
					'precio_con_derecho' 	=> $work['precio_con_derecho'],
					'destacado' 			=> $work['destacado'],
					'idCategorias_parentId' => $work['idCategorias_parentId'],
					'cantidadPalabras' 		=> $work['cantidadPalabras'],
					'cantidad_paginas' 		=> $work['cantidad_paginas'],
					'indice' 					=> $work['indice'],
					'nivel' 					=> $work['nivel'],
					// 'indice' 				=> $this->guardarIndice($work['indice']),
					'foto' 					=> $this->savePhoto($work['foto']),
					'archivo_publico' 		=> $this->saveFile('archivo_publico', $work['archivo_publico']),
					'archivo_privado' 		=> $this->saveFile('archivo_privado', $work['archivo_privado']),
					'archivo_vista_previa'	=> $this->saveFile('archivo_vista_previa', $work['archivo_vista_previa']),
					'idEstados' 				=> $work['idEstados'],
					// 'idPrecios' 			=> $work['idPrecios'],
					'notificado' 				=> $work['notificado']
					);
			$this->db->insert('Trabajos',$data);

			if(isset($work['sub_category']) && count($work['sub_category']) > 0) {
				$work['idTrabajos'] = $this->db->insert_id(); // obtengo el id que acaba de insertar.
				foreach($work['sub_category'] AS $sub_cat)
				{
					$data = array(
								'idCategorias' => $sub_cat['id'],
								'idTrabajos' => $work['idTrabajos']
								);
					$this->db->insert('TrabajosCategorias',$data);
					echo 'inserta la subcategoria' , '<br />';
				}
			}

			// $work['idTrabajos'] = $this->db->insert_id();
			// if(isset($work['idTrabajos']) && $work['idTrabajos'] > 0) {
			// 	if(isset($work['idCategorias']) && sizeof($work['idCategorias']) > 0) {
			// 		foreach($work['idCategorias'] as $idCategorias) {
			// 			$data = array(
			// 					'idCategorias' => $idCategorias,
			// 					'idTrabajos' => $work['idTrabajos']
			// 					);
			// 			$this->db->insert('TrabajosCategorias',$data);
			// 		}
			// 	}
			// }

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



	public function update($work, $id_trabajo)
	{
		try
		{


			$this->db->trans_begin();




			$data = array(
					'idUsuarios' 			=> $work['idUsuarios'],
					'texto' 					=> $work['texto'],
					'titulo'  					=> $work['titulo'],
					'resumen' 				=> $work['resumen'],
					'fecha' 					=> date('Y-m-d',strtotime($work['fecha'])),
					'palabrasClave' 			=> $work['palabrasClave'],
					'monto_por_venta'  	=> $work['monto_por_venta'],
					'precio_sin_derecho' 	=> $work['precio_sin_derecho'],
					'precio_con_derecho' 	=> $work['precio_con_derecho'],
					'destacado' 			=> $work['destacado'],
					'idCategorias_parentId' => $work['idCategorias_parentId'],
					'cantidadPalabras' 		=> $work['cantidadPalabras'],
					'cantidad_paginas' 		=> $work['cantidad_paginas'],
					'indice' 					=> $work['indice'],
					'nivel' 					=> $work['nivel'],
					// 'indice' 				=> $this->guardarIndice($work['indice']),
					'foto' 					=> $this->savePhoto($work['foto']),
					// 'archivo_publico' 		=> $this->saveFile('archivo_publico',$work['archivo_publico']),
					'idEstados' 				=> $work['idEstados'],
					'idPrecios' 				=> $work['idPrecios'],
					'notificado' 				=> $work['notificado']
					);

			$this->db->where('idTrabajos', $id_trabajo);
			$this->db->update('Trabajos', $data);



			// $this->db->insert('Trabajos',$data);

			// if(isset($work['sub_category']) && count($work['sub_category']) > 0) {
			// 	$work['idTrabajos'] = $this->db->insert_id(); // obtengo el id que acaba de insertar.
			// 	foreach($work['sub_category'] AS $sub_cat) {
			// 		$data = array(
			// 					'idCategorias' => $sub_cat['id'],
			// 					'idTrabajos' => $work['idTrabajos']
			// 					);
			// 		$this->db->insert('TrabajosCategorias',$data);
			// 	}
			// }

			// $work['idTrabajos'] = $this->db->insert_id();
			// if(isset($work['idTrabajos']) && $work['idTrabajos'] > 0) {
			// 	if(isset($work['idCategorias']) && sizeof($work['idCategorias']) > 0) {
			// 		foreach($work['idCategorias'] as $idCategorias) {
			// 			$data = array(
			// 					'idCategorias' => $idCategorias,
			// 					'idTrabajos' => $work['idTrabajos']
			// 					);
			// 			$this->db->insert('TrabajosCategorias',$data);
			// 		}
			// 	}
			// }

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


	public function update_noadmin($work, $id_trabajo)
	{
		try
		{

			$data = array(
					'idUsuarios' 			=> $work['idUsuarios'],
					'texto' 				=> $work['texto'],
					'titulo'  				=> $work['titulo'],
					'resumen' 			=> $work['resumen'],
					'fecha' 				=> date('Y-m-d',strtotime($work['fecha'])),
					'palabrasClave' 		=> $work['palabrasClave'],
					// 'monto_por_venta'  	=> $work['monto_por_venta'],
					// 'precio_sin_derecho' => $work['precio_sin_derecho'],
					// 'precio_con_derecho' => $work['precio_con_derecho'],
					// 'destacado' 			=> $work['destacado'],
					'idCategorias_parentId' => $work['idCategorias_parentId'],
					'cantidadPalabras' 	=> $work['cantidadPalabras'],
					'cantidad_paginas' 	=> $work['cantidad_paginas'],
					'indice' 				=> $work['indice'],
					'nivel' 				=> $work['nivel'],
					// 'indice' 				=> $this->guardarIndice($work['indice']),
					// 'foto' 				=> $this->savePhoto($work['foto']),
					// 'archivo_publico' 	=> $this->saveFile('archivo_publico',$work['archivo_publico']),
					// 'archivo_privado' 	=> $this->saveFile('archivo_privado',$work['archivo_privado'])
					// 'idEstados' 			=> $work['idEstados'],
					// 'idPrecios' 			=> $work['idPrecios'],
					// 'notificado' 			=> $work['notificado']
					);

			$this->db->where('idTrabajos', $id_trabajo);
			$this->db->update('Trabajos', $data);

			if(isset($work['sub_category']) && count($work['sub_category']) > 0 && $work && gettype($work['sub_category']) != 'integer')
			{  	// SI HAY SUB CATEGORIAS SETEADAS, DEBO ELIMINAR LAS ANTERIORES Y CARGAR LAS NUEVAS
				if ($this->db->delete('TrabajosCategorias', array('idTrabajos' => $id_trabajo))) {
					foreach($work['sub_category'] AS $sub_cat) {
							$data = array(
										'idCategorias' => $sub_cat['id'],
										'idTrabajos' => $id_trabajo
										);
							$this->db->insert('TrabajosCategorias',$data);
					}
				}


			}

			$data = array();  // VUELVO A CERO POR LAS DUDAS
			if (!isset($work['archivo_privado_nograbar'])) {
				echo 'va a grabar el archivo privado nuevo';
				$update = true;
				$data = array('archivo_privado' 	=> $this->saveFile('archivo_privado',$work['archivo_privado']));
			}
			if (!isset($work['archivo_foto_nograbar'])) {
				echo 'va a grabar el archivo foto nuevo';
				$update = true;
				$data = array('foto' 	=> $this->savePhoto($work['foto']));
			}
			if (!isset($work['archivo_vista_previa_nograbar'])) {
				echo 'va a grabar el archivo vista previa nuevo';
				$update = true;
				$data = array('archivo_vista_previa' 	=> $this->saveFile('archivo_vista_previa',$work['archivo_vista_previa']));
			}

			if (isset($update) && $update) {
				$this->db->where('idTrabajos', $id_trabajo);
				$this->db->update('Trabajos', $data);
			}


			// $work['idTrabajos'] = $this->db->insert_id();
			// if(isset($work['idTrabajos']) && $work['idTrabajos'] > 0) {
			// 	if(isset($work['idCategorias']) && sizeof($work['idCategorias']) > 0) {
			// 		foreach($work['idCategorias'] as $idCategorias) {
			// 			$data = array(
			// 					'idCategorias' => $idCategorias,
			// 					'idTrabajos' => $work['idTrabajos']
			// 					);
			// 			$this->db->insert('TrabajosCategorias',$data);
			// 		}
			// 	}
			// }

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




	public function validate($work)
	{
		$errors = false;

		$valid_files = $this->config->item('format_validations');


		if ( $work['archivo_privado'] == '') {
			$errors['sin_archivo'] = 'Debe seleccionar algún archivo.';
		}


		// VALIDO POR LA EXTENSION DEL ARCHIVO SUBIDO,  ARCHIVO PRIVADO
		if ( isset($_FILES['archivo_privado']['type']) && $_FILES['archivo_privado']['type'] != '')
		{
			if (!in_array($_FILES['archivo_privado']['type'], $valid_files)) {
				$errors['valid_file_privado'] = 'El archivo privado debe ser formato doc, pdf, odt';
			}
		}
		// VALIDO POR LA EXTENSION DEL ARCHIVO SUBIDO,  ARCHIVO VISTA PREVIA
		if ( isset($_FILES['archivo_vista_previa']['type']) && $_FILES['archivo_vista_previa']['type'] != '')
		{
			if (!in_array($_FILES['archivo_vista_previa']['type'], $valid_files)) {
				$errors['valid_file_previa'] = 'El archivo de Vista Previa debe ser formato doc, pdf, odt';
			}
		}

		if(!isset($work['titulo'])) {
			$errors['titulo'] = 'El titulo es obligatorio';
		}

		if(!isset($work['texto'])) {
			$errors['texto'] = 'El texto es obligatorio';
		}

		// Ya no es más obligatorio.
		// if(!isset($work['resumen'])) {
		// 	$errors['resumen'] = 'El resumen es obligatorio';
		// }

		// Ya no es obligatorio el mínimo de palabras en descripción y resumen.
		// if (isset($work['texto']))
		// {
		// 	$cant_palabras_texto = str_word_count($work['texto']);
		// 	if($cant_palabras_texto < 70) {
		// 		$errors['texto_minimo'] = 'La descripción debe tener como mínimo 70 palabras';
		// 	}
		// }

		// if (isset($work['resumen']))
		// {
		// 	$cant_palabras_resumen = str_word_count($work['resumen']);
		// 	if($cant_palabras_resumen < 70) {
		// 		$errors['resumen_minimo'] = 'El resumen debe tener como mínimo 70 palabras';
		// 	}
		// }

		// if(!isset($work['idCategorias_parentId'])){
		// 	$errors['idCategorias_parentId'] = 'Debe elegir una categoría';
		// }

		return $errors;
	}

	public function validateArchivoPrivado( $work )
	{
		$errors = false;

		$valid_files = $this->config->item('format_validations');

		// VALIDO POR LA EXTENSION DEL ARCHIVO SUBIDO,  ARCHIVO PRIVADO
		if ( isset($_FILES['archivo_privado']['type']) && $_FILES['archivo_privado']['type'] != '')
		{
			if (!in_array($_FILES['archivo_privado']['type'], $valid_files)) {
				$errors = true;
			}
		}

		return $errors;
	}




	public function saveFile ($tipo, $archivo)
	{
		try {

			// Archivos privados
			if( isset($_FILES[$tipo]) && $tipo == 'archivo_privado' && !empty($archivo) ) {
				if(move_uploaded_file($_FILES[$tipo]['tmp_name'], "web/uploads/trabajos/archivos_privado/$archivo")) {
					// print_r($_FILES[$tipo]);
					return $archivo;
				} else {
					return "";
				}

			// Archivos publicos
			}elseif( isset($_FILES[$tipo]) && $tipo == 'archivo_publico' && !empty($archivo) ) {
				if(move_uploaded_file($_FILES[$tipo]['tmp_name'], "web/uploads/trabajos/archivos_publico/$archivo")) {
					// print_r($_FILES[$tipo]);
					return $archivo;
				} else {
					return "";
				}

			// Archivos publicos
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

	public function savePhoto($foto)
	{
		try {
			if(isset($_FILES['foto']) && !empty($foto))
			{
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
					// $config['source_image']	= UPLOADS . "web/uploads/trabajos/portadas/$foto";
					if($foto_portada) {
						return $foto;
					} else{
						return $foto; // TODO: aca debe retornar vacio si no pudo hacer resize
					}

				} else {
					return $foto;
				}


			} else {
				return $foto;
			}

		} catch (Exception $e) {
			return "";
		}
	}


	public function getAllNiveles()
	{
		$type = $this->db->query( "SHOW COLUMNS FROM Trabajos WHERE Field = 'nivel'" )->row( 0 )->Type;
		preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
		$enum = explode("','", $matches[1]);
		return $enum;
	}


}

?>
