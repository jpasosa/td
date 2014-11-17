<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Roles_model extends CI_Model {

    public function __construct(){
        parent::__construct();
        //$this->output->enable_profiler(TRUE);
        $this->load->model('admin_permisos/permisos_model');
    }

    public function get_all($filter = NULL) {
        try {

        	if(!isset($filter['where'])){
				$filter['where'] = "";
			}

			if(!isset($filter['limit'])){
				$filter['limit'] = "";
			}

            $query = $this->db->query("
            	SELECT R.idRoles,R.descripcion as 'rolDescripcion',R.key as 'rolKey'
            	FROM Roles R ". $filter['where'] ."
            	ORDER BY R.descripcion ". $filter['limit']);
            return $query->result_array();
        }
        catch (Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function contar($filter = NULL){
		try {
			if(!isset($filter['where'])){
				$filter['where'] = "";
			}

			$query = $this->db->query("SELECT COUNT(R.idRoles) as 'max' FROM Roles R ".$filter['where']);
			$rows = $query->row_array();
			if($rows['max'] <= 0 ){
				return 0;
			}
			else{
				return $rows['max'];
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	public function get($rol){
		try {
			$query = $this->db->query("SELECT R.idRoles,R.descripcion as 'rolDescripcion',R.key as 'rolKey' FROM Roles R WHERE R.idRoles =".(int)$rol['idRoles'] . " ORDER BY R.descripcion");
			$rol = $query->row_array();
			$dataRol = $this->permisos_model->get($rol);
			$rol['permisos'] = $dataRol['permisos'];
			unset($dataRol);
			return $rol;
		} catch (Exception $e) {
			return NULL;
		}
	}

	public function delete($rol){
		try {
			$this->db->delete('Roles','idRoles = '.(int)$rol['idRoles']);
			if($this->db->affected_rows() > 0){
				return true;
			}
			else{
				return false;
			}
		} catch (Exception $e) {
			return false;
		}
	}

	public function update($rol){
		try {
			$this->db->update('Roles',$rol,'idRoles = '.(int)$rol['idRoles']);
		} catch (Exception $e) {
		}
	}

	public function insert($rol){
		try {
			$this->db->insert('Roles',$rol);
			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	public function validar($rol,$notId = false){
		$errores = false;
		if(!isset($rol['idRoles']) and !$notId){
			$errores['idRoles'] = "Dato incorrecto";
		}
		if(!isset($rol['descripcion'])){
			$errores['descripcion'] = "La descripci&oacute;n es obligatoria";
		}
		if(!isset($rol['key'])){
			$errores['key']= "La llave es obligatoria";
		}
		return $errores;
	}

}