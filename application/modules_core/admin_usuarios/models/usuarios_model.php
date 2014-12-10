<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Usuarios_model extends CI_Model {



		public function __contruct(){
				parent::__construct();
		}




		public function get($usuario) {

				$this->load->model('admin_permisos/permisos_model');
				try {
						$condicion = "";

						if(isset($usuario['idUsuarios'])){
										$condicion = "AND U.idUsuarios = ". (int)$usuario['idUsuarios'];
						}elseif(isset($usuario['email'])){
										$condicion = "AND U.email = ". $this->db->escape($usuario['email']);
						}else {
										return NULL;
						}

						$query = $this->db->query("
																				SELECT U.*,R.key as 'rolKey',R.descripcion as 'rolDescripcion' FROM Usuarios U,Roles R
																				WHERE U.idRoles = R.idRoles ".$condicion);
						unset($usuario);
						$usuario = $query->row_array();

						if(!isset($usuario)){
										return NULL;
						}

						$usuario['permisos'] = $this->permisos_model->get($usuario);
						return $usuario;

				} catch (Exception $e) {
						unset($usuario);
						throw new Exception($e->getMessage());
				}
		}





		public function update($usuario,$isAdmin = false){
				try {
						if(isset($usuario['fileFoto']['newFileName']) ) {
										$this->procesarFoto($usuario['fileFoto']);
										$foto = $usuario['fileFoto']['newFileName'];
										$this->db->set('avatar',$foto);
						}
						if(isset($usuario['fileFoto']['old_foto'])){
										$this->deleteFoto($usuario['fileFoto']);
										$this->db->set('avatar','');
						}
						if(isset($usuario['fecha'])){
										$this->db->set('fecha',date('Y-m-d',strtotime($usuario['fecha'])));
						}
						if(isset($usuario['lugar'])){
										$this->db->set('lugar',$usuario['lugar']);
						}
						if(isset($usuario['intereses'])){
										$this->db->set('intereses',$usuario['intereses']);
						}
						if(isset($usuario['idFormasPago'])){
										$this->db->set('idFormasPago',$usuario['idFormasPago']);
						}
						if(isset($usuario['profesion'])){
										$this->db->set('profesion',$usuario['profesion']);
						}
						if(isset($usuario['biografia'])){
										$this->db->set('biografia',$usuario['biografia']);
						}
						if(isset($usuario['regalias'])) {
										$this->db->set('regalias',$usuario['regalias']);
						}
						if(isset($usuario['estado'])) {
										$this->db->set('estado',$usuario['estado']);
						}
						if(isset($usuario['esAutor'])) {
										$this->db->set('esAutor',$usuario['esAutor']);
						}
						if(isset($usuario['esEditorial'])) {
										$this->db->set('esEditorial',$usuario['esEditorial']);
						}

						$dataUsuario = array(		'nombre' 	=> $usuario['nombre'],
													'apellido' 	=> $usuario['apellido'],
													'nombre_mostrar' 	=> $usuario['nombre_mostrar'],
													'email' 		=> $usuario['email'],
													'telefono' 	=> $usuario['telefono'],
													'direccion_calle' => $usuario['direccion_calle'],
													'direccion_numero' => $usuario['direccion_numero'],
													'cod_postal'=> $usuario['cod_postal'],
													'localidad' 	=> $usuario['localidad'],
													'ciudad' 	=> $usuario['ciudad'],
													'pais' 		=> $usuario['pais']
											);

						if(isset($usuario['idRoles']) && $isAdmin){
										$data['idRoles'] = (int)$usuario['idRoles'];
						}

						if(isset($usuario['clave'])){
										$usuario['clave'] = trim($usuario['clave']);
										if(!empty($usuario['clave'])){
														$this->db->set('clave',"PASSWORD(".$this->db->escape($usuario['clave']). ")",false);
										}
						}

						$this->db->update('Usuarios',$dataUsuario,"idUsuarios = ".(int)$usuario['idUsuarios']);
						unset($usuario);
						return TRUE;
				} catch (Exception $e) {
						return FALSE;
				}
		}





		public function delete($usuario){
			try {
				if((int)$usuario['idUsuarios'] == 1){
					return FALSE;
				}
				$is_in_publicaciones = $this->isInPublicaciones($usuario['idUsuarios']);
				if ($is_in_publicaciones) {
					return false; // EL USUARIO YA SE ENCUENTRA CON PUBLICACIONES ACTIVAS, NO SE PUEDE BORRAR.
				}
				$is_in_pedidos = $this->isInPedidos($usuario['idUsuarios']);
				if ($is_in_pedidos) {
					return false; // EL USUARIO YA SE ENCUENTRA EN LA TABLA PEDIDOS
				}
				$is_in_regalias = $this->isInRegalias($usuario['idUsuarios']);
				if ($is_in_regalias) {
					return false; // EL USUARIO YA SE ENCUENTRA EN LA TABLA REGALIAS
				}

				$this->db->delete('Usuarios',"idUsuarios = " .(int)$usuario['idUsuarios']);
				if($this->db->affected_rows() > 0){
					return true;
				}else{
					return false;
				}

			} catch (Exception $e) {
				return false;
			}
		}

		// SI EL USUARIO SE ENCUENTRA EN ALGUNA PUBLICACIÓN
		protected function isInPublicaciones($id_user)
		{
				try {
						$sql = "SELECT * FROM Trabajos T WHERE T.idUsuarios = $id_user AND T.idEstados = 2 ";
						$query = $this->db->query($sql);
						$trabajo = $query->result_array();

						if(isset($trabajo[0])) {
								$is_in_publicaciones = true;
						}else{
								$is_in_publicaciones = false;
						}

						return $is_in_publicaciones;

				} catch (Exception $e) {
						return false;
				}
		}

		// SI EL USUARIO SE ENCUENTRA EN ALGUN PEDIDO
		protected function isInPedidos($id_user)
		{
				try {
					$sql            = "SELECT * FROM Pedidos P WHERE P.idUsuarios = $id_user";
					$query		= $this->db->query($sql);
					$pedido 	= $query->result_array();

					if(isset($pedido[0])) {
							$is_in_pedidos = true;
					}else{
							$is_in_pedidos = false;
					}

					return $is_in_pedidos;

				} catch (Exception $e) {
					return false;
				}
		}

		// SI EL USUARIO SE ENCUENTRA EN REGALIAS
		protected function isInRegalias($id_user)
		{
				try {
					$sql            = "SELECT * FROM Regalias R WHERE R.idUsuarios = $id_user";
					$query		= $this->db->query($sql);
					$regalia 	= $query->result_array();

					if(isset($regalia[0])) {
							$is_in_regalia = true;
					}else{
							$is_in_regalia = false;
					}

					return $is_in_regalia;

				} catch (Exception $e) {
					return false;
				}
		}





		//Si existe el usuario devuelve verdadero.
		public function existe($usuario){
				try {
								$query = $this->db->query('SELECT U.idUsuarios FROM Usuarios U WHERE U.email = '. $this->db->escape($usuario['email']) . ' OR U.userName = ' . $this->db->escape($usuario['userName']) );
								if($query->num_rows() <= 0){
												return false;
								}else{
												return true;
								}
				} catch (Exception $e) {
								throw new Exception($e->getMessage());
				}
		}





		public function validarPerfil($usuario){
				$this->load->library('email');
				foreach ($usuario as $key => $value) {
								$usuario[$key] = trim($value);
				}
				$error = false;

				if(!isset($usuario['nombre']) or empty($usuario['nombre']) )  {
								$error['nombre'] = 'El nombre es obligatorio';
				}
				if(!isset($usuario['apellido']) or  empty($usuario['apellido']) )   {
								$error['apellido'] = 'El apellido es obligatorio';
				}
				if(!isset($usuario['nombre_mostrar']) or  empty($usuario['nombre_mostrar']) )   {
								$error['nombre_mostrar'] = 'El nombre a mostrar es obligatorio';
				}
				if(!isset($usuario['email']) or empty($usuario['email']) )   {
								$error['email'] = 'El email es obligatorio';
				}elseif (!$this->email->valid_email($usuario['email'])) {
								$error['email'] = 'El email es incorrecto';
				}

				if(isset($usuario['clave']) && empty($usuario['clave']) ) {
								$error['clave'] = 'La contrase&ntilde;a es obligatoria';
				}elseif (isset($usuario['clave']) && (!isset($usuario['clave_re']) or empty($usuario['clave_re'])) ) {
								$error['clave_re'] = 'Debe re-ingresar la clave';
				}elseif(isset($usuario['clave'],$usuario['clave_re']) && $usuario['clave'] != $usuario['clave_re']) {
								$error['clave'] = 'Las contrase&ntilde;as no coinciden';
				}

				return $error;
		}







		public function validarNuevo($usuario)
		{
				$this->load->library('email');
				$errors = false;

				// if (isset($usuario['fileFoto']['name']) && mb_strlen($usuario['fileFoto']['name']) > 60) {
				// 	$errors['avatar_largo'] = true;
				// }

				if(!isset($usuario['nombre'])){
					$errors['nombre'] = true;
				}
				if(!isset($usuario['apellido'])){
					$errors['apellido'] = true;
				}
				if(!isset($usuario['nombre_mostrar'])){
					$errors['nombre_mostrar'] = true;
				}
				if (!isset($usuario['email'])) {
					$errors['email'] = true;
				}elseif (!$this->email->valid_email($usuario['email'])) {
					$errors['email_not_valid'] = true;
				}

				if(!isset($usuario['telefono'])){
					$errors['telefono'] = true;
				}elseif (!preg_match("/^[0-9-]+$/", $usuario['telefono'])) {
					$errors['telefono_incorrecto'] = true;
				}


				return $errors;
		}







		public function listar($filter, $isAdmin = false){
				try {
						if (!isset($filter['where'])){
										$filter['where'] = "";
						}
						if( isset($filter['idUsuarios'])) {
										$filter['where'] = "AND U.idUsuarios = ".(int)$filter['value'];
						}elseif (isset($filter['email'])) {
										$filter['where'] = "AND U.email like '".trim($filter['value']) . "%'";
						}elseif (isset($filter['apellido'])){
										$filter['where'] = "AND U.apellido like '%".trim($filter['value']) . "%'";
						}

						if(!$isAdmin){
										$filter['where'] = " AND U.estado = 'Activo' ";
						}

						if(!isset($filter['limit'])){
										$filter['limit'] = "";
						}


						$query = $this->db->query("
																SELECT U.*,R.key as 'rolKey',R.descripcion as 'rolDescripcion'
																FROM Usuarios U,Roles R
																WHERE U.idRoles = R.idRoles
																		AND R.idRoles > 1 " . $filter['where'] . "
																ORDER BY U.regalias DESC " . $filter['limit']);

						if($query->num_rows() <= 0){
										return NULL;
						}else{
										return $query->result_array();
						}
				} catch (Exception $e) {
								//throw new Exception($e->getMessage());
								return array();
				}
		}







		public function contar($filter = NULL){
				try {
						if(isset($filter['idUsuarios'])) {
										$filter['where'] = "AND U.idUsuarios = ".(int)$filter['value'];
						}elseif (isset($filter['email'])) {
										$filter['where'] = "AND U.email like '%".trim($filter['value']) ."%'";
						}elseif(isset($filter['apellido'])){
										$filter['where'] = "AND U.apellido like '%".trim($filter['value']) . "%'";
						}

						$query = $this->db->query("
																SELECT COUNT(U.idUsuarios) as 'max'
																FROM Usuarios U,Roles R
																WHERE U.idRoles = R.idRoles ".$filter['where']);
						$rows = $query->row_array();
						if($rows['max'] <= 0 ) {
										return 0;
						}else{
										return $rows['max'];
						}
				} catch (Exception $e) {
						throw new Exception($e->getMessage());
				}
		}













public function alta($usuario) {
		try {
				if(isset($usuario['fileFoto']) && !isset($usuario['fileFoto']['delete']) ) {
								$this->procesarFoto($usuario['fileFoto']);
								$foto = $usuario['fileFoto']['newFileName'];
								$this->db->set('avatar',$foto);
				}

				if(isset($usuario['fecha'])){
								$this->db->set('fecha',date('Y-m-d',strtotime($usuario['fecha'])));
				}

				if(isset($usuario['lugar'])){
								$this->db->set('lugar',$usuario['lugar']);
				}

				if(isset($usuario['profesion'])){
								$this->db->set('profesion',$usuario['profesion']);
				}

				if(isset($usuario['biografia'])){
								$this->db->set('biografia',$usuario['biografia']);
				}

				if(isset($usuario['intereses'])){
								$this->db->set('intereses',$usuario['intereses']);
				}

				$dataUsuario = array(
										'nombre' 			=> $usuario['nombre'],
										'apellido' 			=> $usuario['apellido'],
										'nombre_mostrar' 	=> $usuario['nombre_mostrar'],
										'email' 				=> $usuario['email'],
										'regalias' 			=> $usuario['regalias'],
										'esAutor' 			=> $usuario['esAutor'],
										'esEditorial' 		=> $usuario['esEditorial'],
										'telefono' 			=> $usuario['telefono'],
										'direccion_calle' 	=> $usuario['direccion_calle'],
										'direccion_numero' => $usuario['direccion_numero'],
										'cod_postal'		=> $usuario['cod_postal'],
										'localidad' 			=> $usuario['localidad'],
										'ciudad' 			=> $usuario['ciudad'],
										'pais' 				=> $usuario['pais'],
										'estado' 			=> $usuario['estado'],
										'idFormasPago' 	=> $usuario['idFormasPago']
										);

				$this->db->insert('Usuarios',$dataUsuario);
				$idUsuarios = $this->db->insert_id();

				if(!isset($idUsuarios) or $idUsuarios < 1 ){
								return 0;
				}else {
								return $idUsuarios;
				}

		} catch (Exception $e) {
						return 0;
		}
}







public function deleteFoto($foto){
		try {
				if( is_file(FCPATH . "/assets/imagenes/Usuarios/".$foto['old_foto'])
								&& file_exists(FCPATH . "/assets/imagenes/Usuarios/".$foto['old_foto'] ) ) {
														unlink(FCPATH . "/assets/imagenes/Usuarios/".$foto['old_foto']);
				}
				return true;

		} catch (Exception $e) {
				return FALSE;
		}
}




		public function procesarFoto($foto){
				try {
						$errores = NULL;

						if(move_uploaded_file($foto['tmp_name'],  "web/uploads/usuarios/avatar/". $foto['newFileName']))
						{
							$image = $foto['newFileName'];
							// USUARIO COMUN (285 x 166)
							$config = array();
							$config['image_library'] 	= 'gd2';
							$config['source_image']	= "web/uploads/usuarios/avatar/$image";
							$config['create_thumb'] = FALSE;
							$config['maintain_ratio'] = TRUE;
							$config['width']	 		= 285;
							$config['height']			= 166;
							$this->image_lib->initialize($config);
							$foto_user = $this->image_lib->resize();
							$this->image_lib->clear();
							// USUARIO THUMB (52 x 30)
							$config = array();
							$config['image_library'] 	= 'gd2';
							$config['source_image']	= "web/uploads/usuarios/avatar/$image";
							$config['new_image'] 	= "web/uploads/usuarios/avatar/thumbs/$image";
							$config['create_thumb'] = FALSE;
							$config['maintain_ratio'] = TRUE;
							$config['width']	 		= 52;
							$config['height']			= 30;
							$this->image_lib->initialize($config);
							$foto_user_thumb = $this->image_lib->resize();
							if ( ! $foto_user_thumb) {
									$errores['foto'] = 'No se pudo redimensionar la imagen';
							}
							$this->image_lib->clear();
						}else{
										$errores['foto'] = 'No se pudo cargar la imagen';
						}

						echo $this->image_lib->display_errors();

						return $errores;

				} catch (Exception $e) {
						return NULL;
				}
		}












		public function checkEmail($usuario){
				try {
						$sql = '
								SELECT U.idUsuarios
								FROM Usuarios U
								WHERE U.email = ' .$this->db->escape(trim($usuario['email']));
						$query = $this->db->query($sql);
						return $query->row_array();

				} catch (Exception $e) {
				return 0;
				}
		}





		public function getAll(){
				try {
						$sql = "
								SELECT *
								FROM Usuarios U
								WHERE U.estado = 1
								ORDER BY U.nombre ASC,U.apellido
								ASC";
						$query = $this->db->query($sql);
						return $query->result_array();

				} catch (Exception $e) {
						return array();
				}
		}

		public function getUsuariosById($id){
				try {
						$sql = "
								SELECT *
								FROM Usuarios U
								WHERE U.idUsuarios = $id
								";
						$query = $this->db->query($sql);
						$user = $query->result_array();
						$user = $user[0];
						return $user;

				} catch (Exception $e) {
						return false;
				}
		}





}
?>