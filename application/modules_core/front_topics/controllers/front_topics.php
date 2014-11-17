<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Front_topics extends CI_class {


	public function __construct()
	{
		parent::__construct();
		// $this->load->library('session');
		// $this->load->model('destacados_model');

		// $this->config->load('emails');
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


	// LISTAR LOS TEMAS
	public function listar()
	{
		try {
			$data 					= array();
			// TODO: aca tenemos que ver que categoria lista.
			// deberia ser todas las categorias, dos por cada.

			$all_topics_to_show 		= $this->repo_categorias->getAllCategorysWithWorksActives();
			$data['all_topics_to_show'] 	= $all_topics_to_show;

			// $temas 				= $this->repo_categorias->getAllTemasToShow(27);
			// $data['temas']		= $temas;

			// NOMBRE DE LA CATEGORIA
			// $category_name		= $this->repo_categorias->getCategoryName(27);
			// $data['category_name']	= $category_name;
			// DOS TRABAJOS AL AZAR
			$works_random			= $this->repo_trabajos->getTwoWorksRandom();
			$data['works_random'] 	= $works_random;
			// ULTIMAS PUBLICACIONES
			$last_works				= $this->repo_trabajos->getLastWorks(); // para poner a la derecha
			$data['last_works'] 		= $last_works;
			// ULTIMOS AUTORES
			$autores 				= $this->repo_usuarios->getUltimosAutores(3);
			$data['autores'] 		= $autores;
			// $admin 				= isAdmin($this->session);
			$data['category_id']		= 27;
			$data['admin']			= $this->admin;
			$data['login_user'] 		= (boolean)$this->login_user;
			if ($data['login_user']) { // ID DEL USUARIO LOGUEADO
				$data['id_user'] = $this->session->userdata['idUsuarios'];
			}
			$data['section'] 			= $this->section; // en donde estamos
			$data['body_id'] 		= 'temas';
			$data['title']				= 'WordRev - Publica, comparte y obten conocimiento';
			$data['form_validate'] 	= base_url('/login/validate');
			$data['form_action_search'] = $this->form_action_search;



			$this->load->view('front_templates/heads', $data);
			$this->load->view('front_templates/header',$data);
			$this->load->view('front_templates/main_content',$data);
			$this->load->view('front_templates/footer',$data);

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	// LISTAR LOS TEMAS DE UNA CATEGORIA
	public function listarPorCategoria($id_category, $pagina, $page)
	{
		try {
			$data 					= array();
			// DOS TRABAJOS AL AZAR
			$works_random			= $this->repo_trabajos->getTwoWorksRandom();
			$data['works_random'] 	= $works_random;
			// ULTIMAS PUBLICACIONES
			$last_works				= $this->repo_trabajos->getLastWorks(); // para poner a la derecha
			$data['last_works'] 		= $last_works;
			// ULTIMOS AUTORES
			$autores 				= $this->repo_usuarios->getUltimosAutores(3);
			$data['autores'] 		= $autores;
			// GENERALES
			// $admin 				= isAdmin($this->session);
			// $data['category_id']		= 27;
			$data['admin']			= $this->admin;
			$data['login_user'] 		= (boolean)$this->login_user;
			if ($data['login_user']) { // ID DEL USUARIO LOGUEADO
				$data['id_user'] = $this->session->userdata['idUsuarios'];
			}
			$data['section'] 			= $this->section; // en donde estamos
			$data['body_id'] 		= 'temas';
			$data['title']				= 'WordRev - Publica, comparte y obten conocimiento';
			$data['form_validate'] 	= base_url('/login/validate');
			// CANTIDAD DE TRABAJOS PUBLICADOS
			$cant_works			= $this->repo_trabajos->getCantidadPublicadosByCategory($id_category);
			$data['cant_works']		= $cant_works;
			$data['form_action_search'] = $this->form_action_search;

			$is_subcategory 		= $this->repo_categorias->isSubCategory($id_category);


			if($is_subcategory)
			{
				// SUB CATEGORIA
				$parent_id = $this->repo_categorias->getParentId($id_category);
				// CATEGORIA, NOMBRE Y ID
				$category_name			= $this->repo_categorias->getCategoryName($parent_id);
				$category_image			= $this->repo_categorias->getCategoryImage($parent_id);
				$data['category_image']		= $category_image;
				$data['category_name']		= $category_name;
				$data['category_id']			= $parent_id;

			} else { // CATEGORIA
				// CATEGORIA, NOMBRE Y ID
				$category_name			= $this->repo_categorias->getCategoryName($id_category);
				$category_image			= $this->repo_categorias->getCategoryImage($id_category);
				$data['category_image']		= $category_image;
				$data['category_name']		= $category_name;
				$data['category_id']			= $id_category;
			}

			// PAGINADOR
			$pager['per_page']	= $this->config->item('cant_public_por_pagina');
			$pager['total']		= $cant_works;
			$pager['page']		= $page;
			$pager['base_url']	= $this->config->base_url() . 'temas/listarPorCategoria/' . $id_category . '/pagina';
			$pager['limit']		= limit($pager['page'], $pager['per_page']);
			$paginador 			= paginas_front($pager['total'], $pager['per_page'],$pager['page'], $pager['base_url'] );
			$data['paginador']	= $paginador;

			if($is_subcategory)
			{	// SUBCATEGORIA
				$data['is_subcategory']		= true;
				$works_by_category 		= $this->repo_trabajos->getWorksBySubCategory($id_category, $pager['limit'], array('resumen' => 'limit_chars.250'));

			} else
			{	// CATEGORIA
				$data['is_subcategory']		= false;
				$works_by_category 		= $this->repo_trabajos->getWorksByCategory($id_category, $pager['limit'], array('resumen' => 'limit_chars.250'));
			}
			$data['works_by_category'] 	= $works_by_category;
			// $temas 					= $this->repo_categorias->getAllTemasToShow(27);
			// $data['temas']			= $temas;





			$this->load->view('front_templates/heads', $data);
			$this->load->view('front_templates/header',$data);
			$this->load->view('front_templates/main_content',$data);
			$this->load->view('front_templates/footer',$data);

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	// Muestra una categoria especifica. Puede entrar desde el Breadcumb.
	public function ver($id_category)
	{


		try {
			// LAS PUBLICACIONES DE ESA CATEGORIA O SUBCATEGORIA
			if($this->repo_categorias->isSubCategory($id_category)) {
				$temas = $this->repo_categorias->getTemaToShowIsSubCategory($id_category, array('resumen' => 'limit_chars.250'));
			} else {
				$temas = $this->repo_categorias->getTemaToShow($id_category, array('resumen' => 'limit_chars.250'));
			}
			$data['temas']			= $temas;

			// ID DE LA CATEGORIA
			$data['category_id']		= $id_category;
			// ULTIMOS AUTORES
			$autores 				= $this->repo_usuarios->getUltimosAutores(3);
			$data['autores'] 		= $autores;

			// CATEGORIAS Y SUBCATEGORIAS
			if($this->repo_categorias->isSubCategory($id_category)) { // ES UNA SUBCATEGORIA
				$sub_category_id 	= $id_category;
				$sub_category_name = $this->repo_categorias->getCategoryName($sub_category_id);
				$category_id 		= $this->repo_categorias->getParentId($sub_category_id);
				$category_name	= $this->repo_categorias->getCategoryName($category_id);
				$category_image 	= $this->repo_categorias->getCategoryImage($category_id);
				$data['sub_category_id'] 		= $sub_category_id;
				$data['sub_category_name'] 	= $sub_category_name;
			} else {												// ES UNA CATEGORIA
				$category_id 		= $id_category;
				$category_name	= $this->repo_categorias->getCategoryName($category_id);
				$category_image 	= $this->repo_categorias->getCategoryImage($category_id);
			}
			$data['category_image']	= $category_image;
			$data['category_name']	= $category_name;
			$data['category_id']		= $category_id;
			$data['form_action_search'] = $this->form_action_search;

			// DOS TRABAJOS AL AZAR
			$works_random			= $this->repo_trabajos->getTwoWorksRandom();
			$data['works_random'] 	= $works_random;
			// ULTIMOS TRABAJOS
			$last_works				= $this->repo_trabajos->getLastWorks(); // para poner a la derecha
			$data['last_works'] 		= $last_works;
			// ES ADMIN ?
			// $admin 				= isAdmin($this->session);
			$data['admin']			= $this->admin;
			$data['login_user'] 		= (boolean)$this->login_user;
			if ($data['login_user']) { // ID DEL USUARIO LOGUEADO
				$data['id_user'] = $this->session->userdata['idUsuarios'];
			}
			$data['section'] 			= $this->section; // en donde estamos
			$data['body_id'] 		= 'temas';
			$data['title']				= 'WordRev - Publica, comparte y obten conocimiento';
			$data['form_validate'] 	= base_url('/login/validate');


			$this->load->view('front_templates/heads', $data);
			$this->load->view('front_templates/header',$data);
			$this->load->view('front_templates/main_content',$data);
			$this->load->view('front_templates/footer',$data);

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}




	}








}
?>
