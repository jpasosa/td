<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_estadisticas extends CI_Class
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('all_models/repo_pagos');
	}

	public function index()
	{

	}

	public function public_mas_visitadas()
	{
		if(!isLogged($this->session)) {
			redirect('admin/login');
		}

		$data 			= array();
		$baseUrl 		= PUBLIC_FOLDER_ADMIN . "estadisticas/public_mas_visitadas/pagina";
		$filter['limit'] 	= limit($this->uri->segment(5,1),$this->config->item('filas_por_paginas') );
		$data['paginas']	= paginas($this->repo_trabajos->contar(),$this->config->item('filas_por_paginas'),$this->uri->segment(5,1), $baseUrl );

		$mas_visitadas 			= $this->repo_trabajos->getPublicMasVisitadas($filter);
		$data['mas_visitadas'] 	= $mas_visitadas;

		$script = '';
		// LEVANTO VISTAS
		$this->load->view('admin_templates/header',array('title' => 'Panel de Control :: Trabajos Más visitados'));
		$this->load->view('admin_templates/menu_lateral', $data);
		$this->load->view('admin_templates/menu', $data);
		$this->load->view('public_mas_visitadas', $data);
		$this->load->view('admin_templates/footer',array('datatable' => true, 'scripts' => $script ));

	}

	public function autores_mas_visitados()
	{
		if(!isLogged($this->session)) {
			redirect('admin/login');
		}

		$filter = '';

		$data 			= array();
		$baseUrl 		= PUBLIC_FOLDER_ADMIN . "estadisticas/autores_mas_visitados/pagina";
		$filter['limit'] 	= limitForArray($this->uri->segment(5,1),$this->config->item('filas_por_paginas') );
		$data['paginas']	= paginas($this->repo_trabajos->contarAutoresMasVisitados(),$this->config->item('filas_por_paginas'),$this->uri->segment(5,1), $baseUrl );

		$mas_visitados 			= $this->repo_trabajos->getAutoresMasVisitados($filter);
		$data['mas_visitados'] 	= $mas_visitados;

		$script = '';
		// LEVANTO VISTAS
		$this->load->view('admin_templates/header',array('title' => 'Panel de Control :: Trabajos Más visitados'));
		$this->load->view('admin_templates/menu_lateral', $data);
		$this->load->view('admin_templates/menu', $data);
		$this->load->view('autores_mas_visitados', $data);
		$this->load->view('admin_templates/footer',array('datatable' => true, 'scripts' => $script ));

	}

	public function public_mas_vendidas()
	{
		if(!isLogged($this->session)) {
			redirect('admin/login');
		}

		$data 			= array();
		$baseUrl 		= PUBLIC_FOLDER_ADMIN . "estadisticas/public_mas_vendidas/pagina";
		$filter['limit'] 	= limitForArray($this->uri->segment(5,1),$this->config->item('filas_por_paginas') );
		$data['paginas']	= paginas($this->repo_trabajos->contarMasVendidas(),$this->config->item('filas_por_paginas'),$this->uri->segment(5,1), $baseUrl );

		$mas_vendidas 			= $this->repo_trabajos->getPublicMasVendidas($filter);
		$data['mas_vendidas'] 	= $mas_vendidas;

		$script = '';
		// LEVANTO VISTAS
		$this->load->view('admin_templates/header',array('title' => 'Panel de Control :: Trabajos Más visitados'));
		$this->load->view('admin_templates/menu_lateral', $data);
		$this->load->view('admin_templates/menu', $data);
		$this->load->view('public_mas_vendidas', $data);
		$this->load->view('admin_templates/footer',array('datatable' => true, 'scripts' => $script ));
	}

	public function autores_mas_vendidos()
	{
		if(!isLogged($this->session)) {
			redirect('admin/login');
		}

		$data 			= array();
		$baseUrl 		= PUBLIC_FOLDER_ADMIN . "estadisticas/autores_mas_vendidos/pagina";
		$filter['limit'] 	= limitForArray($this->uri->segment(5,1),$this->config->item('filas_por_paginas') );
		$data['paginas']	= paginas($this->repo_trabajos->contar_autores_mas_vendidos(),$this->config->item('filas_por_paginas'),$this->uri->segment(5,1), $baseUrl );
		// $data['tot_reg'] = $this->repo_trabajos->RegaliasTotales();

		// LEVANTO ESTO PARA OBTENER LOS TOTALES, AUNQ UE ESTE PAGINANDO
		$autores_mas_vendidos 		= $this->repo_trabajos->getAutoresMasVendidos(null);
		if (isset($autores_mas_vendidos[0])) {
			$data['regalias_totales_pagas'] 		= $autores_mas_vendidos[0]['reg_pagadas'];
			$data['regalias_totales_no_pagas'] 	= $autores_mas_vendidos[0]['reg_no_pagadas'];
		}

		// ESTO ES LO QUE SIRVE
		$autores_mas_vendidos 		= $this->repo_trabajos->getAutoresMasVendidos($filter);
		$data['autores_mas_vendidos'] 	= $autores_mas_vendidos;




		$script = '';

		// LEVANTO VISTAS
		$this->load->view('admin_templates/header',array('title' => 'Panel de Control :: Trabajos Más visitados'));
		$this->load->view('admin_templates/menu_lateral', $data);
		$this->load->view('admin_templates/menu', $data);
		$this->load->view('autores_mas_vendidos', $data);
		$this->load->view('admin_templates/footer',array('datatable' => true, 'scripts' => $script ));
	}


}


?>
