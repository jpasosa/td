<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Front_works extends CI_class {


	public function __construct(){
		parent::__construct();
		// $this->load->library('session');
		$this->load->model('works_model');
		$this->config->load('emails');
		// $this->load->config('emails');
		//$this->session->sess_destroy();                      // Tengo que poner esto para hacer pruebas
		//$this->output->enable_profiler(TRUE); // Para habilitar barra depuración
	}



	public function index()
	{
		try {

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}


	// DAR DE ALTA UNA PUBLICACIÓN
	public function add()
	{
		try {
			$data 	= array();
			// ULTIMOS AUTORES
			$autores 				= $this->repo_usuarios->getUltimosAutores(3);
			$data['autores'] 		= $autores;

			$data['admin']			= $this->admin;
			$data['login_user'] 		= (boolean)$this->login_user;
			if($data['login_user']) // SI ESTÁ LOGUEADO
			{
				$data['id_user'] 	= $this->session->userdata['idUsuarios'];
				$is_editorial 	= $this->repo_usuarios->isEditorial($data['id_user']);
				if ($is_editorial) {
					$data['is_editorial'] = true;
				} else {
					$data['is_editorial'] = false;
				}
			}

			$data['section'] 			= $this->section; // en donde estamos
			$data['form_validate'] 	= base_url('/login/validate');
			$data['body_id'] 		= 'crearcuenta';

			// $categorias 	= $this->repo_categorias->getSubCategorysById(27);

			$message 	= "";
			$data['form_action_search'] = $this->form_action_search;


			if($this->input->server('REQUEST_METHOD') == 'GET') { // Comienza a cargar la publicación.
				$work = $this->getDataEmpty();
			}else{ // GUARDAR, por post.
				$work = $this->getData();
			}


			$errors = $this->works_model->validate($work);

			if($this->input->server('REQUEST_METHOD') == 'POST' and !$errors)
			{
				if($this->works_model->add($work)) {
					$user 			= $this->repo_usuarios->getById($work['idUsuarios']);

					if ($user['esAutor'] == 0) {
						$update_user_autor = $this->repo_usuarios->setAutor($user['idUsuarios']);
					}

					if($this->notifyUser($user, $work)) { // ENVIO DE MAILS
						$message = 'El nuevo trabajo se ha creado con éxito, y se han enviado los mails respectivos.';
					}else {
						$message = 'El nuevo trabajo se ha creado con éxito. No se han podido enviar los mails.';
					}
					$this->session->set_flashdata('work_success', $message);

					redirect('');
					// $data['success'] = $message;
				} else{
					// Acá no se pudo grabar, por algún motivo. Quizás sea mejor armar con mensaje flash como arriba.
					$message = 'No se ha podido guardar';
					$data['errors'] = $message;
				}

			} elseif($this->input->server('REQUEST_METHOD') == 'POST' and $errors) {

				if ( isset($errors['valid_file_privado']) ) {
					$data['valid_file_privado'] = true;
				}
				if ( isset($errors['valid_file_previa']) ) {
					$data['valid_file_privado'] = true;
				}

				foreach($errors as $key => $error){
					$message .=  $error .'<br>';
				}
				$data['errors'] = $message;

			}


			$data['work'] 		= $work;
			$data['section'] 		= $this->section; // en donde estamos
			$data['precios'] 		= $this->repo_precios->getAll();
			$data['categorias'] 	= $this->repo_categorias->getAll();
			$data['estados'] 	= $this->repo_estadostrabajos->getAll();
			$data['usuarios'] 	= $this->repo_usuarios->getAll();
			$data['form_action'] = site_url('trabajos/alta/');



			// $parentCat =array();
			// foreach($data['categorias'] as $categoria){
			// 	if($categoria['parentId'] == 0){
			// 		$parentCat[] = $categoria;
			// 	}
			// }

			// $data['parentCat'] = $parentCat;
			$data['this'] = $this;
			// $data['usuarios'] = $this->usuarios_model->getAll();
			// $data['admin'] = $admin;
			// $data['this'] = $this;
			$data['title']		= 'WordRev - Publica, comparte y obten conocimiento';


			$this->load->view('front_templates/heads', $data);
			if(!$data['admin']) {
				$this->load->view('front_templates/header',$data);
			} else {
				$this->load->view('front_templates/admin_header',$data);
			}
			$this->load->view('front_templates/main_content',$data);
			$this->load->view('front_templates/footer',$data);

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}


	// EDITAR UNA PUBLICACION
	public function edit($id_work)
	{
		try {
			$data 	= array();
			// ULTIMOS AUTORES
			$autores 				= $this->repo_usuarios->getUltimosAutores(3);
			$data['autores'] 		= $autores;

			// $admin 				= isAdmin($this->session);
			$data['admin']			= $this->admin;
			$data['login_user'] 		= (boolean)$this->login_user;
			if($data['login_user']) // LOGUEADO
			{
				$data['id_user'] = $this->session->userdata['idUsuarios'];
				$is_editorial 	= $this->repo_usuarios->isEditorial($data['id_user']);
				if ($is_editorial) {
					$data['is_editorial'] = true;
				} else {
					$data['is_editorial'] = false;
				}
			}
			$data['section'] 			= $this->section; // en donde estamos
			$data['form_validate'] 	= base_url('/login/validate');
			$data['form_action_search'] = $this->form_action_search;

			$message 				= "";

			if($this->input->server('REQUEST_METHOD') == 'GET') { // Comienza a editar la publicación.
				$get_work 	= $this->repo_trabajos->getByIdIncludeSubcategorys($id_work);
				$work 		= $this->getDataForEdit($get_work);

			}else{ // GUARDAR, por post.
				$work 				= $this->getData();

			}

			$errors = $this->works_model->validate($work);


			if($this->input->server('REQUEST_METHOD') == 'POST' and !$errors)
			{
				if($this->works_model->update_noadmin($work, $id_work)) {
					$user 			= $this->repo_usuarios->getById($work['idUsuarios']);
					$message 		= 'El nuevo trabajo se ha editado con éxito.';
					$this->session->set_flashdata('work_success', $message);
					redirect('');
				} else{
					// Acá no se pudo grabar, por algún motivo. Quizás sea mejor armar con mensaje flash como arriba.
					$message = 'No se ha podido guardar el trabajo editado';
					$data['errors'] = $message;
				}

			} elseif($this->input->server('REQUEST_METHOD') == 'POST' and $errors) {
				foreach($errors as $key => $error){
					$message .=  $error .'<br>';
				}
				$data['errors'] = $message;
			}

			$data['trabajo']		= $work;
			$data['work'] 		= $work;
			$data['section'] 		= $this->section; // en donde estamos
			$data['precios'] 		= $this->repo_precios->getAll();
			$data['categorias'] 	= $this->repo_categorias->getAll();
			$data['estados'] 	= $this->repo_estadostrabajos->getAll();
			$data['usuarios'] 	= $this->repo_usuarios->getAll();
			$data['form_action'] = site_url('trabajos/editar/'. $id_work);
			$data['body_id'] 	= 'crearcuenta';



			// $parentCat =array();
			// foreach($data['categorias'] as $categoria){
			// 	if($categoria['parentId'] == 0){
			// 		$parentCat[] = $categoria;
			// 	}
			// }

			// $data['parentCat'] = $parentCat;
			$data['this'] = $this;
			// $data['usuarios'] = $this->usuarios_model->getAll();
			$data['admin'] = $this->admin;
			// $data['this'] = $this;
			$data['title']		= 'WordRev - Publica, comparte y obten conocimiento';

			$this->load->view('front_templates/heads', $data);
			if(!$this->admin) {
				$this->load->view('front_templates/header',$data);
			} else {
				$this->load->view('front_templates/admin_header',$data);
			}			$this->load->view('front_templates/main_content',$data);
			$this->load->view('front_templates/footer',$data);

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}



	// VER UNA PUBLICACIÓN
	public function show($work_id = NULL) {
		try {
			if ( $work_id == NULL ) {
				redirect();
			}
			$data 	= array();
			// ULTIMOS AUTORES
			$autores 				= $this->repo_usuarios->getUltimosAutores(3);
			$data['autores'] 		= $autores;
			// $admin 				= isAdmin($this->session);
			$last_works				= $this->repo_trabajos->getLastWorks(); // para poner a la derecha
			$data['form_validate'] 	= base_url('/login/validate');
			$data['last_works'] 		= $last_works;
			$data['admin']			= $this->admin;
			# ESTA LOGUEADO ?
			$data['login_user'] 		= (boolean)$this->login_user;

			# PUBLICACION
			$work 					= $this->repo_trabajos->getToShowById($work_id);

			# USUARIO LOGUEADO
			if($data['login_user']) {
				$data['id_user']				= (int)$this->session->userdata['idUsuarios'];
				$data['comprar_articulo']	= 'front_works/comprar/' . $work_id;
				# ES UNA PUBLICACION FAVORITA ?
				$data['is_favorito']		= $this->repo_trabajos->isFavorito($data['id_user'], $work_id);

				// VISTA PREVIA
				$is_editorial = $this->repo_usuarios->isEditorial($data['id_user']);
				if ($work['archivo_vista_previa'] != '' && $is_editorial) {
					$data['vista_previa'] = true;
				} else {
					$data['vista_previa'] = false;
				}
			} else {
				$data['comprar_articulo']	= 'login/crear_cuenta/' . $work_id;
			}
			$data['section'] 			= $this->section; // en donde estamos




			$work_add_visita 		= $this->repo_trabajos->addVisita($work_id);
			$works_random			= $this->repo_trabajos->getTwoWorksRandom();
			// $categorias 	= $this->repo_categorias->getSubCategorysById(27);
			$message 	= "";
			$data['work'] 			= $work;
			$data['works_random']	= $works_random;
			$data['section'] 			= $this->section; // en donde estamos
			$data['form_action'] 	= site_url('trabajos/alta/');
			$data['this'] 			= $this;
			$data['admin'] 			= $this->admin;
			$data['body_id'] 		= 'perfil';
			$data['title']				= 'WordRev - Publica, comparte y obten conocimiento';
			$data['form_action_search'] = $this->form_action_search;

			// VISTAS
			$this->load->view('front_templates/heads', $data);
			$this->load->view('front_templates/header',$data);
			$this->load->view('front_templates/main_content',$data);
			$this->load->view('front_templates/footer',$data);

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}


	// COMPRAR LA PUBLICACIÓN
	public function comprar($work_id)
	{
		try {
			$data 	= array();
			// ULTIMOS AUTORES
			$autores 				= $this->repo_usuarios->getUltimosAutores(3);
			$data['autores'] 		= $autores;
			// $admin 				= isAdmin($this->session);
			$last_works				= $this->repo_trabajos->getLastWorks(); // para poner a la derecha
			$data['form_validate'] 	= base_url('/login/validate');
			$data['last_works'] 		= $last_works;
			$data['admin']			= $this->admin;
			# ESTA LOGUEADO ?
			$data['login_user'] 		= (boolean)$this->login_user;
			# USUARIO LOGUEADO
			if($data['login_user']) {
				$data['id_user']				= (int)$this->session->userdata['idUsuarios'];
				$data['comprar_articulo']	= 'login/crear_cuenta';
				# ES UNA PUBLICACION FAVORITA ?
				$data['is_favorito']		= $this->repo_trabajos->isFavorito($data['id_user'], $work_id);
			} else {
				$data['comprar_articulo']	= 'login/crear_cuenta';
			}
			$data['section'] 			= $this->section; // en donde estamos
			$work 					= $this->repo_trabajos->getToShowById($work_id);
			$works_random			= $this->repo_trabajos->getTwoWorksRandom();
			$data['form_action_search'] = $this->form_action_search;
			// $categorias 	= $this->repo_categorias->getSubCategorysById(27);
			$message 	= "";
			$data['work'] 			= $work;
			$data['works_random']	= $works_random;
			$data['section'] 			= $this->section; // en donde estamos
			$data['form_action'] 	= site_url('trabajos/alta/');
			$data['this'] 			= $this;
			$data['admin'] 			= $this->admin;
			$data['body_id'] 		= 'comprar_publicacion';
			$data['title']				= 'WordRev - Publica, comparte y obten conocimiento';
			$data['id_publicacion']	= $work_id;

			// VISTAS
			$this->load->view('front_templates/heads', $data);
			$this->load->view('front_templates/header',$data);
			$this->load->view('front_templates/main_content',$data);
			$this->load->view('front_templates/footer',$data);

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	// BUSCADOR
	public function buscar()
	{
		try {
			$data 	= array();
			// ULTIMOS AUTORES
			$autores 				= $this->repo_usuarios->getUltimosAutores(3);
			$data['autores'] 		= $autores;
			// $admin 				= isAdmin($this->session);
			$last_works				= $this->repo_trabajos->getLastWorks(); // para poner a la derecha
			$data['form_validate'] 	= base_url('/login/validate');
			$data['last_works'] 		= $last_works;
			$data['admin']			= $this->admin;
			# ESTA LOGUEADO ?
			$data['login_user'] 		= (boolean)$this->login_user;
			$data['form_action_search'] = $this->form_action_search;
			# USUARIO LOGUEADO
			if($data['login_user']) {
				$data['id_user']				= (int)$this->session->userdata['idUsuarios'];
			}
			$data['section'] 			= $this->section; // en donde estamos
			// DOS TRABAJOS AL AZAR
			$works_random			= $this->repo_trabajos->getTwoWorksRandom();
			$data['works_random']	= $works_random;
			// SECCION
			$data['section'] 			= $this->section; // en donde estamos

			// $data['this'] 			= $this;
			$data['admin'] 			= $this->admin;
			$data['body_id'] 		= 'perfil';
			$data['title']				= 'WordRev - Publica, comparte y obten conocimiento';
			$data['form_action_search'] = $this->form_action_search;
			// DESTACADOS
			$palabras_clave 		= $this->input->post('buscar');
			$data['palabras_clave']	= $palabras_clave;
			$buscados 				= $this->repo_trabajos->getBuscados(array('palabras_buscadas' => $palabras_clave, 'resumen' => 'limit_chars.250'));
			$data['buscados']		= $buscados;



			// VISTAS
			$this->load->view('front_templates/heads', $data);
			$this->load->view('front_templates/header',$data);
			$this->load->view('front_templates/main_content',$data);
			$this->load->view('front_templates/footer',$data);

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	// CONFIRMAR LA COMPRA DE LA PUBLICACION
	public function confirmar_comprar($work_id)
	{
		try {
			# ESTA LOGUEADO ?
			$data['login_user'] 		= (boolean)$this->login_user;
			$data['form_action_search'] = $this->form_action_search;

			if($data['login_user']) // LOGUEADO, EMPIEZA LAS TRANSACCIONES
			{
				$id_user						= (int)$this->session->userdata['idUsuarios'];
				$compra['fecha'] 				= date('Y-m-d');
				$compra['idUsuarios'] 			= $id_user;
				$compra['idTrabajos'] 			= $work_id;
				$compra['monto_venta_total'] 	= $this->repo_trabajos->getPrecioConDerecho($work_id);
				// INSERTO LA COMPRA

				$insert_pedido = $this->repo_compras->insert($compra);
				if($insert_pedido > 0)
				{
					$id_author	= $this->repo_trabajos->getIdUsuario($compra['idTrabajos']);
					$title		= $this->repo_trabajos->getTitulo($work_id);
					// ENVIO MAIL AL ADMINISTRADOR
					$mail['from'] 	= $this->config->item('email_admin');
					$mail['to'] 		= array($this->config->item('email_programador'), $this->config->item('email_sistemas')); // TODO: aca va al administrador
					$mail['title'] 	= 'Realizaron un pedido';
					$mail['body'] 	= 'Realizaron un pedido por la publicación: ' . $title . ' por $' . $compra['monto_venta_total'];


					if ($this->sendMail($mail)) {
						$send_mail_admin = true;
					} else {
						$send_mail_admin = false;
					}

					// ENVIO MAIL AL COMPRADOR
					$mail['from'] 	= $this->config->item('email_admin');
					$mail['to'] 		= $this->session->userdata['userName'];
					$mail['title'] 	= 'Realizaste un Pedido';
					$mail['body'] 	= 'Realizaste un pedido por la publicación ' . $title . '. En la brevedad te contactarán para coordinar la compra. ';
					if ($this->sendMail($mail))
					{
						if ($send_mail_admin) {
							$message = 'Realizaste un pedido. Se te envio un mail a vos y al administrador.';
						} else {
							$message = 'Se te envió un mail a vos, no se pudo enviar al administrador.';
						}

					// FALLÓ EL ENVIO AL COMPRADOR
					} else {
						if ($send_mail_admin) {
							$message = 'Se envió un mail al administrador, pero falló tu envio. Se va a contactar con vos el administrador.';
						} else {
							$message =  'No se pudieron enviar los mails.';
						}

					}
					$this->session->set_flashdata('message_success', $message);
					redirect();
				}


			} else { 	// NO ESTABA LOGUEADO
				$message = 'No se pudo realizar el pedido de la publicación, se encuentra deslogueado.';
				$this->session->set_flashdata('message_error', $message);
				redirect();
			}










		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}





	protected function getData()
	{
		$work = array();

		if($this->input->get_post('titulo')) {
			$work['titulo'] = trim($this->input->get_post('titulo'));
		}

		if($this->input->get_post('texto')) {
			$work['texto'] = trim($this->input->get_post('texto'));
		}

		if($this->input->get_post('resumen')) {
			$work['resumen'] = trim($this->input->get_post('resumen'));
		}

		if($this->input->get_post('cantidad_paginas')) {
			$work['cantidad_paginas'] = trim($this->input->get_post('cantidad_paginas'));
		}else {
			$work['cantidad_paginas'] = '';
		}

		if($this->input->post('fecha')) {
			$work['fecha']= $this->input->post('fecha');
		} else {
			$work['fecha'] = date('Y-m-d');
		}

		if($this->input->get_post('cantidadPalabras')) {
			$work['cantidadPalabras'] = trim($this->input->get_post('cantidadPalabras'));
		}else {
			$work['cantidadPalabras'] = '';
		}

		if($this->input->get_post('palabrasClave')) {
			$work['palabrasClave'] = trim($this->input->get_post('palabrasClave'));
		}else {
			$work['palabrasClave'] = '';
		}

		if($this->input->get_post('archivo_publico')) {
			$work['archivo_publico'] = $this->input->get_post('archivo_publico');
		}else {
			$work['archivo_publico'] = '';
		}

		if($this->input->get_post('archivo_privado')) {
			$work['archivo_privado'] = $this->input->get_post('archivo_privado');
		}else {
			$work['archivo_publico'] = '';
		}

		if($this->input->get_post('archivo_vista_previa')) {
			$work['archivo_vista_previa'] = $this->input->get_post('archivo_vista_previa');
		}else {
			$work['archivo_vista_previa'] = '';
		}

		if($this->input->get_post('idUsuarios')) {
			$work['idUsuarios'] = (int)$this->input->get_post('idUsuarios');
		}

		if($this->input->get_post('destacado')) {
			$work['destacado'] = (int)$this->input->get_post('destacado');
		}else {
			$work['destacado'] = 0;
		}

		if($this->input->get_post('foto')) {
			$work['foto'] = $this->input->get_post('foto');
		}else {
			$work['foto'] = $this->input->get_post('name_foto');
		}

		if($this->input->get_post('indice')) {
			$work['indice'] = $this->input->get_post('indice');
		}else {
			$work['indice'] = '';
		}

		if($this->input->post('idCategorias_parentId')) {
			$work['idCategorias_parentId']  = $this->input->post('idCategorias_parentId');
		}else {
			$work['idCategorias_parentId'] = '';
		}


		if($this->input->post('subCategorys')) {
			foreach($this->input->post('subCategorys') AS $k => $sub_cat) {
				$sub_cat_name =  $this->repo_categorias->getSubCategoryName($sub_cat);
				$work['sub_category'][$k]['id'] = $sub_cat;
				$work['sub_category'][$k]['name'] = $sub_cat_name;
			}
		}else {
			$work['sub_category'] = 0;
		}

		if($this->input->get_post('precio_sin_derecho')) {
			$work['precio_sin_derecho'] = (float)$this->input->get_post('precio_sin_derecho');
		}else {
			$work['precio_sin_derecho'] = 0.0;
		}

		if($this->input->get_post('precio_con_derecho')) {
			$work['precio_con_derecho'] = (float)$this->input->get_post('precio_con_derecho');
		}else {
			$work['precio_con_derecho'] = '';
		}

		if($this->input->get_post('monto_por_venta')) {
			$work['monto_por_venta'] = (float)$this->input->get_post('monto_por_venta');
		}else {
			$work['monto_por_venta'] = '';
		}

		if($this->input->get_post('idEstados')) {
			$work['idEstados'] = (int)$this->input->get_post('idEstados');
		}else {
			$work['idEstados'] = 1;
		}

		if($this->input->post('idPrecios')){
			$work['idPrecios'] = (int)$this->input->post('idPrecios');
		}

		if($this->input->post('notificado')){
			$work['notificado'] = (int)$this->input->post('notificado');
		}else {
			$work['notificado'] = 0;
		}

		// if(isset($_FILES['indice']['tmp_name']) and !empty($_FILES['indice']['tmp_name'])){
		// 	$work['indice'] = sha1(md5(uniqid())). "_".$_FILES['indice']['name'];
		// } else{
		// 	$work['indice'] = "";
		// }










		// FOTO
		if(isset($_FILES['foto']['tmp_name']) and !empty($_FILES['foto']['tmp_name'])){
			$work['foto'] = sha1(md5(uniqid())). "_".$_FILES['foto']['name'];
		} else {
			if($this->input->post('name_foto')){
				$work['foto'] = $this->input->post('name_foto');
				$work['archivo_foto_nograbar'] = true;
			}else {
				$work['foto'] = '';
			}
		}
		// ARCHIVO PRIVADO
		if(isset($_FILES['archivo_privado']['tmp_name']) and !empty($_FILES['archivo_privado']['tmp_name'])){
			$work['archivo_privado'] = sha1(md5(uniqid())). "_".$_FILES['archivo_privado']['name'];
			$work['archivo_privado_para_mostrar'] = '. . . ' . substr($work['archivo_privado'], -25);
		} else{
			if($this->input->post('name_privado')){
				$work['archivo_privado'] = $this->input->post('name_privado');
				$work['archivo_privado_nograbar'] = true;
			}else {
				$work['archivo_privado'] = '';
			}
			// $work['archivo_privado'] = "";
			// $work['archivo_privado_para_mostrar'] = '. . . ' . substr($work['archivo_privado'], -25);
		}
		// ARCHIVO VISTA PREVIA
		if(isset($_FILES['archivo_vista_previa']['tmp_name']) and !empty($_FILES['archivo_vista_previa']['tmp_name'])){
			$work['archivo_vista_previa'] = sha1(md5(uniqid())). "_".$_FILES['archivo_vista_previa']['name'];
			$work['vista_previa_mostrar'] = '. . . ' . substr($work['archivo_vista_previa'], -25);
		} else{
			if($this->input->post('name_vista_previa')){
				$work['archivo_vista_previa'] = $this->input->post('name_vista_previa');
				$work['archivo_vista_previa_nograbar'] = true;
			}else {
				$work['archivo_vista_previa'] = '';
			}
			// $work['archivo_vista_previa'] = "";
			// $work['vista_previa_mostrar'] = '. . . ' . substr($work['archivo_privado'], -25);
		}
		// if(isset($_FILES['archivo_publico']['tmp_name']) and !empty($_FILES['archivo_publico']['tmp_name'])){
		// 	$work['archivo_publico'] = sha1(md5(uniqid())). "_".$_FILES['archivo_publico']['name'];
		// } else {
		// 	$work['archivo_publico'] = "";
		// }




		return $work;
	}

	protected function getDataForEdit($work)
	{


		// ARCHIVO PRIVADO
		if( isset($work['archivo_privado']) && $work['archivo_privado'] != '' ){
			$work['archivo_privado_para_mostrar'] = '. . . ' . substr($work['archivo_privado'], -25);
		}

		// ARCHIVO PRIVADO
		if( isset($work['archivo_vista_previa']) && $work['archivo_vista_previa'] != '' ){
			$work['vista_previa_mostrar'] = '. . . ' . substr($work['archivo_vista_previa'], -25);
		}


		return $work;
	}

	protected function getDataEmpty()
	{
		$work = array();
		$work['idUsuarios'] 			= 0;
		$work['titulo'] 				= '';
		$work['texto'] 				= '';
		$work['resumen'] 			= '';
		$work['cantidad_paginas'] 	= '';
		$work['fecha'] 				= date('Y-m-d');
		$work['cantidadPalabras'] 	= '';
		$work['palabrasClave'] 		= '';
		$work['archivo_publico'] 		= '';
		$work['archivo_publico'] 		= '';
		$work['archivo_vista_pevria']= '';
		$work['idUsuarios'] 			= 0;
		$work['destacado'] 			= 0;
		$work['foto'] 				= '';
		$work['indice'] 				= '';
		$work['idCategorias'] 		= '';
		$work['idCategorias_parentId'] = '';
		$work['precio_sin_derecho'] = 0.0;
		$work['precio_con_derecho'] = 0.0;
		$work['monto_por_venta'] 	= 0.0;
		$work['idEstados'] 			= '';
		$work['idPrecios'] 			= 0;

		return $work;
	}




	public function notifyUser($user, $work) {
		try {
			$this->load->library('email');
			$data_mail = array();

			if(isAdmin($this->session)) // ADMINISTRADOR
			{

				if($work['notificado'] == 1) { // DEBE ENVIAR MAIL AL USER Y AL ADMIN.
					if($work['idEstados'] == 1)
					{ // estado PENDIENTE
							// Mail al USER
							$data_mail['title'] 	= 'WordRev :: Trabajo pendiente de aprobación';
							// $data_mail['body'] 	= $user['nombre'] . ' ' . $user['apellido'] . ' ' . ' Tu trabajo de titulo ' . $work['titulo'] . 'esta pendiente de aprobación. Vas a ser notificado cuando sea aprobado por el administrador.';
							$data_mail['body'] 	= 'sos ADMIN, creaste trabajo en estado PENDIENTE, manda al USUARIO';
							$data_mail['from'] 	= 'juanpablososa@gmail.com';
							$data_mail['to'] 		= $this->config->item('email_admin'); // es un mail al USER
							$this->sendMail($data_mail);
							// Mail al ADMIN
							$data_mail['title'] 	= 'WordRev :: Trabajo pendiente de aprobación';
							$data_mail['body'] 	= 'sos ADMIN, creaste trabajo en estado PENDIENTE, manda al ADMIN';
							$data_mail['from'] 	= 'juanpablososa@gmail.com';
							$data_mail['to'] 		= $this->config->item('email_admin');
							$this->sendMail($data_mail);
					}else if($work['idEstados'] == 2) { // APROBADO
							// Mail al USER
							$data_mail['title'] 	= 'WordRev :: Trabajo Aprobado';
							$data_mail['body'] 	= 'sos ADMIN, creaste trabajo en estado APROBADO, manda al USUARIO';
							$data_mail['from'] 	= 'juanpablososa@gmail.com';
							$data_mail['to'] 		= $this->config->item('email_admin'); // es un mail al USER
							$this->sendMail($data_mail);
							// Mail al ADMIN
							$data_mail['title'] 	= 'WordRev :: Trabajo Aprobado';
							$data_mail['body'] 	= 'sos ADMIN, creaste trabajo en estado APROBADO, manda al ADMIN';
							$data_mail['from'] 	= 'juanpablososa@gmail.com';
							$data_mail['to'] 		= $this->config->item('email_admin');
							$this->sendMail($data_mail);
					}

				}else if($work['notificado'] == 0 && $work['idEstados'] == 1) { // SE ENVIA UN MAIL AL ADMIN, POR Q ESTA PENDIENTE.
					// Mail al ADMIN
					$data_mail['title'] 	= 'WordRev :: Trabajo pendiente de aprobación';
					$data_mail['body'] 	= 'sos ADMIN, creaste trabajo en estado PENDIENTE, y pusiste no NOTIFICAR al user';
					$data_mail['from'] 	= 'juanpablososa@gmail.com';
					$data_mail['to'] 		= $this->config->item('email_admin');
					$this->sendMail($data_mail);
				} else if($work['notificado'] == 0 && $work['idEstados'] == 2) { // Se envia un mail al admin, avisando que creo un trabajo ya activo.
					// Mail al ADMIN
					$data_mail['title'] 	= 'WordRev :: Trabajo pendiente de aprobación';
					$data_mail['body'] 	= 'sos ADMIN, creaste trabajo en estado Aprobado, y pusiste no NOTIFICAR al user';
					$data_mail['from'] 	= 'juanpablososa@gmail.com';
					$data_mail['to'] 		= $this->config->item('email_admin');
					$this->sendMail($data_mail);
				}

			}else if($user['idRoles'] == 2) // USER COMUN // Debe enviar mail al user y al admin.
			{
				// Mail al USER
				$data_mail['title'] 	= 'Creaste una publicación. Está pendiente de Aprobación.';
				// $data_mail['body'] 	= $user['nombre'] . ' ' . $user['apellido'] . ' ' . ' Tu trabajo de titulo ' . $work['titulo'] . 'esta pendiente de aprobación. Vas a ser notificado cuando sea aprobado por el administrador.';;
				$data_mail['body'] 	= 'Tu publicación de titulo ' . $work['titulo'] . ' está pendiente de aprobación. Te enviaremos un mail cuando ya la publiquemos. 					Muchas gracias.';
				$data_mail['from'] 	= $this->config->item('email_admin');
				$data_mail['to'] 		= $user['email'];
				$this->sendMail($data_mail);
				// Mail al ADMIN
				$data_mail['title'] 	= 'Crearon una publicación, pendiente de aprobación.';
				$data_mail['body'] 	= 'Crearon una publicación de título ' . $work['titulo'] . '. Está pendiente de aprobación';
				$data_mail['from'] 	= $this->config->item('email_admin');
				$data_mail['to'] 		= array($this->config->item('email_admin'), $this->config->item('email_programador'));
				$this->sendMail($data_mail);
			}

			return true;

			// echo $this->email->print_uger();
		} catch (Exception $e) {
			die('No pudo enviar el mail por algún motivo');
		}


	}




	public function sendMail($data_mail) {




		$this->email->from($data_mail['from']);
		$this->email->to($data_mail['to']);
		$this->email->subject($data_mail['title']);
		$this->email->message($data_mail['body']);
		if($this->email->send()) {
			return true;
		}else {
			return false;
		}
	}








}
?>
