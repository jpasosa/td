<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Repo_compras extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->config('estados');
	}


	public function getByIdForViewPedido($id_pedidos){
		try {
			$sql =  "SELECT P.idPedidos, P.fecha, P.idUsuarios AS idUsuariosComprador, P.idTrabajos, P.monto_venta_total, P.modalidad,
					T.titulo, UC.email AS email_comprador
					FROM Pedidos P
					INNER JOIN Trabajos T
						ON P.idTrabajos=T.idTrabajos
					INNER JOIN Usuarios UC
						ON P.idUsuarios=UC.idUsuarios
					WHERE P.idPedidos = $id_pedidos";

			$query	= $this->db->query($sql);
			$rows 	= $query->row_array();

			if(isset($rows) ){
				return $rows;
			} else{
				return false;
			}



		} catch (Exception $e) {
			return array();
		}
	}

	public function getById($id_pedidos){
		try {
			$sql =  "SELECT *
					FROM Pedidos P
					WHERE P.idPedidos = $id_pedidos";

			$query	= $this->db->query($sql);
			$rows 	= $query->row_array();

			if(isset($rows) ){
				return $rows;
			} else{
				return false;
			}



		} catch (Exception $e) {
			return array();
		}
	}

	// INSERTA UNA COMPRA.
	// RETURN 0  OR  ID INSERTADO
	public function insert($compra)
	{
		try {

			$insert_pedido = $this->db->insert('Pedidos', $compra);

			if($insert_pedido) {
				$insert_id 	= $this->db->insert_id();
				return $insert_id;
			} else {
				return 0;
			}

		} catch (Exception $e) {
			return array();
		}
	}

	// INSERTA UNA COMPRA (UN PEDIDO Y UNA REGALIA), HECHA POR EL ADMIN MANUALMENTE
	public function insertCompraManual($compra)
	{
		try {

			$pedido = array(	'fecha' 				=> date('Y-m-d',strtotime($compra['fecha'])),
								'idUsuarios' 			=> $compra['idUsuariosComprador'],
								'idTrabajos' 			=> $compra['idTrabajos'],
								'monto_venta_total' => $compra['monto_venta_total'],
								'modalidad' 			=> $compra['modalidad']
								);

			$insert_pedido = $this->db->insert('Pedidos', $pedido);


			if($insert_pedido)
			{
				$id_pedido 	= $this->db->insert_id();

				$regalia 	= array(	'idPedidos' 			=> $id_pedido,
										'idUsuarios' 			=> $compra['idUsuariosAutor'],
										'monto_al_autor' 	=> $compra['monto_al_autor'],
										'monto_de_venta' 	=> $compra['monto_venta_total'],
										'fecha' 				=> date('Y-m-d',strtotime($compra['fecha'])),
										'estado_regalias' 	=> $compra['regalias'],
										'notificado' 			=> $compra['notificado']
								);
				$insert_compra = $this->db->insert('Regalias', $regalia);

				if ($insert_compra) {
					if ($compra['regalias'] == 1) {
						$this->repo_regalias->updateRegalias($compra['idUsuariosAutor'], $compra['monto_al_autor']);
					}
					$compra['idPedidos'] = $id_pedido;

					return $compra;
				}

			} else {
				return false; // NO PUDO INSERTAR EL PEDIDO
			}

		} catch (Exception $e) {
			return array();
		}
	}






	public function listar_pedidos($filter = NULL)
	{
		try {
			$sql = '';
			$condicion= '';
			$estado = '';
			if(isset($filter['idTrabajos'])) {
				$condicion = " WHERE T.idTrabajos = ".(int)$filter['idTrabajos']. " AND R.idPedidos IS NULL";
			}
			elseif(isset($filter['idPedidos'])) {
				$condicion = " WHERE P.idPedidos = ".(int)$filter['idPedidos']. " AND R.idPedidos IS NULL";
			}

			elseif(isset($filter['titulo'])) {
				$condicion = " WHERE T.titulo like '%" . trim($filter['titulo']) ."%' AND R.idPedidos IS NULL";
			}

			elseif(isset($filter['fecha'])) {
				$condicion = " WHERE P.fecha like '%" . trim($filter['fecha']) ."%' AND R.idPedidos IS NULL ";
			} else {
				$condicion = " WHERE R.idPedidos IS NULL";
			}

			$sql = 'SELECT P.idPedidos, P.fecha, P.idUsuarios AS idUsuariosComprador, P.idTrabajos, P.monto_venta_total, P.modalidad,
					T.titulo, UC.email AS email_comprador
					FROM Pedidos P
					INNER JOIN Trabajos T
						ON P.idTrabajos=T.idTrabajos
					INNER JOIN Usuarios UC
						ON P.idUsuarios=UC.idUsuarios
					LEFT JOIN Regalias R
						ON P.idPedidos=R.idPedidos
						' . $condicion . ' ORDER BY P.fecha DESC ' . $filter['limit'];

			$query 		= $this->db->query($sql);
			$compras 	= $query->result_array();


			foreach ( $compras AS $k => $ped )
			{
				// EMAIL E ID DEL AUTOR
                        $author = $this->repo_usuarios->getAuthorByWork($compras[$k]['idTrabajos']);
                        $compras[$k]['idUsuariosAutor']         = $author['idUsuarios'];
                        $compras[$k]['emailUsuariosAutor']    = $author['email'];
			}



			if(sizeof($compras) == 0){
				return array();
			}else{
				return $compras;
			}

		} catch (Exception $e) {
			return array();
		}
	}


	public function listar_compras($filter = NULL)
	{
		try {
			$sql = '';
			$condicion= '';
			$estado = '';
			if(isset($filter['idCompra'])) {
				$condicion = " WHERE R.idRegalias = ".(int)$filter['idCompra']. " ";
			}
			elseif(isset($filter['idPedidos'])) {
				$condicion = " WHERE P.idPedidos = ".(int)$filter['idPedidos']. " ";
			}

			elseif(isset($filter['titulo'])) {
				$condicion = " WHERE T.titulo like '%" . trim($filter['titulo']) ."%'";
			}

			elseif(isset($filter['fecha'])) {
				$condicion = " WHERE P.fecha like '%" . trim($filter['fecha']) ."%'";
			}

			$sql = 'SELECT R.idRegalias, P.fecha, R.idPedidos, UA.email as emailAutor, R.monto_al_autor, R.monto_de_venta, R.estado_regalias, R.notificado,
							T.titulo
					FROM Regalias R
					INNER JOIN Usuarios UA
						ON R.idUsuarios = UA.idUsuarios
					INNER JOIN Pedidos P
						ON R.idPedidos = P.idPedidos
					INNER JOIN Trabajos T
						ON P.idTrabajos = T.idTrabajos
						' . $condicion . ' ORDER BY P.fecha DESC ' . $filter['limit'];

			$query = $this->db->query($sql);
			$compras = $query->result_array();


			if(sizeof($compras) == 0){
				return array();
			}else{
				return $compras;
			}

		} catch (Exception $e) {
			return array();
		}
	}



	public function contar($filter = NULL){
		try
		{
			$sql = '';
			$condicion = "";
			if(isset($filter['idTrabajos'])) {
				$condicion = " WHERE T.idTrabajos = ".(int)$filter['idTrabajos']. " ";
			}
			elseif(isset($filter['idPedidos'])) {
				$condicion = " WHERE P.idPedidos = ".(int)$filter['idPedidos']. " ";
			}

			elseif(isset($filter['titulo'])) {
				$condicion = " WHERE T.titulo like '%" . trim($filter['titulo']) ."%'";
			}

			elseif(isset($filter['fecha'])) {
				$condicion = " WHERE P.fecha like '%" . trim($filter['fecha']) ."%'";
			}


			$sql = 'SELECT COUNT(P.idPedidos) as "max"
					FROM Pedidos P
					INNER JOIN Regalias R
						ON P.idPedidos=R.idPedidos
					INNER JOIN Trabajos T
						ON P.idTrabajos=T.idTrabajos
						' . $condicion;

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

	public function contar_pedidos($filter = NULL){
		try
		{
			$sql = '';
			$condicion = "";
			if(isset($filter['idTrabajos'])) {
				$condicion = " WHERE T.idTrabajos = ".(int)$filter['idTrabajos']. " AND R.idPedidos IS NULL";
			}
			elseif(isset($filter['idPedidos'])) {
				$condicion = " WHERE P.idPedidos = ".(int)$filter['idPedidos']. " AND R.idPedidos IS NULL";
			}

			elseif(isset($filter['titulo'])) {
				$condicion = " WHERE T.titulo like '%" . trim($filter['titulo']) ."%' AND R.idPedidos IS NULL";
			}

			elseif(isset($filter['fecha'])) {
				$condicion = " WHERE P.fecha like '%" . trim($filter['fecha']) ."%' AND R.idPedidos IS NULL";
			}  else {
				$condicion = " WHERE R.idPedidos IS NULL";
			}


			$sql = 'SELECT COUNT(P.idPedidos) as "max"
					FROM Pedidos P
					INNER JOIN Trabajos T
						ON P.idTrabajos=T.idTrabajos
					LEFT JOIN Regalias R
						ON P.idPedidos=R.idPedidos
						' . $condicion;

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

	public function contar_compras ($filter = NULL){
		try
		{
			$sql = '';
			$condicion= '';
			$estado = '';
			if(isset($filter['idCompra'])) {
				$condicion = " WHERE R.idRegalias = ".(int)$filter['idCompra']. " ";
			}
			elseif(isset($filter['idPedidos'])) {
				$condicion = " WHERE P.idPedidos = ".(int)$filter['idPedidos']. " ";
			}

			elseif(isset($filter['titulo'])) {
				$condicion = " WHERE T.titulo like '%" . trim($filter['titulo']) ."%'";
			}

			elseif(isset($filter['fecha'])) {
				$condicion = " WHERE P.fecha like '%" . trim($filter['fecha']) ."%'";
			}

			$sql = 'SELECT COUNT(R.idRegalias) as "max"
					FROM Regalias R
					INNER JOIN Usuarios UA
						ON R.idUsuarios = UA.idUsuarios
					INNER JOIN Pedidos P
						ON R.idPedidos = P.idPedidos
					INNER JOIN Trabajos T
						ON P.idTrabajos = T.idTrabajos
						' . $condicion . ' ORDER BY P.fecha DESC ' . $filter['limit'];

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

	public function getCantBajadas($id_pedido){
		try {
			$sql =  "SELECT *
					FROM Pedidos P
					WHERE P.idPedidos = $id_pedido";

			$query	= $this->db->query($sql);
			$rows 	= $query->row_array();

			if(isset($rows['cant_bajadas']) ){
				return $rows['cant_bajadas'];
			} else{
				return false;
			}



		} catch (Exception $e) {
			return array();
		}
	}





}

?>
