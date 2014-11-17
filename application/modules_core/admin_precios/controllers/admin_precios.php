<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_precios extends MX_Controller {

	protected $precio;

	public function __construct() {
		parent::__construct();
		$this->load->model('admin_precios/precios_model');

	}

	public function index()
	{
		$this->listing();
	}

	protected  function getData() {
		$precios = array();


		if($this->uri->segment(4)) {
			$idPrecios = (int)$this->uri->segment(4);
			$precios['idPrecios'] = $idPrecios;
		}

		if($this->input->post('precio')){
			$precios['precio'] = $this->input->post('precio');
		} else {
			$precios['precio'] = '';
		}

		return $precios;
	}


	public function add()
	{
		if(!isLogged($this->session) or !checkRol('administrador',$this->session)) {
			redirect('admin/login');
		}
		$data 		= array();
		$errores 	= array();
		$mensaje	= '';

		$data['titulo'] 		= 'Agregar nueva';
		$data['form_action'] = PUBLIC_FOLDER_ADMIN .'precios/nuevo.html';
		$data['boton'] 		= 'Agregar';
		$this->precio 		= $this->getData();
		$errores 			= false;		// Por ahora no validamos. Puede ser una mejora.
		// $errores = $this->categorias_model->validarNueva($this->categoria);


		if($this->input->server('REQUEST_METHOD') == 'POST' and !$errores)
		{
			$this->precio['idPrecios'] = (int) $this->precios_model->add($this->precio);
			if($this->precio['idPrecios'] > 0){
				$mensaje = '$.sticky("Se ha creado con éxito", {autoclose : 5000, position: "top-center", type: "st-success" });';
				//$data['idCategorias'] = $this->categoria['idCategorias'];
				//$data['titulo'] = 'Editar tipos';
				//$data['form_action'] = PUBLIC_FOLDER .'categorias/editar/'.$this->categoria['idCategorias'].'.html';
				//$data['boton'] = 'Guardar cambios';
				$this->precio = array();
			}
			else{
				$mensaje = '$.sticky("No se pudo agregar el registro", {autoclose : 5000, position: "top-center", type: "st-error" });';
			}
		}
		elseif($this->input->server('REQUEST_METHOD') == 'POST'){ // por ahora sin errores
			foreach($errores as $key => $value){
				$mensaje .= '$.sticky("'. $value .'", {autoclose : 5000, position: "top-center", type: "st-error" });';
			}
		}

		$data['precio'] = $this->precio;

		$scripts = '
		<script>
			$(document).ready(function(){
				'. $mensaje .'
			});
		</script>
		';


		// $data['precios'] = $this->precios_model->getSubCategoriasDisponibles();

		$this->load->view('admin_templates/header',array('title' => 'Panel de Control :: Categorias'));
		$this->load->view('admin_templates/menu_lateral',$data);
		$this->load->view('admin_templates/menu',$data);
		$this->load->view('add_edit_precio',$data);
		$this->load->view('admin_templates/footer',array('datatable' => true,'scripts' => $scripts ));
	}

	public function edit() {
		if(!isLogged($this->session) or !checkRol('administrador',$this->session)){
			redirect('admin/login');
		}
		$data 				= array();
		$errores 			= array();
		$mensaje 			= '';
		$this->precio 		= $this->getData();
		$data['titulo'] 		= 'Editar';
		$data['form_action'] = PUBLIC_FOLDER_ADMIN .'precios/editar/'. $this->precio['idPrecios'] .'.html';
		$data['boton'] 		= 'Guardar cambios';



		//$errores = $this->precios_model->validarEditar($this->precio);

		if($this->input->server('REQUEST_METHOD') == 'POST' and !$errores) // sin errores
		{
			if($this->precios_model->edit($this->precio)){
				$mensaje = '$.sticky("Los cambios se guardaron con éxito", {autoclose : 5000, position: "top-center", type: "st-success" });';
			}
			else{
				$mensaje = '$.sticky("No se pudieron guardar los cambios", {autoclose : 5000, position: "top-center", type: "st-error" });';
			}
		}
		elseif($this->input->server('REQUEST_METHOD') == 'POST'){ // por el momento no va a validar
			foreach($errores as $key => $value){
				$mensaje .= '$.sticky("'. $value .'", {autoclose : 5000, position: "top-center", type: "st-error" });';
			}
		}

		$this->precio = $this->precios_model->getById($this->precio);

		$scripts = '
		<script>
		$(document).ready(function(){
		'. $mensaje .'
		});
		</script>
		';

		$data['idPrecios'] = $this->precio['idPrecios'];
		// $data['categorias'] = $this->precios_model->getSubCategoriasDisponibles($this->precio);
		$data['precio'] = $this->precio;

		$this->load->view('admin_templates/header',array('title' => 'Panel de Control :: Categorias'));
		$this->load->view('admin_templates/menu_lateral',$data);
		$this->load->view('admin_templates/menu',$data);
		$this->load->view('add_edit_precio',$data);
		$this->load->view('admin_templates/footer',array('datatable' => true,'scripts' => $scripts ));
	}

	public function listing()
	{
		if(!isLogged($this->session) or !checkRol('administrador',$this->session)){
			redirect('admin/login');
		}

		$data = array();

		if($this->uri->segment(4) and $this->uri->segment(4) != 'pagina' and $this->uri->segment(5))
		{ // FILTRANDO
			echo 'estamos por ahora sin filtros'; die();
			// $filter[$this->uri->segment(4)] = addslashes(urldecode($this->uri->segment(5)));
			// $filter['value'] = addslashes(urldecode($this->uri->segment(5)));
			// $filter['limit'] = limit($this->uri->segment(7,1),$this->config->item('filas_por_paginas') );
			// $baseUrl = PUBLIC_FOLDER_ADMIN . "categorias/listar/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/pagina";
			// $data['paginas'] = paginas($this->categorias_model->contar($filter),$this->config->item('filas_por_paginas'),$this->uri->segment(7,1), $baseUrl );
			// $data['filter'] = $this->uri->segment(4);
			// $data['value'] = urldecode($this->uri->segment(5));
		} else { 	// LISTADO DE PRECIOS SIN FILTROS
			$baseUrl = PUBLIC_FOLDER_ADMIN . "precios/listar/pagina";
			$filter['limit'] = limit($this->uri->segment(5,1),$this->config->item('filas_por_paginas') );
			$data['paginas'] = paginas($this->precios_model->contar(),$this->config->item('filas_por_paginas'),$this->uri->segment(5,1), $baseUrl );
		}


		$data['precios'] = $this->precios_model->listing($filter);

		$script = '
		<script>
			$("#btn-filter").live("click",function() {
				if($("#filter :selected").val() != "" && $.trim($("#value").val()) != "") {
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
			$(document).ready(function() {
				$(".delete-tipo").click(function() {
					var id =  $(this).val();
					smoke.confirm("Esta seguro que desea eliminar el precio seleccionado?",function(e) {
						if (e){
							$.post("'. PUBLIC_FOLDER_ADMIN . 'precios/eliminar",{
									id: id
							},function(data) {
								var rst = JSON.parse(data);
								if(rst.error) {
									$.sticky(rst.mensaje, {autoclose : 5000, position: "top-center", type: "st-error" });
								}else{
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


		$this->load->view('admin_templates/header',array('title' => 'Panel de Control :: Precios :: Listado'));
		$this->load->view('admin_templates/menu_lateral',$data);
		$this->load->view('admin_templates/menu',$data);
		$this->load->view('list_precios',$data);

		$this->load->view('footer',array('datatable' => true,'scripts' => $script ));

	}

	public function erase()
	{
		try {
			if(!isLogged($this->session) && !checkRol('administrador', $this->session)) {
				redirect('admin/login');
			}

			$this->precio['idPrecios'] = (int)$this->input->post('id');


			if(!isset($this->precio['idPrecios']) || $this->precio['idPrecios'] <= 0){
				redirect('admin/precios/listar');
			}

			if($this->precios_model->erase($this->precio)){
				$json = array(
						'mensaje' => 'Se ha eliminado con éxito el registro seleccionado',
						'error' => false
				);
			}else{
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


}


?>
