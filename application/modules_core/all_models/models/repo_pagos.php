<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Repo_pagos extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->config('estados');
	}

	public function listarUsuariosRegalias($filter = NULL)
	{
		try {
			$sql = '';
			$condicion= '';
			$estado = '';
			if(isset($filter['idUsuarios'])) {
				$condicion = " AND U.idUsuarios = ".(int)$filter['idUsuarios']. " ";
			}
			// elseif(isset($filter['idPedidos'])) {
			// 	$condicion = " WHERE P.idPedidos = ".(int)$filter['idPedidos']. " ";
			// }

			// elseif(isset($filter['titulo'])) {
			// 	$condicion = " WHERE T.titulo like '%" . trim($filter['titulo']) ."%'";
			// }

			// elseif(isset($filter['fecha'])) {
			// 	$condicion = " WHERE P.fecha like '%" . trim($filter['fecha']) ."%'";
			// }

			$sql = 'SELECT *
					FROM Usuarios U
					WHERE U.estado = 1 AND U.regalias != 0 ' . $condicion .
					'ORDER BY U.regalias DESC ' . $filter['limit'];

			$query = $this->db->query($sql);
			$usuarios_regalias = $query->result_array();


			if(sizeof($usuarios_regalias) == 0){
				return array();

			}else{



				return $usuarios_regalias;
			}

		} catch (Exception $e) {
			return array();
		}
	}


	public function selectUsuarios()
	{
		try {
			$sql = 'SELECT *
					FROM Usuarios U
					WHERE U.estado = 1 AND U.regalias != 0';

			$query = $this->db->query($sql);
			$usuarios = $query->result_array();


			if(sizeof($usuarios) == 0){
				return array();

			}else{
				return $usuarios;
			}

		} catch (Exception $e) {
			return array();
		}
	}


	public function contar($filter = NULL)
	{
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


			$sql = 'SELECT COUNT(U.idUsuarios) as "max"
					FROM Usuarios U
					WHERE U.estado = 1 AND U.regalias != 0
					ORDER BY U.regalias DESC ' . $filter['limit'];

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

	// GENERA EL PAGO DEL AUTOR
	public function generatePago($regalias, $monto_total, $id_autor)
	{
		try {

			$pago = array(	'idUsuarios' 		=> $id_autor,
							'monto_total' 	=> $monto_total,
							'fecha_pago' 	=> date('Y-m-d')
							);
			$insert_pago = $this->db->insert('Pagos', $pago);

			if($insert_pago)
			{
				$id_pago 	= $this->db->insert_id();
				foreach ($regalias AS $reg)
				{
					$pagos_regalias	= array(	'idPagos' 	=> $id_pago,
												'idRegalias' 	=> $reg['idRegalias'] );
					$insert_pag_reg	= $this->db->insert('PagosRegalias', $pagos_regalias);
					if ($insert_pag_reg) { // PASO DE ESTADO LA REGALIA, POR QUE YA FUE GENERADO EL PAGO PARA ESA REGALÍA
						$this->db->where('idRegalias', $reg['idRegalias']);
						$this->db->update('Regalias', array('estado_regalias' => 2));
					}
					// TODO: podemos comprobar que vaya insertando bien los registros, por las dudas.
				}
				$this->db->where('idUsuarios', $id_autor);
				$this->db->update('Usuarios', array('regalias' => 0));

				return true;

			} else {
				return false; // NO PUDO INSERTAR EL PAGO
			}

		} catch (Exception $e) {
			return array();
		}
	}

	public function getPagosRealizados($filter = NULL)
	{
		try {
			// $sql = '';
			// $condicion= '';
			// $estado = '';
			// if(isset($filter['idTrabajos'])) {
			// 	$condicion = " WHERE T.idTrabajos = ".(int)$filter['idTrabajos']. " ";
			// }
			// elseif(isset($filter['idPedidos'])) {
			// 	$condicion = " WHERE P.idPedidos = ".(int)$filter['idPedidos']. " ";
			// }

			// elseif(isset($filter['titulo'])) {
			// 	$condicion = " WHERE T.titulo like '%" . trim($filter['titulo']) ."%'";
			// }

			// elseif(isset($filter['fecha'])) {
			// 	$condicion = " WHERE P.fecha like '%" . trim($filter['fecha']) ."%'";
			// }

			$sql = 'SELECT *
					FROM Pagos P
					INNER JOIN Usuarios U
						ON P.idUsuarios=U.idUsuarios
					ORDER BY P.fecha_pago DESC ' . $filter['limit'];

			$query = $this->db->query($sql);
			$pagos = $query->result_array();


			if(isset($pagos[0])){
				return $pagos;
			}else{
				return array();
			}

		} catch (Exception $e) {
			return array();
		}
	}

	public function getRegaliasPagadas($id_user)
	{
		try {
			$sql = "SELECT SUM(P.monto_total) as regalias_pagadas
					FROM Pagos P
					WHERE P.idUsuarios = $id_user"
					;

			$query = $this->db->query($sql);
			$pagos = $query->result_array();


			// if ($id_user == 35) {



			// 	//
			// 	// Debagueo un objeto / arreglo / variable
			// 	//
			// 	echo ' <br/> <div style="font-weight: bold; color: green;"> $pagos: </div> <pre>' ;
			// 	echo '<div style="color: #3741c6;">';
			// 	if(is_array($pagos)) {
			// 	    print_r($pagos);
			// 	}else {
			// 	var_dump($pagos);
			// 	}
			// 	echo '</div>';
			// 	echo '</pre>';
			// 	die('--FIN--DEBUGEO----');



			// }





			if(isset($pagos[0])){
				if ($pagos[0]['regalias_pagadas'] == '') {
					return 0;
				} else {
					return $pagos[0]['regalias_pagadas'];
				}
			}else{
				return 0;
			}

		} catch (Exception $e) {
			return array();
		}
	}

	public function getAutor($id_pago)
	{
		try {
			$sql = "SELECT *
					FROM Pagos P
					INNER JOIN Usuarios U
						ON P.idUsuarios=U.idUsuarios
					WHERE P.idPagos = $id_pago;";

			$query = $this->db->query($sql);
			$autor = $query->result_array();

			if(isset($autor[0])){
				return $autor[0]['nombre'] . ' ' . $autor[0]['apellido'];
			}else{
				return array();
			}

		} catch (Exception $e) {
			return array();
		}
	}


	public function detallePagos($id_pago)
	{
		try {
			// $sql = '';
			// $condicion= '';
			// $estado = '';
			// if(isset($filter['idTrabajos'])) {
			// 	$condicion = " WHERE T.idTrabajos = ".(int)$filter['idTrabajos']. " ";
			// }
			// elseif(isset($filter['idPedidos'])) {
			// 	$condicion = " WHERE P.idPedidos = ".(int)$filter['idPedidos']. " ";
			// }

			// elseif(isset($filter['titulo'])) {
			// 	$condicion = " WHERE T.titulo like '%" . trim($filter['titulo']) ."%'";
			// }

			// elseif(isset($filter['fecha'])) {
			// 	$condicion = " WHERE P.fecha like '%" . trim($filter['fecha']) ."%'";
			// }

			$sql = "SELECT PR.idPagosRegalias, R.fecha, R.monto_al_autor, T.titulo
					FROM PagosRegalias PR
					INNER JOIN Regalias R
						ON PR.idRegalias=R.idRegalias
					INNER JOIN Pedidos PED
						ON R.idPedidos=PED.idPedidos
					INNER JOIN Trabajos T
						ON PED.idTrabajos=T.idTrabajos
					INNER JOIN Pagos P
						ON PR.idPagos=P.idPagos
					WHERE PR.idPagos = $id_pago";

			$query = $this->db->query($sql);
			$pagos = $query->result_array();


			if(isset($pagos[0])){
				return $pagos;
			}else{
				return array();
			}

		} catch (Exception $e) {
			return array();
		}
	}

	public function getMontoTotal($id_pago)
	{
		try {
			// $sql = '';
			// $condicion= '';
			// $estado = '';
			// if(isset($filter['idTrabajos'])) {
			// 	$condicion = " WHERE T.idTrabajos = ".(int)$filter['idTrabajos']. " ";
			// }
			// elseif(isset($filter['idPedidos'])) {
			// 	$condicion = " WHERE P.idPedidos = ".(int)$filter['idPedidos']. " ";
			// }

			// elseif(isset($filter['titulo'])) {
			// 	$condicion = " WHERE T.titulo like '%" . trim($filter['titulo']) ."%'";
			// }

			// elseif(isset($filter['fecha'])) {
			// 	$condicion = " WHERE P.fecha like '%" . trim($filter['fecha']) ."%'";
			// }

			$sql = "SELECT SUM(R.monto_al_autor) AS total
					FROM PagosRegalias PR
					INNER JOIN Regalias R
						ON PR.idRegalias=R.idRegalias
					INNER JOIN Pedidos PED
						ON R.idPedidos=PED.idPedidos
					INNER JOIN Trabajos T
						ON PED.idTrabajos=T.idTrabajos
					INNER JOIN Pagos P
						ON PR.idPagos=P.idPagos
					WHERE PR.idPagos = $id_pago";

			$query = $this->db->query($sql);
			$monto = $query->result_array();


			if(isset($monto[0])){
				return $monto[0]['total'];
			}else{
				return array();
			}

		} catch (Exception $e) {
			return array();
		}
	}



}

?>
