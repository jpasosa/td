<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Repo_categorias extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->config('estados');
	}


	public function getById($categoria)
	{
		try {

			$query = $this->db->query("SELECT C.* FROM Categorias C WHERE C.idCategorias = " . (int)$categoria['idCategorias']);

			$categoria = $query->row_array();
			$categoria['subCategorias'] = $this->getSubCategorias($categoria);
			return $categoria;

		} catch (Exception $e) {
			return array();
		}
	}

	public function getOnlyById($id)
	{
		try {

			$query 		= $this->db->query("SELECT C.* FROM Categorias C WHERE C.idCategorias = $id");
			$categoria 	= $query->row_array();
			return $categoria;

		} catch (Exception $e) {
			return array();
		}
	}


	public function getCategorys()
	{
		try {
			$query = $this->db->query("SELECT C.* FROM Categorias C WHERE C.parentId = 0 ORDER BY C.nombreCategoria ASC");
			return $query->result_array();
		} catch (Exception $e) {
			return array();
		}
	}


	public function getCategoryName($id)
	{
		try {
			$query = $this->db->query("SELECT C.nombreCategoria FROM Categorias C WHERE C.idCategorias = $id");
			$categoria = $query->result_array();

			if(isset($categoria[0])) {
				$name_category = $categoria[0]['nombreCategoria'];
			} else {
				$name_category = false; // no encontró la categoria
			}
			return $name_category;
		} catch (Exception $e) {
			return array();
		}
	}

	public function getCategoryImage($id)
	{
		try {
			$query = $this->db->query("SELECT * FROM Categorias C WHERE C.idCategorias = $id");
			$categoria = $query->result_array();
			if(isset($categoria[0])) {
				$name_category = $categoria[0]['imagen'];
			} else {
				$name_category = false; // no encontró la categoria
			}
			return $name_category;
		} catch (Exception $e) {
			return array();
		}
	}


	public function getSubCategorys($categoria) // controlar bien, no la testie
	{
		try {
			$query = $this->db->query("SELECT C.* FROM Categorias C WHERE C.parentId = " . (int)$categoria['idCategorias'] ." ORDER BY C.nombreCategoria ASC");
			return $query->result_array();
		} catch (Exception $e) {
			return array();
		}
	}



	public function getSubCategoryName($id)
	{
		try {

			$query = $this->db->query("SELECT C.nombreCategoria FROM Categorias C WHERE C.idCategorias = " . (int)$id);
			$sub_category = $query->result_array();

			return $sub_category[0]['nombreCategoria'];

		} catch (Exception $e) {
			return array();
		}
	}


	public function getSubCategorysById($id)
	{
		try {
			$query = $this->db->query("SELECT C.* FROM Categorias C WHERE C.parentId = " . (int)$id ." ORDER BY C.nombreCategoria ASC");
			return $query->result_array();
		} catch (Exception $e) {
			return array();
		}
	}


	public function getSubCategorysByName($name) // TODO
	{
		try {
			// $query = $this->db->query("SELECT C.* FROM Categorias C WHERE C.parentId = " . (int)$categoria['idCategorias'] ." ORDER BY C.nombreCategoria ASC");
			return $query->result_array();
		} catch (Exception $e) {
			return array();
		}
	}




	public function getSubCategoriasDisponibles($categoria = NULL)
	{
		try {
			if(isset($categoria)) {
				$query = $this->db->query("SELECT C.* FROM Categorias C WHERE (C.parentId = 0 OR C.parentId = ". $categoria['idCategorias'] .") AND C.idCategorias NOT IN (SELECT parentId FROM Categorias WHERE parentId > 0)  ORDER BY C.nombreCategoria ASC");
				//$query = $this->db->query("SELECT C.* FROM Categorias C WHERE C.parentId = 0 AND C.idCategorias NOT IN (SELECT parentId FROM Categorias WHERE parentId > 0) ORDER BY C.nombreCategoria ASC");
			} else {
				$query = $this->db->query("SELECT C.* FROM Categorias C WHERE C.parentId = 0 AND C.idCategorias NOT IN (SELECT parentId FROM Categorias WHERE parentId > 0) ORDER BY C.nombreCategoria ASC");
			}

			return $query->result_array();
		} catch (Exception $e) {
			return array();
		}
	}


	public function getSubCategorysByWork($id_work)
	{
		try {
			$query = "SELECT TC.* FROM TrabajosCategorias TC WHERE TC.idTrabajos = $id_work";
			$sub_categorys = $this->db->query($query);
			return $sub_categorys->result_array();
		} catch (Exception $e) {
			return array();
		}
	}


	// SELECCIONA PUBLICACIONES SOBRE LA CATEGORIA DADA
	public function getAllTemasToShow($id_category)
	{
		try {
			$sql = "
				SELECT *
				FROM Trabajos T
				WHERE T.idCategorias_parentId = $id_category
						AND T.idEstados = 2
				ORDER BY rand()
				LIMIT 2 ";
			$query = $this->db->query($sql);

			$temas = $query->result_array();

			foreach($temas AS $k => $tem)
			{
				// AUTOR
				$usuario = $this->repo_usuarios->getById($tem['idUsuarios']);

				$cant_publicados = $this->repo_trabajos->getCantidadPublicados($tem['idUsuarios']);
				$temas[$k]['autor'] = $usuario;
				$temas[$k]['autor']['publicados'] = $cant_publicados;
				// CATEGORIA
				$categoria = $this->repo_categorias->getCategoryName($tem['idCategorias_parentId']);
				$temas[$k]['categoria']['nombre'] 	= $categoria;
				$temas[$k]['categoria']['id'] 		= $tem['idCategorias_parentId'];
				// SUBCATEGORIA
				$sub_categorias = $this->repo_categorias->getSubCategorysByWork($tem['idTrabajos']);
				if( count($sub_categorias) == 0 ) {
					$temas[$k]['sub_categoria'] = NULL;
				}else{
					foreach($sub_categorias as $k_sc => $sc) {
						$temas[$k]['sub_categoria'][$k_sc]['id']	=	$sc['idCategorias'];
						$temas[$k]['sub_categoria'][$k_sc]['nombre']	= $this->repo_categorias->getSubCategoryName($sc['idCategorias']);
					}
				}

			}

	            return $temas;

		} catch (Exception $e) {
			return false;
		}
	}


	// Selecciona publicaciones sobre la categoria dada
	// params puede recibir  'resumen' => 'limit_chars.250'
	//                            y esto quiere decir que el campo descripcion de Trabajos lo limita a 250 letras
	public function getTemaToShow($id_category, $params = null)
	{
		try {
			$sql = "
				SELECT *
				FROM Trabajos T
				WHERE T.idCategorias_parentId = $id_category
						AND T.idEstados = 2
				ORDER BY rand()
				LIMIT 2 ";
			$query = $this->db->query($sql);

			$temas = $query->result_array();


			foreach($temas AS $k => $tem)
			{
				if (isset($params) && is_array($params) && isset($params['resumen']))
				{
					$cant_letras = explode(".", $params['resumen']);
					$cant_letras = (int)$cant_letras[1];
					$this->load->helper('text');
					$temas[$k]['resumen'] = character_limiter($tem['resumen'], $cant_letras);
				}
				// AUTOR
				$usuario = $this->repo_usuarios->getById($tem['idUsuarios']);

				$cant_publicados = $this->repo_trabajos->getCantidadPublicados($tem['idUsuarios']);
				$temas[$k]['autor'] = $usuario;
				$temas[$k]['autor']['publicados'] = $cant_publicados;
				// CATEGORIA
				$categoria = $this->repo_categorias->getCategoryName($tem['idCategorias_parentId']);
				$categoria_imagen = $this->repo_categorias->getCategoryImage($tem['idCategorias_parentId']);
				$temas[$k]['categoria']['imagen'] 	= $categoria_imagen;
				$temas[$k]['categoria']['nombre'] 	= $categoria;
				$temas[$k]['categoria']['id'] 			= $tem['idCategorias_parentId'];
				// SUBCATEGORIA
				$sub_categorias = $this->repo_categorias->getSubCategorysByWork($tem['idTrabajos']);
				if( count($sub_categorias) == 0 ) {
					$temas[$k]['sub_categoria'] = NULL;
				}else{
					foreach($sub_categorias as $k_sc => $sc) {
						$temas[$k]['sub_categoria'][$k_sc]['id']	=	$sc['idCategorias'];
						$temas[$k]['sub_categoria'][$k_sc]['nombre']	= $this->repo_categorias->getSubCategoryName($sc['idCategorias']);
					}
				}

			}

	            return $temas;

		} catch (Exception $e) {
			return false;
		}
	}

	// SELECCIONA PUBLICACIONES SOBRE LA SUB CATEGORIA DADA
	public function getTemaToShowIsSubCategory($id_category, $params = null)
	{
		try {

			$temas = array();

			$sql 		= "SELECT * FROM TrabajosCategorias WHERE idCategorias = $id_category";
			$query 		= $this->db->query($sql);

			$trab_cat 	= $query->result_array();

			$count_work = 0;
			foreach( $trab_cat AS $k => $tc )
			{
				if($this->repo_trabajos->isEstadoAprobado($tc['idTrabajos']))
				{
					$work = $this->repo_trabajos->getById($tc['idTrabajos']);
					if (isset($params) && is_array($params) && isset($params['resumen']))
					{
						$cant_letras = explode(".", $params['resumen']);
						$cant_letras = (int)$cant_letras[1];
						$this->load->helper('text');
						$work['resumen'] = character_limiter($work['resumen'], $cant_letras);
					}

					$count_work++;
					if($count_work == 4) {
						break;
					} else {
						array_push($temas, $work);
					}

				}
			}

			foreach($temas AS $k => $tem)
			{
				// AUTOR
				$usuario = $this->repo_usuarios->getById($tem['idUsuarios']);

				$cant_publicados = $this->repo_trabajos->getCantidadPublicados($tem['idUsuarios']);
				$temas[$k]['autor'] = $usuario;
				$temas[$k]['autor']['publicados'] = $cant_publicados;
				// CATEGORIA
				$categoria = $this->repo_categorias->getCategoryName($tem['idCategorias_parentId']);
				$temas[$k]['categoria']['nombre'] 	= $categoria;
				$temas[$k]['categoria']['id'] 		= $tem['idCategorias_parentId'];
				// SUBCATEGORIA
				$sub_categorias = $this->repo_categorias->getSubCategorysByWork($tem['idTrabajos']);
				if( count($sub_categorias) == 0 ) {
					$temas[$k]['sub_categoria'] = NULL;
				}else{
					foreach($sub_categorias as $k_sc => $sc) {
						$temas[$k]['sub_categoria'][$k_sc]['id']	=	$sc['idCategorias'];
						$temas[$k]['sub_categoria'][$k_sc]['nombre']	= $this->repo_categorias->getSubCategoryName($sc['idCategorias']);
					}
				}

			}

	            return $temas;

		} catch (Exception $e) {
			return false;
		}
	}

	// ELIMINACION DE UNA CATEGORIA, TODO: DEBE CONTROLAR QUE NO ESTÉ EN UNA PUBLICACION
	// Y DEBE AVISAR QUE ES ASI, HAY QYE VER COMO QUIEREN HACER.
	public function delete($categoria)
	{
		try {
			$this->db->trans_begin();

			$this->db->where('idCategorias',$categoria['idCategorias']);
			$this->db->delete('Categorias');

			$this->db->where('parentId',$categoria['idCategorias']);
			$this->db->delete('Categorias');

			if($this->db->trans_status() === TRUE){
				$this->db->trans_commit();
				return TRUE;
			}
			else{
				$this->db->trans_rollback();
				return FALSE;
			}
		} catch (Exception $e) {
			$this->db->trans_rollback();
			return FALSE;
		}
	}

	// SELECCIONA TODAS LAS CATEGORIAS Y SUBCATEGORIAS
	public function getAll()
	{
		try {
			$sql = "SELECT C.* FROM Categorias C ORDER BY C.nombreCategoria ASC";
			$query = $this->db->query($sql);
			return $query->result_array();
		} catch (Exception $e) {
			return array();
		}
	}

	// SELECCIONAR TODAS LAS CATEGORIAS QUE TENGAN PUBLICACIONES RELACIONADAS
	public function getAllCategorysWithWorksActives($params = null)
	{
		try {
			// SELECCIONO SOLAMENTE LAS CATEGORIAS QUE TENGAS PUBLICACIONES ACTIVAS
			$cat_con_publicaciones = array();
			$categorias = $this->getCategorys();
			foreach($categorias AS $cat)
			{
				$id_categoria= $cat['idCategorias'];
				$sql 		= "	SELECT * FROM Trabajos T WHERE T.idCategorias_parentId = $id_categoria";
				$query 		= $this->db->query($sql);
				$trabajos 	= $query->result_array();
				if(count($trabajos) > 0) {
					array_push($cat_con_publicaciones, $id_categoria);
				}
			}

			$all_works_category = array();

			foreach( $cat_con_publicaciones AS $cat) {
				array_push($all_works_category, $this->getTemaToShow($cat, array('resumen' => 'limit_chars.250')));
			}

			foreach($all_works_category AS $k => $works) {
				if(!isset($works[0])) {
					unset($all_works_category[$k]);
				}
			}

			return $all_works_category;

		} catch (Exception $e) {
			return false;
		}

	}

	// SELECCIONA LA CATEGORIA PADRE
	public function getParentId($id_sub_category)
	{
		try {
			$sql 		= "SELECT parentId FROM Categorias WHERE idCategorias = $id_sub_category ";
			$query 		= $this->db->query($sql);
			$result 		= $query->result_array();
			$category_id = $result[0]['parentId'];

			return $category_id;

		} catch (Exception $e) {
			// ERROR
			return array();
		}
	}


	// ES UNA SUBCATEGORIA?
	public function isSubCategory($id_cat)
	{
		try {
			$sql = "SELECT parentId FROM Categorias WHERE idCategorias = $id_cat ";
			$query = $this->db->query($sql);
			$res =  $query->result_array();
			if ($res[0]['parentId'] == 0) {
				return false;
			} else {
				return true;
			}

		} catch (Exception $e) {
			// ERROR
			return array();
		}
	}

	// VA A SELECCIONAR 10 CATEGORIAS, CON SUS SUB_CATEGORIAS RESPECTIVAS Y LAS IMÁGENES PARA MOSTRAR EN LA HOME
	public function getCategorysToHomepage()
	{
		try {
			$sql = "
				SELECT *
				FROM Categorias C
				WHERE C.parentId = 0
				ORDER BY rand()
				LIMIT 10";
			$query = $this->db->query($sql);

			$categorias = $query->result_array();

			foreach($categorias AS $k => $cat)
			{
				$categorias[$k]['categoria_id'] 		= $cat['idCategorias'];
				$categorias[$k]['categoria_nombre'] = $cat['nombreCategoria'];
				$categorias[$k]['categoria_imagen'] 	= $cat['imagen'];
				// SUBCATEGORIAS
				$sub_categorias = $this->getSubCategorys($cat);
				if(count($sub_categorias > 0))
				{
					foreach($sub_categorias AS $k_sub => $sc) {
						$categorias[$k]['subcategorias'][$k_sub]['sub_cat_id'] 		= $sc['idCategorias'];
						$categorias[$k]['subcategorias'][$k_sub]['sub_cat_nombre'] 	= $sc['nombreCategoria'];
					}
				}
				unset($categorias[$k]['idCategorias']);
				unset($categorias[$k]['nombreCategoria']);
				unset($categorias[$k]['parentId']);
				unset($categorias[$k]['imagen']);
			}

	            return $categorias;

		} catch (Exception $e) {
			return false;
		}
	}

}

?>
