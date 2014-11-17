<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Models_tucuenta extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->config('estados');
	}


	public function validate($data_validate)
	{
		try
		{

			$errors 		= false;

			$editorial 	= $data_validate['editorial'];
			$web 		= $data_validate['web'];
			$solicitar 	= $data_validate['solicitar'];

			if ( $editorial == '')		$errors['editorial'] 	= 'Debe completar el nombre de la editorial';
			if ( $web == '')			$errors['web'] 		= 'Debe completar el nombre de la web';
			if ( $solicitar == false)	$errors['solicitar'] 	= 'Debe hacer click en solicitar';

			return $errors;

		} catch (Exception $e) {
			return array();
		}
	}

	public function enviarSolicitud( $solicitar, $author )
	{
		try
		{
			$this->email->from($this->config->item('email_admin'));
			$this->email->to($this->config->item('email_admin'), $this->config->item('email_programador'), $this->config->item('email_sistemas')); // TODO: volar los dos últimos
			$this->email->subject('Solicita acceso como editorial');
			$this->email->message($author['email'] . ', ha solicitado el acceso a una cuenta como editorial. Su web es '
							. $solicitar['web'] . ' y el nombre de la editorial es ' . $solicitar['editorial'] . '.');
			if($this->email->send())
			{
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
