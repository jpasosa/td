<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_categorias extends CI_Class {

	protected $categoria;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('admin_categorias/categorias_model');

	}

	public function index()
	{
		$this->nueva();
	}

	protected  function getData()
	{
		$categorias = array();
		$idCategorias = -1;


		if($this->input->get_post('idCategorias')) {
			$idCategorias = (int)$this->input->get_post('idCategorias');
		}
		elseif($this->uri->segment(4)) {
			$idCategorias = (int)$this->uri->segment(4);
		}

		$categorias['idCategorias'] = $idCategorias;

		$cat = $this->categorias_model->getById($categorias);


		if($this->input->post('nombreCategoria')){
			$categorias['nombreCategoria'] = $this->input->post('nombreCategoria');
		} else {
			$categorias['nombreCategoria'] = '';
		}

		if($this->input->post('nombreSubCategoria') and $this->input->post('nombreSubCategoria') != ''){
			$categorias['nombreSubCategoria'] = $this->input->post('nombreSubCategoria');
		}

		if($this->input->post('parentId')) {
			$categorias['parentId'] = $this->input->post('parentId');
		}

		if($this->input->post('estado')){
			$categorias['estado'] = $this->input->post('estado');
		}


		// IMAGEN DE LA CATEGORIA
		if(isset($_FILES['image']['error']) && $_FILES['image']['error'] != 4 ){
			$categorias['image'] = $_FILES['image']['name'];
		}
		elseif ($this->input->post('image_name')) {
			$categorias['image'] = $this->input->post('image_name');
		}
		elseif (isset($cat['imagen']) && $cat['imagen'] != '') {
			$categorias['image'] = $cat['imagen'];
		}
		else {
			$categorias['image'] = '';
		}

		// BORRADO DE LA IMAGEN DE LA CATEGORIA
		if(isset($_POST['del_imagen'])) {
			$categorias['del_imagen'] = 1;
		} else {
			$categorias['del_imagen'] = 0;
		}


		return $categorias;
	}

	public function nueva()
	{
		if(!isLogged($this->session) or !checkRol('administrador',$this->session)){
			redirect('admin/login');
		}
		$data 					= array();
		$errores 				= array();
		$mensaje 				= '';
		$data['titulo'] 			= 'Agregar nueva';
		$data['form_action'] 	= PUBLIC_FOLDER_ADMIN .'categorias/nueva.html';
		$data['boton'] 			= 'Agregar';
		$this->categoria 		= $this->getData();
		$errores 				= $this->categorias_model->validarNueva($this->categoria);
		$data['section']			= $this->section;
		$data['parent_category']= true; // lo pongo en true para que en la vista muestre la carga de la imágen.

		if($this->input->server('REQUEST_METHOD') == 'POST' and !$errores)
		{

			$this->categoria['idCategorias'] = (int) $this->categorias_model->nueva($this->categoria);
			if($this->categoria['idCategorias'] > 0){
				$mensaje = '$.sticky("Se ha creado con éxito", {autoclose : 5000, position: "top-center", type: "st-success" });';
				//$data['idCategorias'] = $this->categoria['idCategorias'];
				//$data['titulo'] = 'Editar tipos';
				//$data['form_action'] = PUBLIC_FOLDER .'categorias/editar/'.$this->categoria['idCategorias'].'.html';
				//$data['boton'] = 'Guardar cambios';
				$this->categoria = array();
			}
			else{
				$mensaje = '$.sticky("No se pudo agregar el registro", {autoclose : 5000, position: "top-center", type: "st-error" });';
			}
		}
		elseif($this->input->server('REQUEST_METHOD') == 'POST'){
			foreach($errores as $key => $value){
				$mensaje .= '$.sticky("'. $value .'", {autoclose : 5000, position: "top-center", type: "st-error" });';
			}
		}

		$data['categoria'] = $this->categoria;

		$scripts = '
		<script>
			$(document).ready(function(){
				'. $mensaje .'
			});
		</script>
		';


		$data['categorias'] = $this->categorias_model->getSubCategoriasDisponibles();

		$this->load->view('admin_templates/header',array('title' => 'Panel de Control :: Categorias'));
		$this->load->view('admin_templates/menu_lateral',$data);
		$this->load->view('admin_templates/menu',$data);
		$this->load->view('add_edit_categoria',$data);
		$this->load->view('admin_templates/footer',array('datatable' => true,'scripts' => $scripts ));
	}

	public function editar()
	{
		if(!isLogged($this->session) or !checkRol('administrador',$this->session)){
			redirect();
		}
		$data = array();
		$errores = array();
		$mensaje = '';

		$this->categoria = $this->getData();


		// SABER SI ES UNA CATEGORIA PADRE
		if($this->is_categorias->isParentCategory($this->categoria['idCategorias'])) {
			$data['parent_category'] = true;
		} else {
			$data['parent_category'] = false;
		}
		// SABER SI DEBE MOSTRAR EL INPUT PARA ELIMINAR LA IMÁGEN
		if($data['parent_category'] == true && $this->categoria['image'] != '') {
			$data['show_del_image'] = true;
		} else {
			$data['show_del_image'] = false;
		}


		$data['titulo'] 		= 'Editar';
		$data['form_action']= PUBLIC_FOLDER_ADMIN .'categorias/editar/'. $this->categoria['idCategorias'] .'.html';
		$data['boton'] 		= 'Guardar cambios';
		$data['section']		= $this->section;

		$errores 			= $this->categorias_model->validarEditar($this->categoria);

		if($this->input->server('REQUEST_METHOD') == 'POST' and !$errores)
		{

			$categoria = $this->categoria;


			$categoria_cuando_ingrese = $this->categorias_model->getById($this->categoria);

			if($this->categorias_model->editarMejorada($this->categoria, $categoria_cuando_ingrese)) {
				$mensaje = '$.sticky("Los cambios se guardaron con éxito", {autoclose : 5000, position: "top-center", type: "st-success" });';
			}
			else{
				$mensaje = '$.sticky("No se pudieron guardar los cambios", {autoclose : 5000, position: "top-center", type: "st-error" });';
			}
		}

		elseif($this->input->server('REQUEST_METHOD') == 'POST')
		{
			foreach($errores as $key => $value){
				$mensaje .= '$.sticky("'. $value .'", {autoclose : 5000, position: "top-center", type: "st-error" });';
			}
		}

		$this->categoria = $this->categorias_model->getById($this->categoria);




		$scripts = '
		<script>
		$(document).ready(function(){
		'. $mensaje .'
		});
		</script>
		';

		$data['idCategorias'] = $this->categoria['idCategorias'];
		$data['categorias'] = $this->categorias_model->getSubCategoriasDisponibles($this->categoria);
		$data['categoria'] = $this->categoria;



		$this->load->view('admin_templates/header',array('title' => 'Panel de Control :: Categorias'));
		$this->load->view('admin_templates/menu_lateral',$data);
		$this->load->view('admin_templates/menu',$data);
		$this->load->view('add_edit_categoria',$data);
		$this->load->view('admin_templates/footer',array('datatable' => true,'scripts' => $scripts ));
	}

	public function listar()
	{
		if(!isLogged($this->session) or !checkRol('administrador',$this->session)){

			redirect();
		}


		$data = array();


		if($this->uri->segment(4) and $this->uri->segment(4) != 'pagina' and $this->uri->segment(5))
		{ 	// FILTRANDO
			$filter[$this->uri->segment(4)] = addslashes(urldecode($this->uri->segment(5)));
			$filter['value'] = addslashes(urldecode($this->uri->segment(5)));
			$filter['limit'] = limit($this->uri->segment(7,1),$this->config->item('filas_por_paginas') );
			$baseUrl = PUBLIC_FOLDER_ADMIN . "categorias/listar/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/pagina";
			$data['paginas'] = paginas($this->categorias_model->contar($filter),$this->config->item('filas_por_paginas'),$this->uri->segment(7,1), $baseUrl );
			$data['filter'] = $this->uri->segment(4);
			$data['value'] = urldecode($this->uri->segment(5));
		} else { // LISTADO DE CATEGORIAS SIN FILTROS
			$baseUrl 		= PUBLIC_FOLDER_ADMIN . "categorias/listar/pagina";
			$filter['limit'] 	= limit($this->uri->segment(5,1),$this->config->item('filas_por_paginas') );
			$data['paginas']	= paginas($this->categorias_model->contarCatPadres(),$this->config->item('filas_por_paginas'),$this->uri->segment(5,1), $baseUrl );
		}



		$data['categorias'] = $this->categorias_model->listar($filter);

		$script = '
		<script>
		$("#btn-filter").live("click",function(){
		if($("#filter :selected").val() != "" && $.trim($("#value").val()) != ""){
		window.location = "' .PUBLIC_FOLDER_ADMIN .'categorias/listar/"+$("#filter :selected").val() + "/" + encodeURI($.trim($("#value").val()));
	}
	});
	$("#btn-clean").live("click",function(){
	$("#filter").val("");
	$("#value").val("");
	window.location = "' .PUBLIC_FOLDER_ADMIN .'categorias/listar.html";
	});
	</script>
	<script type="text/javascript">
	$(document).ready(function(){
	$(".delete-tipo").click(function(){
	var id=  $(this).val();
	smoke.confirm("Esta seguro que desea eliminar el tipo seleccionado?",function(e){
	if (e){
	$.post("'. PUBLIC_FOLDER_ADMIN . 'categorias/eliminar",{id:id},function(data){
	var rst = JSON.parse(data);
	if(rst.error){
	$.sticky(rst.mensaje, {autoclose : 5000, position: "top-center", type: "st-error" });
	}
	else {
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


		$this->load->view('admin_templates/header',array('title' => 'Panel de Control :: Categorias :: Listado'));
		$this->load->view('admin_templates/menu_lateral',$data);
		$this->load->view('admin_templates/menu',$data);
		$this->load->view('listado_categorias',$data);

		$this->load->view('footer',array('datatable' => true,'scripts' => $script ));

	}

	public function eliminar()
	{
		try {
			if(!isLogged($this->session) and !checkRol('administrador', $this->session)){
				redirect('admin/login');
			}


			$this->categoria['idCategorias']= (int)$this->input->post('id');
			if(!isset($this->categoria['idCategorias']) or $this->categoria['idCategorias'] <= 0){
				redirect('admin/categorias/listar.html');
			}

			if($this->categorias_model->delete($this->categoria)){
				$json = array(
						'mensaje' => 'Se ha eliminado con &eacute;xito el registro seleccionado',
						'error' => false
				);
			}
			else {
				$json = array(
						'error' => true,
						'mensaje' => 'El registro no se pudo eliminar. Ya pertenece a una publicaci&oacute;n'
				);
			}

			echo json_encode($json);

		} catch (Exception $e) {
			$json = array(
					'error' => true,
					'mensaje' => 'El registro no se pudo eliminar. Ya pertenece a una publicaci&oacute;n'
			);
			echo json_encode($json);
		}


	}

	public function getSubCategoriasOption()
	{
		if(!isLogged($this->session)){
			redirect('admin/login');
		}

		if(!$this->uri->segment(4)) {
			redirect();
		}

		$data = "";
		$categoria['idCategorias'] = $this->uri->segment(4);
		$subCategorias = $this->categorias_model->getSubCategorias($categoria);

		if($this->input->post('idTrabajos') and $this->input->post('idTrabajos') > 0)
		{

			$this->load->model('admin_trabajos/trabajos_model');
			$trabajo['idTrabajos'] = $this->input->post('idTrabajos');
			$trabajo_categorias = $this->trabajos_model->getCategorias($trabajo);

			foreach($subCategorias as $subCategoria)
			{
				if(sizeof($trabajo_categorias)> 0){
					foreach($trabajo_categorias as $trabajo_categoria) {
						if($subCategoria['idCategorias'] == $trabajo_categoria['idCategorias']) {
							// saco los números que estan al final de $subCategoria en las 3 lineas ,que decia 1, 2 y 3.
							// $data .= '<option value="'. $subCategoria['idCategorias'] . '" selected="selected">'. $subCategoria['nombreCategoria'] .'1</option>' ;
							$data .= '<option value="'. $subCategoria['idCategorias'] . '" selected="selected">'. $subCategoria['nombreCategoria'] .'</option>' ;
						} else {
							$data .= '<option value="'. $subCategoria['idCategorias'] . '" >'. $subCategoria['nombreCategoria'] .'</option>' ;
						}
					}
				}else{
					$data .= '<option value="'. $subCategoria['idCategorias'] . '">'. $subCategoria['nombreCategoria'] .'</option>' ;
				}
			}
		}
		else
		{
			foreach($subCategorias as $subCategoria){
				$data .= '<option value="'. $subCategoria['idCategorias'] . '">'. $subCategoria['nombreCategoria'] .'</option>' ;
			}
		}



		echo $data;


	}
}


?>
