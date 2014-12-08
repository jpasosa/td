<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Repo_usuarios extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->config('estados');
	}

	// TRAE EL USUARIO
	public function get($usuario)
	{
		$this->load->model('admin_permisos/permisos_model');
		try {
			$condicion = "";

			if(isset($usuario['idUsuarios'])) {
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

	// TRAE EL USUARIO
	public function getMail($id_user)
	{
		try {

			$query = $this->db->query("
									SELECT email FROM Usuarios
									WHERE idUsuarios = $id_user");
			$usuario = $query->row_array();

			if(isset($usuario['email'])){
				return $usuario['email'];
			} else {
				return false;
			}

		} catch (Exception $e) {
			unset($usuario);
			throw new Exception($e->getMessage());
		}
	}

	// TRAE EL USUARIO
	public function getNombreApellido($id_user)
	{
		try {

			$query = $this->db->query("
									SELECT nombre, apellido FROM Usuarios
									WHERE idUsuarios = $id_user");
			$usuario = $query->row_array();

			if(isset($usuario['nombre'])){
				return $usuario['nombre'] . ' ' . $usuario['apellido'];
			} else {
				return false;
			}

		} catch (Exception $e) {
			unset($usuario);
			throw new Exception($e->getMessage());
		}
	}

	// TRAE REGALIAS
	public function getRegalia($id_user)
	{
		try {
			$sql 	= "SELECT regalias FROM Usuarios
						WHERE idUsuarios = $id_user ";
			$query 	= $this->db->query($sql);
			$regalia = $query->row_array();

			if ( isset($regalia['regalias'])) {
				return $regalia['regalias'];
			} else {
				return false;
			}

		} catch (Exception $e) {
			unset($usuario);
			throw new Exception($e->getMessage());
		}
	}

	// TRAE REGALIAS
	public function getAllRegalias()
	{
		try {
			$sql 	= "SELECT * FROM Usuarios WHERE regalias > 0; ";
			$query 	= $this->db->query($sql);
			$regalia = $query->result_array();;

			if ( isset($regalia[0])) {
				return $regalia;
			} else {
				return false;
			}

		} catch (Exception $e) {
			unset($usuario);
			throw new Exception($e->getMessage());
		}
	}

	// ELIMINA UN USUARIO
	public function delete($usuario)
	{
		try {
			if((int)$usuario['idUsuarios'] == 1){
				return FALSE;
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





	// SI EL USUARIO EXISTE DEVUELVE TRUE
	public function exist($usuario)
	{
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

	// BUSCA SI EXISTE EL MAIL
	public function existMail($mail)
	{
		try {
			$query = $this->db->query("SELECT U.idUsuarios FROM Usuarios U WHERE U.email = '$mail' ");
			if($query->num_rows() <= 0){
				return false;
			}else{
				return true;
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	// TRAE EL USUARIO, PASANDO EL ID DE LA PUBLICACION
	public function getAuthorByWork($id_work)
	{
		try {
			$condicion = "";

			$query = $this->db->query("
									SELECT U.idUsuarios, U.email
									FROM Trabajos T
									INNER JOIN Usuarios U
										ON T.idUsuarios = U.idUsuarios
									WHERE T.idTrabajos = $id_work");

			$usuario = $query->row_array();

			if(!isset($usuario)){
				return NULL;
			} else {
				return $usuario;
			}

		} catch (Exception $e) {
			unset($usuario);
			throw new Exception($e->getMessage());
		}
	}

	// SELECCIONA TODOS LOS USUARIOS
	public function getAll()
	{
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

	// SELECCIONA TODOS LOS USUARIOS
	public function getPublicaciones($id_user)
	{
		try {
			$sql = "
					SELECT T.idTrabajos, T.titulo
					FROM Trabajos T
					INNER JOIN Usuarios U
						ON T.idUsuarios=U.idUsuarios
					WHERE U.idUsuarios = $id_user
						AND T.idEstados = 2
					";
			$query = $this->db->query($sql);
			$publicaciones = $query->result_array();

			return $publicaciones;

		} catch (Exception $e) {
			return array();
		}
	}

	// SELECCIONA POR ID
	public function getById($id)
	{
		try {
			$sql = "
					SELECT *
					FROM Usuarios U
					WHERE U.idUsuarios = $id
					";
			$query = $this->db->query($sql);
			$user = $query->result_array();
			if(isset($user[0])) {
				$user = $user[0];
			}

			return $user;

		} catch (Exception $e) {
			return false;
		}
	}

	// SELECCIONA POR ID
	public function getByEmail($email)
	{
		try {
			$sql = "
					SELECT *
					FROM Usuarios U
					WHERE U.email = '$email'
					";
			$query = $this->db->query($sql);
			$user = $query->result_array();
			if(isset($user[0])) {
				$user = $user[0];
			}

			return $user;

		} catch (Exception $e) {
			return false;
		}
	}

	// SELECCIONA POR LA VERIFICACION QUE SE AGREGA AL CREAR UNA CUENTA POR PRIMERA VEZ
	public function getByVerificacion($hash)
	{
		try {
			$sql = "
					SELECT *
					FROM Usuarios U
					WHERE U.verificacion = '$hash'
					";
			$query 	= $this->db->query($sql);
			$user 	= $query->result_array();

			if(isset($user[0])) {
				$user = $user[0]['idUsuarios'];
			} else {
				$user = false;
			}

			return $user;

		} catch (Exception $e) {
			return false;
		}
	}


	// SELECCIONA LOS ÚLTIMOS AUTORES CREADOS
	// TODO: Vamos a tener que verificar si el autor ya tiene al menos un trabajo creado. ..
	public function getUltimosAutores($limit)
	{
		try {
			$limit = (int)$limit;
			$sql = "SELECT *
					FROM Usuarios U
					WHERE U.estado = 1
							AND U.esAutor = 1
					ORDER BY U.idUsuarios DESC
					LIMIT $limit
					";
			$query = $this->db->query($sql);
			$autores = $query->result_array();
			return $autores;

		} catch (Exception $e) {
			return false;
		}
	}


	// COMPRUEBA SI EXISTE EL USUARIO, CONTROLANDO EL MAIL
	public function existUser($email)
	{
		try {
			$sql = "SELECT *
					FROM Usuarios U
					WHERE U.email = '$email'
					";
			$query = $this->db->query($sql);
			$user = $query->result_array();
			if(count($user) > 0) {
				$user = true;
			}else {
				$user = false;
			}

			return $user;

		} catch (Exception $e) {
			return false;
		}
	}

	// COMPRUEBA SI EXISTE EL USUARIO, CONTROLANDO EL MAIL
	public function isEditorial($id_user)
	{
		try {
			$sql = "SELECT *
					FROM Usuarios U
					WHERE U.idUsuarios = $id_user
					";
			$query 	= $this->db->query($sql);
			$user 	= $query->result_array();

			if ($user[0]['esEditorial'] == 1) {
				return true;
			} else {
				return false;
			}

		} catch (Exception $e) {
			return false;
		}
	}

	// CREA UN USUARIO
	public function create($user)
	{
		try {

			$user['nombre'] = $user['user'];
			unset($user['user']);
			$user['clave'] = $user['pass'];
			unset($user['pass']);
			unset($user['repeat_pass']);

			$user['fecha'] = trim($user['fecha']);
			// $user['fecha'] = date('Y-m-d', strtotime(str_replace('-', '/', $user['fecha'])));
			$user['fecha'] = str_replace('/', '-', $user['fecha']); // modifico este valor, por que si no, no convierte bien.
			$user['fecha'] = date('Y-m-d',strtotime($user['fecha']));

			$insert_user = $this->db->insert('Usuarios', $user);

			if($insert_user) {
				$insert_id 		= $this->db->insert_id();
				$clave 			= $user['clave'];
				$sql 			= "UPDATE Usuarios SET clave = PASSWORD('$clave') WHERE  idUsuarios=$insert_id";
				$query 			= $this->db->query($sql);
				$user_update 	= $this->db->affected_rows();
			}
			if($user_update == 1) {
				return true;
			}else {
				return false;
			}

		} catch (Exception $e) {
				return false;
		}
	}

	// ACTIVA UN USUARIO
	public function activate($id_user)
	{
		try {


			$data = array('estado' => 1);
			$this->db->where('idUsuarios', $id_user);
			$this->db->update('Usuarios', $data);

			$user_update 	= $this->db->affected_rows();

			if($user_update == 1) {
				return true;
			} else {
				return false;
			}


		} catch (Exception $e) {
				return false;
		}
	}

	// SELECCIONA PUBLICACIONES Y EL PERFIL DEL USUARIO.
	public function getPerfilAndWorks($id_author, $limit)
	{
		try {
			// USUARIO
			$sql = "	SELECT *
					FROM Usuarios U
					WHERE estado = 1 AND idUsuarios = $id_author
					;";
			$query = $this->db->query($sql);
			$result = $query->result_array();
			$perfil_works['usuario'] 	= $result[0];

			$perfil_works['usuario']['fecha'] = date('d-m-Y',strtotime($perfil_works['usuario']['fecha']));
			// PUBLICACIONES
			$sql = "	SELECT
						T.idTrabajos, T.idUsuarios, T.titulo, T.fecha, T.idCategorias_parentId
					FROM Trabajos T
					WHERE T.idEstados = 2
						AND T.idUsuarios = $id_author
					ORDER BY T.idCategorias_parentId
					$limit;";
			$query = $this->db->query($sql);
			$perfil_works['cantidad'] = 0;
			$all_publicaciones 		= $query->result_array();
			// CATEGORIA
			$categorias = array();
			foreach($all_publicaciones AS $pu) {
				array_push($categorias, $pu['idCategorias_parentId']);
			}
			$categorias = array_unique($categorias);

			foreach($categorias AS $cat_id)
			{
				// PUBLICACIONES de una categoria
				$sql = "	SELECT * FROM Trabajos T WHERE T.idEstados = 2 AND T.idUsuarios = $id_author AND T.idCategorias_parentId = $cat_id
						ORDER BY T.fecha;";
				$query = $this->db->query($sql);
				$perfil_works['publicaciones'][$cat_id] 	= $query->result_array();
				// $perfil_works['publicaciones'][$cat_id]['imagen'] = $this->repo_categorias->getCategoryImage($cat_id);

				// echo $perfil_works['publicaciones'][$cat_id]['imagen']; die();

				foreach($perfil_works['publicaciones'][$cat_id] AS $k => $work)
				{
					// CATEGORIA
					$categoria = $this->repo_categorias->getCategoryName($work['idCategorias_parentId']);
					$perfil_works['publicaciones'][$cat_id][$k]['categoria']['imagen'] 	= $this->repo_categorias->getCategoryImage($work['idCategorias_parentId']);
					$perfil_works['publicaciones'][$cat_id][$k]['categoria']['nombre'] 	= $categoria;
					$perfil_works['publicaciones'][$cat_id][$k]['categoria']['id'] 		= $work['idCategorias_parentId'];
					// SUBCATEGORIA
					$sub_categorias = $this->repo_categorias->getSubCategorysByWork($work['idTrabajos']);
					if( count($sub_categorias) == 0 ) {
						$perfil_works['publicaciones'][$cat_id][$k]['sub_categoria'] = NULL;
					}else{
						$last_subCategory = end($sub_categorias);
						foreach($sub_categorias as $k_sc => $sc) {
							$perfil_works['publicaciones'][$cat_id][$k]['sub_categoria'][$k_sc]['id']	=	$sc['idCategorias'];
							$perfil_works['publicaciones'][$cat_id][$k]['sub_categoria'][$k_sc]['nombre']	= $this->repo_categorias->getSubCategoryName($sc['idCategorias']);
							if($last_subCategory['idCategorias'] == $sc['idCategorias']) {
								$perfil_works['publicaciones'][$cat_id][$k]['sub_categoria'][$k_sc]['ultima'] = true;
							}else {
								$perfil_works['publicaciones'][$cat_id][$k]['sub_categoria'][$k_sc]['ultima'] = false;
							}
						}
					}
					$perfil_works['cantidad'] = $k + 1;
				}
			}

	            return $perfil_works;

		} catch (Exception $e) {
			return false;
		}
	}

	// SELECCIONA PUBLICACIONES Y PERFIL DE UN USUARIO, SEGÚN UNA CATEGORIA
	public function getPerfilAndWorksByCategory($id_author, $limit, $id_category)
	{
		try {
			// USUARIO
			$sql = "	SELECT *
					FROM Usuarios U
					WHERE estado = 1 AND idUsuarios = $id_author
					;";
			$query = $this->db->query($sql);
			$result = $query->result_array();
			$all_publicaciones['usuario'] 	= $result[0];
			// PUBLICACIONES
			$sql = "	SELECT
						T.idTrabajos, T.idUsuarios, T.titulo, T.fecha, T.idCategorias_parentId
					FROM Trabajos T
					WHERE T.idEstados = 2
						AND T.idUsuarios = $id_author AND T.idCategorias_parentId = $id_category
					$limit;";
			$query = $this->db->query($sql);
			$all_publicaciones['publicaciones'] = $query->result_array();

			foreach($all_publicaciones['publicaciones'] AS $k => $work)
			{
				// CATEGORIA
				$categoria = $this->repo_categorias->getCategoryName($work['idCategorias_parentId']);
				$all_publicaciones['publicaciones'][$k]['categoria']['nombre'] 	= $categoria;
				$all_publicaciones['publicaciones'][$k]['categoria']['id'] 		= $work['idCategorias_parentId'];
				// SUBCATEGORIA
				$sub_categorias = $this->repo_categorias->getSubCategorysByWork($work['idTrabajos']);
				if( count($sub_categorias) == 0 ) {
					$all_publicaciones['publicaciones'][$k]['sub_categoria'] = NULL;
				}else{
					$last_subCategory = end($sub_categorias);
					foreach($sub_categorias as $k_sc => $sc) {
						$all_publicaciones['publicaciones'][$k]['sub_categoria'][$k_sc]['id']	=	$sc['idCategorias'];
						$all_publicaciones['publicaciones'][$k]['sub_categoria'][$k_sc]['nombre']	= $this->repo_categorias->getSubCategoryName($sc['idCategorias']);
						if($last_subCategory['idCategorias'] == $sc['idCategorias']) {
							$all_publicaciones['publicaciones'][$k]['sub_categoria'][$k_sc]['ultima'] = true;
						}else {
							$all_publicaciones['publicaciones'][$k]['sub_categoria'][$k_sc]['ultima'] = false;
						}
					}
				}
			}


	            return $all_publicaciones;





		} catch (Exception $e) {
			return false;
		}
	}

	// VALIDA UN USUARIO
	public function validate($user)
	{
		$errors = false;

		if( !isset($user['nombre']) || ( isset($user['nombre']) && $user['nombre'] == '' ) ) {
			$errors['nombre'] = 'El nombre es obligatorio';
		}

		if(!isset($user['apellido']) || ( isset($user['apellido']) && $user['apellido'] == '' ) ) {
			$errors['apellido'] = 'El apellido es obligatorio';
		}

		if ( isset($user['avatar']) && mb_strlen($user['avatar']) > 100 ) {
			$errors['avatar'] = 'El nombre de la imágen del avatar es muy largo';
		}

		if(!isset($user['email']) || ( isset($user['email']) && $user['email'] == '' ) ) {
			$errors['email'] = 'El email es obligatorio';
		}

		if(!isset($user['telefono']) || ( isset($user['telefono']) && $user['telefono'] == '' ) ) {
			$errors['telefono'] = 'El telefono es obligatorio';
		}

		if(!isset($user['fecha']) || ( isset($user['fecha']) && $user['fecha'] == '' ) ) {
			$errors['fecha'] = 'El fecha es obligatorio';
		}

		if(!isset($user['intereses']) || ( isset($user['intereses']) && $user['intereses'] == '' ) ) {
			$errors['intereses'] = 'El intereses es obligatorio';
		}

		if(!isset($user['lugar']) || ( isset($user['lugar']) && $user['lugar'] == '' ) ) {
			$errors['lugar'] = 'El lugar es obligatorio';
		}

		if(!isset($user['profesion']) || ( isset($user['profesion']) && $user['profesion'] == '' ) ) {
			$errors['profesion'] = 'La profesion es obligatorio';
		}

		if(!isset($user['biografia']) || ( isset($user['biografia']) && $user['biografia'] == '' ) ) {
			$errors['biografia'] = 'La biografia es obligatorio';
		}

		return $errors;
	}

	// ACTUALIZA UN USUARIO
	public function update($user)
	{
		try
		{


			$this->db->trans_begin();

			$user['fecha'] = str_replace('/', '-', $user['fecha']); // modifico este valor, por que si no, no convierte bien.

			$data = array(
					'idUsuarios' 	=> $user['idUsuarios'],
					'idRoles' 		=> $user['idRoles'],
					'nombre'  		=> $user['nombre'],
					'apellido' 		=> $user['apellido'],
					'fecha' 			=> date('Y-m-d',strtotime($user['fecha'])),
					'email' 			=> $user['email'],
					'telefono'  		=> $user['telefono'],
					'direccion_calle'  => $user['direccion_calle'],
					'direccion_numero'=> $user['direccion_numero'],
					'cod_postal'  	=> $user['cod_postal'],
					'localidad'  		=> $user['localidad'],
					'ciudad'  		=> $user['ciudad'],
					'pais'  			=> $user['pais'],
					// 'estado' 		=> $user['estado'],
					'esEditorial' 	=> $user['esEditorial'],
					//'esAutor' 		=> $user['esAutor'],
					'intereses' 		=> $user['intereses'],
					'avatar'			=> $this->saveAvatar($user['avatar']),
					'lugar' 			=> $user['lugar'],
					'profesion' 		=> $user['profesion'],
					'biografia' 		=> $user['biografia'],
					'observaciones' => $user['observaciones'],
					);

			if($user['del_avatar'] == 1) {
				$data['avatar'] = '';
			}

			$this->db->where('idUsuarios', $user['idUsuarios']);
			$this->db->update('Usuarios', $data);


			// $this->db->insert('Trabajos',$data);

			// if(isset($work['sub_category']) && count($work['sub_category']) > 0) {
			// 	$work['idTrabajos'] = $this->db->insert_id(); // obtengo el id que acaba de insertar.
			// 	foreach($work['sub_category'] AS $sub_cat) {
			// 		$data = array(
			// 					'idCategorias' => $sub_cat['id'],
			// 					'idTrabajos' => $work['idTrabajos']
			// 					);
			// 		$this->db->insert('TrabajosCategorias',$data);
			// 	}
			// }

			// $work['idTrabajos'] = $this->db->insert_id();
			// if(isset($work['idTrabajos']) && $work['idTrabajos'] > 0) {
			// 	if(isset($work['idCategorias']) && sizeof($work['idCategorias']) > 0) {
			// 		foreach($work['idCategorias'] as $idCategorias) {
			// 			$data = array(
			// 					'idCategorias' => $idCategorias,
			// 					'idTrabajos' => $work['idTrabajos']
			// 					);
			// 			$this->db->insert('TrabajosCategorias',$data);
			// 		}
			// 	}
			// }

			if($this->db->trans_status()) {
				$this->db->trans_commit();
				return true;
			} else {
				$this->db->trans_rollback();
				return false;
			}

		} catch (Exception $e) {
			$this->db->trans_rollback();
			return false;
		}
	}

	// ACTUALIZA UN USUARIO
	public function setAutor($id_user)
	{
		try
		{

			$this->db->where('idUsuarios', $id_user);
			$this->db->update('Usuarios', array('esAutor' => 1));

			$user_update 	= $this->db->affected_rows();

			if ($user_update) {
				return true;
			} else {
				return false;
			}

		} catch (Exception $e) {
			return false;
		}
	}


	// GRABA EL AVATAR DE UN USUARIO
	public function saveAvatar($foto)
	{
		try {
			if(isset($_FILES['avatar']) && !empty($foto)){
				if(move_uploaded_file($_FILES['avatar']['tmp_name'], "web/uploads/usuarios/avatar/$foto")){
					// USUARIO COMUN (285 x 166)
					$config = array();
					$config['image_library'] 	= 'gd2';
					$config['source_image']	= "web/uploads/usuarios/avatar/$foto";
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
					$config['source_image']	= "web/uploads/usuarios/avatar/$foto";
					$config['new_image'] 	= "web/uploads/usuarios/avatar/thumbs/$foto";
					$config['create_thumb'] = FALSE;
					$config['maintain_ratio'] = TRUE;
					$config['width']	 		= 52;
					$config['height']			= 30;
					$this->image_lib->initialize($config);
					$foto_user_thumb = $this->image_lib->resize();
					if($foto_user_thumb){
						return $foto;
					} else{
						return $foto; // TODO: aca debe retornar vacio si no pudo hacer resize
					}

				} else {
					return $foto;
				}
			} else {
				return $foto;
			}

		} catch (Exception $e) {
			return "";
		}
	}

	public function changePass($id_user)
	{
		try {
			$this->load->helper('string_helper');
			$clave = random_string();
			$this->db->set('clave','PASSWORD('.$this->db->escape($clave).')',false);
			$this->db->update('Usuarios',NULL,'idUsuarios = '.$id_user);
			return $clave;
		} catch (Exception $e) {

		}
	}



}

?>
