<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Front_tucuenta extends CI_class {


	public function __construct(){
		parent::__construct();
		// $this->load->library('session');
		// $this->load->model('works_model');
		$this->config->load('emails');
		// $this->load->config('emails');
		//$this->session->sess_destroy();                      // Tengo que poner esto para hacer pruebas
		//$this->output->enable_profiler(TRUE); // Para habilitar barra depuración
	}



	public function index() {
		try {

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	// También puede entrar desde link a autores
	public function mis_publicaciones($id_user, $pagina, $page)
	{
		try {
			$data 				= array();
			// ULTIMOS AUTORES
			$autores 			= $this->repo_usuarios->getUltimosAutores(3);
			$data['autores'] 	= $autores;
			// es ADMIN ?
			// $admin 			= isAdmin($this->session);
			$data['admin']		= $this->admin;
			// ULTIMOS TRABAJOS
			$last_works			= $this->repo_trabajos->getLastWorks();
			$data['last_works'] 	= $last_works;
			// LOGUEADO ?
			$data['login_user'] 	= (boolean)$this->login_user;
			// ID DEL USUARIO LOGUEADO
			if($data['login_user']) {
				$data['id_user'] = $this->session->userdata['idUsuarios'];
				if($data['id_user'] != $id_user) { // POR SEGURIDAD, QUE CORRESPONDA MISMO ID DE LA SESSION
					redirect('');
				}
			} else {
				redirect('');
			}
			// SECCION
			$data['section'] 		= $this->section; // en donde estamos
			// VALIDAR y ACCION PARA LOGUEARSE, SI NO ESTA LOGUEADO
			$data['form_action']	= site_url('trabajos/alta/');
			$data['form_validate']= base_url('/login/validate');
			// DOS TRABAJOS AL AZAR
			$works_random		= $this->repo_trabajos->getTwoWorksRandom();
			$data['works_random']= $works_random;
			// TITULO e ID de BODY
			$data['body_id'] 	= 'temas';
			$data['title']			= 'WordRev - Publica, comparte y obten conocimiento';
			// CANTIDAD DE PUBLICACIONES DEL AUTOR SELECCIONADO
			$count_works		= $this->repo_trabajos->getCountWorksOfAuthor($id_user);
			// PAGINADOR
			$pager['per_page']	= $this->config->item('cant_public_por_pagina');
			$pager['total']		= $count_works;
			$pager['page']		= $page;
			$pager['base_url']	= $this->config->base_url() . 'tucuenta/mis_publicaciones/' . $id_user . '/pagina';
			$pager['limit']		= limit($pager['page'], $pager['per_page']);
			$paginador 			= paginas_front($pager['total'], $pager['per_page'],$pager['page'], $pager['base_url'] );
			$data['paginador']	= $paginador;
			// PUBLICACIONES DEL AUTOR SELECCIONADO
			$works_of_author	= $this->repo_trabajos->getWorksOfAuthor($id_user, $pager['limit']);
			$data['works_of_author'] = $works_of_author;
			// USUARIO
			$author 			= $this->repo_usuarios->getById($id_user);
			$data['author']		= $author;
			$data['form_action_search'] = $this->form_action_search;





			$this->load->view('front_templates/heads', $data);
			$this->load->view('front_templates/header',$data);
			$this->load->view('front_templates/main_content',$data);
			$this->load->view('front_templates/footer',$data);


		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	// SOLICITAR EL ACCESO A SER UNA EDITORIAL
	public function acceso_editorial()
	{
		try {
			$this->load->model('models_tucuenta');
			$data 				= array();
			# ULTIMOS AUTORES
			$autores 			= $this->repo_usuarios->getUltimosAutores(3);
			$data['autores'] 	= $autores;
			// es ADMIN ?
			// $admin 			= isAdmin($this->session);
			$data['admin']		= $this->admin;
			# ULTIMOS TRABAJOS
			$last_works			= $this->repo_trabajos->getLastWorks();
			$data['last_works'] 	= $last_works;
			# LOGUEADO ?
			$data['login_user'] 	= (boolean)$this->login_user;
			# ID DEL USUARIO LOGUEADO
			if($data['login_user'])
			{
				$data['id_user']	= $this->session->userdata['idUsuarios'];
				$author 		= $this->repo_usuarios->getById($data['id_user']);
				$data['author']	= $author;
			} else {
				redirect();
			}
			# SECCION
			$data['section'] 		= $this->section; // en donde estamos
			# VALIDAR y ACCION PARA LOGUEARSE, SI NO ESTA LOGUEADO
			$data['form_action']	= site_url('front_tucuenta/acceso_editorial/');
			# DOS TRABAJOS AL AZAR
			$works_random		= $this->repo_trabajos->getTwoWorksRandom();
			$data['works_random']= $works_random;
			# TITULO e ID de BODY
			$data['body_id'] 	= 'temas';
			$data['title']			= 'WordRev - Publica, comparte y obten conocimiento';
			$data['form_action_search'] = $this->form_action_search;

			if ( $this->input->server('REQUEST_METHOD') == 'POST' )
			{	// POST, TRAE LOS DATOS.
				$nombre_editorial 	= $this->input->post('nombre_editorial');
				$web 				= $this->input->post('sitio_editorial');
				// SOLICITAR VA A SER   "ON" o false. . . .
				$solicitar 			= $this->input->post('solicitar');
				$data_validate 		= array(
											'editorial' 	=> $nombre_editorial,
											'web' 		=> $web,
											'solicitar' 	=> $solicitar,
											);
				$errors = $this->models_tucuenta->validate($data_validate);

				if (!$errors)
				{
					$enviar_solicitud = $this->models_tucuenta->enviarSolicitud($data_validate, $data['author']);
					if ( $enviar_solicitud ) {
						$message = 'Su solicitud fue enviada con éxito';
					} else {
						$message = 'Su solicitud no pudo ser enviada';
					}
					$this->session->set_flashdata('work_success', $message);
					redirect('');
				} else {
					$data['errors'] 				= $errors;
					$data['nombre_editorial'] 	= $data_validate['editorial'];
					$data['web'] 				= $data_validate['web'];
				}

			} else {  // VIENE POR GET
				$data['nombre_editorial'] 	= '';
				$data['web'] 				= '';
			}


			$this->load->view('front_templates/heads', $data);
			$this->load->view('front_templates/header',$data);
			$this->load->view('front_templates/main_content',$data);
			$this->load->view('front_templates/footer',$data);


		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	// También puede entrar desde link a autores
	public function mis_favoritos($id_user, $pagina, $page)
	{
		try {
			$data 				= array();
			// ULTIMOS AUTORES
			$autores 			= $this->repo_usuarios->getUltimosAutores(3);
			$data['autores'] 	= $autores;
			// es ADMIN ?
			// $admin 			= isAdmin($this->session);
			$data['admin']		= $this->admin;
			// ULTIMOS TRABAJOS
			$last_works			= $this->repo_trabajos->getLastWorks();
			$data['last_works'] 	= $last_works;
			// LOGUEADO ?
			$data['login_user'] 	= (boolean)$this->login_user;
			// ID DEL USUARIO LOGUEADO
			if($data['login_user']) {
				$data['id_user'] = $this->session->userdata['idUsuarios'];
				if($data['id_user'] != $id_user) { // POR SEGURIDAD, QUE CORRESPONDA MISMO ID DE LA SESSION
					redirect('');
				}
			} else {
				redirect('');
			}
			// SECCION
			$data['section'] 		= $this->section; // en donde estamos
			// VALIDAR y ACCION PARA LOGUEARSE, SI NO ESTA LOGUEADO
			$data['form_action']	= site_url('trabajos/alta/');
			$data['form_validate']= base_url('/login/validate');
			// DOS TRABAJOS AL AZAR
			$works_random		= $this->repo_trabajos->getTwoWorksRandom();
			$data['works_random']= $works_random;
			// TITULO e ID de BODY
			$data['body_id'] 	= 'temas';
			$data['title']			= 'WordRev - Publica, comparte y obten conocimiento';

			// CANTIDAD DE PUBLICACIONES EN FAVORITOS
			$count_favoritos	= $this->repo_trabajos->getCountWorksOfAuthorInFavorite($id_user);
			// PAGINADOR
			$pager['per_page']	= $this->config->item('cant_public_por_pagina');
			$pager['total']		= $count_favoritos;
			$pager['page']		= $page;
			$pager['base_url']	= $this->config->base_url() . 'tucuenta/mis_favoritos/' . $id_user . '/pagina';
			$pager['limit']		= limit($pager['page'], $pager['per_page']);
			$paginador 			= paginas_front($pager['total'], $pager['per_page'],$pager['page'], $pager['base_url'] );
			$data['paginador']	= $paginador;
			$data['form_action_search'] = $this->form_action_search;


			// PUBLICACIONES FAVORITAS DEL AUTOR SELECCIONAD
			$works_of_author	= $this->repo_trabajos->getWorksOfAuthorInFavorite($id_user, $pager['limit']);
			$data['works_of_author'] = $works_of_author;







			// USUARIO
			$author 			= $this->repo_usuarios->getById($id_user);
			$data['author']		= $author;





			$this->load->view('front_templates/heads', $data);
			$this->load->view('front_templates/header',$data);
			$this->load->view('front_templates/main_content',$data);
			$this->load->view('front_templates/footer',$data);


		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}


	// ES EL PERFIL DEL USUARIO, MUESTRA SUS PUBLICACIONES SEPARADAS POR CATEGORIAS
	public function perfil($id_user)
	{
		try {
			$data 				= array();
			// ULTIMOS AUTORES
			$autores 			= $this->repo_usuarios->getUltimosAutores(3);
			$data['autores'] 	= $autores;
			// es ADMIN ?
			// $admin 			= isAdmin($this->session);
			$data['admin']		= $this->admin;
			// ULTIMOS TRABAJOS
			$last_works			= $this->repo_trabajos->getLastWorks();
			$data['last_works'] 	= $last_works;
			// LOGUEADO ?
			$data['login_user'] 	= (boolean)$this->login_user;
			// ID DEL USUARIO LOGUEADO
			if($data['login_user']) {
				$data['id_user'] = $this->session->userdata['idUsuarios'];
			}
			// SECCION
			$data['section'] 		= $this->section; // en donde estamos

			// VALIDAR y ACCION PARA LOGUEARSE, SI NO ESTA LOGUEADO
			$data['form_action']	= site_url('trabajos/alta/');
			$data['form_validate']= base_url('/login/validate');

			// DOS TRABAJOS AL AZAR
			$works_random		= $this->repo_trabajos->getTwoWorksRandom();
			$data['works_random']= $works_random;

			$data['form_action_search'] = $this->form_action_search;

			// TITULO e ID de BODY
			$data['body_id'] 	= 'perfil';
			$data['title']			= 'WordRev - Publica, comparte y obten conocimiento';
			// CANTIDAD DE PUBLICACIONES DEL AUTOR SELECCIONADO
			$count_works		= $this->repo_trabajos->getCountWorksOfAuthor($id_user);
			// PAGINADOR
			// $pager['per_page']	= $this->config->item('cant_public_por_pagina');
			// $pager['total']		= $count_works;
			// $pager['page']		= $page;
			// $pager['base_url']	= $this->config->base_url() . 'tucuenta/mis_publicaciones/' . $id_user . '/pagina';
			// $pager['limit']		= limit($pager['page'], $pager['per_page']);
			// $paginador 			= paginas_front($pager['total'], $pager['per_page'],$pager['page'], $pager['base_url'] );
			// $data['paginador']	= $paginador;
			// PUBLICACIONES Y PERFIL DEL AUTOR SELECCIONADO
			$perfil_and_works			= $this->repo_usuarios->getPerfilAndWorks($id_user, 'LIMIT 0, 9189181981981');
			$data['perfil_and_works'] 	= $perfil_and_works;

			// USUARIO
			$author 					= $this->repo_usuarios->getById($id_user);
			$data['author']				= $author;
			// VISTAS
			$this->load->view('front_templates/heads', $data);
			$this->load->view('front_templates/header',$data);
			$this->load->view('front_templates/main_content',$data);
			$this->load->view('front_templates/footer',$data);


		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}


	// HACE CLICK EN VER MAS, DENTRO DEL PERFIL DEL USUARIO
	public function perfilVerMas($id_user, $id_category, $pagina, $page)
	{
		try {
			$data 				= array();
			// ULTIMOS AUTORES
			$autores 			= $this->repo_usuarios->getUltimosAutores(3);
			$data['autores'] 	= $autores;
			// es ADMIN ?
			// $admin 			= isAdmin($this->session);
			$data['admin']		= $this->admin;
			// ULTIMOS TRABAJOS
			$last_works			= $this->repo_trabajos->getLastWorks();
			$data['last_works'] 	= $last_works;
			// LOGUEADO ?
			$data['login_user'] 	= (boolean)$this->login_user;
			// ID DEL USUARIO LOGUEADO
			if($data['login_user']) {
				$data['id_user'] = $this->session->userdata['idUsuarios'];
			}
			// SECCION
			$data['section'] 		= $this->section; // en donde estamos
			// VALIDAR y ACCION PARA LOGUEARSE, SI NO ESTA LOGUEADO
			$data['form_action']	= site_url('trabajos/alta/');
			$data['form_validate']= base_url('/login/validate');
			// DOS TRABAJOS AL AZAR
			$works_random			= $this->repo_trabajos->getTwoWorksRandom();
			$data['works_random']	= $works_random;
			// TITULO e ID de BODY
			$data['body_id'] 	= 'perfil';
			$data['title']			= 'WordRev - Publica, comparte y obten conocimiento';
			// CANTIDAD DE PUBLICACIONES DEL AUTOR SELECCIONADO
			$count_works		= $this->repo_trabajos->getCountWorksByUserAndCategory($id_user, $id_category);
			// CATEGORIA
			$name_category	= $this->repo_categorias->getCategoryName($id_category);
			$data['name_category'] = $name_category;
			$data['id_category'] = $id_category;

			$data['form_action_search'] = $this->form_action_search;
			// PAGINADOR
			$pager['per_page']	= $this->config->item('cant_public_por_pagina');
			$pager['total']		= $count_works;
			$pager['page']		= $page;
			$pager['base_url']	= $this->config->base_url() . 'tucuenta/perfilVerMas/' . $id_user . '/' . $id_category . '/pagina';
			$pager['limit']		= limit($pager['page'], $pager['per_page']);
			$paginador 			= paginas_front($pager['total'], $pager['per_page'],$pager['page'], $pager['base_url'] );
			$data['paginador']	= $paginador;

			// PUBLICACIONES Y PERFIL DEL AUTOR SELECCIONADO
			$perfil_and_works			= $this->repo_usuarios->getPerfilAndWorksByCategory($id_user, $pager['limit'], $id_category );
			$data['perfil_and_works'] 	= $perfil_and_works;





			// USUARIO
			$author 					= $this->repo_usuarios->getById($id_user);
			$data['author']				= $author;
			// VISTAS
			$this->load->view('front_templates/heads', $data);
			$this->load->view('front_templates/header',$data);
			$this->load->view('front_templates/main_content',$data);
			$this->load->view('front_templates/footer',$data);


		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}


	// EDITAR EL PERFIL
	public function editarPerfil($id_user)
	{
		try {
			$data 	= array();
			// ULTIMOS AUTORES
			$autores 				= $this->repo_usuarios->getUltimosAutores(3);
			$data['autores'] 		= $autores;

			// $admin = isAdmin($this->session);
			$data['admin']			= $this->admin;
			$data['login_user'] 		= (boolean)$this->login_user;
			$data['form_action_search'] = $this->form_action_search;
			// ID DEL USUARIO LOGUEADO
			if($data['login_user']) {
				$data['id_user'] = $this->session->userdata['idUsuarios'];
				if($data['id_user'] != $id_user) { // POR SEGURIDAD, QUE CORRESPONDA MISMO ID DE LA SESSION
					redirect('');
				}
			} else {
				redirect('');
			}
			$data['section'] 			= $this->section; // en donde estamos
			$data['form_validate'] 	= base_url('/login/validate');

			$message 				= "";

			if($this->input->server('REQUEST_METHOD') == 'GET') { // COMIENZA A EDITAR EL PERFIL
				$user = $this->repo_usuarios->getById($id_user);

				$user['fecha'] = date('d/m/Y', strtotime($user['fecha']));

				$user['del_avatar'] = 0;

				if ( isset($user['avatar']) && $user['avatar'] != '') {
					$user['avatar_para_mostrar'] = '. . . ' . substr($user['avatar'], -25);
				}

				$errors = false;
			}else{ // GUARDAR, por post.
				$user = $this->getData();
				$errors = $this->repo_usuarios->validate($user);
			}



			if($this->input->server('REQUEST_METHOD') == 'POST' and !$errors)
			{
				if($this->repo_usuarios->update($user)) {
					$message 		= 'El usuario se ha editado con éxito.';
					$this->session->set_flashdata('message_success', $message);
					redirect('');
				}else{
					$message 		= 'El usuario NO se pudo modificar.';
					$this->session->set_flashdata('message_error', $message);
					redirect('');
				}

			} elseif($this->input->server('REQUEST_METHOD') == 'POST' and $errors) {
				foreach($errors as $key => $error){
					$message .=  $error .'<br>';
				}
				$data['errors'] = $message;
			}

			$data['user']		= $user;
			// $data['work'] 		= $work;
			$data['section'] 		= $this->section; // en donde estamos
			$data['estados'] 	= $this->repo_estados->getAll();


			// $data['precios'] 		= $this->repo_precios->getAll();
			// $data['categorias'] 	= $this->repo_categorias->getAll();
			// $data['estados'] 	= $this->repo_estadostrabajos->getAll();
			// $data['usuarios'] 	= $this->repo_usuarios->getAll();
			$data['form_action'] = site_url('tucuenta/editarPerfil/'. $user['idUsuarios']);
			$data['body_id'] 	= 'crearcuenta'; // no seria el correcto

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

			// VISTAS
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


	// También puede entrar desde link a autores
	public function mis_estadosdecuenta($id_user, $pagina, $page)
	{
		try {
			$data 				= array();
			// ULTIMOS AUTORES
			$autores 			= $this->repo_usuarios->getUltimosAutores(3);
			$data['autores'] 	= $autores;
			// es ADMIN ?
			// $admin 			= isAdmin($this->session);
			$data['admin']		= $this->admin;
			// ULTIMOS TRABAJOS
			$last_works			= $this->repo_trabajos->getLastWorks();
			$data['last_works'] 	= $last_works;
			// LOGUEADO ?
			$data['login_user'] 	= (boolean)$this->login_user;
			$data['form_action_search'] = $this->form_action_search;
			// ID DEL USUARIO LOGUEADO
			if($data['login_user']) {
				$data['id_user'] = $this->session->userdata['idUsuarios'];
				if($data['id_user'] != $id_user) { // POR SEGURIDAD, QUE CORRESPONDA MISMO ID DE LA SESSION
					redirect('');
				}
			} else {
				redirect('');
			}
			// SECCION
			$data['section'] 		= $this->section; // en donde estamos
			// VALIDAR y ACCION PARA LOGUEARSE, SI NO ESTA LOGUEADO
			// $data['form_action']	= site_url('trabajos/alta/');
			// $data['form_validate']= base_url('/login/validate');
			// DOS TRABAJOS AL AZAR
			// $works_random		= $this->repo_trabajos->getTwoWorksRandom();
			// $data['works_random']= $works_random;
			// TITULO e ID de BODY
			$data['body_id'] 	= 'temas';
			$data['title']			= 'WordRev - Publica, comparte y obten conocimiento';
			// CANTIDAD DE PUBLICACIONES DEL AUTOR SELECCIONADO VENDIDAS
			$count_works		= $this->repo_trabajos->getCountWorksOfAuthorSolded($id_user);
			// PAGINADOR
			$pager['per_page']	= $this->config->item('cant_public_por_pagina');
			$pager['total']		= $count_works;
			$pager['page']		= $page;
			$pager['base_url']	= $this->config->base_url() . 'tucuenta/mis_estadosdecuenta/' . $id_user . '/pagina';
			$pager['limit']		= limit($pager['page'], $pager['per_page']);
			$paginador 			= paginas_front($pager['total'], $pager['per_page'],$pager['page'], $pager['base_url'] );
			$data['paginador']	= $paginador;
			// PUBLICACIONES VENDIDAS DEL AUTOR
			$works_sold		= $this->repo_trabajos->getWorksOfAuthorSolded($id_user, $pager['limit']);
			$data['works_sold'] 	= $works_sold;
			// MONTO ACUMULADO
			$monto_acumulado 		= $this->repo_trabajos->getMontoAcumulado($id_user); // TENGO QUE SUMAR LAS REGALÍAS
			$data['monto_acumulado'] 	= $monto_acumulado;


			// USUARIO
			$author 			= $this->repo_usuarios->getById($id_user);
			$data['author']		= $author;





			$this->load->view('front_templates/heads', $data);
			$this->load->view('front_templates/header',$data);
			$this->load->view('front_templates/main_content',$data);
			$this->load->view('front_templates/footer',$data);


		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}



	protected function getData()
	{
		$user = array();

		if($this->input->get_post('idUsuarios')) {
			$user['idUsuarios'] = trim($this->input->get_post('idUsuarios'));
		}

		if($this->input->get_post('idRoles')) {
			$user['idRoles'] = trim($this->input->get_post('idRoles'));
		}

		if($this->input->get_post('nombre')) {
			$user['nombre'] = trim($this->input->get_post('nombre'));
		}else {
			$user['nombre'] = '';
		}

		if($this->input->get_post('apellido')) {
			$user['apellido'] = trim($this->input->get_post('apellido'));
		} else {
			$user['apellido'] = '';
		}

		if($this->input->get_post('email')) {
			$user['email'] = trim($this->input->get_post('email'));
		}else {
			$user['email'] = '';
		}


		if($this->input->get_post('telefono')) {
			$user['telefono'] = trim($this->input->get_post('telefono'));
		}else {
			$user['telefono'] = '';
		}

		// if($this->input->get_post('estado')) {
		// 	$user['estado'] = trim($this->input->get_post('estado'));
		// }

		if($this->input->get_post('esEditorial')) {
			$user['esEditorial'] = trim($this->input->get_post('esEditorial'));
		}else {
			$user['esEditorial'] = 0;
		}

		if($this->input->get_post('regalias')) {
			$user['regalias'] = trim($this->input->get_post('regalias'));
		}else {
			$user['regalias'] = 0;
		}

		if($this->input->get_post('esAutor')) {
			$user['esAutor'] = trim($this->input->get_post('esAutor'));
		}else {
			$user['esAutor'] = 0;
		}

		if($this->input->get_post('fecha')) {
			$user['fecha'] = trim($this->input->get_post('fecha'));
		}else {
			$user['fecha'] = '';
		}

		if($this->input->get_post('intereses')) {
			$user['intereses'] = trim($this->input->get_post('intereses'));
		}else {
			$user['intereses'] = '';
		}

		if($this->input->get_post('lugar')) {
			$user['lugar'] = trim($this->input->get_post('lugar'));
		}else {
			$user['lugar'] = '';
		}

		if($this->input->get_post('profesion')) {
			$user['profesion'] = trim($this->input->get_post('profesion'));
		}else {
			$user['profesion'] = '';
		}

		if($this->input->get_post('biografia')) {
			$user['biografia'] = trim($this->input->get_post('biografia'));
		}else {
			$user['biografia'] = '';
		}

		if($this->input->get_post('observaciones')) {
			$user['observaciones'] = trim($this->input->get_post('observaciones'));
		}else {
			$user['observaciones'] = '';
		}


		if(isset($_POST['del_avatar']) && $_POST['del_avatar'] == 0) {
			$user['del_avatar'] = 1;
		} else {
			$user['del_avatar'] = 0;
		}

		if(isset($_FILES['avatar']['tmp_name']) and !empty($_FILES['avatar']['tmp_name'])){
			$user['avatar'] = sha1(md5(uniqid())). "_".$_FILES['avatar']['name'];
		} else {
			if($this->input->post('name_avatar')){
				// die('esta posteado');
				$user['avatar'] = $this->input->post('name_avatar');
			}
		}



		if ( isset($user['avatar']) && $user['avatar'] != '') {
			$user['avatar_para_mostrar'] = '. . . ' . substr($user['avatar'], -25);
		}






		return $user;
	}





}
?>
