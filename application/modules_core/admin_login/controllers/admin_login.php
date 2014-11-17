<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_login extends MX_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->library('session');
        $this->load->model('login_model');
        $this->load->config('emails');
        //$this->session->sess_destroy();                      // Tengo que poner esto para hacer pruebas
        //$this->output->enable_profiler(TRUE); // Para habilitar barra depuración
    }

	public function index() {
		try {
			if(isLogged($this->session)) {
                                        redirect(); // Acá lo va a tener que mandar a donde comienza el backend si está logueado.
			}
			$data['title'] = 'Ingreso al Panel de Control';
			$data['form_register'] = base_url('admin/login/register'); // TODO: Falta este controlador. Debe ser regisracion de nuevo USER.
			                                                              // Va a una ventana para registrar nuevo user. ver bien.
			$data['form_forgot'] = base_url('admin/login/forgot');      // me olvidé la clave
			$data['form_validate'] = base_url('admin/login/validate');  // validacion del user
			$this->load->view('header');
			$this->load->view('login',$data);
			$this->load->view('footer');

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}


    public function validate(){

        try {

            // if($this->input->post('username') && $this->input->post('password'))
            if( $this->input->post() )
            {
                    if(isLogged($this->session)){
                        redirect(''); // supongo que esto debería mandar a donde está el backend. . .
                    }

                    if ($this->input->post('username')) {
                        $dataUsuario['email']  = $this->input->post('username');
                    } else {
                        $dataUsuario['email']  = '';
                    }

                    if ($this->input->post('password')) {
                        $dataUsuario['clave']   = $this->input->post('password');
                    } else {
                        $dataUsuario['clave']   = '';
                    }



                    $usuario                    = $this->login_model->validateAdmin($dataUsuario);

                    if(isset($usuario) && $usuario['idUsuarios'] > 0)
                    { // Lo encontró en la base como user registrado.
                        $this->load->model('admin_usuarios/usuarios_model');
                        $usuario = $this->usuarios_model->get($usuario);
                        fillSession($usuario,$this->session); // lo mete dentro de la session.
                        redirect('admin_usuarios'); // Comienza todo en admin_usuarios

                    }else{
                        $data['error_login'] = true;
                    }

                    $data['title'] = 'Ingreso al panel de control';
                    $data['form_register'] = base_url('login/register');
                    $data['form_forgot'] = base_url('login/forgot');
                    $data['form_validate'] = base_url('login/validate');
                    $this->load->view('header');
                    $this->load->view('login',$data);
                    $this->load->view('footer');
            }else{
                // echo 'entro en el else que chequea que esten puestos el username y el pass'; die();
                    redirect('default_controller');
            }

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function forgot()
    {
        try {
            if(isLogged($this->session)){
                redirect();
            }
            elseif(!$this->input->post('email')){
                redirect('login');
            }

            $usuario['email'] = $this->input->post('email');
            $data = $this->login_model->forgot($usuario);
            if(!$data['errores']){
                $this->notificar($data['usuario']);
                $data['title'] = 'Ingreso al panel de control';
                $data['form_register'] = base_url('login/register');
                $data['form_forgot'] = base_url('login/forgot');
                $data['form_validate'] = base_url('login/validate');
                $this->load->view('header');
                $this->load->view('forgot-success',$data);
                $this->load->view('footer');
            }
            else {
                if(isset($data['email'])){
                    $data['error'] = 'La direcci&oacute;n de correo es incorrecta.';
                }
                elseif (isset($data['noExiste'])){
                    $data['error'] = 'No se ha encontrado la direcci&oacute;n de correo.';
                }
                $data['title'] = 'Ingreso al panel de control';
                $data['form_register'] = base_url('login/register');
                $data['form_forgot'] = base_url('login/forgot');
                $data['form_validate'] = base_url('login/validate');
                $this->load->view('header');
                $this->load->view('forgot-invalid',$data);
                $this->load->view('footer');
            }

        } catch (Exception $e) {

        }
    }

    public function close()
    {
        try {
                if(!isLogged($this->session)){
                    redirect('admin/login');
                }
                $this->session->sess_destroy();
                redirect('admin/login');

        } catch (Exception $e) {
                throw new Exception($e->getMessage());
        }
    }

    protected function notificar($usuario)
    {
        $config['protocol'] = 'mail';
        $config['charset'] = 'iso-8859-1';
        $config['mailtype'] = 'html';

        $this->load->library('email');
        $this->email->initialize($config);

        $this->email->subject('Nueva contraseña');
        $this->email->from($this->config->item('emailFrom'));
        $this->email->to($usuario['email']);

        $data['usuario'] = $usuario;

        $this->email->message($this->load->view('template_email_forgot',$data,true));

        $this->email->send();
    }


}
?>
