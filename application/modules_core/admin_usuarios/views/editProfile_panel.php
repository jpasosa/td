<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="row-fluid">
						<div class="span12">
							<h3 class="heading">P&eacute;rfil de usuario</h3>
							<div class="row-fluid">
								<div class="span8">
									<form class="form-horizontal" method="post" action="<?php echo $actionForm;?>" >
									<?php if(isset($idUsuarios)) echo $idUsuarios;?>
										<fieldset>								
											<div class="control-group formSep">
												<label for="nombre" class="control-label">Nombre <span class="f_req">*</span></label>
												<div class="controls">
													<input type="text" id="nombre" name="nombre" class="input-xlarge" value="<?php echo $usuario['nombre'];?>" />
												</div>
                                                                                                <div class="controls">&nbsp;</div>
                                                                                                <label for="apellido" class="control-label">Apellido <span class="f_req">*</span></label>
												<div class="controls">
													<input type="text" id="apellido" name="apellido" class="input-xlarge" value="<?php echo $usuario['apellido'];?>" />
												</div>
											</div>
											<div class="control-group formSep">
												<label for="email" class="control-label">Email <span class="f_req">*</span></label>
												<div class="controls">
													<input type="text" id="email" name="email" class="input-xlarge" value="<?php echo $usuario['email'];?>" />
												</div>
											</div>
											<div class="control-group formSep">
												<label for="clave" class="control-label">Contrase&ntilde;a</label>
												<div class="controls">
													<div class="sepH_b">
														<input type="password" id="clave" name="clave" class="input-xlarge" value="" />
														<span class="help-block">Ingrese su clave</span>
													</div>
													<input type="password" id="clave_re" name="clave_re" class="input-xlarge" />
													<span class="help-block">Repita su clave</span>
												</div>
											</div>
				
											<div class="control-group formSep">
												<label class="control-label">Tel&eacute;fono</label>
                                                                                                <div class="controls">
												<input type="text" id="telefono" name="telefono" value="<?php echo $usuario['telefono']; ?>"
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="controls">&nbsp;</div>
                                                                                        <div class="control-group">
												<label class="control-label">D.N.I</label>
                                                                                                <div class="controls">
												<input type="text" id="dni" name="dni" value="<?php echo $usuario['dni']; ?>"
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="controls formSep">&nbsp;</div>
                                                                                        <?php if(isset($isAdmin) and $isAdmin == true): ?>
                                                                                        <div class="control-group formSep">
												<label class="control-label">Grupo</label>
												<div class="controls text_line">
                                                                                                    <select name="rol" id="id">
												<?php foreach($grupos as $grupo):?>
                                                                                                <?php if($usuario['rolKey'] == $grupo['rolKey']):?>
                                                                                                <option value="<?php echo $grupo['idRoles'];?>" selected="selected"><?php echo $grupo['rolDescripcion'];?></option>
                                                                                                <?php else: ?>
                                                                                                <option value="<?php echo $grupo['idRoles'];?>"><?php echo $grupo['rolDescripcion'];?></option>
                                                                                                <?php endif;?>
                                                                                                <?php endforeach;?>
                                                                                                </select>
												</div>
                                                                                        </div>
                                                                                        <div class="control-group formSep">
												<label class="control-label">Estado</label>
												<div class="controls text_line">
                                                                                                    <select name="estado" id="estado">
												<?php foreach($estados as $estado):?>
                                                                                                <?php if($usuario['estado'] == $estado):?>
                                                                                                <option selected="selected"><?php echo $estado;?></option>
                                                                                                <?php else: ?>
                                                                                                <option><?php echo $estado;?></option>
                                                                                                <?php endif;?>
                                                                                                <?php endforeach;?>
                                                                                                </select>
												</div>
                                                                                        </div>
                                                                                        <?endif;?>									
                         
											<div class="control-group">
												<div class="controls">
													<button class="btn btn-gebo" type="submit">Guardar Cambios</button>
												<a href="javascript:history.back(-1);" class="btn">Volver</a>
												</div>
											</div>
										</fieldset>
									</form>
								</div>
							</div>
						</div>
					</div>
