<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="row-fluid">
    <div class="span12">
        <div class="span12">
            <select id="filter">
                <option value="" <?php if(isset($filter)) echo 'selected="selected"';?>>Filtrar por</option>
                <option value="idUsuarios" <?php if(isset($filter) and $filter == "idUsuarios") echo 'selected="selected"';?>>ID Usuario</option>
                <option value="email" <?php if(isset($filter) and $filter == "email") echo 'selected="selected"';?>>Email</option>
                <option value="apellido" <?php if(isset($filter) and $filter == 'apellido') echo 'selected="selected"';?>>Apellido</option>
            </select> <input type="text" name="value" id="value" value="<?php if(isset($value)) echo $value;?>"> <button class="btn" type="button" id="btn-filter">Filtrar</button><button class="btn" type="button" id="btn-clean">Limpiar</button>
            <div class="controls">&nbsp;</div>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Apellido y Nombre</th>
                        <th>Email</th>
                        <th>Estado</th>
                        <th>Es autor</th>
                        <th>Regalías acum.</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!isset($usuarios) or sizeof($usuarios) < 1):?>
                                <tr>
                                    <td colspan="7">No se encontraron resultados</td>
                                </tr>
                    <?php else:?>
                                <?php foreach($usuarios as $usuario): ?>
                                        <tr id="tr_user_<?php echo $usuario['idUsuarios'];?>">
                                            <td><?php echo $usuario['idUsuarios'];?></td>
                                            <td>
                                                <a href="<?php echo PUBLIC_FOLDER_ADMIN;?>trabajos/listar/userName/<?php echo $usuario['email']; ?>">
                                                    <?php echo $usuario['apellido']. " ". $usuario['nombre'];?>
                                                </a>
                                            </td>
                                            <td><?php echo $usuario['email'];?></td>
                                            <td>
                                                    <?php if($usuario['estado']):?>
                                                                Activo
                                                    <?php else:?>
                                                                Inactivo
                                                    <?php endif;?>
                                            </td>
                                            <td>
                                                    <?php if($usuario['esAutor']):?>
                                                            Si
                                                    <?php else:?>
                                                            No
                                                    <?php endif;?>
                                            </td>
                                            <td><?php echo 'U$D ' . $usuario['regalias'];?></td>
                                            <td>
                                                <a href="<?php echo PUBLIC_FOLDER_ADMIN;?>usuarios/editarPerfil/<?php echo $usuario['idUsuarios'];?>"
                                                    class="sepV_a btn" title="Editar">
                                                    <i class="icon-pencil"></i>
                                                </a>
                                                <button title="Eliminar" class="delete-user" value="<?php echo $usuario['idUsuarios'];?>">
                                                    <i class="icon-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                <?php endforeach;?>
                    <?php endif;?>
                </tbody>
            </table>
        <?php echo $paginas;?>
        </div>
    </div>
</div>