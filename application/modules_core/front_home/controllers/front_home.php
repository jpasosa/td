<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Front_home extends CI_class
{


	public function __construct()
	{
		parent::__construct();
		//$this->output->enable_profiler(TRUE);


	}

	// HOMEPAGE
	public function homepage()
	{
		// ULTIMOS AUTORES
		$autores 			= $this->repo_usuarios->getUltimosAutores(3);
		$data['autores'] 	= $autores;
		// ULTIMOS TRABAJOS
		$last_works			= $this->repo_trabajos->getLastWorks();
		$data['last_works'] 	= $last_works;
		$data['form_validate'] = base_url('/login/validate');

		// LOGUEADO ?
		$data['login_user'] 	= (boolean)$this->login_user;
		if ($data['login_user']) { // ID DEL USUARIO LOGUEADO
			$data['id_user'] = $this->session->userdata['idUsuarios'];
			$last_visited = (int)$this->session->userdata('id_last_visited'); // si antes había visitado una publicación
			if ($last_visited > 0 ) {
				$newdata = array('id_last_visited'  => 0);
				$this->session->set_userdata($newdata);
				redirect('trabajos/ver/' . $last_visited);
			}
		}


		$data['section'] 		= $this->section;
		$data['this'] 		= $this;
		$data['body_id']		= 'home';
		$data['title']			= 'WordRev - Publica, comparte y obten conocimiento';
		// CATEGORIAS PARA MOSTRAR EN LA PARTE INFERIOR
		$categorias 		= $this->repo_categorias->getCategorysToHomepage();
		$data['categorias']	= $categorias;
		$data['form_action_search'] = $this->form_action_search;

		// $data['usuarios'] = $this->usuarios_model->getAll();

		// VISTAS
		$this->load->view('front_templates/heads', $data);
		$this->load->view('front_templates/header',$data);
		$this->load->view('front_templates/main_content',$data);
		$this->load->view('front_templates/footer',$data);
	}





}

?>
