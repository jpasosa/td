<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_compras extends CI_class
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

	public function nueva()
	{
		$mensaje 	= "";
		$data 		= array();
		$trabajo 	= $this->getData();

		$errors = $this->trabajos_model->validarNuevo($trabajo);


		if($this->input->server('REQUEST_METHOD') == 'POST' and !$errors) {
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
		$data['categorias'] 	= $this->categorias_model->getAll();
		$data['estados'] 	= $this->estadostrabajos_model->getAll();
		$data['form_action'] = PUBLIC_FOLDER_ADMIN . "trabajos/nuevo.html";

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



	public function editar($id_pedido)
	{
		$mensaje 	= "";
		$data 		= array();

		// cuando seleccionas el autor que te traiga las publicaciones de ese autor. . . .

		if ($this->input->server('REQUEST_METHOD') == 'GET')
		{ 	// VIENE POR GET
			$compras 							= $this->repo_compras->getById($id_pedido);
			$compras['idUsuariosComprador'] 	= $compras['idUsuarios'];
			$exist_regalia 						= $this->repo_regalias->existRegaliaByPedido($id_pedido);

			if (!$exist_regalia)
			{ // CARGA CAMPOS VACIOS PARA MOSTRAR EN LA LISTA, POR QUE NO EXISTE REGALIAS
				$compras['monto_al_autor']	= $this->repo_trabajos->getMontoPorVenta($compras['idTrabajos']);
				$compras['notificado']		= 0; // NO FUE NOTIFICADO
			} else { // CARGA LOS DATOS DE LA REGALÍA
				$regalias = $this->repo_regalias->getByIdPedido($id_pedido);
				$compras['monto_al_autor']	= $regalias['monto_al_autor'];
				$compras['notificado']		= $regalias['notificado'];
			}
			// EMAIL E ID DEL AUTOR
			$author = $this->repo_usuarios->getAuthorByWork($compras['idTrabajos']);
			$compras['idUsuariosAutor'] 	= $author['idUsuarios'];
			$compras['emailUsuariosAutor'] 	= $author['email'];
			$compras['tituloPublicacion']		= $this->repo_trabajos->getTitulo($compras['idTrabajos']);


		} else { // VIENE POR POST
			$compras 	= $this->getData();

		}

		$errors = false; // TODO: VERFICAR QUE LOS DATOS CARGADOS SEAN CORRECTOS
		//$errors = $this->repo_compras->validarEditar($compras);
		if($this->input->server('REQUEST_METHOD') == 'POST' and !$errors)
		{ 	// EDIT SIN ERRORES

			// ENVIA AL COMPRADOR EL LINK DE DESCARGA
			$envio_comprador = $this->enviarMailUsuarioComprador($compras);
			if ($envio_comprador)
			{
				if($this->repo_regalias->editar($compras)) // EDITA Y MANDA MAIL SI ES NECESARIO.
				{
					$mensaje = 'Se ha editado con éxito, y se ha notificado por mail al Usuario';
					$this->session->set_flashdata('success', $message);
					redirect('admin/compras/listar_pedidos');
				}else{
					$mensaje = 'No se pudo editar';
					$this->session->set_flashdata('error', $message);
					redirect('admin/compras/listar_pedidos');
				}

			} else { // NO PUDO ENVIAR EL MAIL DE DESCARGA
				$mensaje = 'No pudo enviar mail de descarga, y no actualizó el pedido.';
				$this->session->set_flashdata('error', $message);
				redirect('admin/compras/listar_pedidos');
			}

		}


		$data['compras'] 		= $compras;
		// $data['precios'] 		= $this->precios_model->getAll();
		// $data['categorias'] 	= $this->categorias_model->getAll();

		$data['usuarios'] 		= $this->usuarios_model->getAll();
		$data['usuarios_otros'] 	= $this->usuarios_model->getAll();

		$data['form_action'] = PUBLIC_FOLDER_ADMIN . "compras/editar/".$compras['idPedidos'];

		$this->load->view('admin_templates/header',array('title' => 'Panel de Control :: Trabajos'));
		$this->load->view('admin_templates/menu_lateral',$data);
		$this->load->view('admin_templates/menu',$data);
		$this->load->view('editar_pedidos',$data);
		$this->load->view('admin_templates/footer',array('datatable' => true));
	}

	protected function enviarMailUsuarioComprador ($compras)
	{
		$archivo_publico= $this->repo_trabajos->getArchivoPublico($compras['idTrabajos']);
		$mail_user 		= $this->repo_usuarios->getMail($compras['idUsuariosComprador']);
		$from 			= $this->config->item('email_admin');
		$to 			= $mail_user;
		$title 			= 'Wordrev.com :: Link de descarga Trabajo Pedido';
		$link_descarga 	= RAIZ . RUTA . 'admin/compras/link_descarga/' . $compras['idPedidos'] . '/' .$archivo_publico;
		$message 		= 'Fue aprobada tu compra. Te enviamos el link de descarga, recordá que solamente podés descargarlo dos veces.'
							. $link_descarga;

		$enviar_mail 	= $this->sendEmailComprador( $from, $to, $title, $message );

		if ($enviar_mail) {
			return true;
		} else {
			return false;
		}
	}

	public function cargar_compra()
	{
		$mensaje 	= "";
		$data 		= array();


		// Cuando seleccionas el autor que te traiga las publicaciones de ese autor. . . .


		if ($this->input->server('REQUEST_METHOD') == 'GET')
		{ 	// VIENE POR GET
			$compras = $this->getDataEmpty();
		} else { // VIENE POR POST
			$compras 		= $this->getDataAgregarCompra();

			$compras 		= $this->repo_regalias->checkDataAgregarCompra($compras);

			$insert_compra = $this->repo_compras->insertCompraManual($compras); // INSERTA Y CARGA REGALÍA

			$compras['idPedidos'] = $insert_compra['idPedidos'];

			// ENVIO DEL MAIL AL COMPRADOR
			$email_comprador = $this->repo_usuarios->getMail($compras['idUsuariosComprador']);
			$work_title 		= $this->repo_trabajos->getTitulo($compras['idTrabajos']);
			$monto_venta 	= $compras['monto_venta_total'];
			$title 			= 'Wordrev :: Compraste una Publiación';
			$body 			= 'Compraste la publicación de titulo ' . $work_title . ' por una suma de ' . $monto_venta;
			$envio_mail_comprador 	= $this->sendEmailComprador($this->config->item('email_admin'), $email_comprador, $title, $body);

			// ENVIA AL COMPRADOR EL LINK DE DESCARGA
			$envio_comprador = $this->enviarMailUsuarioComprador($compras);

			if ($compras['notificado'] == 1) {
				$email 		= $this->repo_usuarios->getMail($compras['idUsuariosAutor']);
				// ENVIO AL AUTOR LOR LAS REGALÍAS
				$envio_mail = $this->sendEmailRegalias($email, $work_title, $compras['monto_al_autor']);
			}
			redirect('admin/compras/listar_compras');

		}

		// $errors = false; // TODO: VERFICAR QUE LOS DATOS CARGADOS SEAN CORRECTOS
		// //$errors = $this->repo_compras->validarEditar($compras);
		// if($this->input->server('REQUEST_METHOD') == 'POST' and !$errors)
		// { 	// EDIT SIN ERRORES
		// 	if($this->repo_regalias->editar($compras)) // EDITA Y MANDA MAIL SI ES NECESARIO.
		// 	{
		// 		$mensaje = 'Se ha editado con éxito, y se ha notificado por mail al Usuario';
		// 		$this->session->set_flashdata('success', $message);
		// 		redirect('admin/compras/listar_pedidos');
		// 	}else{
		// 		$mensaje = 'No se pudo editar';
		// 		$this->session->set_flashdata('error', $message);
		// 		redirect('admin/compras/listar_pedidos');
		// 	}
		// }


		$data['compras'] 		= $compras;
		// $data['precios'] 		= $this->precios_model->getAll();
		// $data['categorias'] 	= $this->categorias_model->getAll();
		$data['section']			= $this->section;
		$data['title']				= 'Panel de Control :: Trabajos';
		$data['usuarios'] 		= $this->usuarios_model->getAll();
		$data['publicaciones'] 	= $this->repo_trabajos->getAll();
		// $data['usuarios_otros'] 	= $this->usuarios_model->getAll();

		$data['form_action'] = PUBLIC_FOLDER_ADMIN . "compras/cargar_compra/";

		$this->load->view('admin_templates/header', $data);
		$this->load->view('admin_templates/menu_lateral',$data);
		$this->load->view('admin_templates/menu',$data);
		$this->load->view('cargar_compra', $data);
		$this->load->view('admin_templates/footer',array('datatable' => true));
	}

	public function ver_pedido($id_pedido)
	{
		$mensaje 	= "";
		$data 		= array();

			$compras 		= $this->repo_compras->getByIdForViewPedido($id_pedido);
			// $compras['idUsuariosComprador'] = $compras['idUsuarios'];
			// $exist_regalia 	= $this->repo_regalias->existRegaliaByPedido($id_pedido);
			// if (!$exist_regalia)
			// { // CARGA CAMPOS VACIOS PARA MOSTRAR EN LA LISTA, POR QUE NO EXISTE REGALIAS
			// 	$compras['monto_al_autor']	= 0;
			// 	$compras['notificado']		= 0; // NO FUE NOTIFICADO
			// } else { // CARGA LOS DATOS DE LA REGALÍA
			// 	$regalias = $this->repo_regalias->getByIdPedido($id_pedido);
			// 	$compras['monto_al_autor']	= $regalias['monto_al_autor'];
			// 	$compras['notificado']		= $regalias['notificado'];
			// }
			// // EMAIL E ID DEL AUTOR
			// $author = $this->repo_usuarios->getAuthorByWork($compras['idTrabajos']);
			// $compras['idUsuariosAutor'] 	= $author['idUsuarios'];
			// $compras['emailUsuariosAutor'] 	= $author['email'];
			// $compras['tituloPublicacion']		= $this->repo_trabajos->getTitulo($compras['idTrabajos']);



		$data['compras'] 		= $compras;
		$data['usuarios'] 		= $this->usuarios_model->getAll();





		$this->load->view('admin_templates/header',array('title' => 'Panel de Control :: Trabajos'));
		$this->load->view('admin_templates/menu_lateral',$data);
		$this->load->view('admin_templates/menu',$data);
		$this->load->view('ver_pedidos',$data);
		$this->load->view('admin_templates/footer',array('datatable' => true));
	}



	protected function getData()
	{
		$compras = array();


		if($this->input->post('idPedidos')) {
			$compras['idPedidos']  = (int)$this->input->post('idPedidos');
		} else {
			$compras['idPedidos'] = 100;
		}

		if($this->input->get_post('fecha')) {
			$compras['fecha'] = $this->input->get_post('fecha');
		} else {
			$compras['fecha'] = '';
		}


		if($this->input->post('idUsuariosComprador')) {
			$compras['idUsuariosComprador']= trim($this->input->post('idUsuariosComprador'));
		}

		if($this->input->post('idTrabajos')) {
			$compras['idTrabajos']= trim($this->input->post('idTrabajos'));
		}

		if($this->input->post('monto_venta_total')) {
			$compras['monto_venta_total']= trim($this->input->post('monto_venta_total'));
		}

		if($this->input->post('modalidad')) {
			$compras['modalidad']= (int)$this->input->post('modalidad');
		}

		if($this->input->post('monto_al_autor')) {
			$compras['monto_al_autor']= trim($this->input->post('monto_al_autor'));
		} else {
			$compras['monto_al_autor']= 0;
		}

		if($this->input->post('tituloPublicacion')) {
			$compras['tituloPublicacion']= trim($this->input->post('tituloPublicacion'));
		}

		if($this->input->post('regalias')) {
			$compras['regalias']= (int)$this->input->post('regalias');
		} else {
			$compras['regalias']= 0;
		}

		if($this->input->post('notificado')) {
			$compras['notificado'] = (int)$this->input->post('notificado');
		} else {
			$compras['notificado']= 0;
		}

		if($this->input->post('idUsuariosAutor')) {
			$compras['idUsuariosAutor'] = (int)$this->input->post('idUsuariosAutor');
		}

		if($this->input->post('emailUsuariosAutor')) {
			$compras['emailUsuariosAutor'] = $this->input->post('emailUsuariosAutor');
		}

		return $compras;
	}



	protected function getDataAgregarCompra()
	{
		$compras = array();


		// if($this->input->post('idPedidos')) {
		// 	$compras['idPedidos']  = (int)$this->input->post('idPedidos');
		// } else {
		// 	$compras['idPedidos'] = 100;
		// }

		if($this->input->get_post('fecha')) {
			$compras['fecha'] = $this->input->get_post('fecha');
		} else {
			$compras['fecha'] = '';
		}


		if($this->input->post('idUsuariosComprador')) {
			$compras['idUsuariosComprador']= trim($this->input->post('idUsuariosComprador'));
		}

		if($this->input->post('idTrabajos')) {
			$compras['idTrabajos']= trim($this->input->post('idTrabajos'));
		}

		if(isset($_POST['monto_venta_total'])) {
			$compras['monto_venta_total']= (float)trim($this->input->post('monto_venta_total'));
		}







		if(isset($_POST['modalidad'])) {
			$compras['modalidad']= (int)$this->input->post('modalidad');
		}

		if($this->input->post('monto_al_autor')) {
			$compras['monto_al_autor']= (float)trim($this->input->post('monto_al_autor'));
		} else {
			$compras['monto_al_autor']= 0;
		}

		if($this->input->post('tituloPublicacion')) {
			$compras['tituloPublicacion']= trim($this->input->post('tituloPublicacion'));
		}

		if($this->input->post('regalias')) {
			$compras['regalias']= (int)$this->input->post('regalias');
		} else {
			$compras['regalias']= 0;
		}

		if($this->input->post('notificado')) {
			$compras['notificado'] = (int)$this->input->post('notificado');
		} else {
			$compras['notificado']= 0;
		}

		if($this->input->post('idUsuariosAutor')) {
			$compras['idUsuariosAutor'] = (int)$this->input->post('idUsuariosAutor');
		}

		if($this->input->post('emailUsuariosAutor')) {
			$compras['emailUsuariosAutor'] = $this->input->post('emailUsuariosAutor');
		}

		return $compras;
	}




	protected function getDataEmpty()
	{
		$compras = array();

		$compras['fecha']  				= '';
		$compras['idUsuariosComprador']= 0;
		$compras['idTrabajos']  			= 0;
		$compras['monto_venta_total']  = 0.0;
		$compras['modalidad']  			= 0;
		$compras['idUsuariosAutor']  	= 0;
		$compras['monto_al_autor']  	= 0.0;
		$compras['regalias']  	= 0;
		$compras['notificado']  			= 0;

		return $compras;
	}


	public function listar_pedidos()
	{
		if(!isLogged($this->session))
		{
			redirect('admin/login');
		}

		$data = array();



		if($this->uri->segment(4) and $this->uri->segment(4) != 'pagina' and $this->uri->segment(5))
		{ 	// FILTRO
			$filter[$this->uri->segment(4)] = addslashes(urldecode($this->uri->segment(5)));
			$filter['value'] 	= addslashes(urldecode($this->uri->segment(5)));
			$filter['limit'] 	= limit($this->uri->segment(7, 1), $this->config->item('filas_por_paginas') ); // el limit debe elegir nro de página como 1er parámetro.
			$baseUrl 		= PUBLIC_FOLDER_ADMIN . "compras/listar_pedidos/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/pagina";
			$data['paginas']	= paginas($this->repo_compras->contar_pedidos($filter) - 1,$this->config->item('filas_por_paginas'),$this->uri->segment(7,1), $baseUrl );
			$data['filter'] 	= $this->uri->segment(4);
			$data['value'] 	= urldecode($this->uri->segment(5));
		} else { // LISTADO DE TRABAJOS
			$baseUrl = PUBLIC_FOLDER_ADMIN . "compras/listar_pedidos/pagina";
			$filter['limit'] = limit($this->uri->segment(5,1),$this->config->item('filas_por_paginas') );
			$data['paginas'] = paginas($this->repo_compras->contar_pedidos() - 1,$this->config->item('filas_por_paginas'),$this->uri->segment(5,1), $baseUrl );
		}


		$data['section'] 		= 'compras';

		$data['compras'] 	= $this->repo_compras->listar_pedidos($filter);



		$script = '
		<script>
			$("#btn-filter").live("click",function(){
				if($("#filter :selected").val() != "" && $.trim($("#value").val()) != ""){
					window.location = "' .PUBLIC_FOLDER_ADMIN .'compras/listar_pedidos/"+$("#filter :selected").val() + "/" + encodeURI($.trim($("#value").val()));
				}
			});
			$("#btn-clean").live("click",function(){
				$("#filter").val("");
				$("#value").val("");
				window.location = "' .PUBLIC_FOLDER_ADMIN .'compras/listar_pedidos.html";
			});
		</script>
		<script type="text/javascript">
			$(document).ready(function(){
				$(".delete-tipo").click(function() {
					var id=  $(this).val();
					smoke.confirm("Esta seguro que desea eliminar el trabajo seleccionado?",function(e){
					if (e){
						$.post("'. PUBLIC_FOLDER_ADMIN . 'compras/eliminar",{id:id},function(data) {
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
							$.post("'. PUBLIC_FOLDER_ADMIN . 'compras/notifyUserAjax", {
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

		// LEVANTO VISTAS
		$this->load->view('admin_templates/header',array('title' => 'Panel de Control :: Trabajos :: Listado'));
		$this->load->view('admin_templates/menu_lateral', $data);
		$this->load->view('admin_templates/menu', $data);
		$this->load->view('listado_pedidos', $data);
		$this->load->view('admin_templates/footer',array('datatable' => true, 'scripts' => $script ));
	}


	public function listar_compras()
	{

		if(!isLogged($this->session))
		{
			redirect('admin/login');
		}

		$data = array();

		if($this->uri->segment(4) and $this->uri->segment(4) != 'pagina' and $this->uri->segment(5))
		{ 	// FILTRO DEL LISTADO DE COMPRAS
			$filter[$this->uri->segment(4)] = addslashes(urldecode($this->uri->segment(5)));
			$filter['value'] = addslashes(urldecode($this->uri->segment(5)));
			$filter['limit'] = limit($this->uri->segment(7, 1), $this->config->item('filas_por_paginas') ); // el limit debe elegir nro de página como 1er parámetro.
			$baseUrl = PUBLIC_FOLDER_ADMIN . "compras/listar_compras/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/pagina";
			$data['paginas'] = paginas($this->repo_compras->contar($filter),$this->config->item('filas_por_paginas'),$this->uri->segment(7,1), $baseUrl );
			$data['filter'] = $this->uri->segment(4);
			$data['value'] = urldecode($this->uri->segment(5));
		}else{ // LISTADO DE COMPRAS
			$baseUrl = PUBLIC_FOLDER_ADMIN . "compras/listar_compras/pagina";
			$filter['limit'] = limit($this->uri->segment(5,1),$this->config->item('filas_por_paginas') );
			$data['paginas'] = paginas($this->repo_compras->contar(),$this->config->item('filas_por_paginas'),$this->uri->segment(5,1), $baseUrl );
		}


		$data['section'] 		= 'compras';

		$data['compras'] 	= $this->repo_compras->listar_compras($filter);



		$script = '
		<script>
			$("#btn-filter").live("click",function(){
				if($("#filter :selected").val() != "" && $.trim($("#value").val()) != ""){
					window.location = "' .PUBLIC_FOLDER_ADMIN .'compras/listar_compras/"+$("#filter :selected").val() + "/" + encodeURI($.trim($("#value").val()));
				}
			});
			$("#btn-clean").live("click",function(){
				$("#filter").val("");
				$("#value").val("");
				window.location = "' .PUBLIC_FOLDER_ADMIN .'compras/listar_compras.html";
			});
		</script>
		<script type="text/javascript">
			$(document).ready(function(){
				$(".notify").click(function(){
					var id_compra =  $(this).val();
					smoke.confirm("Esta seguro que desea enviar notificacion via email?",function(e) {
						if (e){
							$.post("'. PUBLIC_FOLDER_ADMIN . 'compras/notifyUserAjax", {
								id_compra: id_compra
							},function(data) {
								var rst = JSON.parse(data);
								if(rst.error) {
									alert("no se pudo enviar el mail");
								}
								else {
									alert("Mail enviado");
									location.reload(false);
								}
							});
						}
					});
				});
			});
		</script>
		<script type="text/javascript">
			$(document).ready(function() {
				$(".asignar_regalia").click(function(){
					var id_compra =  $(this).val();
					smoke.confirm("Esta seguro que desea asignar las regalías?",function(e) {
						if (e){
							$.post("'. PUBLIC_FOLDER_ADMIN . 'compras/asignRegaliasAjax", {
								id_compra: id_compra
							},function(data) {
								var rst = JSON.parse(data);
								if(rst.error) {
									alert("No se pudieron actualizar las regalías");
								}else{
									alert("Regalías actualizadas correctamente.");
									location.reload(false);
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

		// LEVANTO VISTAS
		$this->load->view('admin_templates/header',array('title' => 'Panel de Control :: Trabajos :: Listado'));
		$this->load->view('admin_templates/menu_lateral', $data);
		$this->load->view('admin_templates/menu', $data);
		$this->load->view('listado_compras', $data);
		$this->load->view('admin_templates/footer',array('datatable' => true, 'scripts' => $script ));
	}


	// DEFINIMOS LA CANTIDAD DE REGALIAS QUE CUANDO LAS SUPERE DEBE AVISAR AL ADMINISTRADOR
	public function aviso_regalias()
	{

		if(!isLogged($this->session))
		{
			redirect('admin/login');
		}

		$data = array();

		if ($this->input->server('REQUEST_METHOD') == 'POST')
		{
			$regal = (int)$this->input->post('alarma');

			$content = '<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");
						$config["param_monto_regalias"] = ' . $regal . ';
						?>';
			file_put_contents("application/config/parametrizacion.php", $content);

			$data['alarma_regalia']	= $regal;
		} else {
			$alarma_regalia 		= (int)$this->config->item('param_monto_regalias');
			$data['alarma_regalia'] 	= $alarma_regalia;
		}

		$data['form_action']		= $data['form_action'] = PUBLIC_FOLDER_ADMIN . "compras/aviso_regalias.html";




		$script = '
		<link rel="stylesheet" href="'.ADMIN_FOLDER.'/lib/smoke/themes/gebo.css" />
		<script src="'. ADMIN_FOLDER .'lib/smoke/smoke.js"></script>
		';

		// LEVANTO VISTAS
		$this->load->view('admin_templates/header',array('title' => 'Panel de Control :: Trabajos :: Listado'));
		$this->load->view('admin_templates/menu_lateral', $data);
		$this->load->view('admin_templates/menu', $data);
		$this->load->view('aviso_regalias', $data);
		$this->load->view('admin_templates/footer',array('datatable' => true, 'scripts' => $script ));
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
			$this->email->from('jpasosa@gmail.com', 'Juan Pablo');
			$this->email->to('juanpablososa@gmail.com', 'sistemas@zerodigital.com.ar'); // TODO: ACA DEBE IR $user_mail
			// $this->email->cc('another@another-example.com');
			// $this->email->bcc('them@their-example.com');
			$this->email->subject('Pedido Aprobado de Textosdigitales');
			$this->email->message($user_mail . ' tu pedido, sobre la publicación titulo "' . $work_title . ' " ha sido aprobado');
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

			// avisar por mail al usuario. . . .

			$id_compra = (int)$this->input->post('id_compra');
			$compra 	= $this->repo_regalias->getById($id_compra);
			$user 		= $this->usuarios_model->getUsuariosById($compra['idUsuarios']);
			$titulo 		= $this->repo_regalias->getTituloById($id_compra);

			if($this->sendEmailRegalias($user['email'], $titulo, $compra['monto_al_autor'])) {
				$this->repo_regalias->setNotificadoTrue($id_compra);
				$json = array(
						'mensaje' 	=> 'Se ha enviado el mail con éxito',
						'error' 		=> false
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

	public function sendEmailRegalias($user_mail, $work_title, $monto_regalias)
	{
		try {
			if(!isLogged($this->session) and !checkRol('administrador', $this->session)){
				redirect('admin/login');
			}
			$this->load->library('email');
			$this->email->from($this->config->item('email_admin'));
			$this->email->to($user_mail, $this->config->item('email_programador'), $this->config->item('email_sistemas')); // TODO: volar programador y sistemas
			$this->email->subject('Regalías acumuladas por venta de Publicación');
			$this->email->message($user_mail . ' se acumularion regalías por ' . $monto_regalias .  ' por la venta de la publicación de titulo ' . $work_title);
			if($this->email->send())	 {
				return true; // ENVÍO MAIL
			}else {
				return false;
			}
		} catch (Exception $e) {
			die('No pudo enviar el mail por algún motivo');
		}
	}

	public function sendEmailComprador($from, $to, $title, $body)
	{
		try {
			if(!isLogged($this->session) and !checkRol('administrador', $this->session)){
				redirect('admin/login');
			}

			$this->load->library('email');
			$this->email->from($this->config->item('email_admin'));
			// $this->email->to(array('sistemas@zerodigital.com.ar', 'juanpablososa@gmail.com', $to )); // TODO: MODIFICAR ENVIO DE MAIL
			$this->email->to($to, $this->config->item('email_programador'), $this->config->item('email_sistemas')); // TODO: MODIFICAR ENVIO DE MAIL
			$this->email->subject($title);
			$this->email->message($body);
			// ENVIO DEL MAIL

			$mail_enviado = $this->email->send();
			if( $mail_enviado )	 {
				return true; // Pudo enviar el mail correctamente
			}else {
				return false;
			}
			// echo $this->email->print_uger();
		} catch (Exception $e) {
			die('No pudo enviar el mail por algún motivo');
		}
	}


	// ASIGNAR REGALÍAS AL AUTOR
	public function asignRegaliasAjax()
	{
		try {

			$id_compra = (int)$this->input->post('id_compra');
			$compra 	= $this->repo_regalias->getById($id_compra);
			$user 		= $this->usuarios_model->getUsuariosById($compra['idUsuarios']);


			$update_reg = $this->repo_regalias->updateRegalias( $user['idUsuarios'], $compra['monto_al_autor'] );

			if ($update_reg)
			{
				$est_reg = $this->repo_regalias->setEstadoRegaliasTrue($id_compra);
				if ($est_reg) {
					$json = array(
								'mensaje' 	=> 'Se actualizó correctamente las regalías.',
								'error' 		=> false
								);
				} else {
					$json = array(
								'error' 		=> true,
								'mensaje' 	=> 'No se pudieron actualizar las regalías.'
								);
				}

			} else {
				$json = array(
								'error' 		=> true,
								'mensaje' 	=> 'No se pudieron actualizar las regalías.'
								);
			}

			echo json_encode($json);

		} catch (Exception $e) {
			$json = array(
					'error' 		=> true,
					'mensaje' 	=> 'El Usuario no pudo ser notificado.'
			);
			echo json_encode($json);
		}
	}


	public function link_descarga($id_pedido, $file = null)
	{
		$cant_bajadas = $this->repo_compras->getCantBajadas($id_pedido);
		$cant_bajadas = (int) $cant_bajadas;

		if ($cant_bajadas < 2)
		{
			$cant_bajadas = $cant_bajadas + 1;
			$data = array('entregado' => 1, 'cant_bajadas' => $cant_bajadas);
			// HAGO EL UPDATE PARA SUMAR LA CANTIDAD DE BAJADAS Y PARA PONER QUE YA ESTÁ ENTREGADO
			$this->db->where('idPedidos',$id_pedido);
			$this->db->update('Pedidos', $data);

			if ($file == null || $file == '') {
				$message = 'El archivo no existe en el servidor.';
				$this->session->set_flashdata('flash_error', $message);
				redirect('');
			} else {
				// BAJADA DEL ARCHIVO
				$this->load->helper('download');
				file_get_contents($file); // Read the file's contents
		        	$name = $file;
		        	$download = force_download($name, 'nombre nnombre');
			}

		} else { // TENEMOS QUE AVISAR QUE NO LO PUEDE BAJAR
			$message = 'El archivo ya fue descargado dos veces.';
			$this->session->set_flashdata('flash_error', $message);
			redirect('');
		}

		return true;
	}



}

?>
