<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Templates_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->config('estados');
	}

	public function validate($contacto)
	{
		$errors = false;




		if(isset($contacto['nombre']) && $contacto['nombre'] == '') {
			$errors['nombre'] = 'El nombre es obligatorio';
		}

		if(isset($contacto['email']) && $contacto['email'] == '') {
			$errors['email'] = 'El email es obligatorio';
		}

		if(isset($contacto['comentario']) && $contacto['comentario'] == '') {
			$errors['comentario'] = 'El comentario es obligatorio';
		}


		// if(!isset($work['idCategorias_parentId'])){
		// 	$errors['idCategorias_parentId'] = 'Debe elegir una categoría';
		// }

		return $errors;
	}


}

?>
