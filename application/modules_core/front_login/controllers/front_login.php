<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Front_login extends CI_class {


	public function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->model('admin_login/login_model');
		$this->config->load('emails');
	}



	public function index()
	{
		try {

			# ULTIMOS TRABAJOS
			$last_works			= $this->repo_trabajos->getLastWorks();
			$data['last_works']	= $last_works;
			// ULTIMOS AUTORES
			$autores 				= $this->repo_usuarios->getUltimosAutores(3);
			$data['autores'] 		= $autores;

			$data['login_user'] 	= (boolean)$this->login_user;
			$data['section'] 		= $this->section; // en donde estamos
			$data['body_id']		= 'login';
			$data['form_validate'] = base_url('/login/validate');
			$data['this'] 		= $this;
			$data['title']			= 'WordRev - Publica, comparte y obten conocimiento';
			$data['form_action_search'] = $this->form_action_search;

			$this->load->view('front_templates/heads', $data);
			$this->load->view('front_templates/header',$data);
			$this->load->view('front_templates/main_content',$data);
			$this->load->view('front_templates/footer',$data);



		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}



	public function validate()
	{

		try {

			# ULTIMOS TRABAJOS
			$last_works			= $this->repo_trabajos->getLastWorks();
			$data['last_works']	= $last_works;
			// ULTIMOS AUTORES
			$autores 				= $this->repo_usuarios->getUltimosAutores(3);
			$data['autores'] 		= $autores;

			$data['login_user'] 	= $this->login_user;
			$data['section'] 		= $this->section; // en donde estamos
			$data['body_id']		= 'login';
			$data['form_action_search'] = $this->form_action_search;


			if($this->input->post('username') && $this->input->post('password'))
			{
				$data['login_user'] 		= $this->login_user;
				$dataUsuario['email'] 	= $this->input->post('username');
				$dataUsuario['clave'] 	= $this->input->post('password');
				$usuario 				= $this->login_model->validate($dataUsuario);

				if(isset($usuario) && $usuario['idUsuarios'] > 0)
				{ 	// Lo encontró en la base como user registrado.
					$this->load->model('admin_usuarios/usuarios_model');
					$usuario = $this->usuarios_model->get($usuario);
					fillSession($usuario,$this->session); // lo mete dentro de la session.
					$admin 			= isAdmin($this->session);
					if($admin) {
						redirect('admin_usuarios'); 	// BACKEND
					} else {
						redirect('homepage'); 		// FRONTEND
					}

				}else {
					$data['error_login'] = true;
				}

				// $data['title'] = 'Ingreso al panel de control';
				// $data['form_register'] = base_url('login/register');
				// $data['form_forgot'] = base_url('login/forgot');
				// $data['form_validate'] = base_url('login/validate');

				$data['section'] 		= $this->section; // en donde estamos
				$data['body_id']		= 'login';
				$data['form_validate'] = base_url('/login/validate');
				$data['this'] 		= $this;
				$data['title']			= 'WordRev - Publica, comparte y obten conocimiento';


				$this->load->view('front_templates/heads', $data);
				$this->load->view('front_templates/header',$data);
				$this->load->view('front_templates/main_content',$data);
				$this->load->view('front_templates/footer',$data);

			}else{
				redirect('homepage');
			}

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}


	public function logout()
	{
		if(isLogged($this->session)){
			try {
				$user_name = $this->session->userdata('userName');
				$this->session->sess_destroy();
				redirect('homepage');
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}

		} else {
				echo "NO EXISTE NINGUN USUARIO PARA DESTRUIR EN SESION";
		}
	}



	public function crear_cuenta( $recordar = null)
	{
		try {
			if ($recordar != null) {
				$newdata = array('id_last_visited'  => $recordar);
				$this->session->set_userdata($newdata);
			}
			# ULTIMOS TRABAJOS
			$last_works				= $this->repo_trabajos->getLastWorks();
			$data['last_works']		= $last_works;
			# ULTIMOS AUTORES
			$autores 				= $this->repo_usuarios->getUltimosAutores(3);
			$data['autores'] 		= $autores;
			# LOS DE SIEMPRE
			$data['error_validate'] 	= false;
			$data['login_user'] 		= (boolean)$this->login_user;
			$data['section'] 			= $this->section; // en donde estamos
			$data['body_id']			= 'crearcuenta';
			$data['form_validate'] = base_url('/login/validate'); // para el login de la derecha
			$data['form_validate_cuenta'] 	= base_url('/login/crear_cuenta');
			$data['this'] 			= $this;
			$data['title']				= 'WordRev - Publica, comparte y obten conocimiento';
			$data['form_action_search'] = $this->form_action_search;

			if($this->input->server('REQUEST_METHOD') == 'GET') {
				$usuario_nuevo = $this->getDataEmpty();
			}

			if($this->input->server('REQUEST_METHOD') == 'POST' && $this->input->post('user') && $this->input->post('email')
				&& $this->input->post('pass') && $this->input->post('repeat_pass') )
			{
				$user 			= $this->input->post('user');
				$email 			= $this->input->post('email');
				$pass 			= $this->input->post('pass');
				$repeat_pass 	= $this->input->post('repeat_pass');
				$exist_user 	= $this->repo_usuarios->existUser($email);
				if ($exist_user) {
					$data['error_validate'] = true;
					$data['error_campos'] = 'usuario ya existe';
				} else {
					if($pass != $repeat_pass) {
						$data['error_validate'] 	= true;
						$data['error_campos'] 	= 'Las contraseñas no conciden';
					} else {
						$data['error_validate'] 	= false;
					}
				}

				$usuario_nuevo = $this->getData();

				// creacion de usuario, paso las validaciones
				if ($data['error_validate'] == false)
				{
					if($this->repo_usuarios->create($usuario_nuevo)) {
						// ENVIO DE MAIL
						$mail['to'] 		= $usuario_nuevo['email'];
						$mail['title'] 	= 'Activación de Cuenta - Hacer click en el link';
						$mail['body'] 	= 'Para poder activar su cuenta, haga click, en el siguiente link: ' . base_url() . 'login/activar_cuenta/' . $usuario_nuevo['verificacion'];

						$envia_mail = $this->sendMail($mail);
						if($envia_mail) {
							$message = 'Se ha creado el usuario con éxito, y se envió un mail de verificación';
							$this->session->set_flashdata('work_success', $message);
							redirect();
						}else {
							$message = 'No se pudo enviar mail de verificación a su casilla';
							$this->session->set_flashdata('work_success', $message);
							redirect();
						}


					} else {
						$message = 'No se pudo crear el usuario';
						$this->session->set_flashdata('flash_error', $message);
						redirect();
					}


				}



			} elseif ($this->input->server('REQUEST_METHOD') == 'POST')
			{
				$data['error_validate'] = true;
				$data['error_campos'] = 'hay algun campo que no completo';
				$usuario_nuevo = $this->getData();
			}

			$data['usuario_nuevo'] = $usuario_nuevo;

			// VISTAS
			$this->load->view('front_templates/heads', $data);
			$this->load->view('front_templates/header',$data);
			$this->load->view('front_templates/main_content',$data);
			$this->load->view('front_templates/footer',$data);

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	protected function getDataEmpty()
	{
		$user = array();
		$user['user'] = '';
		$user['apellido'] = '';
		$user['email'] = '';
		$user['pass'] = '';
		$user['repeat_pass'] = '';
		$user['telefono'] = '';
		$user['avatar'] = '';
		$user['fecha'] = '';
		$user['intereses'] = '';
		$user['lugar'] = '';
		$user['profesion'] = '';

		return $user;
	}

	protected function getData()
	{
		$user = array();
		if ($this->input->post('user')) {
			$user['user'] = $this->input->post('user');
		} else {
			$user['user'] = '';
		}

		if ($this->input->post('apellido')) {
			$user['apellido'] = $this->input->post('apellido');
		} else {
			$user['apellido'] = '';
		}
		if ($this->input->post('email')) {
			$user['email'] = $this->input->post('email');
		} else {
			$user['email'] = '';
		}
		if ($this->input->post('pass')) {
			$user['pass'] = $this->input->post('pass');
		} else {
			$user['pass'] = '';
		}
		if ($this->input->post('fecha')) {
			$user['fecha'] = $this->input->post('fecha');
		} else {
			$user['fecha'] = '';
		}

		$user['repeat_pass'] = '';

		if ($this->input->post('telefono')) {
			$user['telefono'] = $this->input->post('telefono');
		} else {
			$user['telefono'] = '';
		}


		if ( isset($_FILES['avatar']) && $_FILES['avatar']['name'] != '' ) {
			$user['avatar'] = $this->savePhoto($_FILES['avatar']['name']);
		} else {
			$user['avatar'] = '';
		}
		if ($this->input->post('fecha')) {
			$user['fecha'] = $this->input->post('fecha');
		} else {
			$user['fecha'] = '';
		}
		if ($this->input->post('user')) {
			$user['intereses'] = $this->input->post('intereses');
		} else {
			$user['intereses'] = '';
		}
		if ($this->input->post('lugar')) {
			$user['lugar'] = $this->input->post('lugar');
		} else {
			$user['lugar'] = '';
		}
		if ($this->input->post('profesion')) {
			$user['profesion'] = $this->input->post('profesion');
		} else {
			$user['profesion'] = '';
		}
		$user['estado']			= 0;
		$user['fecha_created_at'] = date('Y-m-d');
		$user['verificacion']		= hash('md5', $user['email']);

		return $user;
	}

	// RECUPERAR CLAVE
	public function recuperar_clave()
	{
		try {
			# ULTIMOS TRABAJOS
			$last_works				= $this->repo_trabajos->getLastWorks();
			$data['last_works']		= $last_works;
			# ULTIMOS AUTORES
			$autores 				= $this->repo_usuarios->getUltimosAutores(3);
			$data['autores'] 		= $autores;
			# LOS DE SIEMPRE
			$data['error_validate'] 	= false;
			$data['login_user'] 		= (boolean)$this->login_user;
			$data['section'] 			= $this->section; // en donde estamos
			$data['body_id']			= 'crearcuenta';
			$data['form_validate'] 	= base_url('/login/recuperar_clave');
			$data['this'] 			= $this;
			$data['title']				= 'WordRev - Publica, comparte y obten conocimiento';
			$data['form_action_search'] = $this->form_action_search;

			if($this->input->post('username'))
			{
				$exist_mail = $this->repo_usuarios->existMail($this->input->post('username'));
				if ($exist_mail)
				{
					$user 			= $this->repo_usuarios->getByEmail($this->input->post('username'));
					$new_pass 		= $this->repo_usuarios->changePass($user['idUsuarios']);
					// ENVIO DE MAIL
					$mail['to'] 		= $this->input->post('username');
					$mail['title'] 	= 'Recuperación de clave de WordRev.com';
					$mail['body'] 	= 'Tu nueva clave es ' . $new_pass;
					$envia_mail 	= $this->sendMail($mail);
					if($envia_mail) {
						$message = 'Se ha enviado un mail con tu nueva clave.';
						$this->session->set_flashdata('work_success', $message);
						redirect();
					}else{
						$message = 'No se pudo enviar mail con la clave nueva';
						$this->session->set_flashdata('work_success', $message);
						redirect();
					}

				}else{ // NO EXISTE EL MAIL
					$data['error_login'] = true;
				}

			}

			// VISTAS
			$this->load->view('front_templates/heads', $data);
			$this->load->view('front_templates/header',$data);
			$this->load->view('front_templates/main_content',$data);
			$this->load->view('front_templates/footer',$data);

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}





	// ACTIVACIÓN DE LA CUENTA DEL USUARIO
	public function activar_cuenta ($hash)
	{
		$user_to_activate = $this->repo_usuarios->getByVerificacion($hash);

		if(!$user_to_activate)
		{
			# NO ENCONTRO USER EN LA BASE DE DATOS
			$message = 'No pudo encontrar su usuario en la Base de Datos.';
			$this->session->set_flashdata('work_success', $message);
			redirect();

		}else {
			$activar_user = $this->repo_usuarios->activate($user_to_activate);

			if($activar_user) {
				# ACTIVO EL USUARIO
				$data_mail['to'] 		= array($this->config->item('email_programador'), $this->config->item('email_sistemas'), $this->config->item('email_admin'));
				$data_mail['title'] 	= 'Se activó un Usuario Nuevo';
				$data_mail['body'] 	= 'El usuario de email ' . $this->repo_usuarios->getMail($user_to_activate) . ' se ha activado.';
				$this->sendMail($data_mail);
				$message = 'Se activo su Usuario con éxito. Ya puede comenzar a usarlo.';
				$this->session->set_flashdata('work_success', $message);
				redirect();
			}else {
				# NO PUDO ACTIVAR USUARIO
				$message = 'No se pudo activar su usuario.';
				$this->session->set_flashdata('work_success', $message);
				redirect();
			}
		}

		$data['form_action_search'] = $this->form_action_search;
	}

	// GUARDA LA FOTO
	public function savePhoto($foto)
	{
		try {
			if(isset($_FILES['avatar']) && !empty($foto)){
				if(move_uploaded_file($_FILES['avatar']['tmp_name'], "web/uploads/usuarios/avatar/$foto")){
					$config['image_library'] = 'gd2';
					$config['source_image']	= UPLOADS . "web/uploads/usuarios/avatar/$foto";
					$config['create_thumb'] = FALSE;
					$config['maintain_ratio'] = TRUE;
					$config['width']	 = 150;
					$config['height']	= 150;
					$this->load->library('image_lib',$config);
					// TODO: no me esta andando el resize de la imagen
					if($this->image_lib->resize()){
						return $foto;
					} else{
						return $foto; // TODO: aca debe retornar vacio si no pudo hacer resize
					}

				} else {
					return "";
				}
			} else {
				return "";
			}

		} catch (Exception $e) {
			return "";
		}
	}

	// ENVIO DEL MAIL
	private function sendMail($data_mail)
	{
		$this->load->library('email');
		$this->email->from($this->config->item('email_admin'));
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
