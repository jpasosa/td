<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Repo_estadostrabajos extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->config('estados');
	}

	// SELECCIONA TODOS LOS ESTADOS QUE PUEDE TENER UNA PUBLICACIÓN
	public function getAll()
	{
		try {
			$sql = "SELECT E.* FROM EstadosTrabajos E ORDER BY E.idEstados ASC";
			$query = $this->db->query($sql);
			return $query->result_array();
		} catch (Exception $e) {
			return array();
		}
	}


}

?>
