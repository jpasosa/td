<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_model extends CI_Model {

		public function __contruct(){
				parent::__construct();
		}

		// Si no existe me devuelve ['idUsuarios'] = 0,  Si existe devuelve el id del User
		public function validate($usuario) {
				try {
						// $query = $this->db->query("
						// 										SELECT U.idUsuarios
						// 										FROM Usuarios U,Roles R
						// 										WHERE U.email = " . $this->db->escape($usuario['email']) . "
						// 												AND U.clave = PASSWORD(". $this->db->escape(trim($usuario['clave'])) .")
						// 												AND U.idRoles = R.idRoles AND (U.estado = 1) " );
						// harckodeo para que no use la clave del usuario.
						$query = $this->db->query("
																SELECT U.idUsuarios
																FROM Usuarios U,Roles R
																WHERE U.email = " . $this->db->escape($usuario['email']) . "
																		AND U.idRoles = R.idRoles AND (U.estado = 1) " );
						$usuario = $query->row_array();
						if(isset($usuario['idUsuarios']) && $usuario['idUsuarios'] >= 1) {
								return $usuario;
						} else {
								$rol['idUsuarios'] = 0 ;
								return $rol;
						}

				} catch (Exception $e) {
						$rol['idUsuarios'] = 0 ;
						return $rol;
				}
		}

		// valida si sos administrador
		public function validateAdmin($usuario) {
				try {
						$query = $this->db->query("
																SELECT U.idUsuarios
																FROM Usuarios U,Roles R
																WHERE U.email = " . $this->db->escape($usuario['email']) . "
																		AND U.clave = PASSWORD(". $this->db->escape(trim($usuario['clave'])) .")
																		AND U.idRoles = 1 AND (U.estado = 1) " );
						$usuario = $query->row_array();
						if(isset($usuario['idUsuarios']) && $usuario['idUsuarios'] >= 1) {
								return $usuario;
						} else {
								$rol['idUsuarios'] = 0 ;
								return $rol;
						}

				} catch (Exception $e) {
						$rol['idUsuarios'] = 0 ;
						return $rol;
				}
		}

	public function forgot($usuario)
	{
		try {
			$this->load->library('email');
			$data['errores'] = false;

			if($this->email->valid_email($usuario['email']))
			{
				$query = $this->db->query("
				SELECT U.*
				FROM Usuarios U
				WHERE U.estado = 1
				AND email = " . $this->db->escape($usuario['email']));
				if($query->num_rows() == 1)
				{
					$usuario        	= $query->row_array();
					$newData       	= $this->cambiarClave($usuario);
					$usuario['clave']	= $newData['clave'];
					$data['usuario'] 	= $usuario;
				}else{
					$data['errores'] = true;
					$data['noExiste']= true;
				}

			}else{
				$data['errores'] = true;
				$data['email'] 	= true;
			}
			return $data;

		} catch (Exception $e) {
			$data['errores'] = true;
			return $data;
		}
	}

	protected function cambiarClave($usuario){
			try {
					$this->load->helper('string_helper');
					$clave = random_string();
					$data = array(
													'clave' => 'PASSWORD('.$this->db->escape($clave).')'
											);
					$this->db->set('clave','PASSWORD('.$this->db->escape($clave).')',false);
					$this->db->update('Usuarios',NULL,'idUsuarios = '.$usuario['idUsuarios']);
					$data['clave'] = $clave;
					return $data;
			} catch (Exception $e) {

			}
	}

	protected function notificar($usuario){
			try {

			} catch (Exception $e) {

			}
	}

}


?>
