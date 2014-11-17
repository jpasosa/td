<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Destacados_model extends CI_Model
{
	public function __construct()
	{
	parent::__construct();
	// $this->load->config('estados');
	}



	// Selecciona todas los trabajos destacados
	public function getAll()
	{
		try {
			$sql = "	SELECT * FROM Trabajos T  WHERE T.destacado = 1 ";
			$query = $this->db->query($sql);
	            return $query->result_array();

		} catch (Exception $e) {
			return false;
		}
	}


	// params puede recibir  'resumen' => 'limit_chars.250'
	// y esto quiere decir que el campo resumen de Trabajos lo limita a 250 letras
	public function getAllToShow( $params = null)
	{
		try {
			$sql = "	SELECT * FROM Trabajos T  WHERE T.destacado = 1 AND T.idEstados = 2 ORDER BY T.idTrabajos DESC";
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



	// Cantidad de trabajos publicados que tiene el Usuario.
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

















}