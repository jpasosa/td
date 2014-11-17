<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Categorias_model extends CI_Model {

	public function __construct(){
		parent::__construct();
	}

	public function nueva($categoria){
		try {

			$data = array(
					'nombreCategoria' => $categoria['nombreCategoria']
					);

			$this->db->insert('Categorias',$data);
			$categoria['idCategorias'] = (int)$this->db->insert_id();

			if(isset($categoria['nombreSubCategoria']))
			{
				$data = array(
							'nombreCategoria' => $categoria['nombreSubCategoria'],
							'parentId' => $categoria['idCategorias']
							);
				$this->db->insert('Categorias',$data);
			}

			// SUBCATEGORIAS
			// if(isset($categoria['parentId']) && sizeof($categoria['parentId']) > 0)
			// {
			// 	$data = array();
			// 	foreach($categoria['parentId'] AS $idSubCategorias) {
			// 		$_categoria = array(
			// 				'parentId' => $idSubCategorias,
			// 				);
			// 		$this->db->where('idCategorias',$categoria['idCategorias']);
			// 		$this->db->update('Categorias',$_categoria);
			// 	}
			// }
			// FIN SUBCATEGORIAS

			// SUBCATEGORIAS
			if(isset($categoria['parentId']) && sizeof($categoria['parentId']) > 0)
			{
				$data = array();
				foreach($categoria['parentId'] AS $idSubCategorias) {
					$categorias = array(
							'parentId' => $categoria['idCategorias'],
							);
					$this->db->where('idCategorias',$idSubCategorias);
					$this->db->update('Categorias',$categorias);
				}
			}
			// FIN SUBCATEGORIAS


			// CARGA DE LA IMÁGEN
			$image['imagen'] = $this->savePhoto($categoria['image']);
			$this->db->where('idCategorias',$categoria['idCategorias']);
			$this->db->update('Categorias',$image);


			/*
			if($categoria['idCategorias'] == 0){
				return 0;
			} else {
				return $categoria['idCategorias'];
			}
			*/

			if(!isset($categoria['idCategorias'])){
				return 0;
			}
			else{
				return $categoria['idCategorias'];
			}


		} catch (Exception $e) {
			return 0;
		}
	}

	public function editar($categoria){
		try {

			$this->db->trans_begin();

			$data = array('nombreCategoria' => $categoria['nombreCategoria']);

			$this->db->where('idCategorias',$categoria['idCategorias'] );
			$this->db->update('Categorias',$data);

			// SUBCATEGORIAS
			if(isset($categoria['nombreSubCategoria']))
			{
				$data = array(
						'nombreCategoria' => $categoria['nombreSubCategoria'],
						'parentId' => $categoria['idCategorias']
				);
				$this->db->insert('Categorias',$data);
			}
			if(isset($categoria['parentId']) and sizeof($categoria['parentId']) > 0)
			{
				$data = array();
				foreach($categoria['parentId'] as $idSubCategorias)
				{
					$_categoria = array(
							'parentId' => $categoria['idCategorias'],
					);
					$this->db->where('idCategorias',$idSubCategorias);
					$this->db->update('Categorias',$_categoria);
				}

			} else {
				$data = array('parentId' => 0);
				$this->db->where('parentId',$categoria['idCategorias']);
				$this->db->update('Categorias',$data);
			}
			// FIN SUBCATEGORIAS


			// CARGA DE LA IMÁGEN
			if($categoria['del_imagen'] == 0) {
				$image['imagen'] = $this->savePhoto($categoria['image']);
			} else {
				$image['imagen'] = '';
			}
			$this->db->where('idCategorias',$categoria['idCategorias']);
			$this->db->update('Categorias',$image);

			// FIN DE TRANSACCIONES
			if($this->db->trans_status()) {
				$this->db->trans_commit();
				return TRUE;
			} else {
				$this->db->trans_rollback();
				return FALSE;
			}


		} catch (Exception $e) {
			return FALSE;
		}
	}




	public function editarMejorada($categoria, $categoria_al_ingresar)
	{
		try {

			$this->db->trans_begin();
			$data = array('nombreCategoria' => $categoria['nombreCategoria']);

			$this->db->where('idCategorias',$categoria['idCategorias'] );
			$this->db->update('Categorias',$data);



			// COMPARO LAS SUBCATEGORIAS AL INGRESAR, CON LAS QUE QUEDARON AHORA, Y VUELO LAS QUE ELIMINARON
			if (isset($categoria_al_ingresar['subCategorias']) && count($categoria_al_ingresar['subCategorias']) > 0)
			{
				if (isset($categoria['parentId'])) {
					foreach( $categoria_al_ingresar['subCategorias'] AS $cat)
					{
						if (!in_array($cat['idCategorias'], $categoria['parentId'])) { // VUELO LAS QUE ELIMINARON
							$this->db->where('idCategorias',$cat['idCategorias'] );
							$this->db->update('Categorias',array('parentId' => 0));
						}
					}

				} else { // SAQUE TODAS LAS SUBCATEGORIAS. DEBO BORRAR TODAS.
					foreach( $categoria_al_ingresar['subCategorias'] AS $cat)
					{	// VUELO TODAS LAS SUBCATEGORIAS
						$this->db->where('idCategorias',$cat['idCategorias'] );
						$this->db->update('Categorias',array('parentId' => 0));
					}
				}

			}

			// SUBCATEGORIAS
			if(isset($categoria['nombreSubCategoria']))
			{
				$data = array(
						'nombreCategoria' => $categoria['nombreSubCategoria'],
						'parentId' => $categoria['idCategorias']
				);
				$this->db->insert('Categorias',$data);
			}
			if(isset($categoria['parentId']) and sizeof($categoria['parentId']) > 0)
			{
				$data = array();
				foreach($categoria['parentId'] as $idSubCategorias)
				{
					$_categoria = array(
							'parentId' => $categoria['idCategorias'],
					);
					$this->db->where('idCategorias',$idSubCategorias);
					$this->db->update('Categorias',$_categoria);
				}

			} else {
				$data = array('parentId' => 0);
				$this->db->where('parentId',$categoria['idCategorias']);
				$this->db->update('Categorias',$data);
			}
			// FIN SUBCATEGORIAS


			// CARGA DE LA IMÁGEN
			if($categoria['del_imagen'] == 0) {
				$image['imagen'] = $this->savePhoto($categoria['image']);
			} else {
				$image['imagen'] = '';
			}
			$this->db->where('idCategorias',$categoria['idCategorias']);
			$this->db->update('Categorias',$image);

			// FIN DE TRANSACCIONES
			if($this->db->trans_status()) {
				$this->db->trans_commit();
				return TRUE;
			} else {
				$this->db->trans_rollback();
				return FALSE;
			}


		} catch (Exception $e) {
			return FALSE;
		}
	}


	public function validarNueva($categoria){
		$errores = FALSE;
		if(!isset($categoria['nombreCategoria']) or $categoria['nombreCategoria'] == ''){
			$errores['nombreCategoria'] = 'El nombre es requerido';
		}

		return $errores;
	}

	public function validarEditar($categoria){
		$errores = FALSE;
		if(!isset($categoria['nombreCategoria']) or $categoria['nombreCategoria'] == ''){
			$errores['nombreCategoria'] = 'El nombre es requerido';
		}
		if($categoria['idCategorias'] < 1 ){
			$errores['idCategorias'] = 'El n&uacute;mero de identificaci&oacute;n es incorrecto';
		}
		return $errores;
	}

	public function existe($categoria){
		try {
			$query = $this->db->query("SELECT C.idCategorias FROM Categorias C WHERE C.nombreCategoria = ". $this->db->escape($categoria['nombreCategoria']));
			$tipo = $query->row_array();
			if($categoria->idTiposActividades() > 0){
				return TRUE;
			}
			else{
				return FALSE;
			}
		} catch (Exception $e) {
			return FALSE;
		}
	}

	public function listar($filter = NULL){
		try {
			$sql = '';
			$condicion= '';
			if(isset($filter['idCategorias'])) {
				$condicion = " AND C.idCategorias = ".(int)$filter['idCategorias']. " ";
			}
			elseif(isset($filter['nombreCategoria'])) {
				$condicion = " AND C.nombreCategoria like '%" . trim($filter['nombreCategoria']) ."%'";
			}

			$sql = 'SELECT * FROM Categorias C WHERE C.parentId = 0 '. $condicion .' ORDER BY C.nombreCategoria ASC ' . $filter['limit'];


			$query = $this->db->query($sql);
			$categorias = $query->result_array();
			if(sizeof($categorias) == 0){
				return array();
			}
			else{
				return $categorias;
			}

		} catch (Exception $e) {
			return array();
		}
	}

	public function contar($filter = NULL){
		try {
			$sql = '';
			$condicion = "";
			if(isset($filter['idCategorias'])){
				$condicion = " WHERE C.idCategorias = ".(int)$filter['idCategorias']. " ";
			}
			elseif(isset($filter['nombreCategoria'])){
				$condicion = " WHERE C.nombreCategoria like '%" . trim($filter['nombreCategoria']) ."%'";
			}

			$sql = "SELECT COUNT(C.idCategorias) as 'max' FROM Categorias C ".$condicion;

			$query = $this->db->query($sql);
			$rows = $query->row_array();
			if($rows['max'] <= 0 ){
				return 0;
			}
			else{
				return $rows['max'];
			}
		} catch (Exception $e) {
			//throw new Exception($e->getMessage());
			return 0;
		}
	}

	public function contarCatPadres($filter = NULL){
		try {
			$sql = '';
			$condicion = "";
			if(isset($filter['idCategorias'])){
				$condicion = "  AND C.idCategorias = ".(int)$filter['idCategorias']. " ";
			}
			elseif(isset($filter['nombreCategoria'])){
				$condicion = " AND C.nombreCategoria like '%" . trim($filter['nombreCategoria']) ."%'";
			}

			$sql = "SELECT COUNT(C.idCategorias) as 'max' FROM Categorias C WHERE C.parentId = 0 ".$condicion;

			$query = $this->db->query($sql);
			$rows = $query->row_array();
			if($rows['max'] <= 0 ){
				return 0;
			}
			else{
				return $rows['max'];
			}
		} catch (Exception $e) {
			//throw new Exception($e->getMessage());
			return 0;
		}
	}

	public function getById($categoria){
		try {

			$query = $this->db->query("SELECT C.* FROM Categorias C WHERE C.idCategorias = " . (int)$categoria['idCategorias']);

			$categoria = $query->row_array();
			$categoria['subCategorias'] = $this->getSubCategorias($categoria);
			return $categoria;

		} catch (Exception $e) {
			return array();
		}
	}


	public function getSubCategorias($categoria)
	{
		try {
			if(isset($categoria['idCategorias'])) {
				$query = $this->db->query("SELECT C.* FROM Categorias C WHERE C.parentId = " . (int)$categoria['idCategorias'] ." ORDER BY C.nombreCategoria ASC");
				return $query->result_array();
			} else {
				return FALSE;
			}

		} catch (Exception $e) {
			return array();
		}
	}


	public function getSubCategoriasDisponibles($categoria = NULL)
	{
		try {
			if(isset($categoria)) {
				$query = $this->db->query("SELECT C.* FROM Categorias C WHERE (C.parentId = 0 OR C.parentId = ". $categoria['idCategorias'] .") AND C.idCategorias NOT IN (SELECT parentId FROM Categorias WHERE parentId > 0)  ORDER BY C.nombreCategoria ASC");
				//$query = $this->db->query("SELECT C.* FROM Categorias C WHERE C.parentId = 0 AND C.idCategorias NOT IN (SELECT parentId FROM Categorias WHERE parentId > 0) ORDER BY C.nombreCategoria ASC");
			} else {
				$query = $this->db->query("SELECT C.* FROM Categorias C WHERE C.parentId = 0 AND C.idCategorias NOT IN (SELECT parentId FROM Categorias WHERE parentId > 0) ORDER BY C.nombreCategoria ASC");
			}

			return $query->result_array();
		} catch (Exception $e) {
			return array();
		}
	}

	public function delete($categoria){
		try {

			// VERIFICO QUE NO EXISTA RELACIÓN EN OTROS TRABAJOS, SI ES ASI NO DEBERIA BORRARLA
			if ($this->isInTrabajos($categoria['idCategorias'])) {
				return false;
			}

			$this->db->trans_begin();

			$this->db->where('idCategorias',$categoria['idCategorias']);
			$this->db->delete('Categorias');

			$this->db->where('parentId',$categoria['idCategorias']);
			$this->db->delete('Categorias');

			if($this->db->trans_status() === TRUE){
				$this->db->trans_commit();
				return TRUE;
			}
			else{
				$this->db->trans_rollback();
				return FALSE;
			}
		} catch (Exception $e) {
			$this->db->trans_rollback();
			return FALSE;
		}
	}

	public function getAll(){
		try {
			$sql = "SELECT C.* FROM Categorias C ORDER BY C.nombreCategoria ASC";
			$query = $this->db->query($sql);
			return $query->result_array();
		} catch (Exception $e) {
			return array();
		}
	}

	protected function isInTrabajos( $id_categoria )
	{
		try {

			// CONTROLO SI ESTA DENTRO DE LA TABLA TRABAJOS LA CATEGORIA
			$sql 			= "SELECT * FROM Trabajos T WHERE idCategorias_parentId = $id_categoria";
			$query 			= $this->db->query($sql);
			$find_category 	= $query->result_array();
			if (count($find_category) > 0) {
				return true;
			}

			// CONTROLO SI ESTA DENTRO DE LA TABLA TRABAJOSCATEGORIAS
			$sql 			= "SELECT * FROM TrabajosCategorias T WHERE idCategorias = $id_categoria";
			$query 			= $this->db->query($sql);
			$find_category 	= $query->result_array();

			if (count($find_category) > 0) {
				return true;
			} else {
				return false;
			}

		} catch (Exception $e) {
			return array();
		}
	}


	private function savePhoto($image)
	{
		try {
			if(isset($_FILES['image']) && !empty($image))
			{
				if(move_uploaded_file($_FILES['image']['tmp_name'], "web/uploads/categorias/iconos/$image"))
				{
					// THUMB
					$config = array();
					$config['image_library'] 	= 'gd2';
					$config['source_image']	= "web/uploads/categorias/iconos/$image";
					$config['new_image'] 	= "web/uploads/categorias/iconos/thumbs/$image";
					$config['create_thumb'] = FALSE;
					$config['maintain_ratio'] = TRUE;
					$config['width']	 		= 90;
					$config['height']			= 90;
					$this->load->library('image_lib', $config);
					$foto_thumb = $this->image_lib->resize();
					$this->image_lib->clear();
					// NORMAL
					$config = array();
					$config['image_library'] 	= 'gd2';
					$config['source_image']	= "web/uploads/categorias/iconos/$image";
					$config['create_thumb'] = FALSE;
					$config['maintain_ratio'] = TRUE;
					$config['width']	 		= 146;
					$config['height']			= 146;
					$this->image_lib->initialize($config);
					$foto_normal = $this->image_lib->resize();

					if($foto_normal && $foto_thumb ){
						return $image;
					} else{
						return $image; // TODO: aca debe retornar vacio si no pudo hacer resize
					}
				} else {
					return $image;
				}

			} else {
				return $image;
			}

		} catch (Exception $e) {
			return "";
		}
	}

}

?>