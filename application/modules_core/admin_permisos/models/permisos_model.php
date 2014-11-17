<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permisos_model extends CI_Model {

	public function __construct(){
		parent::__construct();
	}

	public function get($rol) {
		try {
			$query = $this->db->query("
				SELECT P.idPermisos,P.key as 'permKey',
					P.valor as 'permValue', P.descripcion as 'permDescripcion'
				FROM Roles R, Permisos P
				WHERE  R.idRoles = P.idRoles AND P.idRoles = ".(int)$rol['idRoles'] );
			$rol['permisos'] = $query->result_array();
			return $rol;
		} catch (Exception $e) {
			//throw new Exception($e->getMessage());
		}
	}


	public function update($permiso){
		try {
			$data = array(
				'valor' => $permiso['valor'],
				'descripcion' => $permiso['descripcion'],
				'key' => $permiso['key']
			);
			$this->db->update('Permisos',$data,"idPermisos = ".(int)$permiso['idPermisos'] );
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	public function insert($permiso){
		try {
			$this->db->insert('Permisos',$permiso);
			return $this->db->insert_id();
		} catch (Exception $e) {
			return false;
		}
	}

	public function delete($permiso){
		try {
			$this->db->delete('Permisos','idPermisos = '.(int)$permiso['idPermisos']);
			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	public function validar($permiso,$validar = array()){
		$errores = false;
		if(!isset($permiso['idPermisos']) and isset($validar['idPermisos'])){
			$errores['idPermisos'] = "Dato incorrecto";
		}
		if(!isset($permiso['idRoles']) and isset($validar['idRoles'])){
			$errores['idRoles'] = "Dato incorrecto";
		}
		if(!isset($permiso['valor'])){
			$errores['valor'] = "Debe ingresar un valor";
		}
		if(!isset($permiso['key'])){
			$errores['key']= "La llave es obligatoria";
		}
		return $errores;
	}

}