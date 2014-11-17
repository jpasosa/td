<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div class="row-fluid">
    <div class="span12">
    <h3 class="heading">Cargar Nueva Compra</h3>
    <div class="row-fluid">
        <div class="span8">
            <form class="form-horizontal" method="post"	enctype="multipart/form-data" action="<?php echo $form_action;?>">
                <?php if(isset($compras['idPedidos'])):?>
                            <input type="hidden" name="idPedidos" id="idPedidos" value="<?php echo $compras['idPedidos'];?>">
                <?php endif;?>
                <fieldset>
                    <div class="control-group formSep">
                        <label for="fecha" class="control-label">Fecha</label>
                        <div class="controls">
                            <input type="text" id="nombre" name="fecha" class="input-xlarge datepicker"
                                        value="<?php if(isset($compras['fecha'])) echo $compras['fecha'];?>" />
                            <a href="#" class="required" rel="tooltip" data-placement="right" title="El fecha es requerido">
                            </a>
                        </div>
                    </div>

                    <div class="control-group formSep">
                        <label for="apellido" class="control-label">Usuario Comprador</label>
                        <div class="controls text_line">
                            <input type="hidden" name="idUsuariosComprador" value="<?php echo $compras['idUsuariosComprador']; ?>" />
                            <select name="idUsuariosComprador">
                                <?php foreach($usuarios as $usuario):?>
                                    <?php if($usuario['idUsuarios'] == $compras['idUsuariosComprador']):?>
                                        <option value="<?php echo $usuario['idUsuarios'];?>" selected="selected" >
                                            <?php echo $usuario['email']. " - ". $usuario['nombre'] . " " .$usuario['apellido'];?>
                                        </option>
                                    <?php else:?>
                                        <option value="<?php echo $usuario['idUsuarios'];?>" >
                                            <?php echo $usuario['email']. " - ". $usuario['nombre'] . " " .$usuario['apellido'];?>
                                        </option>
                                    <?php endif;?>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group formSep">
                        <label for="apellido" class="control-label">Autor</label>
                        <div class="controls text_line">
                            <input  type="hidden" name="idUsuariosAutor" value="<?php echo $compras['idUsuariosAutor']; ?>" />
                            <select id="autor" name="idUsuariosAutor">
                                <?php foreach($usuarios as $usuario):?>
                                    <?php if($usuario['idUsuarios'] == $compras['idUsuariosAutor']):?>
                                        <option value="<?php echo $usuario['idUsuarios'];?>" selected="selected" >
                                            <?php echo $usuario['email']. " - ". $usuario['nombre'] . " " .$usuario['apellido'];?>
                                        </option>
                                    <?php else:?>
                                        <option value="<?php echo $usuario['idUsuarios'];?>" >
                                            <?php echo $usuario['email']. " - ". $usuario['nombre'] . " " .$usuario['apellido'];?>
                                        </option>
                                    <?php endif;?>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group formSep">
                        <label for="apellido" class="control-label">Publicación</label>
                        <div class="controls text_line">
                            <input type="hidden" name="idTrabajos" value="<?php echo $compras['idTrabajos']; ?>" />
                            <select id="publicaciones" name="idTrabajos">
                                <option> Ingresar Autor . . . . . .  </option>
                            </select>
                        </div>
                    </div>

                    <div class="control-group formSep">
                        <label class="control-label">Monto Venta Total </label>
                        <div class="controls">
                            <input  type="text" class="input-xlarge" name="monto_venta_total"
                                value="<?php if(isset($compras['monto_venta_total'])) echo $compras['monto_venta_total'];?>" />
                        </div>
                    </div>

                    <div class="control-group formSep">
                        <label class="control-label">Modalidad</label>
                        <div class="controls text_line">
                            <!-- <input type="hidden" name="modalidad" value="<?php echo $compras['modalidad']; ?>"> -->
                            <select name="modalidad">
                                <option value="0" <?php if(isset($compras['modalidad']) && $compras['modalidad'] == 0) echo 'selected="selected"';?> >
                                    Sin cesión de derechos
                                </option>
                                <option value="1" <?php if(!isset($compras['modalidad']) || $compras['modalidad'] == 1) echo 'selected="selected"';?> >
                                    Con cesión de derechos
                                </option>
                            </select>
                        </div>
                    </div>



                    <div class="control-group formSep">
                        <label class="control-label">Monto al Autor </label>
                        <div class="controls">
                            <input  type="text" id="monto_al_autor" class="input-xlarge" name="monto_al_autor" validate="true"
                                value="<?php if(isset($compras['monto_al_autor'])) echo $compras['monto_al_autor'];?>" />
                        </div>
                    </div>

                    <div class="control-group formSep">
                        <label class="control-label">Regalías</label>
                        <div class="controls text_line">
                            <select name="regalias">
                                <option value="0">No asignar regalías.</option>
                                <option  value="1">Asignar regalías.</option>
                            </select>
                        </div>
                    </div>

                    <div class="control-group formSep">
                        <label class="control-label">Notificar </label>
                        <div class="controls text_line">
                            <select name="notificado" id="esAutor">
                                <option value="0" <?php if(isset($compras['notificado']) && $compras['notificado'] == 0) echo 'selected="selected"';?>>
                                    NO
                                </option>
                                <option  value="1" <?php if(!isset($compras['notificado']) || $compras['notificado'] == 1) echo 'selected="selected"';?>>
                                    SI
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <div class="controls">
                            <button class="btn btn-gebo" id="save" type="submit">Cargar Compra</button>
                            <a href="javascript:history.back(-1);" class="btn">Volver</a>
                        </div>
                    </div>

            </fieldset>
        </form>
    </div>
</div>
</div>
</div>



<script type="text/javascript">
    $(document).ready(function(){
        $(".required").tooltip();
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy'
        });

        $('#email').live('change',function() {
            $('#checkEmail').html('');
            $('#save').removeAttr('disabled');
            <?php if(isset($compras['idUsuarios'])):?>
                    var idUsuarios = <?php echo $compras['idUsuarios'];?>;
            <?php else:?>
                    var idUsuarios = 0;
            <?php endif;?>
            var emailValue = $.trim($('#email').val());
            if(emailValue == '' && emailValue.length < 4){
                    return false;
            }
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '<?php echo PUBLIC_FOLDER;?>usuarios/checkEmail',
                data: {email: emailValue },
                success: function(user){
                    console.log(user);
                    if(user.existe && idUsuarios != user.idUsuarios){
                        var _html = 'Ya existe un usuario con ese email.<br><a href="<?php echo PUBLIC_FOLDER;?>usuarios/editarPerfil/'+ user.idUsuarios + '" target="_blank">Para visualizar los datos haga clic aqu&iacute;</a>';
                        $('#checkEmail').html(_html);
                        $('#save').attr('disabled','disabled');
                    }
                },
                error: function(e) {
                    console.log(e);
                }
            });
        });
    });
</script>
