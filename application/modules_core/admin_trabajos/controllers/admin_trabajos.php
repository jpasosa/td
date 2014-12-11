<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_trabajos extends MX_Controller
{


	public function __construct()
	{
		parent::__construct();
		$this->load->model('admin_trabajos/estadostrabajos_model');
		$this->load->model('admin_trabajos/trabajos_model');
		$this->load->model('admin_usuarios/usuarios_model');
		$this->load->model('admin_categorias/categorias_model');
		$this->load->model('admin_precios/precios_model');
		$this->config->load('preciomax');
		//$this->output->enable_profiler(TRUE);
	}



	public function nuevo()
	{
		$mensaje 	= "";
		$data 		= array();
		$trabajo 	= $this->getData();

		$errors = $this->trabajos_model->validarNuevo($trabajo);

		if($this->input->server('REQUEST_METHOD') == 'POST' and !$errors)
		{
			if($this->trabajos_model->nuevo($trabajo)){
				$message = 'El nuevo trabajo se ha creado con éxito';
				$this->session->set_flashdata('work_success', $message);
				redirect('admin/trabajos/listar');
				// $data['success'] = $mensaje;
			} else{
				// Acá no se pudo grabar, por algún motivo. Quizás sea mejor armar con mensaje flash como arriba.
				$mensaje = 'No se ha podido guardar';
				$data['errors'] = $mensaje;
			}

		} elseif($this->input->server('REQUEST_METHOD') == 'POST' and $errors) {
			foreach($errors as $key => $error){
				$mensaje .=  $error .'<br>';
			}
			$data['errors'] = $mensaje;
		}


		$data['trabajo'] 		= $trabajo;
		$data['precios'] 		= $this->precios_model->getAll();
		$data['categorias'] 		= $this->categorias_model->getAll();
		$data['niveles']			= $this->trabajos_model->getAllNiveles();
		$data['preciomax']		= $this->config->item('preciomax');
		$data['estados'] 		= $this->estadostrabajos_model->getAll();
		$data['form_action'] 	= PUBLIC_FOLDER_ADMIN . "trabajos/nuevo.html";

		$parentCat =array();
		foreach($data['categorias'] as $categoria){
			if($categoria['parentId'] == 0){
				$parentCat[] = $categoria;
			}
		}
		$data['parentCat'] = $parentCat;
		$data['this'] = $this;
		$data['usuarios'] = $this->usuarios_model->getAll();
		$this->load->view('admin_templates/header',array('title' => 'Panel de Control :: Trabajos'));
		$this->load->view('admin_templates/menu_lateral',$data);
		$this->load->view('admin_templates/menu',$data);
		$this->load->view('add_edit_trabajo',$data);
		$this->load->view('admin_templates/footer',array('datatable' => true));
	}




	public function editar()
	{
		$mensaje 	= "";
		$data 		= array();
		$trabajo 	= $this->getData();


		$errors = $this->trabajos_model->validarEditar($trabajo);
		if($this->input->server('REQUEST_METHOD') == 'POST' and !$errors)
		{ // edit SIN ERRORES



			if($this->trabajos_model->editar($trabajo)) {
				if(isset($trabajo['idEstados']) && $trabajo['idEstados'] == 2  && $trabajo['notificado'] == 1 )
				{ // NOTIFICA
					$user = $this->usuarios_model->getUsuariosById($trabajo['idUsuarios']);
					if($user != false)
					{
						if($this->notifyUser($user['email'], $trabajo['titulo'])) {
							$mensaje = 'Se ha editado con éxito, y se ha notificado por mail al Usuario';
							$data['success'] = $mensaje;
						}else{
							$mensaje = 'Se ha editado con éxito, pero no se pudo mandar la notificación por mail';
							$data['success'] = $mensaje;
						}
					}


				} else { // SIN NOTIFICAR
					$mensaje = 'Se ha editado con éxito';
					$data['success'] = $mensaje;
				}

			} else{
				$mensaje = 'No se han podido guardar los cambios';
				$data['errors'] = $mensaje;
			}

		} elseif($this->input->server('REQUEST_METHOD') == 'POST' and $errors) { // ERRORES DE VALIDACION.
			foreach($errors as $key => $error){
				$mensaje .=  $error .'<br>';
			}
			$data['errors'] = $mensaje;
		}

		$trabajo = $this->trabajos_model->get($trabajo);

		$data['trabajo'] 		= $trabajo;
		// $data['precios'] 		= $this->precios_model->getAll();
		$data['categorias'] 	= $this->categorias_model->getAll();

		$parentCat =array();
		foreach($data['categorias'] as $categoria){
			if($categoria['parentId'] == 0){
				$parentCat[] = $categoria;
			}
		}

		$data['this'] 		= $this;
		$data['parentCat'] 	= $parentCat;
		$data['usuarios'] 	= $this->usuarios_model->getAll();
		$data['estados']	= $this->estadostrabajos_model->getAll();
		$data['precios'] 	= $this->precios_model->getAll();
		$data['niveles']		= $this->trabajos_model->getAllNiveles();

		$data['form_action'] = PUBLIC_FOLDER_ADMIN . "trabajos/editar/".$trabajo['idTrabajos'];
		$this->load->view('admin_templates/header',array('title' => 'Panel de Control :: Trabajos'));
		$this->load->view('admin_templates/menu_lateral',$data);
		$this->load->view('admin_templates/menu',$data);
		$this->load->view('add_edit_trabajo',$data);
		$this->load->view('admin_templates/footer',array('datatable' => true));
	}



	protected function getData()
	{
		$trabajo = array();

		//$trabajo['idUsuarios'] = $this->session->userdata('idUsuarios');
		if(checkRol('administrador', $this->session))
		{
			if($this->input->post('idUsuarios')) {
				$trabajo['idUsuarios']  = (int)$this->input->post('idUsuarios');
			} else {
				$trabajo['idUsuarios'] = $this->session->userdata('idUsuarios');
			}
		} else {
			$trabajo['idUsuarios'] = $this->session->userdata('idUsuarios');
		}

		if($this->input->get_post('idTrabajos')) {
			$trabajo['idTrabajos'] = (int)$this->input->get_post('idTrabajos');
		} elseif($this->uri->segment(4)){
			$trabajo['idTrabajos'] = (int)$this->uri->segment(4);
		}



		if($this->input->post('titulo')) {
			$trabajo['titulo']= trim($this->input->post('titulo'));
		}

		if($this->input->post('texto')) {
			$trabajo['texto']= trim($this->input->post('texto'));
		}

		if($this->input->post('resumen')) {
			$trabajo['resumen']= trim($this->input->post('resumen'));
		}

		if($this->input->post('titulo')) {
			$trabajo['titulo']= trim($this->input->post('titulo'));
		}

		if($this->input->post('fecha')) {
			$trabajo['fecha']= $this->input->post('fecha');
		} else {
			$trabajo['fecha'] = date('Y-m-d');
		}

		if($this->input->post('palabrasClave')) {
			$trabajo['palabrasClave'] = trim($this->input->post('palabrasClave'));
		} else {
			$trabajo['palabrasClave'] = "";
		}

		if($this->input->post('precio_sin_derecho')){
			$trabajo['precio_sin_derecho'] = (float)$this->input->post('precio_sin_derecho');
		}

		if($this->input->post('precio_con_derecho')){
			$trabajo['precio_con_derecho'] = (float)$this->input->post('precio_con_derecho');
		}

		if($this->input->post('monto_por_venta')){
			$trabajo['monto_por_venta'] = (float)$this->input->post('monto_por_venta');
		}

		if($this->input->post('destacado')) {
			$trabajo['destacado'] = (int)$this->input->post('destacado');
		} else{
			 $trabajo['destacado'] = 0;
		}

		if($this->input->post('idCategorias')) {
			$trabajo['idCategorias']  = $this->input->post('idCategorias');
		}

		if($this->input->post('idCategorias_parentId')) {
			$trabajo['idCategorias_parentId'] = $this->input->post('idCategorias_parentId');
		}

		if($this->input->post('indice')) {
			$trabajo['indice'] = $this->input->post('indice');
		}

		if($this->input->post('cantidadPalabras')) {
			$trabajo['cantidadPalabras'] = (int)$this->input->post('cantidadPalabras');
		} else {
			$trabajo['cantidadPalabras'] = 0;
		}

		if($this->input->post('nivel')) {
			$trabajo['nivel'] = $this->input->post('nivel');
		} else {
			$trabajo['nivel'] = 'Otro';
		}

		if($this->input->post('idEstados')){
			$trabajo['idEstados'] = (int)$this->input->post('idEstados');
		}

		if($this->input->post('idPrecios')){
			$trabajo['idPrecios'] = (int)$this->input->post('idPrecios');
		}


		$trabajo['notificado'] = (int) $this->input->post('notificado');

		// if($this->input->post('removeIndice')){
		// 	$trabajo['removeIndice'] = $this->input->post('removeIndice');
		// }

		// if(isset($_FILES['indice']['tmp_name']) and !empty($_FILES['indice']['tmp_name'])){
		// 	$trabajo['indice'] = sha1(md5(uniqid())). "_".$_FILES['indice']['name'];
		// } else{
		// 	//$trabajo['indice'] = "";
		// }

		// if($this->input->post('ori_indice')){
		// 	$trabajo['ori_indice'] = $this->input->post('ori_indice');
		// }



		if($this->input->post('removeFoto')){
			$trabajo['removeFoto'] = $this->input->post('removeFoto');
		}
		if(isset($_FILES['foto']['tmp_name']) and !empty($_FILES['foto']['tmp_name'])){
			$trabajo['foto'] = sha1(md5(uniqid())). "_".$_FILES['foto']['name'];
		} else {
			$trabajo['foto'] = "";
		}
		if($this->input->post('ori_foto')){
			$trabajo['ori_foto'] = $this->input->post('ori_foto');
		}


		// ARCHIVO PRIVADO
		// ELIMINAR
		if($this->input->post('removearchivo_privado')){
			$trabajo['removearchivo_privado'] = $this->input->post('removearchivo_privado');
		}
		// SI FUE CARGADO
		if(isset($_FILES['archivo_privado']['tmp_name']) and !empty($_FILES['archivo_privado']['tmp_name'])){
			$trabajo['archivo_privado'] = sha1(md5(uniqid())). "_".$_FILES['archivo_privado']['name'];
		} else{
			$tmp_trabajo = $this->trabajos_model->get($trabajo);
			$trabajo['archivo_privado'] = $tmp_trabajo['archivo_privado'];
			// $trabajo['archivo_privado'] = "";
		}
		// EL NOMBRE
		if($this->input->post('ori_archivo_privado')){
			$trabajo['ori_archivo_privado'] = $this->input->post('ori_archivo_privado');
		}


		// ARCHIVO VISTA PREVIA
		// ELIMINAR
		if($this->input->post('removearchivo_vista_previa')){
			$trabajo['removearchivo_vista_previa'] = $this->input->post('removearchivo_vista_previa');
		}
		// SI FUE CARGADO
		if(isset($_FILES['archivo_vista_previa']['tmp_name']) and !empty($_FILES['archivo_vista_previa']['tmp_name'])){
			$trabajo['archivo_vista_previa'] = sha1(md5(uniqid())). "_".$_FILES['archivo_vista_previa']['name'];
		} else{
			$trabajo['archivo_vista_previa'] = "";
		}
		// EL NOMBRE
		if($this->input->post('ori_archivo_privado')){
			$trabajo['ori_archivo_privado'] = $this->input->post('ori_archivo_privado');
		}





		if($this->input->post('removearchivo_publico')){
			$trabajo['removearchivo_publico'] = $this->input->post('removearchivo_publico');
		}

		if(isset($_FILES['archivo_publico']['tmp_name']) and !empty($_FILES['archivo_publico']['tmp_name'])){
			$trabajo['archivo_publico'] = sha1(md5(uniqid())). "_".$_FILES['archivo_publico']['name'];
		} else {
			$trabajo['archivo_publico'] = "";
		}

		if($this->input->post('ori_archivo_publico')) {
			$trabajo['ori_archivo_publico'] = $this->input->post('ori_archivo_publico');
		}
		//



		return $trabajo;
	}


	public function listar()
	{
		//$this->output->enable_profiler(TRUE);
		if(!isLogged($this->session)) {
			redirect('admin/login');
		}
		// elseif(!checkRol('administrador',$this->session)){
		// 	redirect('trabajos/mis_trabajos');
		// }


		$data = array();

		if($this->uri->segment(4) and $this->uri->segment(4) != 'pagina' and $this->uri->segment(5))
		{ // FILTRO
			$filter[$this->uri->segment(4)] = addslashes(urldecode($this->uri->segment(5)));
			$filter['value'] 	= addslashes(urldecode($this->uri->segment(5)));
			$filter['limit'] 	= limit($this->uri->segment(7, 1), $this->config->item('filas_por_paginas') ); // el limit debe elegir nro de página como 1er parámetro.
			$baseUrl 		= PUBLIC_FOLDER_ADMIN . "trabajos/listar/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/pagina";
			$data['paginas']	= paginas($this->trabajos_model->contar($filter),$this->config->item('filas_por_paginas'),$this->uri->segment(7,1), $baseUrl );
			$data['filter'] 	= $this->uri->segment(4);
			$data['value'] 	= urldecode($this->uri->segment(5));
		}
		else
		{ // LISTADO DE TRABAJOS
			$baseUrl = PUBLIC_FOLDER_ADMIN . "trabajos/listar/pagina";
			$filter['limit'] = limit($this->uri->segment(5,1),$this->config->item('filas_por_paginas') );
			$data['paginas'] = paginas($this->trabajos_model->contar(),$this->config->item('filas_por_paginas'),$this->uri->segment(5,1), $baseUrl );
		}

		$data['section'] = 'trabajos';


		$data['trabajos'] = $this->trabajos_model->listar($filter);

		$script = '
		<script>
			$("#btn-filter").live("click",function(){
				if($("#filter :selected").val() != "" && $.trim($("#value").val()) != ""){
					window.location = "' .PUBLIC_FOLDER_ADMIN .'trabajos/listar/"+$("#filter :selected").val() + "/" + encodeURI($.trim($("#value").val()));
				}
			});
			$("#btn-clean").live("click",function(){
				$("#filter").val("");
				$("#value").val("");
				window.location = "' .PUBLIC_FOLDER_ADMIN .'trabajos/listar.html";
			});
		</script>
		<script type="text/javascript">
			$(document).ready(function(){
				$(".delete-tipo").click(function() {
					var id=  $(this).val();
					smoke.confirm("Esta seguro que desea eliminar el trabajo seleccionado?",function(e){
					if (e){
						$.post("'. PUBLIC_FOLDER_ADMIN . 'trabajos/eliminar",{id:id},function(data) {
							var rst = JSON.parse(data);
							if(rst.error) {
								$.sticky(rst.mensaje, {autoclose : 5000, position: "top-center", type: "st-error" });
							} else {
								$(".tr_tipo_"+id).slideUp("fast",function() {
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
		<script type="text/javascript">
			$(document).ready(function(){
				$(".notify").click(function(){
					var id_trabajo =  $(this).val();
					smoke.confirm("Esta seguro que desea enviar notificacion via email?",function(e) {
						if (e){
							$.post("'. PUBLIC_FOLDER_ADMIN . 'trabajos/notifyUserAjax", {
								id_trabajo: id_trabajo
							},function(data) {
								var rst = JSON.parse(data);
								if(rst.error) {
									alert("no se pudo enviar el mail");
								}
								else {
									alert("Mail enviado");
								}
							});
						}
					});
				});
			});
		</script>
		<link rel="stylesheet" href="'.ADMIN_FOLDER.'/lib/smoke/themes/gebo.css" />
		<script src="'. ADMIN_FOLDER .'lib/smoke/smoke.js"></script>
		';


		$this->load->view('admin_templates/header',array('title' => 'Panel de Control :: Trabajos :: Listado'));
		$this->load->view('admin_templates/menu_lateral', $data);
		$this->load->view('admin_templates/menu', $data);
		$this->load->view('admin_trabajos/listado_trabajos', $data);
		$this->load->view('admin_templates/footer',array('datatable' => true, 'scripts' => $script ));
	}

	public function mis_trabajos()
	{

		if(!isLogged($this->session)) {
			redirect('admin/login');
		}

		$data = array();


		if($this->uri->segment(4) and $this->uri->segment(4) != 'pagina' and $this->uri->segment(5))
		{ // FILTROS
			$filter[$this->uri->segment(4)] = addslashes(urldecode($this->uri->segment(5)));
			$filter['idUsuarios'] = $this->session->userdata('idUsuarios');
			$filter['value'] = addslashes(urldecode($this->uri->segment(5)));
			$filter['limit'] = limit($this->uri->segment(7,1),$this->config->item('filas_por_paginas') );
			$baseUrl = PUBLIC_FOLDER_ADMIN . "trabajos/mis_trabajos/".$this->uri->segment(4)."/".$this->uri->segment(5)."/pagina";
			$data['paginas'] = paginas($this->trabajos_model->contar($filter),$this->config->item('filas_por_paginas'),$this->uri->segment(7,1), $baseUrl );
			$data['filter'] = $this->uri->segment(4);
			$data['value'] = urldecode($this->uri->segment(5));
		}else{ // LISTADO DE MIS TRABAJOS
			$baseUrl = PUBLIC_FOLDER_ADMIN . "trabajos/mis_trabajos/pagina";
			$filter['idUsuarios'] = $this->session->userdata('idUsuarios');
			$filter['limit'] = limit($this->uri->segment(5,1),$this->config->item('filas_por_paginas') );
			$data['paginas'] = paginas($this->trabajos_model->contar($filter),$this->config->item('filas_por_paginas'),$this->uri->segment(5,1), $baseUrl );
		}

		$data['section'] = 'mis_trabajos';

		$data['trabajos'] = $this->trabajos_model->listar($filter);

		$script = '
		<script>
			$("#btn-filter").live("click",function(){
				if($("#filter :selected").val() != "" && $.trim($("#value").val()) != ""){
					window.location = "' .PUBLIC_FOLDER_ADMIN .'trabajos/mis_trabajos/"+$("#filter :selected").val() + "/" + encodeURI($.trim($("#value").val()));
				}
			});
			$("#btn-clean").live("click",function() {
				$("#filter").val("");
				$("#value").val("");
				window.location = "' .PUBLIC_FOLDER_ADMIN .'trabajos/mis_trabajos.html";
			});
		</script>
		<script type="text/javascript">
			$(document).ready(function(){
				$(".delete-tipo").click(function(){
					var id=  $(this).val();
					smoke.confirm("Esta seguro que desea eliminar el trabajo seleccionado?",function(e){
						if (e){
							$.post("'. PUBLIC_FOLDER_ADMIN . 'trabajos/eliminar",{id:id},function(data){
								var rst = JSON.parse(data);
								if(rst.error){
									$.sticky(rst.mensaje, {autoclose : 5000, position: "top-center", type: "st-error" });
								} else {
									$(".tr_tipo_"+id).slideUp("fast",function() {
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
	<script src="'. ADMIN_FOLDER .'lib/smoke/smoke.js"></script>
	';

		$data['this'] = $this;
		$this->load->view('admin_templates/header',array('title' => 'Panel de Control :: Trabajos :: Listado'));
		$this->load->view('admin_templates/menu_lateral',$data);
		$this->load->view('admin_templates/menu',$data);
		$this->load->view('listado_trabajos',$data);

		$this->load->view('footer',array('datatable' => true,'scripts' => $script ));
	}

	public function pendientes()
	{
		if(!isLogged($this->session) or !checkRol('administrador',$this->session)){
			redirect();
		}


		$data = array();





		if($this->uri->segment(4) && $this->uri->segment(4) != 'pagina' && $this->uri->segment(5))
		{ // FILTRAMOS por id, nombre de usuario o por titulo.
			$filter[$this->uri->segment(4)] = addslashes(urldecode($this->uri->segment(5))); // $filter['idTrabajos'] = 51
			$filter['estadoPendiente'] = true;
			$filter['value'] = addslashes(urldecode($this->uri->segment(5))); // $filter['value'] = 51
			$filter['limit'] = limit($this->uri->segment(7,1),$this->config->item('filas_por_paginas') );
			$baseUrl = PUBLIC_FOLDER_ADMIN . "trabajos/pendientes/".$this->uri->segment(4)."/".$this->uri->segment(5)."/pagina"; // $baseUrl = admin/trabajos/pendientes/idTrabajos/51/pagina
			$data['paginas'] = paginas($this->trabajos_model->contar($filter),$this->config->item('filas_por_paginas'),$this->uri->segment(7,1), $baseUrl );
			$data['filter'] = $this->uri->segment(4); // $data['filter'] = idTrabajos
			$data['value'] = urldecode($this->uri->segment(5)); // $data['value'] = 51
		} else { // LISTA sin filtros
			$filter['estadoPendiente'] = true;
			$baseUrl = PUBLIC_FOLDER_ADMIN . "trabajos/pendientes/pagina";
			$filter['limit'] = limit($this->uri->segment(5,1),$this->config->item('filas_por_paginas') );
			$data['paginas'] = paginas($this->trabajos_model->contar($filter), $this->config->item('filas_por_paginas'), $this->uri->segment(5,1), $baseUrl );
		}


		$data['trabajos'] = $this->trabajos_model->pendientes($filter);

		$script = '
		<script>
		$("#btn-filter").live("click",function(){
		if($("#filter :selected").val() != "" && $.trim($("#value").val()) != ""){
		window.location = "' .PUBLIC_FOLDER_ADMIN .'trabajos/pendientes/"+$("#filter :selected").val() + "/" + encodeURI($.trim($("#value").val()));
	}
	});
	$("#btn-clean").live("click",function(){
	$("#filter").val("");
	$("#value").val("");
	window.location = "' .PUBLIC_FOLDER_ADMIN .'trabajos/pendientes.html";
	});
	</script>
	<script type="text/javascript">
		$(document).ready(function(){
			$(".aprobar").click(function() {
				var id=  $(this).val();
				smoke.confirm("Esta seguro que desea aprobar el trabajo seleccionado?",function(e) {
					if (e){
						$.post("'. PUBLIC_FOLDER_ADMIN . 'trabajos/aprobar",{id:id},function(data){
							var rst = JSON.parse(data);
							if(rst.error) {
								$.sticky(rst.mensaje, {autoclose : 5000, position: "top-center", type: "st-error" });
							}else {
								$(".tr_tipo_"+id).slideUp("fast",function(){
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

	<script type="text/javascript">
		$(document).ready(function(){
			$(".notify").click(function() {
				var id=  $(this).val();
				var notificar = true;
				smoke.confirm("Esta seguro que desea aprobar y notificar el trabajo seleccionado?",function(e) {
					if (e){
						$.post("'. PUBLIC_FOLDER_ADMIN . 'trabajos/aprobar",{
								id: id,
								notificar : notificar
							},function(data){
							var rst = JSON.parse(data);
							if(rst.error) {
								$.sticky(rst.mensaje, {autoclose : 5000, position: "top-center", type: "st-error" });
							}else {
								$(".tr_tipo_"+id).slideUp("fast",function(){
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
	<script src="'. ADMIN_FOLDER .'lib/smoke/smoke.js"></script>
	';


		$this->load->view('admin_templates/header',array('title' => 'Panel de Control :: Trabajos Pendientes :: Listados'));
		$this->load->view('admin_templates/menu_lateral',$data);
		$this->load->view('admin_templates/menu',$data);
		$this->load->view('listado_trabajos_pendientes',$data);

		$this->load->view('footer',array('datatable' => true,'scripts' => $script ));
	}

	public function eliminar(){
		try {
			if(!isLogged($this->session) and !checkRol('administrador', $this->session)){
				redirect('login');
			}


			$trabajo['idTrabajos']= (int)$this->input->post('id');
			if($trabajo['idTrabajos'] <= 0){
				redirect('trabajos/listar.html');
			}

			if($this->trabajos_model->eliminar($trabajo)){
				$json = array(
						'mensaje' => 'Se ha eliminado con &eacute;xito el registro seleccionado',
						'error' => false
				);
			}
			else {
				$json = array(
						'error' => true,
						'mensaje' => 'El registro no se pudo eliminar. Puede estar relacionado con algún Pedido'
				);
			}

			echo json_encode($json);

		} catch (Exception $e) {
			$json = array(
					'error' => true,
					'mensaje' => 'El registro no se pudo eliminar.'
			);
			echo json_encode($json);
		}


	}

	public function aprobar() {
		try {
			if(!isLogged($this->session) and !checkRol('administrador', $this->session)){
				redirect('login');
			}


			$trabajo['idTrabajos']= (int)$this->input->post('id');
			if($trabajo['idTrabajos'] <= 0){
				redirect('trabajos/listar.html');
			}

			if($this->trabajos_model->aprobar($trabajo)){
				if($this->input->post('notificar')) { // Además de aprobarlo hay que notificarlo.


					$trabajo_reg = $this->trabajos_model->getTrabajosById($trabajo['idTrabajos']);
					$user = $this->usuarios_model->getUsuariosById($trabajo_reg['idUsuarios']);



					$this->notifyUser($user['email'], $trabajo_reg['titulo']);

					$json = array(
						'mensaje' => 'El trabajo se ha aprobado con éxito y se ha enviado un mail',
						'error' => false
					);
				}else {
					$json = array(
						'mensaje' => 'El trabajo se ha aprobado con éxito',
						'error' => false
					);
				}

			}
			else {
				$json = array(
						'error' => true,
						'mensaje' => 'El trabajo no se ha podido aprobar'
				);
			}

			echo json_encode($json);

		} catch (Exception $e) {
			$json = array(
					'error' => true,
					'mensaje' => 'El trabajo no se ha podido aprobar'
			);
			echo json_encode($json);
		}


	}


	public function notifyUser($user_mail, $work_title) {
		try {
			if(!isLogged($this->session) and !checkRol('administrador', $this->session)){
				redirect('admin/login');
			}
			$this->load->library('email');
			$this->email->from($this->config->item('email_admin'));
			$this->email->to($user_mail, $this->config->item('email_programador')); // aca debe ir $user_mail
			// $this->email->cc('another@another-example.com');
			// $this->email->bcc('them@their-example.com');
			$this->email->subject('Trabajo Aprobado de Textosdigitales');
			$this->email->message($user_mail . ' tu trabajo de titulo "' . $work_title . ' " ha sido aprobado');
			if($this->email->send())	 {
				return true; // Pudo enviar el mail correctamente
			}else {
				return false;
			}
			// echo $this->email->print_uger();
		} catch (Exception $e) {
			die('No pudo enviar el mail por algún motivo');
		}


	}

	// va a notificar al usuario, admin hizo click de un boton de notificar.
	public function notifyUserAjax() {
		try {
			if(!isLogged($this->session) and !checkRol('administrador', $this->session)){
				redirect('admin/login');
			}

			$id_trabajo = (int)$this->input->post('id_trabajo');

			$trabajo = $this->trabajos_model->getTrabajosById($id_trabajo);
			$user = $this->usuarios_model->getUsuariosById($trabajo['idUsuarios']);

			if($this->notifyUser($user['email'], $trabajo['titulo'])) {
				$json = array(
						'mensaje' => 'Se ha enviado el mail con éxito',
						'error' => false
				);
			} else {
				$json = array(
						'error' => true,
						'mensaje' => 'No se pudo enviar el mail'
				);
			}

			echo json_encode($json);

		} catch (Exception $e) {
			$json = array(
					'error' => true,
					'mensaje' => 'El Usuario no pudo ser notificado.'
			);
			echo json_encode($json);
		}
	}

}

?>
