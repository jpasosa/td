<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Repo_regalias extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->config('estados');
	}

	// TRAE EL REGISTRO DE LA COMPRA
	public function getById($id_compra){
		try {
			$sql = "SELECT *
					FROM Regalias R
					WHERE idRegalias = $id_compra";
			$query = $this->db->query($sql);
			$regalia = $query->result_array();

			if(isset($regalia[0])) {
				return $regalia[0];
			} else {
				return false;
			}

		} catch (Exception $e) {
			return array();
		}
	}


	// INSERTA UNA REGALIA.
	// RETURN TRUE O FALSE, DEPENDIENDO DE SI LO PUDO INSERTAR O NO.
	public function insert($regalia){
		try {

			$insert_regalia = $this->db->insert('Regalias', $regalia);

			if($insert_regalia) {
				return true;
			} else {
				return false;
			}

		} catch (Exception $e) {
			return array();
		}
	}

	// SELECCIONA LA REGALÍA, PASANDOLE L ID DEL PEDIDO
	public function getByIdPedido($id_pedido){
		try {
			$sql = "SELECT *
					FROM Regalias R
					WHERE idPedidos = $id_pedido";
			$query = $this->db->query($sql);
			$regalia = $query->result_array();
			if(isset($regalia[0])) {
				return $regalia[0];
			} else {
				return false;
			}


		} catch (Exception $e) {
			return array();
		}
	}

	// lista segun el autor, todas sus regalias que fueron registradas, pero todavía no recibió pago por esas
	public function listarRegaliasByAutor($id_user){
		try {
			$sql = "SELECT R.idRegalias, R.fecha, T.titulo, R.monto_al_autor
					FROM Regalias R
					INNER JOIN Pedidos P
						ON R.idPedidos=P.idPedidos
					INNER JOIN Trabajos T
						ON P.idTrabajos=T.idTrabajos
					WHERE R.idUsuarios = $id_user AND R.estado_regalias = 1
					ORDER BY R.fecha";
			$query = $this->db->query($sql);
			$regalia = $query->result_array();
			if(isset($regalia[0])) {
				return $regalia;
			} else {
				return false;
			}


		} catch (Exception $e) {
			return array();
		}
	}

	// MONTO TOTAL DE REGALIAS QUE EL AUTOR TIENE ASIGNADO, PERO TODAVÍA NO SE LE HIZO EL PAGO
	public function totalRegalias($id_user){
		try {
			$sql = "SELECT SUM(R.monto_al_autor) AS total
					FROM Regalias R
					INNER JOIN Pedidos P
						ON R.idPedidos=P.idPedidos
					INNER JOIN Trabajos T
						ON P.idTrabajos=T.idTrabajos
					WHERE R.idUsuarios = $id_user AND R.estado_regalias = 1
					ORDER BY R.fecha";
			$query = $this->db->query($sql);
			$regalia = $query->result_array();
			if(isset($regalia[0])) {
				return $regalia[0]['total'];
			} else {
				return false;
			}


		} catch (Exception $e) {
			return array();
		}
	}

	//
	public function checkDataAgregarCompra($compras){
		try {

			if ($compras['fecha'] == '') {
				$compras['fecha'] = date('d-m-Y');
			}

			return $compras;


		} catch (Exception $e) {
			return array();
		}
	}

	//
	public function getTituloById($id_regalia){
		try {
			$sql = "SELECT T.titulo
					FROM Regalias R
					INNER JOIN Pedidos P
						ON R.idPedidos = P.idPedidos
					INNER JOIN Trabajos T
						ON P.idTrabajos=T.idTrabajos
					WHERE R.idRegalias = $id_regalia";
			$query = $this->db->query($sql);
			$titulo = $query->result_array();
			if(isset($titulo[0])) {
				return $titulo[0]['titulo'];
			} else {
				return false;
			}


		} catch (Exception $e) {
			return array();
		}
	}


	// CONTROLA SI EXISTE LA REGALIA PASANDOLE UN ID DEL PEDIDO
	public function existRegaliaByPedido($id_pedido)
	{
		try {
			$sql = "SELECT *
					FROM Regalias R
					WHERE idPedidos = $id_pedido";
			$query = $this->db->query($sql);
			$exist = $query->result_array();
			if(count($exist) == 1) {
				return true;
			} else {
				return false;
			}

		} catch (Exception $e) {
			return array();
		}
	}

	// CONTROLA SI EXISTE LA REGALIA PASANDOLE UN ID DEL PEDIDO
	public function editar($compras)
	{
		try {

			if ($compras['regalias'] == 1)
			{
				$update_regalias 			= $this->updateRegalias($compras['idUsuariosAutor'], $compras['monto_al_autor']);
				$compras['estado_regalias']	= 1;
			} else {
				$compras['estado_regalias']	= 0;
			}

			$regalias = array(	'idPedidos' 			=> $compras['idPedidos'],
								'idUsuarios' 			=> $compras['idUsuariosAutor'],
								'monto_al_autor'	=> $compras['monto_al_autor'],
								'monto_de_venta'	=> $compras['monto_venta_total'],
								'estado_regalias'	=> $compras['estado_regalias'],
								'fecha'				=> date('Y-m-d',strtotime($compras['fecha'])),
								'notificado' 			=> $compras['notificado']
								);

			$this->db->insert('Regalias', $regalias);

			if($this->db->affected_rows() == 1) {
				if ($compras['notificado'] == 1) {
					$notify_author = $this->notifyAuthor($compras['emailUsuariosAutor'], $compras['tituloPublicacion'], $compras['monto_al_autor']);
				}
				return true;
			} else {
				return false;
			}

		} catch (Exception $e) {
			return array();
		}
	}

	public function updateRegalias( $id_user, $regalias )
	{
		$regalias_actuales = $this->repo_usuarios->getRegalia($id_user);
		// ACTUALIZA LAS REGALÍAS
		$regalias_totales = $regalias_actuales + $regalias;
		$regalias_totales = (int)$regalias_totales;

		if ( $regalias_totales > ( $this->config->item('param_monto_regalias') ) ) {
			// echo 'entra'; die();
			$this->notifyAlarmaRegalias($id_user);
		}

		$this->db->where('idUsuarios', $id_user);
		$this->db->update('Usuarios', array('regalias' => $regalias_totales));
		if($this->db->affected_rows() == 1) {
			return true;
		} else {
			return false;
		}
	}

	public function setNotificadoTrue( $id_regalia )
	{
		$this->db->where('idRegalias', $id_regalia);
		$this->db->update('Regalias', array('notificado' => 1));
		if($this->db->affected_rows() == 1) {
			return true;
		} else {
			return false;
		}
	}

	public function setEstadoRegaliasTrue( $id_regalia )
	{
		$this->db->where('idRegalias', $id_regalia);
		$this->db->update('Regalias', array('estado_regalias' => 1));
		if($this->db->affected_rows() == 1) {
			return true;
		} else {
			return false;
		}
	}


	protected function notifyAuthor($author_mail, $work_title, $regalias)
	{
		try {
			$this->load->library('email');
			$this->email->from($this->config->item('email_admin'));
			$this->email->to($author_mail, $this->config->item('email_programador'), $this->config->item('email_sistemas')); // TODO: volar los dos últimos
			$this->email->subject('Regalías asignadas por la venta de una publicación');
			$this->email->message($author_mail . ' una publicación tuya de título ' . $work_title . ' fue vendida y asignamos regalías por ' . $regalias);
			if($this->email->send())	 {
				return true; // Pudo enviar el mail correctamente
			}else {
				return false;
			}
			// echo $this->email->print_uger();
		} catch (Exception $e) {
			die('No pudo enviar el mail por algún motivo');
		}


	}

	protected function notifyAlarmaRegalias($id_user)
	{
		try {
			$this->email->from($this->config->item('email_admin'));
			$this->email->to($this->config->item('email_programador'), $this->config->item('email_sistemas'), $this->config->item('email_admin'));
			$this->email->subject('Usuario pasó la alarma del monto de regalías');
			$this->email->message('El usuario de id ' . $id_user . ' ha excedido la alarma del monto de regalías.');

			if($this->email->send())	 {
				return true; // Pudo enviar el mail correctamente
			}else {
				return false;
			}
			// echo $this->email->print_uger();
		} catch (Exception $e) {
			die('No pudo enviar el mail por algún motivo');
		}


	}




}

?>
