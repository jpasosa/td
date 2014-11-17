<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class estadostrabajos_model extends CI_Model
{
	public function __contruct()
	{
		parent::__contruct();
	}

	public function getAll()
	{
		try {
			$query = $this->db->query("SELECT * FROM EstadosTrabajos ET ORDER BY ET.estado ASC ");
			return $query->result_array();
		} catch (Exception $e) {
			return array();
		}
	}
}
