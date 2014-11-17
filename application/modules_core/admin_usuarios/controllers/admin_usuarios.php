<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_usuarios extends MX_Controller {





	public function __construct(){
		//parent::__construct();
		//$this->load->library('session');

		$this->load->model('usuarios_model');
		$this->load->model('admin_permisos/roles_model');

	}


	public function index(){
		try {
			if(!isLogged($this->session)){
					redirect('admin/login');
			} elseif (!checkRol('administrador',$this->session)){
					redirect('admin/trabajos/mis_trabajos');
			}
			$this->listar(); // es ADMINISTRADOR

		} catch (Exception $e) {

		}
	}






	public function perfil(){
		try {
				/*
				if(!isLogged($this->session)){
				redirect('login');
				}
				*/
				if(!isLogged($this->session)){
						redirect('admin/login');
				}
				$opciones = array(
														'idUsuarios' => $this->session->userdata('idUsuarios'),
														);
				$this->editarPerfil($opciones);

		} catch (Exception $e){

		}
	}







	public function editarPerfil($opciones = NULL,$errores = NULL){

		if(!isLogged($this->session) ){
				redirect('admin/login');
		}
		$data = array();
		$htmlErrores = "";
		$scriptError = "";
		$usuario = $this->get();

		if(!isset($usuario['idUsuarios']) && isset($opciones['idUsuarios'])){
				$usuario['idUsuarios'] = $opciones['idUsuarios'];
				$data['urlForm'] = PUBLIC_FOLDER_ADMIN . "usuarios/perfil.html";

		} else{
				$data['urlForm'] = PUBLIC_FOLDER_ADMIN . "usuarios/editarPerfil/" . $usuario['idUsuarios'];
		}


		if($this->input->server('REQUEST_METHOD') == 'POST' && !$this->usuarios_model->validarNuevo($usuario)) {
				if($this->usuarios_model->update($usuario)) { // MODIFCACION DEL USER, GRABA DATOS MODIFICADOS.
								$htmlErrores = '$.sticky("El usuario se ha modificado con éxito", {autoclose : 5000, position: "top-center", type: "st-success" });';
				}else{
								$htmlErrores = '$.sticky("Hubo un error al modificar el usuario", {autoclose : 5000, position: "top-center", type: "st-error" });';
				}

		}elseif($this->input->server('REQUEST_METHOD') == 'POST') {
				$errores = $this->usuarios_model->validarNuevo($usuario);
				$htmlErrores = $this->errors($errores);
		}

		// $usuario['idUsuarios'] = 3; //harckodeo

		$usuario = $this->usuarios_model->get($usuario);


		$script = '
				<script>
				$(document).ready(function(){
				$("#fechaNacimiento").datepicker({format: "dd-mm-yyyy"});
				'.$htmlErrores.'
				});
				</script>
				';


		$data['user'] = $usuario;
		$data['grupos'] = $this->roles_model->get_all();
		$data['this'] = $this;
		$data['urlCiudad'] = PUBLIC_FOLDER_ADMIN . "domicilios/getCiudades/";

		$data['title'] = "Editar usuario";
		$this->load->view('admin_templates/header',array('title' => 'Panel de Control :: Editar usuario'));
		$this->load->view('admin_templates/menu_lateral',$data);
		$this->load->view('admin_templates/menu');
		$this->load->view('cargar-user',$data);
		$this->load->view('admin_templates/footer',array('datatable' => true,'scripts' => $script));
	}







	public function alta(){
		if(!isLogged($this->session) or !checkRol('administrador',$this->session)){
			redirect();
		}
		$data = array();
		$htmlErrores = "";
		$scriptError = "";
		$usuario = $this->get();

		if($this->input->server('REQUEST_METHOD') == 'POST' and !$this->usuarios_model->validarNuevo($usuario)){

				$usuario['idUsuarios'] = $this->usuarios_model->alta($usuario);

				if($usuario['idUsuarios'] > 0) {
					$htmlErrores = '$.sticky("El usuario se ha creado con éxito", {autoclose : 5000, position: "top-center", type: "st-success" });';
					$usuario = array();

				}else{
					$htmlErrores = '$.sticky("Hubo un error al crear un nuevo usuario", {autoclose : 5000, position: "top-center", type: "st-error" });';
				}

		}elseif($this->input->server('REQUEST_METHOD') == 'POST') {
			$errores = $this->usuarios_model->validarNuevo($usuario);
			$htmlErrores = $this->errors($errores);
		}

		$script = '
						<script>
						$(document).ready(function(){
						$("#fechaNacimiento").datepicker({format: "yyyy-mm-dd"});
						'.$htmlErrores.'
						});
						</script>
						';
		$data['user'] = $usuario;
		/*$data['provincias'] = $this->domicilios_model->getProvincias();
		$data['ciudades'] = $this->domicilios_model->getCiudades($data['provincias'][0]['idProvincia']);
		*/
		$data['urlCiudad'] = PUBLIC_FOLDER_ADMIN . "domicilios/getCiudades/";
		/*
		if(isset($usuario['idUsuarios']) and $usuario['idUsuarios'] > 0){
		$data['urlForm'] = PUBLIC_FOLDER_ADMIN . "usuarios/editar/".$usuario['idUsuarios'];
		}
		else{
		$data['urlForm'] = PUBLIC_FOLDER_ADMIN . "usuarios/alta";
		}
		*/
		$data['urlForm'] = PUBLIC_FOLDER_ADMIN . "usuarios/alta";
		$data['title'] = "Alta Autor";



		$this->load->view('admin_templates/header',array('title' => 'Panel de Control :: Home'));
		$this->load->view('admin_templates/menu_lateral',$data);
		$this->load->view('admin_templates/menu');
		$this->load->view('cargar-user',$data);
		$this->load->view('admin_templates/footer',array('datatable' => true,'scripts' => $script));


	}








	public function listar() {
		try {
				//$this->output->enable_profiler(TRUE);
				if(!isLogged($this->session)){
								redirect('login');
				}elseif(!checkRol('administrador',$this->session)){
								redirect();
				}

				/*$cantidadRegistros = $this->usuarios_model->contar();
				$baseUrl = PUBLIC_FOLDER_ADMIN . "admin/usuarios/listar/pagina";
				$data['paginas'] = paginas($cantidadRegistros,$this->config->item('filas_por_paginas'),$this->uri->segment(5,1), $baseUrl );
				$filter['limit'] = limit($this->uri->segment(5,1),$this->config->item('filas_por_paginas') );
				$data['usuarios'] = $this->usuarios_model->listar($filter,true);
				$data['userName'] = $this->session->userdata('userName');
				*/

				if($this->uri->segment(4) && $this->uri->segment(4) != 'pagina' && $this->uri->segment(5))
				{ // FILTROS
						$filter[$this->uri->segment(4)] = TRUE;  // tipo de filtro
						$filter['value'] = addslashes(urldecode($this->uri->segment(5)));
						$filter['limit'] = limit($this->uri->segment(7,1),$this->config->item('filas_por_paginas') );
						$baseUrl = PUBLIC_FOLDER_ADMIN . "usuarios/listar/".$this->uri->segment(4)."/".$this->uri->segment(5)."/pagina";
						$data['paginas'] = paginas($this->usuarios_model->contar($filter),$this->config->item('filas_por_paginas'),$this->uri->segment(7,1), $baseUrl );
						$data['filter'] = $this->uri->segment(4);
						$data['value'] = urldecode($this->uri->segment(5));
						$filter['where'] = TRUE;
				} else { // LISTADO DE LOS USUARIOS
						$baseUrl = PUBLIC_FOLDER_ADMIN . "usuarios/listar/pagina";
						$filter['limit'] = limit($this->uri->segment(5,1),$this->config->item('filas_por_paginas') );
						$data['paginas'] = paginas($this->usuarios_model->contar(),$this->config->item('filas_por_paginas'),$this->uri->segment(5,1), $baseUrl );
				}



				$data['usuarios'] = $this->usuarios_model->listar($filter,true);


				// TODO: ver de acomodar esto, y volarlo de acá. . ponerlo en un .js
				$scripts = '
						<script>
						$("#btn-filter").live("click",function(){
										if($("#filter :selected").val() != "" && $.trim($("#value").val()) != ""){
												window.location = "' .PUBLIC_FOLDER_ADMIN .'usuarios/listar/"+$("#filter :selected").val() + "/" + $.trim($("#value").val());
										}
						});
						$("#btn-clean").live("click",function(){
						$("#filter").val("");
						$("#value").val("");
						window.location = "' .PUBLIC_FOLDER_ADMIN .'usuarios/listar/";
						});
						</script>
						<script type="text/javascript">
						$(document).ready(function(){
						$(".delete-user").click(function(){
						var id=  $(this).val();
						smoke.confirm("Esta seguro que desea eliminar el usuario seleccionado?",function(e){
						if (e){
						$.post("'.base_url('/admin/usuarios/eliminar').'",{id:id},function(data){
						console.log(data);
						var rst = JSON.parse(data);
						if(rst.error){
						$.sticky(rst.mensaje, {autoclose : 5000, position: "top-center", type: "st-error" });
						}
						else {
						$("#tr_user_"+id).slideUp("fast",function(){
						$(this).remove();
						$.sticky(rst.mensaje, {autoclose : 5000, position: "top-center", type: "st-success" });
						});
						}
						});
						}
						});
						});
						});
						</script>
						<link rel="stylesheet" href="'.ADMIN_FOLDER.'/lib/smoke/themes/gebo.css" />
						<script src="'. ADMIN_FOLDER .'lib/smoke/smoke.js"></script>'
						;

				$this->load->view('admin_templates/header',array('title' => 'Panel de Control :: Home'));
				$this->load->view('admin_templates/menu_lateral',$data);
				$this->load->view('admin_templates/menu',$data);
				$this->load->view('admin_usuarios/listado_users',$data);
				//$this->load->view('footer',array('datatable' => true,'scripts' => $scripts ));
				$this->load->view('admin_templates/footer',array('datatable' => true,'scripts' => $scripts ));
		} catch (Exception $e) {

		}
	}








	protected function get(){
		$usuario = array();

		if($this->input->get_post('idUsuarios')){
						$usuario['idUsuarios'] = $this->input->get_post('idUsuarios');
		}
		elseif ($this->uri->segment(4)) {
						$usuario['idUsuarios'] = $this->uri->segment(4);
		}

		if($this->input->post('nombre')){
						$usuario['nombre'] = $this->input->post('nombre');
		}

		if($this->input->post('apellido')){
						$usuario['apellido'] = $this->input->post('apellido');
		}

		if($this->input->post('email')){
						$usuario['email'] = $this->input->post('email');
		}

		if($this->input->post('clave')){
						$usuario['clave'] = $this->input->post('clave');
		}

		if($this->input->post('clave_re')){
						$usuario['clave_re'] = $this->input->post('clave_re');
		}

		if($this->input->post('telefono')){
						$usuario['telefono'] = $this->input->post('telefono');
		}else{
						$usuario['telefono'] = "";
		}

		if($this->input->post('esEditorial')){
						$usuario['esEditorial'] = (int)$this->input->post('esEditorial');
		}else{
						$usuario['esEditorial'] = 0;
		}


		if(checkRol('administrador', $this->session)) {
				if($this->input->post('esAutor')) {
								$usuario['esAutor'] = (int)$this->input->post('esAutor');
				}else{
								$usuario['esAutor'] = 0;
				}

				if($this->input->post('regalias')) {
								$usuario['regalias'] = $this->input->post('regalias');
				}else{
								$usuario['regalias'] = 0;
				}
		}

		if($this->input->post('fecha')){
						$usuario['fecha'] = date('Y-m-d',strtotime($this->input->post('fecha')));
		}

		if($this->input->post('intereses')){
						$usuario['intereses'] = trim($this->input->post('intereses'));
		}

		if($this->input->post('lugar')){
						$usuario['lugar'] = trim($this->input->post('lugar'));
		}

		if($this->input->post('profesion')){
						$usuario['profesion'] = trim($this->input->post('profesion'));
		}
		if($this->input->post('biografia')){
						$usuario['biografia'] = trim($this->input->post('biografia'));
		}

		if($this->input->post('oldFoto')){
						$usuario['fileFoto']['oldFoto'] = $this->input->post('oldFoto');
						$usuario['foto'] = $this->input->post('oldFoto');
		}

		if(isset($_FILES['foto']['tmp_name'])){
						$usuario['fileFoto'] = $_FILES['foto'];
						$usuario['fileFoto']['newFileName'] = md5(time().$_FILES['foto']['name']) . ".jpg";
						$usuario['foto'] = $usuario['fileFoto']['newFileName'];
		}


		if($this->input->post('old_foto')){
				$usuario['fileFoto']['old_foto'] = $this->input->post('old_foto');
		}


		if(checkRol('administrador', $this->session)){
				if($this->input->post('estado')){
								$usuario['estado'] = $this->input->post('estado');
				}else{
								$usuario['estado'] = 0;
				}
		}

		return $usuario;
	}









	public function guardarCambios(){
		try {
				if(!isLogged($this->session) and !checkRol('administrador', $this->session)){
								redirect();
				}
				elseif($this->input->server('REQUEST_METHOD') != 'POST') {
								redirect('admin/usuarios');
				}
				$isAdmin = false;

				$usuario = array();
				$usuario = $this->get();
				$usuario['idUsuarios'] = $this->session->userdata('idUsuarios');

				if(checkRol('administrador',$this->session)) {
						$isAdmin = true;

						if($this->input->post('rol')){
						$usuario['idRoles'] = (int)$this->input->post('rol');
						}
						if($this->input->post('estado')){
						$usuario['estado'] = $this->input->post('estado');
						}
						$usuario['idUsuarios'] = (int)$this->input->post('idUsuarios');
				}

				$errores = $this->usuarios_model->validarPerfil($usuario);
				$opciones = array(
														'idUsuarios' => $usuario['idUsuarios']
														);
				if(isset($errores) and $errores){
								$this->editarPerfil($opciones, $errores);
				}else{
								if($isAdmin){
												$this->usuarios_model->update($usuario,true);
								}else{
												$this->usuarios_model->update($usuario,false);
								}
								$this->editarPerfil($opciones, NULL);
				}

		} catch(Exception $e){

		}
	}











	public function eliminar(){
		try {
						if(!isLogged($this->session) and !checkRol('administrador', $this->session)){
										redirect();
						}
						/*
						if(!isLogged($this->session)){
						redirect('login');
						}
						elseif(!checkRol('administrador',$this->session)){
						redirect('admin');
						}
						elseif(!$this->input->post('id')){
						redirect('admin/login');
						}
						*/

						$usuario = array();
						$usuario['idUsuarios'] = (int)$this->input->post('id');
						if($usuario['idUsuarios'] == 0){
										redirect('admin/usuarios');
						}

						if($this->usuarios_model->delete($usuario))
						{
							$json = array(
										'mensaje' => 'Se ha eliminado con &eacute;xito el usuario seleccionado',
										'error' => false
										);
						}else{
							$json = array(
										'error' => true,
										'mensaje' => 'El usuario no se pudo eliminar; puede estar relacionado con Publicacion, Pedidos y/o Compras'
										);
						}

				echo json_encode($json);

		} catch (Exception $e) {
				$json = array(
						'error' => true,
						'mensaje' => 'El usuario no se pudo eliminar'
						);
				echo json_encode($json);
		}
	}









	public function errors($errores){
		$htmlErrores = "";
		if(isset($errores['nombre'])){
						$htmlErrores = '$.sticky("El nombre es obligatorio", {autoclose : 5000, position: "top-center", type: "st-error" });';
		}

		if(isset($errores['apellido'])){
						$htmlErrores .= '$.sticky("El apellido es obligatorio", {autoclose : 5000, position: "top-center", type: "st-error" });';
		}

		if(isset($errores['email'])){
						$htmlErrores .= '$.sticky("El email es obligatorio", {autoclose : 5000, position: "top-center", type: "st-error" });';
		}elseif (isset($errores['email_not_valid'])){
						$htmlErrores .= '$.sticky("El email es incorrecto", {autoclose : 5000, position: "top-center", type: "st-error" });';
		}

		if(isset($errores['dni'])){
						$htmlErrores .= '$.sticky("El dni es obligatorio", {autoclose : 5000, position: "top-center", type: "st-error" });';
		}

		if(isset($errores['telefono'])){
						$htmlErrores .= '$.sticky("El tel&eacute;fono es obligatorio", {autoclose : 5000, position: "top-center", type: "st-error" });';
		}

		if(isset($errores['telefono_incorrecto'])){
						$htmlErrores .= '$.sticky("El tel&eacute;fono es incorrecto", {autoclose : 5000, position: "top-center", type: "st-error" });';
		}

		if(isset($errores['celular'])){
						$htmlErrores .= '$.sticky("El celular es obligatorio", {autoclose : 5000, position: "top-center", type: "st-error" });';
		}

		if(isset($errores['celular_incorrecto'])){
						$htmlErrores .= '$.sticky("El celular es incorrecto", {autoclose : 5000, position: "top-center", type: "st-error" });';
		}

		if(isset($errores['fechaNacimiento'])){
						$htmlErrores .= '$.sticky("La fecha de nacimiento es obligatoria", {autoclose : 5000, position: "top-center", type: "st-error" });';
		}

		if(isset($errores['idProvincia'])){
						$htmlErrores .= '$.sticky("Seleccione una pronvicia", {autoclose : 5000, position: "top-center", type: "st-error" });';
		}

		if(isset($errores['idCiudad'])){
						$htmlErrores .= '$.sticky("Seleccione una ciudad", {autoclose : 5000, position: "top-center", type: "st-error" });';
		}

		if(isset($errores['calle'])){
						$htmlErrores .= '$.sticky("Ingrese una calle", {autoclose : 5000, position: "top-center", type: "st-error" });';
		}

		if(isset($errores['numero'])){
						$htmlErrores .= '$.sticky("Ingrese el n&uacute;mero de la calle", {autoclose : 5000, position: "top-center", type: "st-error" });';
		}

		if(isset($errores['numero_incorrecto'])){
						$htmlErrores .= '$.sticky("El n&uacute;mero es incorrecto", {autoclose : 5000, position: "top-center", type: "st-error" });';
		}

		if(isset($errores['codigoPostal'])){
				$htmlErrores .= '$.sticky("Ingrese el c&oacute;digo postal", {autoclose : 5000, position: "top-center", type: "st-error" });';
		}

		return $htmlErrores;
	}









	public function checkEmail(){
		if(!isLogged($this->session)){
						redirect();
		}

		if($this->input->post('email') && trim($this->input->post('email')) != '') {
						$usuario['email'] = trim($this->input->post('email'));
						$data = $this->usuarios_model->checkEmail($usuario);
						if($data['idUsuarios'] > 0){
										$data['existe'] = true;
						}else{
										$data['idUsuarios'] = 0;
										$data['no_existe'] = true;
						}
						echo json_encode($data);
		} else {
						$data['idUsuarios'] = 0;
						$data['no_existe'] = true;

						echo json_encode($data);
		}
	}









	public function destroy(){
		if(isLogged($this->session)){
			try {
				$user_name = $this->session->userdata('userName');
				$this->session->sess_destroy();
				echo 'La sesión de ' . $user_name . ' fue destruida con éxito';
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}

		} else {
				echo "NO EXISTE NINGUN USUARIO PARA DESTRUIR EN SESION";
		}

	}



}



?>