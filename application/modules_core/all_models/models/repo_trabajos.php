<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Repo_trabajos extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->config('estados');
	}

	// SELECCIONA POR EL ID
	public function getById($id)
	{
		try {
			$sql = "SELECT * FROM Trabajos J WHERE J.idTrabajos = $id ";
			$query = $this->db->query($sql);
			$trabajo = $query->result_array();

			if(isset($trabajo[0])) {
				$trabajo = $trabajo[0];
			}else{
				$trabajo = false;
			}

			return $trabajo;

		} catch (Exception $e) {
			return false;
		}
	}

	// AGARRA TODAS LAS PUBLICACIONES
	public function getAll()
	{
		try {
			$sql = "SELECT * FROM Trabajos T WHERE T.idEstados = 2 ";
			$query = $this->db->query($sql);
			$trabajos = $query->result_array();

			if(isset($trabajos)) {
				$trabajos = $trabajos;
			}else{
				$trabajos = false;
			}

			return $trabajos;

		} catch (Exception $e) {
			return false;
		}
	}

	// SELECCIONA POR EL ID
	public function getTitulo($id)
	{
		try {
			$sql = "SELECT T.titulo FROM Trabajos T WHERE T.idTrabajos = $id ";
			$query = $this->db->query($sql);
			$trabajo = $query->result_array();

			if(isset($trabajo[0])) {
				$trabajo = $trabajo[0]['titulo'];
			}else{
				$trabajo = false;
			}

			return $trabajo;

		} catch (Exception $e) {
			return false;
		}
	}

	// SELECCIONA POR EL ID
	public function getArchivoPublico($id_trabajo)
	{
		try {
			$sql = "SELECT T.archivo_publico FROM Trabajos T WHERE T.idTrabajos = $id_trabajo ";
			$query = $this->db->query($sql);
			$trabajo = $query->result_array();

			if(isset($trabajo[0])) {
				$trabajo = $trabajo[0]['archivo_publico'];
			}else{
				$trabajo = false;
			}

			return $trabajo;

		} catch (Exception $e) {
			return false;
		}
	}

	// ME DÁ EL AUTOR DE LA PUBLICACIÓN
	public function getAutor($id)
	{
		try {
			$sql = "SELECT U.nombre, U.apellido
					FROM Trabajos T
					INNER JOIN Usuarios U
						ON T.idUsuarios=U.idUsuarios
					WHERE T.idTrabajos = $id ";
			$query = $this->db->query($sql);
			$trabajo = $query->result_array();


			if(isset($trabajo[0])) {
				$trabajo = $trabajo[0]['apellido'] . ' ' . $trabajo[0]['nombre'];
			}else{
				$trabajo = false;
			}

			return $trabajo;

		} catch (Exception $e) {
			return false;
		}
	}

	// EL REGISTRO INCLUYENDO LAS SUBCATEGORIAS
	public function getByIdIncludeSubcategorys($id)
	{
		try {
			$work_to_return = array();

			$sql = "SELECT * FROM Trabajos J WHERE J.idTrabajos = $id ";
			$query = $this->db->query($sql);
			$trabajo = $query->result_array();

			if(isset($trabajo[0]))
			{
				$work_to_return = $trabajo[0];

			// if( isset($work_to_return['sub_category']) && count($work_to_return['sub_category'] > 0) )
			// {
				$sql 			= "SELECT * FROM TrabajosCategorias  WHERE idTrabajos = $id ";
				$query 			= $this->db->query($sql);
				$sub_categorias = $query->result_array();

				if(isset($sub_categorias[0])) {
					foreach ($sub_categorias AS $k => $sc)
					{
						$sub_cat[$k]['id'] 	= $sc['idCategorias'];
						$sub_cat[$k]['name'] = $this->repo_categorias->getSubCategoryName($sc['idCategorias']);
					}
					$work_to_return['sub_category'] = $sub_cat;
				}



			// }

			}else
			{
				$work_to_return = false;
			}

			return $work_to_return;

		} catch (Exception $e) {
			return false;
		}
	}

	// SELECCIONA PARA MOSTRAR TODA LA PUBLICACION, SEGUN EL ID QUE LE PEDIMOS.
	public function getToShowById($id_work)
	{
		try {
			$sql = "	SELECT * FROM Trabajos T  WHERE T.idTrabajos = $id_work ";
			$query = $this->db->query($sql);
			$show_work = $query->result_array();

			if(isset($show_work[0]))
			{
				$show_work = $show_work[0];

				// POPULARIDAD . CANTIDAD DE VISITAS EN PORCENTAJE, CALCULADO CON RESPECTO A LA MAYOR VISITADA
				$cant_mayor_visitas	= $this->getMayorVisitada();
				$visitas_actual 			= $show_work['visitas'];
				$porc_visitas 			= $visitas_actual * 100 / $cant_mayor_visitas;
				$show_work['popularidad'] = (int)$porc_visitas;
				// AUTOR
				$usuario = $this->repo_usuarios->getById($show_work['idUsuarios']);
				$cant_publicados = $this->getCantidadPublicados($show_work['idUsuarios']);
				$show_work['autor'] = $usuario;
				$show_work['autor']['publicados'] = $cant_publicados;
				// CATEGORIA
				$categoria = $this->repo_categorias->getCategoryName($show_work['idCategorias_parentId']);
				$show_work['categoria']['nombre'] 	= $categoria;
				$show_work['categoria']['id'] 		= $show_work['idCategorias_parentId'];
				// SUBCATEGORIA
				$sub_categorias = $this->repo_categorias->getSubCategorysByWork($show_work['idTrabajos']);
				if( count($sub_categorias) == 0) {
					$show_work['sub_categoria'] = NULL;
				}else {
					foreach($sub_categorias as $k_sc => $sc) {
						$show_work['sub_categoria'][$k_sc]['id']	=	$sc['idCategorias'];
						$show_work['sub_categoria'][$k_sc]['nombre']	= $this->repo_categorias->getSubCategoryName($sc['idCategorias']);
					}
				}

			}else{
				$show_work = false;
			}

	            return $show_work;

		} catch (Exception $e) {
			return false;
		}
	}

	// SELECCIONA LA CANTIDAD DE VISITAS DE LA PUBLICACION QUE TUVO EL MAXIMO DE VISITAS HASTA EL MOMENTO
	protected function getMayorVisitada()
	{
		try {
			$sql 			= "	SELECT MAX(visitas) AS mayor_visitas FROM Trabajos T  WHERE T.idEstados = 2 ";
			$query 			= $this->db->query($sql);
			$mayor_visitas 	= $query->result_array();
			if(isset($mayor_visitas[0])) {
				$mayor_visitas = $mayor_visitas[0]['mayor_visitas'];
			}else{
				$mayor_visitas = false;
			}

	            return $mayor_visitas;

		} catch (Exception $e) {
			return false;
		}
	}



	// CANTIDAD DE TRABAJOS PUBLICADOS QUE TIENE EL USUARIO.
	public function getCantidadPublicados($id_user)
	{
		try {
			$sql = "	SELECT COUNT(*) AS cant_publicaciones FROM Trabajos T  WHERE T.idEstados = 2 AND T.idUsuarios = $id_user ";
			$query = $this->db->query($sql);
	            $query = $query->result_array();
	            $publicaciones = $query[0]['cant_publicaciones'];

	            return $publicaciones;

		} catch (Exception $e) {
			return false;
		}
	}

	// CANTIDAD DE TRABAJOS PUBLICADOS POR CATEGORIA O SUBCATEGORIA
	public function getCantidadPublicadosByCategory($id_category)
	{
		try {
			// puede llegar a ser la subcategoria

			if($this->repo_categorias->isSubCategory($id_category))
			{ 		// SUBCATEGORIA
				$sql = "SELECT COUNT(*) AS cant_publicaciones FROM TrabajosCategorias TC
						JOIN Trabajos T
							ON TC.idTrabajos=T.idTrabajos
						WHERE T.idEstados = 2 AND TC.idCategorias = $id_category;
						";
				$query 			= $this->db->query($sql);
	            	$query 			= $query->result_array();
	            	$publicaciones 	= $query[0]['cant_publicaciones'];

			} else { // CATEGORIA
				$sql = "	SELECT COUNT(*) AS cant_publicaciones FROM Trabajos T  WHERE T.idEstados = 2 AND T.idCategorias_parentId = $id_category ";
				$query = $this->db->query($sql);
	            	$query = $query->result_array();
		            $publicaciones = $query[0]['cant_publicaciones'];
			}

	            return $publicaciones;

		} catch (Exception $e) {
			return false;
		}
	}

	// SELECCIONA DOS PUBLICACIONES AL AZAR
	public function getTwoWorksRandom()
	{
		try {
			$sql 			= "	SELECT * FROM Trabajos WHERE idEstados = 2 ORDER BY rand() LIMIT 2;";
			$query 			= $this->db->query($sql);
			$works_random	= $query->result_array();

			foreach($works_random AS $k => $wr)
			{
				$category = $this->repo_categorias->getOnlyById($wr['idCategorias_parentId']);
				$works_random[$k]['categoria']['id'] 		= $category['idCategorias'];
				$works_random[$k]['categoria']['nombre'] 	= $category['nombreCategoria'];
				$works_random[$k]['categoria']['imagen']	= $category['imagen'];
			}

	            return $works_random;

		} catch (Exception $e) {
			return false;
		}
	}

	// TRAE ULTIMAS TRES PUBLICACIONES
	public function getLastWorks()
	{
		try {
			$sql = "	SELECT * FROM Trabajos WHERE idEstados = 2 ORDER BY idTrabajos DESC LIMIT 3;";
			$query = $this->db->query($sql);
			$last_works = $query->result_array();

	            return $last_works;

		} catch (Exception $e) {
			return false;
		}
	}

	// TRAE PUBLICACIONES DE ESE AUTOR
	public function getWorksOfAuthor($id_author, $limit)
	{
		try {
			$sql = "	SELECT
						T.idTrabajos, T.idUsuarios, T.titulo, T.texto, T.resumen, T.fecha, T.palabrasClave, T.cantidad_paginas,
						T.foto, T.idCategorias_parentId
					FROM Trabajos T
					WHERE T.idEstados = 2
						AND T.idUsuarios = $id_author
					ORDER BY T.titulo
					$limit;";
			$query = $this->db->query($sql);
			$works_of_author['cantidad'] = 0;
			$works_of_author['publicacion'] = $query->result_array();

			foreach($works_of_author['publicacion'] AS $k => $work)
			{
				// CATEGORIA
				$categoria = $this->repo_categorias->getCategoryName($work['idCategorias_parentId']);
				$works_of_author['publicacion'][$k]['categoria']['nombre'] 	= $categoria;
				$works_of_author['publicacion'][$k]['categoria']['id'] 		= $work['idCategorias_parentId'];
				// SUBCATEGORIA
				$sub_categorias = $this->repo_categorias->getSubCategorysByWork($work['idTrabajos']);
				if( count($sub_categorias) == 0 ) {
					$works_of_author['publicacion'][$k]['sub_categoria'] = NULL;
				}else{
					foreach($sub_categorias as $k_sc => $sc) {
						$works_of_author['publicacion'][$k]['sub_categoria'][$k_sc]['id']	=	$sc['idCategorias'];
						$works_of_author['publicacion'][$k]['sub_categoria'][$k_sc]['nombre']	= $this->repo_categorias->getSubCategoryName($sc['idCategorias']);
					}
				}
				$works_of_author['cantidad'] = $k + 1;

			}

	            return $works_of_author;

		} catch (Exception $e) {
			return false;
		}
	}

	// TRAE LAS PUBLICACIONES QUE VENDIO DICHO AUTOR
	public function getWorksOfAuthorSolded($id_author, $limit)
	{
		try {
			$author = $this->repo_usuarios->getById($id_author);
			$sql = "	SELECT T.titulo, T.idCategorias_parentId, T.foto, C.fecha, C.monto_venta_total
					FROM Regalias R
						INNER JOIN Pedidos C
							ON R.idPedidos=C.idPedidos
						INNER JOIN Trabajos T
							ON C.idTrabajos=T.idTrabajos
					WHERE R.idUsuarios = $id_author
					ORDER BY C.fecha $limit;";
			$query = $this->db->query($sql);
			// $works_of_author['cantidad'] = 0;
			$works_of_author = $query->result_array();

			foreach($works_of_author AS $k => $work)
			{
				// AVATAR
				$works_of_author[$k]['avatar'] 	= $author['avatar'];
				// CATEGORIA
				$categoria = $this->repo_categorias->getCategoryName($work['idCategorias_parentId']);
				$works_of_author[$k]['categoria']['nombre'] 	= $categoria;
				$works_of_author[$k]['categoria']['id'] 		= $work['idCategorias_parentId'];
				// SUBCATEGORIA
				// $sub_categorias = $this->repo_categorias->getSubCategorysByWork($work['idTrabajos']);
				// if( count($sub_categorias) == 0 ) {
				// 	$works_of_author['publicacion'][$k]['sub_categoria'] = NULL;
				// }else{
				// 	foreach($sub_categorias as $k_sc => $sc) {
				// 		$works_of_author['publicacion'][$k]['sub_categoria'][$k_sc]['id']	=	$sc['idCategorias'];
				// 		$works_of_author['publicacion'][$k]['sub_categoria'][$k_sc]['nombre']	= $this->repo_categorias->getSubCategoryName($sc['idCategorias']);
				// 	}
				// }
				// $works_of_author['cantidad'] = $k + 1;
			}


	            return $works_of_author;

		} catch (Exception $e) {
			return false;
		}
	}

	// CALCULA EL MONTO DE LAS VENTAS TOTALES DE UN AUTOR
	public function getMontoAcumulado($id_author)
	{
		try {
			$sql = "	SELECT SUM(R.monto_al_autor) AS monto
					FROM Regalias R
						INNER JOIN Pedidos C
							ON R.idPedidos=C.idPedidos
					WHERE R.idUsuarios = $id_author;";
			$query = $this->db->query($sql);
			// $works_of_author['cantidad'] = 0;

			$monto = $query->result_array();

			if (isset($monto[0])) {
				$monto = $monto[0]['monto'];
			} else {
				$monto = 0;
			}

			return $monto;

		} catch (Exception $e) {
			return false;
		}
	}

	// TRAE LAS PUBLICACIONES FAVORITAS DE ESE AUTOR
	public function getWorksOfAuthorInFavorite($id_author, $limit)
	{
		try {
			$sql = "	SELECT
						T.idTrabajos, T.idUsuarios, T.titulo, T.texto, T.resumen, T.fecha, T.palabrasClave, T.cantidad_paginas,
						T.foto, T.idCategorias_parentId, F.idFavoritos
					FROM Trabajos T
					INNER JOIN Favoritos F
						ON F.idTrabajos=T.idTrabajos
					WHERE T.idEstados = 2
						AND F.idUsuarios = $id_author
					ORDER BY T.titulo
					$limit;";
			$query = $this->db->query($sql);
			$works_of_author['cantidad'] = 0;
			$works_of_author['publicacion'] = $query->result_array();

			foreach($works_of_author['publicacion'] AS $k => $work)
			{
				// CATEGORIA
				$categoria = $this->repo_categorias->getCategoryName($work['idCategorias_parentId']);
				$works_of_author['publicacion'][$k]['categoria']['nombre'] 	= $categoria;
				$works_of_author['publicacion'][$k]['categoria']['id'] 		= $work['idCategorias_parentId'];
				// SUBCATEGORIA
				$sub_categorias = $this->repo_categorias->getSubCategorysByWork($work['idTrabajos']);
				if( count($sub_categorias) == 0 ) {
					$works_of_author['publicacion'][$k]['sub_categoria'] = NULL;
				}else{
					foreach($sub_categorias as $k_sc => $sc) {
						$works_of_author['publicacion'][$k]['sub_categoria'][$k_sc]['id']	=	$sc['idCategorias'];
						$works_of_author['publicacion'][$k]['sub_categoria'][$k_sc]['nombre']	= $this->repo_categorias->getSubCategoryName($sc['idCategorias']);
					}
				}
				$works_of_author['cantidad'] = $k + 1;

			}

	            return $works_of_author;

		} catch (Exception $e) {
			return false;
		}
	}

	// CUENTA LAS PUBLICACIONES APROBADAS DEL AUTOR
	public function getCountWorksOfAuthor($id_author)
	{
		try {
			$sql = "	SELECT
						COUNT(*) AS cantidad
					FROM Trabajos T
					WHERE T.idEstados = 2
						AND T.idUsuarios = $id_author
					ORDER BY T.fecha;";
			$query = $this->db->query($sql);
			$cantidad = $query->result_array();
	            $cantidad = $cantidad[0]['cantidad'];
	            return $cantidad;

		} catch (Exception $e) {
			return false;
		}

	}

	// CUENTA LAS PUBLICACIONES VENDIDAS POR EL AUTO
	public function getCountWorksOfAuthorSolded($id_author)
	{
		try {
			$sql = "	SELECT
						COUNT(*) AS cantidad
					FROM Regalias R
					WHERE R.idUsuarios = $id_author
					;";
			$query = $this->db->query($sql);
			$cantidad = $query->result_array();
	            $cantidad = $cantidad[0]['cantidad'];
	            return $cantidad;

		} catch (Exception $e) {
			return false;
		}

	}

	// CUENTA LAS PUBLICACIONES APROBADAS DEL AUTOR
	public function getCountWorksOfAuthorInFavorite($id_author)
	{
		try {
			$sql = "	SELECT
						COUNT(*) AS cantidad
					FROM Favoritos F
					WHERE F.idUsuarios = $id_author;";
			$query = $this->db->query($sql);
			$cantidad = $query->result_array();
	            $cantidad = $cantidad[0]['cantidad'];
	            return $cantidad;

		} catch (Exception $e) {
			return false;
		}

	}

	// CUENTA LAS PUBLICACIONES DE UNA CATEGORIA DADA DE UN USUARIO EN PARTICULAR
	public function getCountWorksByUserAndCategory($id_user, $id_category)
	{
		try {
			$sql = "	SELECT COUNT(*) AS cantidad
					FROM Trabajos T
					WHERE T.idEstados = 2
						AND T.idUsuarios = $id_user AND T.idCategorias_parentId = $id_category;";
			$query = $this->db->query($sql);
			$cantidad = $query->result_array();
	            $cantidad = $cantidad[0]['cantidad'];
	            return $cantidad;

		} catch (Exception $e) {
			return false;
		}

	}


	// ELIMINA UNA PUBLICACIÓN
	public function erase($trabajo)
	{
		try {
			$this->db->trans_begin();
			$this->db->where('idTrabajos',$trabajo);
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

	// AGREGAR UNA VISITA A LA PUBLICACIÓN
	public function addVisita($id_work)
	{
		try {
			$visitas = $this->getVisita($id_work);
			if ($visitas == null) {
				$visitas = 0;
			}
			$visitas++;
			$data['visitas'] = $visitas;
			$this->db->where('idTrabajos',$id_work);
			$this->db->update('Trabajos', $data);
			if ($this->db->affected_rows() == 1) {
				return true;
			} else {
				return false;
			}

		} catch (Exception $e) {
			$this->db->trans_rollback();
			return false;
		}
	}

	// PUBLICACIONES DE UNA CATEGORIA
	public function getWorksByCategory($id_category, $limit, $params)
	{
		try {
			$sql = "	SELECT * FROM Trabajos T
					WHERE T.idCategorias_parentId = $id_category
							AND T.idEstados = 2 $limit ";
			$query 			= $this->db->query($sql);
			$show_work 	= $query->result_array();


			foreach($show_work AS $k => $work)
			{
				// si esta seteado el parametro que limite las palabras de resumen.
				if (isset($params) && is_array($params) && isset($params['resumen']))
				{
					$cant_letras = explode(".", $params['resumen']);
					$cant_letras = (int)$cant_letras[1];
					$this->load->helper('text');
					$show_work[$k]['resumen'] = character_limiter($work['resumen'], $cant_letras);
				}
				// CATEGORIA
				$categoria = $this->repo_categorias->getCategoryName($work['idCategorias_parentId']);
				$show_work[$k]['categoria']['nombre'] 	= $categoria;
				$show_work[$k]['categoria']['id'] 		= $work['idCategorias_parentId'];
				// SUBCATEGORIA
				$sub_categorias = $this->repo_categorias->getSubCategorysByWork($work['idTrabajos']);
				if( count($sub_categorias) == 0 ) {
					$show_work[$k]['sub_categoria'] = NULL;
				}else{
					foreach($sub_categorias as $k_sc => $sc) {
						$show_work[$k]['sub_categoria'][$k_sc]['id']	=	$sc['idCategorias'];
						$show_work[$k]['sub_categoria'][$k_sc]['nombre']	= $this->repo_categorias->getSubCategoryName($sc['idCategorias']);
					}
				}

			}

	            return $show_work;

		} catch (Exception $e) {
			return false;
		}
	}

	// PUBLICACIONES DE UNA CATEGORIA
	public function getWorksBySubCategory($id_category, $limit, $params)
	{
		try {

			$sql = "SELECT * FROM TrabajosCategorias TC
						JOIN Trabajos T
							ON TC.idTrabajos=T.idTrabajos
						WHERE T.idEstados = 2 AND TC.idCategorias = $id_category;
						";

			$query 			= $this->db->query($sql);
			$show_work 	= $query->result_array();



			foreach($show_work AS $k => $work)
			{
				if (isset($params) && is_array($params) && isset($params['resumen']))
				{
					$cant_letras = explode(".", $params['resumen']);
					$cant_letras = (int)$cant_letras[1];
					$this->load->helper('text');
					$show_work[$k]['resumen'] = character_limiter($work['resumen'], $cant_letras);
				}
				// CATEGORIA
				$parent_id = $this->repo_categorias->getParentId($id_category);
				$categoria = $this->repo_categorias->getCategoryName($parent_id);
				$show_work[$k]['categoria']['nombre'] 	= $categoria;
				$show_work[$k]['categoria']['id'] 		= $work['idCategorias_parentId'];
				// SUBCATEGORIA
				$sub_categorias = $this->repo_categorias->getSubCategorysByWork($work['idTrabajos']);
				if( count($sub_categorias) == 0 ) {
					$show_work[$k]['sub_categoria'] = NULL;
				}else{
					foreach($sub_categorias as $k_sc => $sc) {
						$show_work[$k]['sub_categoria'][$k_sc]['id']	=	$sc['idCategorias'];
						$show_work[$k]['sub_categoria'][$k_sc]['nombre']	= $this->repo_categorias->getSubCategoryName($sc['idCategorias']);
						if ($sc['idCategorias'] == $id_category) {
							$show_work[$k]['sub_categoria'][$k_sc]['seleccionada'] = true;
						}
					}
				}

			}


	            return $show_work;

		} catch (Exception $e) {
			return false;
		}
	}

	// SI ESTÁ APROBADA LA PUBLICACIÓN
	public function isEstadoAprobado($id)
	{
		try {
			$sql = "SELECT * FROM Trabajos T WHERE T.idTrabajos = $id AND T.idEstados = 2 ";
			$query = $this->db->query($sql);
			$trabajo = $query->result_array();

			if(isset($trabajo[0])) {
				$is_aprobado = true;
			}else{
				$is_aprobado = false;
			}

			return $is_aprobado;

		} catch (Exception $e) {
			return false;
		}
	}

	// ES FAVORITA ?
	public function isFavorito($id_user, $id_work)
	{
		try {
			$sql = "	SELECT * FROM Favoritos F
					WHERE F.idUsuarios = $id_user
							AND F.idTrabajos = $id_work";
			$query 			= $this->db->query($sql);
			$is_favorito	 	= $query->result_array();


			if( count($is_favorito) == 1 ) {
				return true;
			} else {
				return false;
			}

		} catch (Exception $e) {
			return false;
		}
	}

	// PONE UNA PUBLICACION COMO FAVORITA
	public function setFavorito($id_user, $id_work)
	{
		try {
			$data = array(
					   'idTrabajos' => $id_work,
					   'idUsuarios' => $id_user
					);
			$this->db->insert('Favoritos', $data);


		} catch (Exception $e) {
			return false;
		}
	}

	// PONE UNA PUBLICACION COMO FAVORITA
	public function unsetFavorito($id_favorito)
	{
		try {
			$data = array(
					   'idFavoritos' => $id_favorito
					);
 			$this->db->delete('Favoritos', $data);

		} catch (Exception $e) {
			return false;
		}
	}

	// AGARRA EL MONTO_POR_VENTA DEL ID PASADO POR PARÁMETRO
	public function getMontoPorVenta($id)
	{
		try {
			$sql = "SELECT * FROM Trabajos WHERE idTrabajos = $id ";
			$query = $this->db->query($sql);
			$trabajo = $query->result_array();

			if(isset($trabajo[0])) {
				$trabajo = $trabajo[0]['monto_por_venta'];
			}else{
				$trabajo = false;
			}

			return $trabajo;

		} catch (Exception $e) {
			return false;
		}
	}

	public function getPrecioConDerecho($id)
	{
		try {
			$sql = "SELECT * FROM Trabajos WHERE idTrabajos = $id ";
			$query = $this->db->query($sql);
			$trabajo = $query->result_array();

			if(isset($trabajo[0])) {
				$trabajo = $trabajo[0]['precio_con_derecho'];
			}else{
				$trabajo = false;
			}

			return $trabajo;

		} catch (Exception $e) {
			return false;
		}
	}

	// TRAE LA CANTIDAD DE VISITAS
	public function getVisita($id)
	{
		try {
			$sql = "SELECT * FROM Trabajos WHERE idTrabajos = $id ";
			$query = $this->db->query($sql);
			$trabajo = $query->result_array();

			if(isset($trabajo[0])) {
				$trabajo = $trabajo[0]['visitas'];
			}else{
				$trabajo = false;
			}

			return $trabajo;

		} catch (Exception $e) {
			return false;
		}
	}

	// SELECCIONA EL IDUSUARIOS POR EL ID DE LA PUBLICACIÓN
	public function getIdUsuario($id)
	{
		try {
			$sql = "SELECT * FROM Trabajos WHERE idTrabajos = $id ";
			$query = $this->db->query($sql);
			$trabajo = $query->result_array();

			if(isset($trabajo[0])) {
				$trabajo = $trabajo[0]['idUsuarios'];
			}else{
				$trabajo = false;
			}

			return $trabajo;

		} catch (Exception $e) {
			return false;
		}
	}

	// selecciono las publicaciones más visitadas.
	public function getPublicMasVisitadas($filter)
	{
		try {
			if (!isset($filter['limit'])) {
				$limit = '';
			} else {
				$limit = $filter['limit'];
			}


			$sql 			= "SELECT * FROM Trabajos T
								INNER JOIN Usuarios U
									ON T.idUsuarios=U.idUsuarios
								WHERE T.idEstados = 2 AND T.visitas > 0
								ORDER BY T.visitas DESC $limit  ";
			$query 			= $this->db->query($sql);
			$mas_visitadas 	= $query->result_array();

			if(isset($mas_visitadas[0])) {

			}else{
				$mas_visitadas = false;
			}

			return $mas_visitadas;

		} catch (Exception $e) {
			return false;
		}
	}

	public function contar($filter = NULL)
	{
		try
		{
			$sql = 'SELECT COUNT(T.idTrabajos) as "max"
					FROM Trabajos T
					INNER JOIN Usuarios U
						ON T.idUsuarios=U.idUsuarios
					WHERE T.idEstados = 2 AND T.visitas > 0
					ORDER BY T.visitas DESC';

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

	public function contar_autores_mas_visitados()
	{
		try
		{
			$sql = 'SELECT COUNT(T.idTrabajos) as "max"
					FROM Trabajos T
					INNER JOIN Usuarios U
						ON T.idUsuarios=U.idUsuarios
					WHERE T.idEstados = 2 AND T.visitas > 0';

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

	// SELECCIONA LOS AUTORES QUE TUVIERON MAS VISITAS EN SUS PUBLICACIONES
	// TODO: VA A FALTAR RESOLVER EL PAGINADOR
	public function getAutoresMasVisitados( $filter )
	{
		try {
			$sql = "SELECT * FROM Trabajos T
					INNER JOIN Usuarios U
						ON T.idUsuarios=U.idUsuarios
					WHERE T.idEstados = 2 AND T.visitas > 0
					ORDER BY T.visitas DESC";
			$query 					= $this->db->query($sql);
			$autores_mas_visitados = $query->result_array();

			// ARMO UN ARRAY CON LOS AUTORES QUE TIENEN VISITAS EN SUS PUBLICACIONES
			$catch_autor_id 		= array();
			$catch_autor_nombre 	= array();
			$catch_autor_apellido 	= array();
			$catch_autor_visitas 	= array();
			foreach ($autores_mas_visitados AS $k => $mas_visit)
			{
				if (!in_array($mas_visit['idUsuarios'], $catch_autor_id))
				{
					array_push($catch_autor_id, $mas_visit['idUsuarios']);
					array_push($catch_autor_nombre, $mas_visit['nombre']);
					array_push($catch_autor_apellido, $mas_visit['apellido']);
				}
			}
			// RECORRO EL ARRAY DE LOS AUTORES Y SUMO PUBLICACIONES
			foreach ($catch_autor_id AS $aut)
			{
				array_push($catch_autor_visitas, $this->getCantVisitasByAuthor($aut));

			}
			// PARA RETORNAR AUTORES
			foreach ($catch_autor_id AS $k => $id) {
				$autores[$k]['id'] = $id;
			}
			foreach ($catch_autor_nombre AS $k => $nombre) {
				$autores[$k]['nombre'] = $nombre;
			}
			foreach ($catch_autor_apellido AS $k => $apellido) {
				$autores[$k]['apellido'] = $apellido;
			}
			foreach ($catch_autor_visitas AS $k => $visitas) {
				$autores[$k]['visitas'] = $visitas;
			}

			$autores[0]['cantidad_maxima'] = $k + 1;

			// PAGINADOR
			if ($filter != NULL && isset($filter['limit'])) {
				$autores = array_slice($autores, $filter['limit']['start'], $filter['limit']['end']);
			}

			return $autores;

		} catch (Exception $e) {
			return false;
		}
	}

	public function contarAutoresMasVisitados()
	{
		$filter = null;
		$cant_aut_mas_visitados = $this->getAutoresMasVisitados( $filter );
		return count($cant_aut_mas_visitados);
	}

	public function getCantVisitasByAuthor($idUsuarios)
	{
		try {
			$sql 		= "SELECT T.visitas  FROM Trabajos T
							WHERE T.idEstados = 2 AND T.visitas > 0 AND T.idUsuarios = $idUsuarios";
			$query 		= $this->db->query($sql);
			$visitas 	= $query->result_array();

			$total_visitas= 0;
			foreach ( $visitas AS $vis)
			{
				$total_visitas += $vis['visitas'];
			}

			return $total_visitas;

		} catch (Exception $e) {
			return false;
		}
	}

	// NOS DÁ LA CANTIDAD DE PUBLICACIONES QUE SE VENDIERON
	public function getCantVendidas($idTrabajos)
	{
		try {
			$sql 		= "SELECT COUNT(R.idRegalias) AS vendidas
							FROM Regalias R
							INNER JOIN Pedidos P
								ON R.idPedidos=P.idPedidos
							WHERE P.idTrabajos = $idTrabajos";
			$query 		= $this->db->query($sql);
			$cant 		= $query->result_array();

			if (isset($cant[0]['vendidas'])) {
				return $cant[0]['vendidas'];
			} else {
				return false;
			}

		} catch (Exception $e) {
			return false;
		}
	}

	// NOS DÁ LA CANTIDAD DE PUBLICACIONES QUE VENDIO EL AUTOR
	public function getCantPublicVendidas($id_user)
	{
		try {
			$sql 		= "SELECT COUNT(R.idRegalias) AS cantidad
							FROM Regalias R
							WHERE R.idUsuarios = $id_user";
			$query 		= $this->db->query($sql);
			$cant 		= $query->result_array();

			if (isset($cant[0]['cantidad'])) {
				return $cant[0]['cantidad'];
			} else {
				return false;
			}

		} catch (Exception $e) {
			return false;
		}
	}

	// NOS DÁ LA CANTIDAD DE PUBLICACIONES QUE SE VENDIERON
	public function getRegalias($idTrabajos)
	{
		try {
			$sql 		= "SELECT SUM(R.monto_al_autor) AS regalias
							FROM Regalias R
							INNER JOIN Pedidos P
								ON R.idPedidos=P.idPedidos
							WHERE P.idTrabajos = $idTrabajos";
			$query 		= $this->db->query($sql);
			$regalias 	= $query->result_array();

			if (isset($regalias[0]['regalias'])) {
				return $regalias[0]['regalias'];
			} else {
				return false;
			}

		} catch (Exception $e) {
			return false;
		}
	}

	// PUBLICACIONES MAS VENDIDAS
	public function getPublicMasVendidas($filter)
	{
		try {

			$sql 			= "SELECT * FROM Regalias R
								INNER JOIN Pedidos P
									ON R.idPedidos=P.idPedidos
								INNER JOIN Trabajos T
									ON P.idTrabajos=T.idTrabajos";
			$query 			= $this->db->query($sql);
			$mas_vendidas 	= $query->result_array();

			// VOY A PONER LOS IDTRABAJOS QUE SE VENDIERON.
			$idTrabajos_sold = array();
			foreach ($mas_vendidas as $mv)
			{
				if (!in_array($mv['idTrabajos'], $idTrabajos_sold)) {
					array_push($idTrabajos_sold, $mv['idTrabajos']);
				}
			}

			foreach ($idTrabajos_sold AS $k => $id_trab) // SON LOS IDTRABAJOS QUE SE VENDIERON
			{
				$total_vendidas[$k]['idTrabajos'] = $id_trab;
				$total_vendidas[$k]['titulo'] 		= $this->getTitulo($id_trab);
				$total_vendidas[$k]['idUsuarios']= $this->getIdUsuario($id_trab);
				$total_vendidas[$k]['autor'] 		= $this->getAutor($id_trab);
				$total_vendidas[$k]['cantidad']	= $this->getCantVendidas($id_trab);
				$total_vendidas[$k]['regalias']	= $this->getRegalias($id_trab);
			}

			$this->aasort($total_vendidas, "cantidad");
			$total_vendidas = array_reverse($total_vendidas);

			// PAGINADOR
			if ($filter != NULL && isset($filter['limit'])) {
				$total_vendidas = array_slice($total_vendidas, $filter['limit']['start'], $filter['limit']['end']);
			}

			return $total_vendidas;


		} catch (Exception $e) {
			return false;
		}
	}

	public function contarMasVendidas()
	{
		$filter = null;
		$mas_vendidas = $this->getPublicMasVendidas($filter);
		return count($mas_vendidas);
	}

	// PUBLICACIONES MAS VENDIDAS
	public function getAutoresMasVendidos( $filter )
	{
		try {
			$sql 			= "SELECT R.idUsuarios FROM Regalias R
								INNER JOIN Pedidos P
									ON R.idPedidos=P.idPedidos
								INNER JOIN Trabajos T
									ON P.idTrabajos=T.idTrabajos";
			$query 			= $this->db->query($sql);
			$mas_vendidas 	= $query->result_array();

			// VOY A PONER LOS AUTORES QUE VENDIERON
			$idUsuarios_sold = array();
			foreach ($mas_vendidas as $mv)
			{
				if (!in_array($mv['idUsuarios'], $idUsuarios_sold)) {
					array_push($idUsuarios_sold, $mv['idUsuarios']);
				}
			}

			$regalias_no_pagadas = 0;
			$regalias_pagadas = 0;
			foreach ($idUsuarios_sold AS $k => $id_autor) // SON LOS AUTORES QUE VENDIERON
			{
				$autores_mas_vendidos[$k]['idUsuarios']= $id_autor;

				$autores_mas_vendidos[$k]['autor'] 	= $this->repo_usuarios->getNombreApellido($id_autor);

				$autores_mas_vendidos[$k]['cantidad']	= $this->getCantPublicVendidas($id_autor);
				$autores_mas_vendidos[$k]['regalias']	= $this->repo_usuarios->getRegalia($id_autor);
				$autores_mas_vendidos[$k]['regalias_pagadas'] = $this->repo_pagos->getRegaliasPagadas($id_autor);

				// if ($id_autor == 35) {
				// 	var_dump($autores_mas_vendidos[$k]['regalias_pagadas']);
				// 	die();
				// }


				$regalias_no_pagadas = $regalias_no_pagadas + $autores_mas_vendidos[$k]['regalias'];
				$regalias_pagadas = $regalias_pagadas + $autores_mas_vendidos[$k]['regalias_pagadas'];
			}

			$this->aasort($autores_mas_vendidos, "cantidad");

			$autores_mas_vendidos = array_reverse($autores_mas_vendidos);

			$autores_mas_vendidos[0]['reg_no_pagadas']	= (float)$regalias_no_pagadas; 	// REGALÍAS TOTALES NO PAGADAS
			$autores_mas_vendidos[0]['reg_pagadas']		= (float)$regalias_pagadas; 		// REGALÍAS TOTALES PAGADAS


			if( count($autores_mas_vendidos) > 0)
			{
				// PAGINADOR
				if ($filter != NULL && isset($filter['limit'])) {
					$autores_mas_vendidos = array_slice($autores_mas_vendidos, $filter['limit']['start'], $filter['limit']['end']);
				}
			}


			return $autores_mas_vendidos;

		} catch (Exception $e) {
			return false;
		}
	}

	protected function aasort(&$array, $key)
	{
			    $sorter=array();
			    $ret=array();
			    reset($array);
			    foreach ($array as $ii => $va) {
			        $sorter[$ii]=$va[$key];
			    }
			    asort($sorter);
			    foreach ($sorter as $ii => $va) {
			        $ret[$ii]=$array[$ii];
			    }
			    $array=$ret;
	}

	// CUENTA LOS AUTORES QUE VENDIERON SUS PUBLICACIONES, EL TOTAL
	public function contar_autores_mas_vendidos()
	{
		$filter = null;
		$aut_mas_vendidos = $this->getAutoresMasVendidos( $filter );
		$cant 				= count($aut_mas_vendidos);

		return $cant;
	}

	// CUENTA LOS AUTORES QUE VENDIERON SUS PUBLICACIONES, EL TOTAL
	public function RegaliasTotales()
	{
		$filter = null;
		$aut_mas_vendidos = $this->getAutoresMasVendidos( $filter );
		$reg_totales		= $aut_mas_vendidos[0]['reg_totales'];

		return $reg_totales;
	}

	// params puede recibir  'resumen' => 'limit_chars.250'
	// y esto quiere decir que el campo resumen de Trabajos lo limita a 250 letras
	public function getBuscados( $params = null )
	{
		try {
			$word_search = '"%' . $params['palabras_buscadas'] . '%"';
			$sql = "	SELECT *
					FROM Trabajos T
					INNER JOIN Usuarios U
						ON T.idUsuarios=U.idUsuarios
					WHERE T.idEstados = 2
						AND (T.titulo LIKE $word_search
												OR T.palabrasClave LIKE $word_search
												OR T.resumen LIKE $word_search
												OR T.texto LIKE $word_search
												OR U.nombre LIKE $word_search
												OR U.apellido LIKE $word_search
												OR U.email LIKE $word_search)
						ORDER BY T.idTrabajos DESC";


			$query = $this->db->query($sql);

			$destacados = $query->result_array();

			foreach($destacados AS $k => $dest)
			{
				// ACORTO EL RESUMEN SI PASARON POR PARAMS
				if (isset($params) && is_array($params) && isset($params['resumen']))
				{
					$cant_letras = explode(".", $params['resumen']);
					$cant_letras = (int)$cant_letras[1];
					$this->load->helper('text');
					$destacados[$k]['resumen'] = character_limiter($dest['resumen'], $cant_letras);
				}
				// AUTOR
				$usuario = $this->repo_usuarios->getById($dest['idUsuarios']);
				$cant_publicados 		= $this->getCantidadPublicados($dest['idUsuarios']);
				$destacados[$k]['autor'] = $usuario;
				$destacados[$k]['autor']['publicados'] = $cant_publicados;
				// CATEGORIA
				$categoria = $this->repo_categorias->getCategoryName($dest['idCategorias_parentId']);
				$destacados[$k]['categoria']['nombre'] 	= $categoria;
				$destacados[$k]['categoria']['id'] 		= $dest['idCategorias_parentId'];
				// SUBCATEGORIA
				$sub_categorias = $this->repo_categorias->getSubCategorysByWork($dest['idTrabajos']);
				if( count($sub_categorias) == 0 ) {
					$destacados[$k]['sub_categoria'] = NULL;
				}else {
					foreach($sub_categorias as $k_sc => $sc) {
						$destacados[$k]['sub_categoria'][$k_sc]['id']	=	$sc['idCategorias'];
						$destacados[$k]['sub_categoria'][$k_sc]['nombre']	= $this->repo_categorias->getSubCategoryName($sc['idCategorias']);
					}
				}

			}

	            return $destacados;

		} catch (Exception $e) {
			return false;
		}
	}




}

?>
