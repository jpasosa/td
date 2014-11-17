<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* The MX_Controller class is autoloaded as required */

class CI_class extends MX_Controller {


	public function __construct(){


		parent::__construct();
		$this->section = $this->router->fetch_class() . '.' . $this->router->fetch_method();

		$this->load->model('all_models/repo_usuarios');
		$this->load->model('all_models/repo_precios');
		$this->load->model('all_models/repo_estadostrabajos');
		$this->load->model('all_models/repo_categorias');
		$this->load->model('all_models/is_categorias');
		$this->load->model('all_models/repo_trabajos');
		$this->load->model('all_models/repo_estados');
		$this->load->model('all_models/repo_regalias');
		$this->load->model('all_models/repo_compras');
		$this->load->model('front_templates/templates_model');

		if(isLogged($this->session)){
			$this->login_user = true;
		} else {
			$this->login_user = false;
		}

		$admin 			= isAdmin($this->session);
		$this->admin 		= $admin;

		$this->form_action_search = base_url('/trabajos/buscar');





	}






	public function index() {

	}






}
?>
