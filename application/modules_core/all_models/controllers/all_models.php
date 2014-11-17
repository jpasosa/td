<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class All_models extends MX_Controller {


	public function __construct(){


		parent::__construct();
		// $this->load->library('session');
		$this->load->model('all_models/repo_categorias');
		$this->load->model('all_models/repo_usuarios');
		$this->load->model('all_models/repo_precios');
		$this->load->model('all_models/repo_estadostrabajos');
		$this->load->model('all_models/repo_trabajos');
	}



	public function index() {
		try {

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}


	// AJAX, SELECCIONA LAS SUBCATEGORIAS
	public function getSubCategorysOptionsAjax()
	{
		$option = '';
		// echo $select;
		$category = $this->input->post('parentId');

		$sub_categorys = $this->repo_categorias->getSubCategorysById($category);

		foreach($sub_categorys AS $cat) {
			$option .= '<option value="' . $cat['idCategorias'] . ' "> ' . $cat['nombreCategoria'] . '</option>';
		}

		echo $option;

	}

	// AJAX, SELECCIONA LAS PUBLICACIONES DE UN AUTOR
	public function getPublicacionesAjax()
	{
		$option = '';
		// echo $select;
		$id_autor = $this->input->post('id_autor');

		$publicaciones = $this->repo_usuarios->getPublicaciones($id_autor);

		foreach($publicaciones AS $publ) {
			$option .= '<option value="' . $publ['idTrabajos'] . ' "> ' . $publ['titulo'] . '</option>';
		}

		echo $option;

	}



	// AJAX, ELIMINA UNA PUBLICACION
	public function delPublicacionAjax()
	{
		$id_publicacion = $this->input->post('id_publicacion');

		$del_publicacion = $this->repo_trabajos->erase($id_publicacion);

		redirect('homepage');

	}



	// AJAX, AGREGA A FAVORITOS
	public function addFavoritos()
	{
		$id_user 		= $this->input->post('id_user');
		$id_trabajo 		= $this->input->post('id_trabajo');

		$set_favorito 	= $this->repo_trabajos->setFavorito($id_user, $id_trabajo);
		// redirect('homepage');
	}

	// AJAX, AGREGA A FAVORITOS
	public function delFavoritos()
	{
		$id_favorito 	= $this->input->post('id_favorito');

		$unset_favorito 	= $this->repo_trabajos->unsetFavorito($id_favorito);
		// redirect('homepage');
	}





}
?>
