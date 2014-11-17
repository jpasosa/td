<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Front_templates extends CI_class {


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


	// BENEFICIOS
	public function view()
	{
		try {
			$data 					= array();
			// $temas 				= $this->repo_categorias->getAllTemasToShow(27);
			// $category_name		= $this->repo_categorias->getCategoryName(27);
			// ULTIMOS AUTORES
			$autores 				= $this->repo_usuarios->getUltimosAutores(3);
			$data['autores'] 		= $autores;

			$works_random			= $this->repo_trabajos->getTwoWorksRandom();
			$last_works				= $this->repo_trabajos->getLastWorks(); // para poner a la derecha

			// $admin 				= isAdmin($this->session);

			// $data['temas']			= $temas;

			$data['works_random'] 	= $works_random;

			$data['last_works'] 		= $last_works;
			// $data['category_name']	= $category_name;
			$data['admin']			= $this->admin;
			$data['login_user'] 		= (boolean)$this->login_user;
			if ($data['login_user']) { // ID DEL USUARIO LOGUEADO
				$data['id_user'] = $this->session->userdata['idUsuarios'];
			}
			$data['section'] 			= $this->section; // en donde estamos
			$data['body_id'] 		= 'beneficios';
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

	// PREGUNTAS FRECUENTES
	public function preguntas_frecuentes()
	{
		try {
			$data 					= array();
			// AUTORES
			$autores 				= $this->repo_usuarios->getUltimosAutores(3);
			$data['autores'] 		= $autores;
			// PUBLICACIONES AL AZAR
			$works_random			= $this->repo_trabajos->getTwoWorksRandom();
			$last_works				= $this->repo_trabajos->getLastWorks(); // para poner a la derecha
			$data['works_random'] 	= $works_random;
			$data['last_works'] 		= $last_works;
			// $data['category_name']	= $category_name;
			$data['admin']			= $this->admin;
			$data['login_user'] 		= (boolean)$this->login_user;
			if ($data['login_user']) { // ID DEL USUARIO LOGUEADO
				$data['id_user'] = $this->session->userdata['idUsuarios'];
			}
			$data['section'] 			= $this->section; // en donde estamos
			$data['body_id'] 		= 'beneficios';
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

	// COPYRIGHT
	public function copyright()
	{
		try {
			$data 					= array();
			// AUTORES
			$autores 				= $this->repo_usuarios->getUltimosAutores(3);
			$data['autores'] 		= $autores;
			// PUBLICACIONES AL AZAR
			$works_random			= $this->repo_trabajos->getTwoWorksRandom();
			$last_works				= $this->repo_trabajos->getLastWorks(); // para poner a la derecha
			$data['works_random'] 	= $works_random;
			$data['last_works'] 		= $last_works;
			// $data['category_name']	= $category_name;
			$data['admin']			= $this->admin;
			$data['login_user'] 		= (boolean)$this->login_user;
			if ($data['login_user']) { // ID DEL USUARIO LOGUEADO
				$data['id_user'] = $this->session->userdata['idUsuarios'];
			}
			$data['section'] 			= $this->section; // en donde estamos
			$data['body_id'] 		= 'beneficios';
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

	// ACERCA DE NOSOTROS
	public function acerca_de_nosotros()
	{
		try {
			$data 					= array();
			// AUTORES
			$autores 				= $this->repo_usuarios->getUltimosAutores(3);
			$data['autores'] 		= $autores;
			// PUBLICACIONES AL AZAR
			$works_random			= $this->repo_trabajos->getTwoWorksRandom();
			$last_works				= $this->repo_trabajos->getLastWorks(); // para poner a la derecha
			$data['works_random'] 	= $works_random;
			$data['last_works'] 		= $last_works;
			// $data['category_name']	= $category_name;
			$data['admin']			= $this->admin;
			$data['login_user'] 		= (boolean)$this->login_user;
			if ($data['login_user']) { // ID DEL USUARIO LOGUEADO
				$data['id_user'] = $this->session->userdata['idUsuarios'];
			}
			$data['section'] 			= $this->section; // en donde estamos
			$data['body_id'] 		= 'beneficios';
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

	// PRIVACIDAD
	public function privacidad()
	{
		try {
			$data 					= array();
			// AUTORES
			$autores 				= $this->repo_usuarios->getUltimosAutores(3);
			$data['autores'] 		= $autores;
			// PUBLICACIONES AL AZAR
			$works_random			= $this->repo_trabajos->getTwoWorksRandom();
			$last_works				= $this->repo_trabajos->getLastWorks(); // para poner a la derecha
			$data['works_random'] 	= $works_random;
			$data['last_works'] 		= $last_works;
			// $data['category_name']	= $category_name;
			$data['admin']			= $this->admin;
			$data['login_user'] 		= (boolean)$this->login_user;
			if ($data['login_user']) { // ID DEL USUARIO LOGUEADO
				$data['id_user'] = $this->session->userdata['idUsuarios'];
			}
			$data['section'] 			= $this->section; // en donde estamos
			$data['body_id'] 		= 'beneficios';
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


	// FORMULARIO DE CONTACTO
	public function contacto()
	{
		try {
			$data 					= array();
			// AUTORES
			$autores 				= $this->repo_usuarios->getUltimosAutores(3);
			$data['autores'] 		= $autores;
			// PUBLICACIONES AL AZAR
			$works_random			= $this->repo_trabajos->getTwoWorksRandom();
			$last_works				= $this->repo_trabajos->getLastWorks(); // para poner a la derecha
			$data['works_random'] 	= $works_random;
			$data['last_works'] 		= $last_works;
			// $data['category_name']	= $category_name;
			$data['admin']			= $this->admin;
			$data['login_user'] 		= (boolean)$this->login_user;
			if ($data['login_user']) { // ID DEL USUARIO LOGUEADO
				$data['id_user'] = $this->session->userdata['idUsuarios'];
			}
			$data['section'] 			= $this->section; // en donde estamos
			$data['body_id'] 		= 'beneficios';
			$data['title']				= 'WordRev - Publica, comparte y obten conocimiento';
			$data['form_validate'] 	= base_url('/front_templates/contacto');
			$message 				= array();
			$contacto 				= $this->getData();
			$data['contacto']		= $contacto;
			$errors  				= $this->templates_model->validate($contacto);
			// $errors 					= false;
			$data['form_action_search'] = $this->form_action_search;



			if($this->input->server('REQUEST_METHOD') == 'POST' and !$errors)
			{
				$this->config->load('emails');
				$this->load->library('email');
				$mail['from'] 	= $contacto['email'];
				$mail['to'] 		= $this->config->item('email_admin');
				$mail['title'] 	= 'Se contactaron desde la Web';
				$mail['body'] 	= $contacto['comentario'];
				if($this->sendMail($mail)) {
					$message = 'Se ha recibido su mensaje, será contestado a la brevedad.';
				} else {
					$message = 'No se pudo enviar el mensaje.';
				}
				$this->session->set_flashdata('work_success', $message);
				redirect('');

			} elseif($this->input->server('REQUEST_METHOD') == 'POST' and $errors) {
				$data['errors'] = $errors;
			}

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
		$contacto = array();

		if($this->input->get_post('nombre')) {
			$contacto['nombre'] = trim($this->input->get_post('nombre'));
		} else {
			$contacto['nombre'] = '';
		}

		if($this->input->get_post('email')) {
			$contacto['email'] = trim($this->input->get_post('email'));
		} else {
			$contacto['email'] = '';
		}

		if($this->input->get_post('comentario')) {
			$contacto['comentario'] = trim($this->input->get_post('comentario'));
		} else {
			$contacto['comentario'] = '';
		}

		return $contacto;

	}


	// ENVIA EL MAIL
	protected function sendMail($data_mail) {
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
