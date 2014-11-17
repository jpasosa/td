<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_cron extends CI_class
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('all_models/repo_compras');
		$this->load->model('all_models/repo_regalias');
		$this->load->model('all_models/repo_usuarios');
		$this->load->model('all_models/repo_trabajos');
		$this->load->model('admin_trabajos/estadostrabajos_model');
		$this->load->model('admin_trabajos/trabajos_model');
		$this->load->model('admin_usuarios/usuarios_model');
		$this->load->model('admin_categorias/categorias_model');
		$this->load->model('admin_precios/precios_model');
		// $this->output->enable_profiler(TRUE);
	}

	public function notif_autores_regalias()
	{

		$autores_regalias = $this->repo_usuarios->getAllRegalias();


		foreach ( $autores_regalias AS $reg )
		{
			$data['from'] 	= $this->config->item('email_admin');
			$data['to'] 		= $reg['email'];
			$data['title'] 	= 'WordRev :: Regalías acumuladas a la fecha';
			$data['body'] 	= $reg['email'] . ', has acumulado ' . $reg['regalias'] . ' regalias';
			$this->sendMail($data);
		}
		echo 'Se han enviado los mails correspondientes.';

	}

	public function admin_montos()
	{

		$autores_regalias = $this->repo_usuarios->getAllRegalias();


		$tabla = '';
		foreach ( $autores_regalias AS $reg )
		{
			$tabla .= $reg['email'] . ' -> ' . $reg['regalias'] . ' regalías
';
		}

		$data['from'] 	= $this->config->item('email_admin');
		$data['to'] 		= array($this->config->item('email_sistemas'), $this->config->item('email_programador'));
		$data['title'] 	= 'WordRev :: Regalías acumuladas a la fecha de Todos los Autores';
		$data['body'] 	= 'Lista de Autores que tienen regalías a la fecha:
' . $tabla;
		$this->sendMail($data);

		echo 'Se ha enviado el mail al administrador con la lista de las regalías de los usuarios.';

	}

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
