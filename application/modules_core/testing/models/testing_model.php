<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Testing_model extends CI_Model {



    public function __contruct(){
        parent::__construct();
    }




    public function get($usuario) {
        $this->load->model('permisos/permisos_model');
        try {
            $condicion = "";

            if(isset($usuario['idUsuarios'])){
                    $condicion = "AND U.idUsuarios = ". (int)$usuario['idUsuarios'];
            }elseif(isset($usuario['email'])){
                    $condicion = "AND U.email = ". $this->db->escape($usuario['email']);
            }else {
                    return NULL;
            }

            $query = $this->db->query("
                                        SELECT U.*,R.key as 'rolKey',R.descripcion as 'rolDescripcion' FROM Usuarios U,Roles R
                                        WHERE U.idRoles = R.idRoles ".$condicion);
            unset($usuario);
            $usuario = $query->row_array();

            if(!isset($usuario)){
                    return NULL;
            }

            $usuario['permisos'] = $this->permisos_model->get($usuario);
            return $usuario;

        } catch (Exception $e) {
            unset($usuario);
            throw new Exception($e->getMessage());
        }
    }





    public function update($usuario,$isAdmin = false){
        try {
            if(isset($usuario['fileFoto']['newFileName']) ) {
                    $this->procesarFoto($usuario['fileFoto']);
                    $foto = $usuario['fileFoto']['newFileName'];
                    $this->db->set('avatar',$foto);
            }

            if(isset($usuario['fileFoto']['old_foto'])){
                    $this->deleteFoto($usuario['fileFoto']);
                    $this->db->set('avatar','');
            }

            if(isset($usuario['fecha'])){
                    $this->db->set('fecha',date('Y-m-d',strtotime($usuario['fecha'])));
            }

            if(isset($usuario['lugar'])){
                    $this->db->set('lugar',$usuario['lugar']);
            }

            if(isset($usuario['intereses'])){
                    $this->db->set('intereses',$usuario['intereses']);
            }
            if(isset($usuario['profesion'])){
                    $this->db->set('profesion',$usuario['profesion']);
            }

            if(isset($usuario['biografia'])){
                    $this->db->set('biografia',$usuario['biografia']);
            }

            if(isset($usuario['regalias'])){
                    $this->db->set('regalias',$usuario['regalias']);
            }

            if(isset($usuario['estado'])){
                    $this->db->set('estado',$usuario['estado']);
            }

            if(isset($usuario['esAutor'])){
                    $this->db->set('esAutor',$usuario['esAutor']);
            }

            $dataUsuario = array(
                                        'nombre' => $usuario['nombre'],
                                        'apellido' => $usuario['apellido'],
                                        'email' => $usuario['email'],
                                        'telefono' => $usuario['telefono']
                                        );

            if(isset($usuario['idRoles']) && $isAdmin){
                    $data['idRoles'] = (int)$usuario['idRoles'];
            }

            if(isset($usuario['clave'])){
                    $usuario['clave'] = trim($usuario['clave']);
                    if(!empty($usuario['clave'])){
                            $this->db->set('clave',"PASSWORD(".$this->db->escape($usuario['clave']). ")",false);
                    }
            }

            $this->db->update('Usuarios',$dataUsuario,"idUsuarios = ".(int)$usuario['idUsuarios']);
            unset($usuario);
            return TRUE;
        } catch (Exception $e) {
            return FALSE;
        }
    }





    public function delete($usuario){
        try {
            if((int)$usuario['idUsuarios'] == 1){
                    return FALSE;
            }
            $this->db->delete('Usuarios',"idUsuarios = " .(int)$usuario['idUsuarios']);
            if($this->db->affected_rows() > 0){
                    return true;
            }else{
                    return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }





    //Si existe el usuario devuelve verdadero.
    public function existe($usuario){
        try {
                $query = $this->db->query('SELECT U.idUsuarios FROM Usuarios U WHERE U.email = '. $this->db->escape($usuario['email']) . ' OR U.userName = ' . $this->db->escape($usuario['userName']) );
                if($query->num_rows() <= 0){
                        return false;
                }else{
                        return true;
                }
        } catch (Exception $e) {
                throw new Exception($e->getMessage());
        }
    }





    public function validarPerfil($usuario){
        $this->load->library('email');
        foreach ($usuario as $key => $value) {
                $usuario[$key] = trim($value);
        }
        $error = false;

        if(!isset($usuario['nombre']) or empty($usuario['nombre']) )  {
                $error['nombre'] = 'El nombre es obligatorio';
        }
        if(!isset($usuario['apellido']) or  empty($usuario['apellido']) )   {
                $error['apellido'] = 'El apellido es obligatorio';
        }
        if(!isset($usuario['email']) or empty($usuario['email']) )   {
                $error['email'] = 'El email es obligatorio';
        }elseif (!$this->email->valid_email($usuario['email'])) {
                $error['email'] = 'El email es incorrecto';
        }

        if(isset($usuario['clave']) && empty($usuario['clave']) ) {
                $error['clave'] = 'La contrase&ntilde;a es obligatoria';
        }elseif (isset($usuario['clave']) && (!isset($usuario['clave_re']) or empty($usuario['clave_re'])) ) {
                $error['clave_re'] = 'Debe re-ingresar la clave';
        }elseif(isset($usuario['clave'],$usuario['clave_re']) && $usuario['clave'] != $usuario['clave_re']) {
                $error['clave'] = 'Las contrase&ntilde;as no coinciden';
        }

        return $error;
    }







    public function validarNuevo($usuario) {
        $this->load->library('email');
        $errors = false;
        if(!isset($usuario['nombre'])){
                $errors['nombre'] = true;
        }
        if(!isset($usuario['apellido'])){
                $errors['apellido'] = true;
        }
        if (!isset($usuario['email'])) {
                $errors['email'] = true;
        }elseif (!$this->email->valid_email($usuario['email'])) {
                $errors['email_not_valid'] = true;
        }

        if(!isset($usuario['telefono'])){
                $errors['telefono'] = true;
        }elseif (!preg_match("/^[0-9-]+$/", $usuario['telefono'])) {
                $errors['telefono_incorrecto'] = true;
        }

        return $errors;
    }







    public function listar($filter, $isAdmin = false){
        try {

            if (!isset($filter['where'])){
                    $filter['where'] = "";
            }

            if( isset($filter['idUsuarios'])) {
                    $filter['where'] = "AND U.idUsuarios = ".(int)$filter['value'];
            }elseif (isset($filter['email'])) {
                    $filter['where'] = "AND U.email like '".trim($filter['value']) . "%'";
            }elseif (isset($filter['apellido'])){
                    $filter['where'] = "AND U.apellido like '%".trim($filter['value']) . "%'";
            }

            if(!$isAdmin){
                    $filter['where'] = " AND U.estado = 'Activo' ";
            }

            if(!isset($filter['limit'])){
                    $filter['limit'] = "";
            }

            $query = $this->db->query("
                                SELECT U.*,R.key as 'rolKey',R.descripcion as 'rolDescripcion'
                                FROM Usuarios U,Roles R
                                WHERE U.idRoles = R.idRoles
                                    AND R.idRoles > 1 ".$filter['where'] . "
                                ORDER BY U.regalias DESC " . $filter['limit']);
            if($query->num_rows() <= 0){
                    return NULL;
            }else{
                    return $query->result_array();
            }
        } catch (Exception $e) {
                //throw new Exception($e->getMessage());
                return array();
        }
    }







    public function contar($filter = NULL){
        try {
            if(isset($filter['idUsuarios'])) {
                    $filter['where'] = "AND U.idUsuarios = ".(int)$filter['value'];
            }elseif (isset($filter['email'])) {
                    $filter['where'] = "AND U.email like '%".trim($filter['value']) ."%'";
            }elseif(isset($filter['apellido'])){
                    $filter['where'] = "AND U.apellido like '%".trim($filter['value']) . "%'";
            }

            $query = $this->db->query("
                                SELECT COUNT(U.idUsuarios) as 'max'
                                FROM Usuarios U,Roles R
                                WHERE U.idRoles = R.idRoles ".$filter['where']);
            $rows = $query->row_array();
            if($rows['max'] <= 0 ) {
                    return 0;
            }else{
                    return $rows['max'];
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }













public function alta($usuario) {
    try {
        if(isset($usuario['fileFoto']) && !isset($usuario['fileFoto']['delete']) ) {
                $this->procesarFoto($usuario['fileFoto']);
                $foto = $usuario['fileFoto']['newFileName'];
                $this->db->set('avatar',$foto);
        }

        if(isset($usuario['fecha'])){
                $this->db->set('fecha',date('Y-m-d',strtotime($usuario['fecha'])));
        }

        if(isset($usuario['lugar'])){
                $this->db->set('lugar',$usuario['lugar']);
        }

        if(isset($usuario['profesion'])){
                $this->db->set('profesion',$usuario['profesion']);
        }

        if(isset($usuario['biografia'])){
                $this->db->set('biografia',$usuario['biografia']);
        }

        if(isset($usuario['intereses'])){
                $this->db->set('intereses',$usuario['intereses']);
        }

        $dataUsuario = array(
                                    'nombre' => $usuario['nombre'],
                                    'apellido' => $usuario['apellido'],
                                    'email' => $usuario['email'],
                                    'regalias' => $usuario['regalias'],
                                    'esAutor' =>$usuario['esAutor'],
                                    'telefono' =>$usuario['telefono'],
                                    'estado' => $usuario['estado']
                                    );

        $this->db->insert('Usuarios',$dataUsuario);
        $idUsuarios = $this->db->insert_id();

        if(!isset($idUsuarios) or $idUsuarios < 1 ){
                return 0;
        }else {
                return $idUsuarios;
        }

    } catch (Exception $e) {
            return 0;
    }
}







public function deleteFoto($foto){
    try {
        if( is_file(FCPATH . "/assets/imagenes/Usuarios/".$foto['old_foto'])
                && file_exists(FCPATH . "/assets/imagenes/Usuarios/".$foto['old_foto'] ) ) {
                            unlink(FCPATH . "/assets/imagenes/Usuarios/".$foto['old_foto']);
        }
        return true;

    } catch (Exception $e) {
        return FALSE;
    }
}




    public function procesarFoto($foto){
        try {
            $errores = NULL;
            if(move_uploaded_file($foto['tmp_name'],  FCPATH . "/assets/imagenes/Usuarios/". $foto['newFileName'])){
                    $config['image_library'] = 'gd2';
                    $config['maintain_ratio'] = TRUE;
                    $config['source_image'] =  FCPATH . "/assets/imagenes/Usuarios/".$foto['newFileName'];
                    $config['create_thumb'] = TRUE;//$this->config->item('img_thumb');
                    $config['width'] = 290;
                    $config['height'] = 170;
                    $this->load->library('image_lib',$config);
                    if ( ! $this->image_lib->resize()) {
                        $errores['foto'] = 'No se pudo redimensionar la imagen';
                    }
                    $this->image_lib->clear();
            }else{
                    $errores['foto'] = 'No se pudo cargar la imagen';
            }
            echo $this->image_lib->display_errors();

            return $errores;

        } catch (Exception $e) {
            return NULL;
        }
    }












    public function checkEmail($usuario){
        try {
            $sql = '
                SELECT U.idUsuarios
                FROM Usuarios U
                WHERE U.email = ' .$this->db->escape(trim($usuario['email']));
            $query = $this->db->query($sql);
            return $query->row_array();

        } catch (Exception $e) {
        return 0;
        }
    }





    public function getAll(){
        try {
            $sql = "
                SELECT *
                FROM Usuarios U
                WHERE U.estado = 1
                ORDER BY U.nombre ASC,U.apellido
                ASC";
            $query = $this->db->query($sql);
            return $query->result_array();

        } catch (Exception $e) {
            return array();
        }
    }





}
?>