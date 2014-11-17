<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_pagos extends MX_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('all_models/repo_compras');
		$this->load->model('all_models/repo_regalias');
		$this->load->model('all_models/repo_usuarios');
		$this->load->model('all_models/repo_trabajos');
		$this->load->model('all_models/repo_pagos');
		$this->load->model('admin_trabajos/estadostrabajos_model');
		$this->load->model('admin_trabajos/trabajos_model');
		$this->load->model('admin_usuarios/usuarios_model');
		$this->load->model('admin_categorias/categorias_model');
		$this->load->model('admin_precios/precios_model');
		// $this->output->enable_profiler(TRUE);
	}


	public function listado_autores()
	{
		if(!isLogged($this->session)) {
			redirect('admin/login');
		}

		$data = array();


		if($this->input->server('REQUEST_METHOD') == 'POST' && isset($_POST['idUsuarios']))
		{
			$filter['idUsuarios'] 	= $_POST['idUsuarios'];
			$data['paginas']		= '';
		}


		if($this->uri->segment(4) and $this->uri->segment(4) != 'pagina' and $this->uri->segment(5))
		{ 	// FILTRO
			$filter[$this->uri->segment(4)] = addslashes(urldecode($this->uri->segment(5)));
			$filter['value'] = addslashes(urldecode($this->uri->segment(5)));
			$filter['limit'] = limit($this->uri->segment(7, 1), $this->config->item('filas_por_paginas') ); // el limit debe elegir nro de página como 1er parámetro.
			$baseUrl = PUBLIC_FOLDER_ADMIN . "pagos/listado_autores/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/pagina";
			$data['paginas'] = paginas($this->repo_pagos->contar($filter),$this->config->item('filas_por_paginas'),$this->uri->segment(7,1), $baseUrl );
			$data['filter'] = $this->uri->segment(4);
			$data['value'] = urldecode($this->uri->segment(5));
		} else { // LISTADO DE TRABAJOS
			$baseUrl = PUBLIC_FOLDER_ADMIN . "pagos/listado_autores/pagina";
			$filter['limit'] = limit($this->uri->segment(5,1),$this->config->item('filas_por_paginas') );
			if ($this->input->server('REQUEST_METHOD') == 'POST' && isset($_POST['idUsuarios'])) {
				$data['paginas'] = '';
			} else {
				$data['paginas'] = paginas($this->repo_pagos->contar(),$this->config->item('filas_por_paginas'),$this->uri->segment(5,1), $baseUrl );
			}
		}


		// $data['section'] 		= 'compras';
		$select_usuarios 			= $this->repo_pagos->selectUsuarios();
		$data['select_usuarios']		= $select_usuarios;
		$usuarios_regalias 			= $this->repo_pagos->listarUsuariosRegalias($filter);
		$data['usuarios_regalias'] 	= $usuarios_regalias;
		$data['form_action'] 		= base_url('admin/pagos/listado_autores');




		$script = '
		<script>
			$("#btn-filter").live("click",function(){
				if($("#filter :selected").val() != "" && $.trim($("#value").val()) != ""){
					window.location = "' .PUBLIC_FOLDER_ADMIN .'compras/listar/"+$("#filter :selected").val() + "/" + encodeURI($.trim($("#value").val()));
				}
			});
			$("#btn-clean").live("click",function(){
				$("#filter").val("");
				$("#value").val("");
				window.location = "' .PUBLIC_FOLDER_ADMIN .'compras/listar.html";
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
		$this->load->view('listado_autores', $data);
		$this->load->view('admin_templates/footer',array('datatable' => true, 'scripts' => $script ));
	}


	public function listado_pagos_realizados()
	{
		if(!isLogged($this->session)) {
			redirect('admin/login');
		}

		$data = array();

		if($this->uri->segment(4) and $this->uri->segment(4) != 'pagina' and $this->uri->segment(5))
		{ 	// FILTRO
			$filter[$this->uri->segment(4)] = addslashes(urldecode($this->uri->segment(5)));
			$filter['value'] = addslashes(urldecode($this->uri->segment(5)));
			$filter['limit'] = limit($this->uri->segment(7, 1), $this->config->item('filas_por_paginas') ); // el limit debe elegir nro de página como 1er parámetro.
			$baseUrl = PUBLIC_FOLDER_ADMIN . "pagos/listado_pagos_realizados/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/pagina";
			$data['paginas'] = paginas($this->repo_pagos->contar($filter),$this->config->item('filas_por_paginas'),$this->uri->segment(7,1), $baseUrl );
			$data['filter'] = $this->uri->segment(4);
			$data['value'] = urldecode($this->uri->segment(5));
		} else { // LISTADO DE TRABAJOS
			$baseUrl = PUBLIC_FOLDER_ADMIN . "pagos/listado_pagos_realizados/pagina";
			$filter['limit'] = limit($this->uri->segment(5,1),$this->config->item('filas_por_paginas') );
			$data['paginas'] = paginas($this->repo_pagos->contar(),$this->config->item('filas_por_paginas'),$this->uri->segment(5,1), $baseUrl );
		}



		$pagos_realizados 			= $this->repo_pagos->getPagosRealizados($filter);
		$data['pagos_realizados'] 	= $pagos_realizados;
		$data['form_action']			= "#";


		$script = '
		<script>
			$("#btn-filter").live("click",function(){
				if($("#filter :selected").val() != "" && $.trim($("#value").val()) != ""){
					window.location = "' .PUBLIC_FOLDER_ADMIN .'compras/listar/"+$("#filter :selected").val() + "/" + encodeURI($.trim($("#value").val()));
				}
			});
			$("#btn-clean").live("click",function(){
				$("#filter").val("");
				$("#value").val("");
				window.location = "' .PUBLIC_FOLDER_ADMIN .'compras/listar.html";
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
		$this->load->view('listado_pagos_realizados', $data);
		$this->load->view('admin_templates/footer',array('datatable' => true, 'scripts' => $script ));
	}


	public function ver_pagos_pendientes($id_autor)
	{
		if(!isLogged($this->session)) {
			redirect('admin/login');
		}

		$data = array();

		// if($this->uri->segment(4) and $this->uri->segment(4) != 'pagina' and $this->uri->segment(5))
		// { 	// FILTRO
		// 	$filter[$this->uri->segment(4)] = addslashes(urldecode($this->uri->segment(5)));
		// 	$filter['value'] = addslashes(urldecode($this->uri->segment(5)));
		// 	$filter['limit'] = limit($this->uri->segment(7, 1), $this->config->item('filas_por_paginas') ); // el limit debe elegir nro de página como 1er parámetro.
		// 	$baseUrl = PUBLIC_FOLDER_ADMIN . "pagos/listado_autores/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/pagina";
		// 	$data['paginas'] = paginas($this->repo_pagos->contar($filter),$this->config->item('filas_por_paginas'),$this->uri->segment(7,1), $baseUrl );
		// 	$data['filter'] = $this->uri->segment(4);
		// 	$data['value'] = urldecode($this->uri->segment(5));
		// } else { // LISTADO DE TRABAJOS
		// 	$baseUrl = PUBLIC_FOLDER_ADMIN . "pagos/listado_autores/pagina";
		// 	$filter['limit'] = limit($this->uri->segment(5,1),$this->config->item('filas_por_paginas') );
		// 	$data['paginas'] = paginas($this->repo_pagos->contar(),$this->config->item('filas_por_paginas'),$this->uri->segment(5,1), $baseUrl );
		// }

		// REGALIAS
		$regalias 				= $this->repo_regalias->listarRegaliasByAutor($id_autor);
		$data['regalias'] 		= $regalias;
		// MONTO TOTAL
		$monto_total 			= $this->repo_regalias->totalRegalias($id_autor);
		$data['monto_total'] 	= $monto_total;

		$data['nombre_autor'] 	= $this->repo_usuarios->getNombreApellido($id_autor);
		$data['form_action'] 	= PUBLIC_FOLDER_ADMIN . "pagos/ver_pagos_pendientes/" . $id_autor . ".html";




		if($this->input->server('REQUEST_METHOD') == 'POST')
		{ 	// POST

			$generate_pago = $this->repo_pagos->generatePago($regalias, $monto_total, $id_autor);
			if ($generate_pago) {
				$message = 'Se ha generado el pago para el autor correctamente, y se han puesto sus regalías en 0';
				$this->session->set_flashdata('success', $message);
			} else {
				$message = 'No se pudo generar el pago.';
				$this->session->set_flashdata('error', $message);
			}

			redirect('admin/pagos/listado_autores');
		} else { // GET

		}


		$script = '
		<script>
			$("#btn-filter").live("click",function(){
				if($("#filter :selected").val() != "" && $.trim($("#value").val()) != ""){
					window.location = "' .PUBLIC_FOLDER_ADMIN .'compras/listar/"+$("#filter :selected").val() + "/" + encodeURI($.trim($("#value").val()));
				}
			});
			$("#btn-clean").live("click",function(){
				$("#filter").val("");
				$("#value").val("");
				window.location = "' .PUBLIC_FOLDER_ADMIN .'compras/listar.html";
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
		$this->load->view('ver_pagos_pendientes', $data);
		$this->load->view('admin_templates/footer',array('datatable' => true, 'scripts' => $script ));
	}



	public function ver_pago_realizado($id_pago)
	{
		if(!isLogged($this->session)) {
			redirect('admin/login');
		}

		$data = array();

		// if($this->uri->segment(4) and $this->uri->segment(4) != 'pagina' and $this->uri->segment(5))
		// { 	// FILTRO
		// 	$filter[$this->uri->segment(4)] = addslashes(urldecode($this->uri->segment(5)));
		// 	$filter['value'] = addslashes(urldecode($this->uri->segment(5)));
		// 	$filter['limit'] = limit($this->uri->segment(7, 1), $this->config->item('filas_por_paginas') ); // el limit debe elegir nro de página como 1er parámetro.
		// 	$baseUrl = PUBLIC_FOLDER_ADMIN . "pagos/listado_autores/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/pagina";
		// 	$data['paginas'] = paginas($this->repo_pagos->contar($filter),$this->config->item('filas_por_paginas'),$this->uri->segment(7,1), $baseUrl );
		// 	$data['filter'] = $this->uri->segment(4);
		// 	$data['value'] = urldecode($this->uri->segment(5));
		// } else { // LISTADO DE TRABAJOS
		// 	$baseUrl = PUBLIC_FOLDER_ADMIN . "pagos/listado_autores/pagina";
		// 	$filter['limit'] = limit($this->uri->segment(5,1),$this->config->item('filas_por_paginas') );
		// 	$data['paginas'] = paginas($this->repo_pagos->contar(),$this->config->item('filas_por_paginas'),$this->uri->segment(5,1), $baseUrl );
		// }

		$detalle_pago			= $this->repo_pagos->detallePagos($id_pago);
		$data['detalle_pago'] 	= $detalle_pago;
		$data['monto_total'] 	= $this->repo_pagos->getMontoTotal($id_pago);
		$data['nombre_autor'] 	= $this->repo_pagos->getAutor($id_pago);
		// $data['form_action'] 	= PUBLIC_FOLDER_ADMIN . "pagos/ver_pagos_pendientes/" . $id_autor . ".html";




		if($this->input->server('REQUEST_METHOD') == 'POST')
		{ 	// POST

			$generate_pago = $this->repo_pagos->generatePago($regalias, $monto_total, $id_autor);
			if ($generate_pago) {
				$message = 'Se ha generado el pago para el autor correctamente, y se han puesto sus regalías en 0';
				$this->session->set_flashdata('success', $message);
			} else {
				$message = 'No se pudo generar el pago.';
				$this->session->set_flashdata('error', $message);
			}

			redirect('admin/pagos/listado_autores');
		} else { // GET

		}


		$script = '
		<script>
			$("#btn-filter").live("click",function(){
				if($("#filter :selected").val() != "" && $.trim($("#value").val()) != ""){
					window.location = "' .PUBLIC_FOLDER_ADMIN .'compras/listar/"+$("#filter :selected").val() + "/" + encodeURI($.trim($("#value").val()));
				}
			});
			$("#btn-clean").live("click",function(){
				$("#filter").val("");
				$("#value").val("");
				window.location = "' .PUBLIC_FOLDER_ADMIN .'compras/listar.html";
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
		$this->load->view('ver_pago_realizado', $data);
		$this->load->view('admin_templates/footer',array('datatable' => true, 'scripts' => $script ));
	}



}

?>
