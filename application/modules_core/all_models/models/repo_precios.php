<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Repo_precios extends CI_Model {

	public function __construct(){
		parent::__construct();
	}


	// ELIMINA UN PRECIO
	public function erase($precio)
	{
		try {
			$this->db->trans_begin();

			$this->db->where('idPrecios',$precio['idPrecios']);
			$this->db->delete('Precios');

			if($this->db->trans_status() === TRUE){
				$this->db->trans_commit();
				return TRUE;
			}else{
				$this->db->trans_rollback();
				return FALSE;
			}
		} catch (Exception $e) {
			$this->db->trans_rollback();
			return FALSE;
		}
	}

	// SELECCIONA EL REGISTRO DE PRECIOS POR EL ID
	public function getById($precio)
	{
		try {
			$query = $this->db->query("SELECT P.* FROM Precios P WHERE P.idPrecios = " . (int)$precio['idPrecios']);
			$precio = $query->row_array();
			return $precio;

		} catch (Exception $e) {
			return array();
		}
	}

	// SELECCIONA TODOS LOS PRECIOS
	public function getAll()
	{
		try {
			$sql = "SELECT P.* FROM Precios P ORDER BY P.idPrecios ASC";
			$query = $this->db->query($sql);
			return $query->result_array();
		} catch (Exception $e) {
			return array();
		}
	}

}

?>