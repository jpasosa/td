<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Is_categorias extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->config('estados');
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

	// ES UNA SUBCATEGORIA?
	public function isParentCategory($id_cat)
	{
		try {
			$sql = "SELECT parentId FROM Categorias WHERE idCategorias = $id_cat ";
			$query = $this->db->query($sql);
			$res =  $query->result_array();
			if ($res[0]['parentId'] == 0) {
				return true;
			} else {
				return false;
			}

		} catch (Exception $e) {
			// ERROR
			return array();
		}
	}

}

?>
