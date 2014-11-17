<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div class="row-fluid">
    <div class="span12">
    <h3 class="heading">Pedido</h3>
    <div class="row-fluid">
        <div class="span8">
            <form class="form-horizontal" method="post"	enctype="multipart/form-data" action="">
                <?php if(isset($compras['idPedidos'])):?>
                            <input type="hidden" name="idPedidos" id="idPedidos" value="<?php echo $compras['idPedidos'];?>">
                <?php endif;?>
                <fieldset>
                    <div class="control-group formSep">
                        <label for="fecha" class="control-label">Fecha</label>
                        <div class="controls">
                            <input type="text" id="nombre" name="fecha" class="input-xlarge" readonly="readonly"
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
                                <option value="">
                                    <?php echo $compras['email_comprador']; ?>
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="control-group formSep">
                        <label for="idTrabajos" class="control-label"> Publicación </label>
                        <div class="controls">
                            <input type="text" name="tituloPublicacion" class="input-xlarge"
                                value="<?php echo $compras['titulo'];?>" readonly="readonly" />
                        </div>
                    </div>

                    <div class="control-group formSep">
                        <label class="control-label">Monto Venta Total </label>
                        <div class="controls">
                            <input  type="text" id="monto_venta_total" class="input-xlarge" name="monto_venta_total" readonly="readonly"
                                value="<?php if(isset($compras['monto_venta_total'])) echo $compras['monto_venta_total'];?>" />
                        </div>
                    </div>

                    <div class="control-group formSep">
                        <label class="control-label">Modalidad</label>
                        <div class="controls text_line">
                            <!-- <input type="hidden" name="modalidad" value="<?php echo $compras['modalidad']; ?>"> -->
                            <select name="modalidad">
                                <option value="0" <?php if(isset($compras['modalidad']) && $compras['modalidad'] == 0) echo 'selected="selected"';?> disabled="disabled">
                                    Sin cesión de derechos
                                </option>
                                <option value="1" <?php if(!isset($compras['modalidad']) || $compras['modalidad'] == 1) echo 'selected="selected"';?> disabled="disabled">
                                    Con cesión de derechos
                                </option>
                            </select>
                        </div>
                    </div>

<!--                     <div class="control-group formSep">
                        <label for="apellido" class="control-label">Autor</label>
                        <div class="controls text_line">
                            <input type="hidden" name="idUsuariosAutor" value="<?php echo $compras['idUsuariosAutor']; ?>">
                            <input type="hidden" name="emailUsuariosAutor" value="<?php echo $compras['emailUsuariosAutor']; ?>">
                            <select name="idUsuariosAutor">
                                <option value="<?php echo $compras['idUsuariosAutor'];?>" >
                                    <?php echo $compras['emailUsuariosAutor']; ?>
                                </option>
                            </select>
                        </div>
                    </div> -->

                    <!-- <div class="control-group formSep">
                        <label class="control-label">Monto al Autor </label>
                        <div class="controls">
                            <input  type="text" id="monto_al_autor" class="input-xlarge" name="monto_al_autor"
                                value="<?php if(isset($compras['monto_al_autor'])) echo $compras['monto_al_autor'];?>" />
                        </div>
                    </div> -->

                    <!-- <div class="control-group formSep">
                        <label class="control-label">Regalías</label>
                        <div class="controls text_line">
                            <select name="regalias">
                                <option value="0">No asignar regalías.</option>
                                <option  value="1">Asignar regalías.</option>
                            </select>
                        </div>
                    </div> -->

                    <!-- <div class="control-group formSep">
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
                    </div> -->

                    <div class="control-group">
                        <div class="controls">
                            <!-- <button class="btn btn-gebo" id="save" type="submit">Cargar Compra</button> -->
                            <a href="javascript:history.back(-1);" class="btn">Volver</a>
                        </div>
                    </div>

            </fieldset>
        </form>
    </div>
</div>
</div>
</div>


