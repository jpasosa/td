<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_permisos extends MX_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->library('session');
		$this->load->model('permisos_model');
		$this->load->model('roles_model');
	}

	public function index(){
		try {
			$this->listarRoles();
		} catch (Exception $e) {
		}
	}



	public function listarRoles(){
		try {
			if(!isLogged($this->session) or !checkRol('administrador',$this->session)){
				redirect("admin");
			}
			$cantidadRegistros = $this->roles_model->contar();
			$baseUrl = PUBLIC_FOLDER . "admin/permisos/listarRoles/pagina";
			$data['paginas'] = paginas($cantidadRegistros,$this->config->item('filas_por_paginas'),$this->uri->segment(5,1), $baseUrl );
			$filter['limit'] = limit($this->uri->segment(5,1),$this->config->item('filas_por_paginas') );
			$data['roles'] = $this->roles_model->get_all($filter);

			$data['userName'] = $this->session->userdata('userName');

			$scripts = '
			<script type="text/javascript">
				$(document).ready(function(){
					$(".delete-rol").click(function(){
					var id=  $(this).val();
						smoke.confirm("Esta seguro que desea eliminar el rol seleccionado?",function(e){
							if (e){
								$.post("'.base_url('/admin/permisos/eliminarRol').'",{id:id},function(data){
								 var rst = JSON.parse(data);
									if(rst.error){
										$.sticky(rst.mensaje, {autoclose : 5000, position: "top-center", type: "st-error" });
									}
									else {
										$("#tr_rol_"+id).slideUp("fast",function(){
											$(this).remove();
											$.sticky(rst.mensaje, {autoclose : 5000, position: "top-center", type: "st-success" });
										});
									}
								});
							}
						});
					});
				});
				gebo_galery_table = {
		        init: function() {
		           $("#dt_roles").dataTable({
						"sDom": "<\'row\'<\'span6\'f>r>",
						"sSearch":       "Filtrar:",
						"bPagination": false,
		                "aaSorting": [[ 2, "asc" ]],
						"aoColumns": [
							{ "bSortable": false },
							{ "bSortable": true },
							{ "bSortable": true },
							{ "bSortable": true },
							{ "bSortable": false }
						]
					});
		           $(\'.dt_actions\').html($(\'.dt_gal_actions\').html());
		        }
		    };
			</script>
			<link rel="stylesheet" href="'.ADMIN_FOLDER.'/lib/smoke/themes/gebo.css" />
			<script src="'. ADMIN_FOLDER .'lib/smoke/smoke.js"></script>';

	   		$this->load->view('admin/header',array('title' => 'Panel de Control :: Home'));
	   		$this->load->view('admin/menu',$data);
	   		$this->load->view('listado_roles_panel',$data);
	   		$this->load->view('admin/menu_lateral');
	   		$this->load->view('admin/footer',array('datatable' => true,'scripts' => $scripts ));


		} catch (Exception $e) {

		}
	}

	protected function getRol(){
		$rol = array();
		if($this->input->post('idRoles')){
			$rol['idRoles'] = (int)$this->input->post('idRoles');
		}
		if($this->input->post('rolDescripcion')){
			$rol['descripcion'] = $this->input->post('rolDescripcion');
		}
		if($this->input->post('rolKey')){
			$rol['key'] = $this->input->post('rolKey');
		}
		return $rol;
	}

	protected function getPermiso(){
		$permiso = array();
		if($this->input->post('idPermisos')){
			$permiso['idPermisos'] = (int)$this->input->post('idPermisos');
		}
		if($this->input->post('permDescripcion')){
			$permiso['descripcion'] = $this->input->post('permDescripcion');
		}
		if($this->input->post('idRoles')){
			$permiso['idRoles'] = (int)$this->input->post('idRoles');
		}
		if($this->input->post('permKey')){
			$permiso['key'] = $this->input->post('permKey');
		}
		if($this->input->post('permValue')){
			$permiso['valor'] = $this->input->post('permValue');
		}
		return $permiso;
	}


	public function guardarCambios(){
		try {
			if(!isLogged($this->session)){
				redirect('login');
			}
			elseif($this->input->server('REQUEST_METHOD') != 'POST'){
				redirect('admin/usuarios');
			}
			$isAdmin = false;

			$rol = array();
			$rol = $this->getRol();


			$errores = $this->roles_model->validar($rol);

			if(isset($errores) and $errores){

				$htmlError = "";
				foreach ($errores as $error){
					$htmlError .= $error ."<br>";
				}
				$json = array(
					'error' => true,
					'mensaje' => $htmlError
				);

			}
			else{
				$this->roles_model->update($rol);
				$json = array(
					'error' => false,
					'mensaje' => "Los cambios se han guardado con &eacute;xito"
				);
			}
			echo json_encode($json);
		}
		catch(Exception $e){

		}
	}

	public function eliminarRol(){
		try {
			if(!isLogged($this->session)){
				redirect('login');
			}
			elseif(!checkRol('administrador',$this->session)){
				redirect('admin');
			}
			elseif(!$this->input->post('id')){
				redirect('admin/permisos');
			}

			$rol = array();
			$rol['idRoles'] = (int)$this->input->post('id');
			if($rol['idRoles'] == 0){
				redirect('admin/permisos');
			}

			if($this->roles_model->delete($rol)){
				$json = array(
				'mensaje' => 'Se ha eliminado con &eacute;xito el rol seleccionado',
				'error' => false
				);
			}
			else {
				$json = array(
					'error' => true,
					'mensaje' => 'El rol no se pudo eliminar'
				);
			}

			echo json_encode($json);

		} catch (Exception $e) {
			$json = array(
					'error' => true,
					'mensaje' => 'El rol no se pudo eliminar'
				);
			echo json_encode($json);
		}
	}

	public function agregarRol(){
		try {
			if(!isLogged($this->session)){
				redirect();
			}
			elseif(!checkRol('administrador',$this->session) ) {
				redirect('admin/permisos');
			}


			$data['userName'] = $this->session->userdata('userName');
			$data['titulo'] = "Agregar Rol";
			$data['boton'] = "Agregar";

			$scripts = '
			<script type="text/javascript">
			$(document).ready(function(){
				$(".submit").click(function(){
					$.post("'.base_url('/admin/permisos/nuevoRol').'",{rolDescripcion:$("#rolDescripcion").val(),rolKey:$("#rolKey").val()},function(data){
						var rst = JSON.parse(data);
						if(rst.error){
							$.sticky(rst.mensaje, {autoclose : 5000, position: "top-center", type: "st-error" });
						}
						else {
							$.sticky(rst.mensaje, {autoclose : 3500, position: "top-center", type: "st-success" });
							setTimeout(function(){
								window.location = "'.base_url('/admin/permisos').'";
							},4000);
						}
					});
				});
			});
			</script>';

			$this->load->view('admin/header',array('title' => 'Panel de Control :: Home'));
	   		$this->load->view('admin/menu',$data);
	   		$this->load->view('agregar_editar_rol',$data);
	   		$this->load->view('admin/menu_lateral');
	   		$this->load->view('admin/footer',array('datatable' => true,'scripts' => $scripts ));


		} catch (Exception $e) {

		}
	}

	public function editarRol(){
		try {
			if(!isLogged($this->session)){
				redirect();
			}
			elseif(!checkRol('administrador',$this->session) ) {
				redirect('admin/permisos');
			}

			$rol = array();
			$rol['idRoles'] = $this->uri->segment(4,0);
			if($rol['idRoles'] == 0){
				redirect('admin/permisos');
			}

			$data['rol'] = $this->roles_model->get($rol);

			$data['userName'] = $this->session->userdata('userName');
			$data['titulo'] = "Editar Rol";
			$data['boton'] = "Guardar cambios";


			$scripts = '
			<script type="text/javascript">
			$(document).ready(function(){
			$("#agregar-permiso").click(function(){
				$("#modalSave").removeClass("edit add");
				$("#modalSave").addClass("add");
				reset();
				$("#modalTitle").html($("#titleAdd").html());
				$("#modalForm").modal();
			});
			$("#modalSave").click(function(){
				$("#modalForm").fadeOut("fast");
				if($(this).hasClass("add")){
					$.post("'.base_url('/admin/permisos/agregar').'",{idRoles:$("#idRoles").val(),permDescripcion:$("#e-permDescripcion").val(),permKey:$("#e-permKey").val(),permValue:$("#e-permValue").val()},function(data){
						var rst = JSON.parse(data);
						if(rst.error){
							$.sticky(rst.mensaje, {autoclose : 5000, position: "top-center", type: "st-error" });
						}
						else {
							$.sticky(rst.mensaje, {autoclose : 5000, position: "top-center", type: "st-success" });
							if($(".odd")){
								$(".odd").remove();
							}
							$("#dt_permisos tbody").append(rst.addTr);
						}
					});
				}
				else{
					$.post("'.base_url('/admin/permisos/editar').'",{idPermisos:$("#idPermisos").val(),permDescripcion:$("#e-permDescripcion").val(),permKey:$("#e-permKey").val(),permValue:$("#e-permValue").val()},function(data){
						var rst = JSON.parse(data);
						if(rst.error){
							$.sticky(rst.mensaje, {autoclose : 5000, position: "top-center", type: "st-error" });
						}
						else {
							$.sticky(rst.mensaje, {autoclose : 5000, position: "top-center", type: "st-success" });
							$("#td_perm_descripcion_"+$("#idPermisos").val()).html(rst.descripcion);
							$("#td_perm_key_"+$("#idPermisos").val()).html(rst.key);
							$("#td_perm_value_"+$("#idPermisos").val()).html(rst.valor);
						}
					});
				}
			});
			$(".editar-perm").live("click",function(){
				$("#modalSave").removeClass("edit add");
				$("#modalSave").addClass("edit");
				reset();
				var id= $(this).val();
				$("#idPermisos").val(id);
				$("#modalTitle").html($("#titleEdit").html());
				$("#e-permDescripcion").val($("#td_perm_descripcion_"+id).html());
				$("#e-permKey").val($("#td_perm_key_"+id).html());
				$("#e-permValue").val($("#td_perm_value_"+id).html());
				$("#modalForm").modal();
			});
				$(".submit").live("click",function(){
				var id =$(this).val();
					$.post("'.base_url('/admin/permisos/guardarCambios').'",{idRoles:id,rolDescripcion:$("#rolDescripcion").val(),rolKey:$("#rolKey").val()},function(data){
						var rst = JSON.parse(data);
						if(rst.error){
							$.sticky(rst.mensaje, {autoclose : 5000, position: "top-center", type: "st-error" });
						}
						else {
							$.sticky(rst.mensaje, {autoclose : 5000, position: "top-center", type: "st-success" });
						}
					});
				});
					$(".delete-perm").live("click",function(){
					var id=  $(this).val();
						smoke.confirm("&iquest;Esta seguro que desea eliminar el permiso seleccionado?",function(e){
							if (e){
								$.post("'.base_url('/admin/permisos/eliminar').'",{id:id},function(data){
								 var rst = JSON.parse(data);
									if(rst.error){
										$.sticky(rst.mensaje, {autoclose : 5000, position: "top-center", type: "st-error" });
									}
									else {
										$("#tr_perm_"+id).slideUp("fast",function(){
											$(this).remove();
											$.sticky(rst.mensaje, {autoclose : 5000, position: "top-center", type: "st-success" });
										});
									}
								});
							}
						});
					});
				});
				function reset(){
					$("#e-permDescripcion").val("");
					$("#e-permKey").val("");
					$("#e-permValue").val("");
					$("#idPermisos").val("");
				}
				gebo_galery_table = {
		        init: function() {
		           $("#dt_permisos").dataTable({
						"sDom": "<\'row\'<\'span6\'f>r>",
						"sSearch":       "Filtrar:",
						"bPagination": false,
		                "aaSorting": [[ 2, "asc" ]],
		                "aoColumns": [
							{ "bSortable": false },
							{ "bSortable": true },
							{ "bSortable": true },
							{ "bSortable": false }
						]
					});
		           $(\'.dt_actions\').html($(\'.dt_gal_actions\').html());
		        }
		    };
			</script>
			<link rel="stylesheet" href="'.ADMIN_FOLDER.'/lib/smoke/themes/gebo.css" />
			<script src="'. ADMIN_FOLDER .'lib/smoke/smoke.js"></script>';


			$this->load->view('admin/header',array('title' => 'Panel de Control :: Home'));
	   		$this->load->view('admin/menu',$data);
	   		$this->load->view('agregar_editar_rol',$data);
	   		$this->load->view('admin/menu_lateral');
	   		$this->load->view('admin/footer',array('datatable' => true,'scripts' => $scripts ));


		} catch (Exception $e) {

		}
	}

	public function eliminar(){
		try {
			if(!isLogged($this->session)){
				redirect('login');
			}
			elseif(!checkRol('administrador',$this->session)){
				redirect('admin');
			}
			elseif(!$this->input->post('id')){
				redirect('admin/permisos');
			}

			$permiso = array();
			$permiso['idPermisos'] = (int)$this->input->post('id');
			if($permiso['idPermisos'] == 0){
				redirect('admin/permisos');
			}

			if($this->permisos_model->delete($permiso)){
				$json = array(
				'mensaje' => 'Se ha eliminado con &eacute;xito el permiso seleccionado',
				'error' => false
				);
			}
			else {
				$json = array(
					'error' => true,
					'mensaje' => 'El permiso no se pudo eliminar'
				);
			}

			echo json_encode($json);

		} catch (Exception $e) {
			$json = array(
					'error' => true,
					'mensaje' => 'El permiso no se pudo eliminar'
				);
			echo json_encode($json);
		}
	}

	public function nuevoRol(){
		try {
			if(!isLogged($this->session)){
				redirect('login');
			}
			elseif($this->input->server('REQUEST_METHOD') != 'POST'){
				redirect('admin/usuarios');
			}
			$isAdmin = false;

			$rol = array();
			$rol = $this->getRol();


			$errores = $this->roles_model->validar($rol,true);

			if(isset($errores) and $errores){

				$htmlError = "";
				foreach ($errores as $error){
					$htmlError .= $error ."<br>";
				}
				$json = array(
					'error' => true,
					'mensaje' => $htmlError
				);

			}
			else{
				$this->roles_model->insert($rol);
				$json = array(
					'error' => false,
					'mensaje' => "Los cambios se han guardado con &eacute;xito"
				);
			}
			echo json_encode($json);


		} catch (Exception $e) {
				$json = array(
					'error' => true,
					'mensaje' => "Hubo un error inexperado"
				);
			echo json_encode($json);

		}
	}

	public function editar(){
		try {
			if(!isLogged($this->session)){
				redirect('login');
			}
			elseif($this->input->server('REQUEST_METHOD') != 'POST'){
				redirect('admin/usuarios');
			}
			$isAdmin = false;

			$permiso = array();
			$permiso = $this->getPermiso();


			$errores = $this->permisos_model->validar($permiso,array('idPermisos' => true));

			if(isset($errores) and $errores){

				$htmlError = "";
				foreach ($errores as $error){
					$htmlError .= $error ."<br>";
				}
				$permiso = array(
					'error' => true,
					'mensaje' => $htmlError
				);

			}
			else{
				$this->permisos_model->update($permiso);
				$permiso['mensaje']= "Los cambios se han guardado con &eacute;xito";
				$permiso['error'] = false;

			}
			echo json_encode($permiso);
		}
		catch(Exception $e){

		}
	}

	public function agregar(){
		try {
			if(!isLogged($this->session)){
				redirect('login');
			}
			elseif($this->input->server('REQUEST_METHOD') != 'POST'){
				redirect('admin/usuarios');
			}
			$isAdmin = false;

			$permiso = array();
			$permiso = $this->getPermiso();


			$errores = $this->permisos_model->validar($permiso,array('idRoles' => true));

			if(isset($errores) and $errores){

				$htmlError = "";
				foreach ($errores as $error){
					$htmlError .= $error ."<br>";
				}
				$permiso = array(
					'error' => true,
					'mensaje' => $htmlError
				);

			}
			else{
				$permiso['idPermisos'] = $this->permisos_model->insert($permiso);
				$permiso['addTr'] = $this->load->view('tr_permiso_add',array('permiso'=>$permiso),true);
				$permiso['mensaje']= "Se ha agregado con &eacute;xito";
				$permiso['error'] = false;

			}
			echo json_encode($permiso);
		}
		catch(Exception $e){

		}
	}

}

?>