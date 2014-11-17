<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


// Helper para sesiones, re-utilizo del proyecto viejo.


function fillSession($data,$session){
    $my_data = array(
                            'idUsuarios' => $data['idUsuarios'],
                            'userName' => $data['email'],
                            'rolKey' => $data['rolKey'],
                            'idRoles' => $data['idRoles'],
                            'permisos' => $data['permisos'],
                            'foto' => $data['avatar']
                            );
    $session->set_userdata($my_data);
    unset($data);
    unset($my_data);
}


function checkRol($rol,$session){
    if(ROL_KEY == $session->userdata('rolKey')) {
    	return true;
    }
    elseif ($rol == $session->userdata('rolKey')) {
    	return true;
    }else {
    	return false;
    }
}

function getPerm($permiso,$session) {
    $permiso = strtolower($permiso);
    $data = $session->userdata('permisos');
    foreach($data['permisos'] as $miPermiso) {
            if($permiso == strtolower($miPermiso['permKey']) ) {
                    return $miPermiso['permValue'];
            }
    }
    return false;
}


function isLogged($session){
    if($session->userdata('idUsuarios')) {
    	return true;
    }else {
    	return false;
    }
}

function isAdmin($session){
    $roles = $session->userdata('permisos');
    if($roles['idRoles'] == 1) {
        return true;
    }else {
        return false;
    }
}



?>
