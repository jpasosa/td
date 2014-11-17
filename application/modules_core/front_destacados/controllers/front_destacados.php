<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Front_destacados extends CI_class {


	public function __construct()
	{
		parent::__construct();
		// $this->load->library('session');
		$this->load->model('destacados_model');

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


	// LISTAR LOS DESTACADOS
	public function listar()
	{
		try {
			$data 					= array();
			// DESTACADOS
			$destacados 			= $this->destacados_model->getAllToShow(array('resumen' => 'limit_chars.250'));
			$data['destacados']		= $destacados;



			// ULTIMOS AUTORES
			$autores 				= $this->repo_usuarios->getUltimosAutores(3);
			$data['autores'] 		= $autores;
			// DOS PUBLICACIONES AL AZAR
			$works_random			= $this->repo_trabajos->getTwoWorksRandom();
			$data['works_random'] 	= $works_random;
			// ULTIMAS PUBLICACIONES
			$last_works				= $this->repo_trabajos->getLastWorks(); // para poner a la derecha
			$data['last_works'] 		= $last_works;
			// ADMIN ?
			// $admin 				= isAdmin($this->session);
			$data['admin']			= $this->admin;
			$data['login_user'] 		= (boolean)$this->login_user;
			if ($data['login_user']) { // ID DEL USUARIO LOGUEADO
				$data['id_user'] = $this->session->userdata['idUsuarios'];
			}
			// SECCION
			$data['section'] 			= $this->section; // en donde estamos
			$data['body_id'] 		= 'destacados';
			$data['title']				= 'WordRev - Publica, comparte y obten conocimiento';
			$data['form_validate'] 	= base_url('/login/validate');
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









}
?>
