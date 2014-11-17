<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Repo_estados extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->config('estados');
	}

	// NO FUNCIONA
	public function getById($categoria){
		try {

			$query = $this->db->query("SELECT C.* FROM Categorias C WHERE C.idCategorias = " . (int)$categoria['idCategorias']);

			$categoria = $query->row_array();
			$categoria['subCategorias'] = $this->getSubCategorias($categoria);
			return $categoria;

		} catch (Exception $e) {
			return array();
		}
	}

	// NO FUNCIONA
	public function getName()
	{
		try {
			$query = $this->db->query("SELECT C.* FROM Categorias C WHERE C.parentId = 0 ORDER BY C.nombreCategoria ASC");
			return $query->result_array();
		} catch (Exception $e) {
			return array();
		}
	}

	// SELECCIONA TODOS LOS ESTADOS QUE PUEDEN TENER LOS TRABAJOS
	public function getAll(){
		try {
			$sql = "SELECT * FROM EstadosTrabajos ORDER BY estado ASC";
			$query = $this->db->query($sql);
			return $query->result_array();
		} catch (Exception $e) {
			return array();
		}
	}



}

?>
