<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="row-fluid">
						<div class="span12">
							<h3 class="heading"><?php echo $titulo;?></h3>
							<div class="row-fluid">
								<div class="span8">
										<fieldset>																	
											<div class="control-group formSep">
												<label for="nombre" class="control-label">Descripci&oacute;n <span class="f_req">*</span></label>
												<div class="controls">
													<input type="text" id="rolDescripcion" name="rolDescripcion" class="input-xlarge" value="<?php if(isset($rol['rolDescripcion'])) echo $rol['rolDescripcion'];?>"/>
												</div>
                                                <div class="controls">&nbsp;</div>
                                                <label for="apellido" class="control-label">Llave <span class="f_req">*</span></label>
												<div class="controls">
												<?php if(isset($rol['rolKey']) and $rol['rolKey'] == 'administrador'): ?>
													<input type="text" id="rolKey" name="rolKey" class="input-xlarge" value="<?php if(isset($rol['rolKey'])) echo $rol['rolKey'];?>" readonly="readonly"/>
												<?php else:?>
													<input type="text" id="rolKey" name="rolKey" class="input-xlarge" value="<?php if(isset($rol['rolKey'])) echo $rol['rolKey'];?>"/>
												<?php endif;?>	
													<span class="help-block">No se permiten espacios. Por ejemplo: nuevo_rol</span>
												</div>
											</div>
											<div class="control-group">
												<div class="controls">
													<button class="btn btn-gebo submit" type="button" value="<?php if(isset($rol['idRoles'])) echo $rol['idRoles'];?>"><?php echo $boton;?></button>
												<a href="javascript:history.back(-1);" class="btn">Volver</a>
												</div>
											</div>
										</fieldset>
								</div>
							</div>
						</div>
</div>
						<?php if(isset($rol['permisos'])):?>
<div class="row-fluid">				
						<div class="span12">
							<h3 class="heading">Permisos<a href="#" role="button" class="btn" id="agregar-permiso" data-toggle="modal">Agregar Permiso</a></h3>				
								
										
							<div class="row-fluid">
								<div class="span8">
								<table class="table table-bordered table-striped table_vam" id="dt_permisos">
								<thead>
									<tr>
										<th>Descripci&oacute;n</th>
										<th>Key</th>
										<th>Valor</th>							
										<th>Acciones</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($rol['permisos'] as $permiso): ?>
									<tr id="tr_perm_<?php echo $permiso['idPermisos'];?>">
										<td id="td_perm_descripcion_<?php echo $permiso['idPermisos'];?>"><?php echo $permiso['permDescripcion'];?></td>
										<td id="td_perm_key_<?php echo $permiso['idPermisos'];?>"><?php echo $permiso['permKey'];?></td>
										<td id="td_perm_value_<?php echo $permiso['idPermisos'];?>"><?php echo $permiso['permValue'];?></td>
										<td>
											<button type="button" title="Editar" class="editar-perm" value="<?php echo $permiso['idPermisos'];?>"><i class="icon-pencil"></i></button>										
											<button type="button"  title="Eliminar" class="delete-perm" value="<?php echo $permiso['idPermisos'];?>"><i class="icon-trash"></i></button>
										</td>
									</tr>
									<?php endforeach;?>											
								</tbody>
								</table>														
								</div>
							</div>
						</div>
						<div class="modal hide" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						  <div class="modal-header">
						    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						    <h3 id="modalTitle"></h3>
						  </div>
						  <div class="modal-body">				
						  	<input type="hidden" name="idPermisos" id="idPermisos">		    
							<input type="text" placeholder="Descripcion..." class="input-xlarge" name="permDescripcion" id="e-permDescripcion">
							<input type="text" placeholder="Llave..." class="input-xlarge" name="permKey" id="e-permKey">
							<input type="text" placeholder="Valor..." class="input-xlarge" name="permValue" id="e-permValue">				
						  </div>
						  <div class="modal-footer">
						    <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
						    <button class="btn btn-primary" id="modalSave">Guardar</button>
						  </div>
						</div>
						<span class="hide" id="titleEdit">Editar Permiso</span>
						<span class="hide" id="titleAdd">Agregar Permiso</span> 
						<input type="hidden" id="idRoles" name="idRoles" value="<?php echo $rol['idRoles'];;?>">
						<?php endif;?>
</div>
