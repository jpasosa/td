<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Precios_model extends CI_Model {

	public function __construct(){
		parent::__construct();
	}



	public function add($precio)
	{
		try {
			$data = array(
					'precio' => $precio['precio']
					);

			$this->db->insert('Precios',$data);
			$precio['idPrecios'] = (int)$this->db->insert_id();

			if(!isset($precio['idPrecios'])) {
				return false;
			}else{
				return $precio['idPrecios'];
			}


		} catch (Exception $e) {
			// Algun tipo de error.
			return false;
		}
	}



	public function edit($precio)
	{
		try {

			$data = array(
					'precio' => $precio['precio']
					);

			$this->db->where('idPrecios',$precio['idPrecios'] );
			$this->db->update('Precios',$data);

			return TRUE;


		} catch (Exception $e) {
			return FALSE;
		}
	}

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


	public function listing($filter = NULL)
	{
		try {
			$sql = '';
			$condicion= '';

			// if(isset($filter['idCategorias'])) {
			// 	$condicion = " AND C.idCategorias = ".(int)$filter['idCategorias']. " ";
			// }
			// elseif(isset($filter['nombreCategoria'])) {
			// 	$condicion = " AND C.nombreCategoria like '%" . trim($filter['nombreCategoria']) ."%'";
			// }

			$sql = 'SELECT * FROM Precios P  '. $condicion .' ORDER BY P.idPrecios ASC ' . $filter['limit'];


			$query = $this->db->query($sql);
			$precios = $query->result_array();
			if(sizeof($precios) == 0){
				return array();
			}else{
				return $precios;
			}

		} catch (Exception $e) {
			return array();
		}
	}

	public function contar($filter = NULL)
	{
		try {
			$sql = '';
			$condicion = "";
			// if(isset($filter['idCategorias'])){
			// 	$condicion = " WHERE C.idCategorias = ".(int)$filter['idCategorias']. " ";
			// }
			// elseif(isset($filter['nombreCategoria'])){
			// 	$condicion = " WHERE C.nombreCategoria like '%" . trim($filter['nombreCategoria']) ."%'";
			// }

			$sql = "SELECT COUNT(P.idPrecios) as 'max' FROM Precios P ".$condicion;

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