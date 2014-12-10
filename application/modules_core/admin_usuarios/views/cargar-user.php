<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div class="row-fluid">
	<div class="span12">
		<h3 class="heading">Usuarios</h3>
		<div class="row-fluid">
			<div class="span8">
				<form class="form-horizontal" method="post"	enctype="multipart/form-data" action="<?php echo $urlForm;?>">
					<?php if(isset($user['idUsuarios'])):?>
						<input type="hidden" name="idUsuarios" id="idUsuarios" value="<?php echo $user['idUsuarios'];?>">
					<?php endif;?>
					<fieldset>
						<div class="control-group formSep">
							<label class="control-label"><?php echo $title;?> </label>
						</div>

						<div class="control-group formSep">
							<label for="nombre" class="control-label">Nombre</label>
							<div class="controls">
								<input type="text" id="nombre" name="nombre" class="input-xlarge"
											value="<?php if(isset($user['nombre'])) echo $user['nombre'];?>" />
								<a href="#" class="required" rel="tooltip" data-placement="right" title="El nombre es requerido">
									<span class="f_req">*</span>
								</a>
							</div>
							<div class="controls">&nbsp;</div>
							<label for="apellido" class="control-label">Apellido</label>
							<div class="controls">
								<input type="text" id="apellido" name="apellido" class="input-xlarge"
																value="<?php if(isset($user['apellido'])) echo $user['apellido'];?>" />
								<a href="#" class="required" rel="tooltip" data-placement="right" title="El apellido es requerido"><span class="f_req">*</span> </a>
							</div>
							<div class="controls">&nbsp;</div>
							<label for="apellido" class="control-label">Nombre a Mostrar</label>
							<div class="controls">
								<input type="text" id="nombre_mostrar" name="nombre_mostrar" class="input-xlarge"
											value="<?php if(isset($user['nombre_mostrar'])) echo $user['nombre_mostrar'];?>" />
								<a href="#" class="required" rel="tooltip" data-placement="right" title="El nombre a mostrar es requerido">
									<span class="f_req">*</span>
								</a>
							</div>
						</div>

							<div class="control-group formSep">
									<label for="email" class="control-label">Email</label>
									<div class="controls">
											<input type="text" id="email" name="email" class="input-xlarge" value="<?php if(isset($user['email'])) echo $user['email'];?>" />
											<div id="checkEmail"></div>
									</div>
							</div>

										<!-- <div class="control-group formSep">
												<label for="clave" class="control-label">Clave</label>
												<div class="controls">
														<input type="password" id="clave" name="clave" class="input-xlarge" value="<?php if(isset($user['clave'])) echo $user['clave'];?>" />
												</div>
										</div> -->

										<div class="control-group formSep">
												<label class="control-label">Intereses </label>
												<div class="controls">
														<textarea  id="intereses" class="input-xlarge" name="intereses" ><?php if(isset($user['intereses'])) echo $user['intereses']; ?></textarea>
														<a href="#" class="required" rel="tooltip" data-placement="right" title="Los Intereses se mostrarán en su perfil"><span class="f_req">*</span> </a>
												</div>
										</div>

										<div class="control-group formSep">
												<label class="control-label">Reseña biografica </label>
												<div class="controls">
														<textarea  id="biografia" class="input-xlarge" name="biografia" ><?php if(isset($user['biografia'])) echo $user['biografia']; ?></textarea>
														<a href="#" class="required" rel="tooltip" data-placement="right" title="La reseña se mostrarán en su perfil"><span class="f_req">*</span> </a>
												</div>
										</div>

										<div class="control-group formSep">
												<label class="control-label">Profesión </label>
												<div class="controls">
														<input type="text" name="profesion" id="profesion" class="input-xlarge" value="<?php if(isset($user['profesion'])) echo $user['profesion'];?>" >
												</div>
										</div>

										<div class="control-group formSep">
												<label class="control-label">Fecha de nacimiento </label>
												<div class="controls">
														<input type="text" name="fecha" id="fecha" class="input-small datepicker" value="<?php if(isset($user['fecha'])) echo date('d-m-Y',strtotime($user['fecha']));?>" >
												</div>
										</div>

										<div class="control-group formSep">
												<label class="control-label">Lugar de nacimiento </label>
												<div class="controls">
														<input type="text" name="lugar" id="lugar" class="input-xlarge" value="<?php if(isset($user['lugar'])) echo $user['lugar'];?>" >
												</div>
										</div>

										<div class="control-group formSep">
												<label class="control-label">Tel&eacute;fono</label>
												<div class="controls">
														<input type="text" id="telefono" name="telefono" value="<?php if(isset($user['telefono'])) echo $user['telefono']; ?>">
														<a href="#" class="required" rel="tooltip" data-placement="right" title="El tel&eacute;fono es requerido"><span class="f_req">*</span> </a>
												</div>
										</div>

										<div class="controls">&nbsp;</div>

										<div class="control-group" style="width: 300px;">
												<label class="control-label">Avatar </label>
												<div class="span4">
														<div data-fileupload="file" id="foto" class="fileupload fileupload-new">
																<input type="hidden" />
																<div class="input-append">
																		<div class="uneditable-input span2">
																				<i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span>
																		</div>
																		<span class="btn btn-file">
																		<span class="fileupload-new">Seleccionar archivo</span>
																		<span class="fileupload-exists">Cambiar</span>
																		<input type="file" name="foto" /> </span><a data-dismiss="fileupload" class="btn fileupload-exists" href="#">Quitar</a>
																</div>
														</div>
														<?php if(isset($user['avatar']) and !empty($user['avatar'])):?>
														<label class="checkbox">
																<input type="checkbox" name="old_foto" value="<?php echo $user['avatar'];?>" >
																Eliminar avatar
														</label>
														<?php endif;?>
												</div>
												<?php if(isset($user['avatar']) and !empty($user['avatar'])):?>
														<img  style="margin: 0 0 0 130px;" src="<?php echo RAIZ . UPLOADS . 'usuarios/avatar/' . $user['avatar']; ?>"  alt="">
												<?php endif;?>
										</div>



										<div class="control-group formSep">
											<label class="control-label">Calle </label>
											<div class="controls">
												<input type="text" name="direccion_calle" id="direccion_calle" class="input-xlarge" value="<?php if(isset($user['direccion_calle'])) echo $user['direccion_calle'];?>" >
											</div>
										</div>
										<div class="control-group formSep">
											<label class="control-label">Número </label>
											<div class="controls">
												<input type="text" name="direccion_numero" id="direccion_numero" class="input-xlarge" value="<?php if(isset($user['direccion_numero'])) echo $user['direccion_numero'];?>" >
											</div>
										</div>
										<div class="control-group formSep">
											<label class="control-label">Código Postal </label>
											<div class="controls">
												<input type="text" name="cod_postal" id="cod_postal" class="input-xlarge" value="<?php if(isset($user['cod_postal'])) echo $user['cod_postal'];?>" >
											</div>
										</div>
										<div class="control-group formSep">
											<label class="control-label">Localidad </label>
											<div class="controls">
												<input type="text" name="localidad" id="localidad" class="input-xlarge" value="<?php if(isset($user['localidad'])) echo $user['localidad'];?>" >
											</div>
										</div>
										<div class="control-group formSep">
											<label class="control-label">Ciudad </label>
											<div class="controls">
												<input type="text" name="ciudad" id="ciudad" class="input-xlarge" value="<?php if(isset($user['ciudad'])) echo $user['ciudad'];?>" >
											</div>
										</div>
										<div class="control-group formSep">
											<label class="control-label">País </label>
											<div class="controls">
												<input type="text" name="pais" id="pais" class="input-xlarge" value="<?php if(isset($user['pais'])) echo $user['pais'];?>" >
											</div>
										</div>


										<div class="formSep">&nbsp;</div>
										<?php if(checkRol('administrador', $this->session)):?>
												<div class="control-group formSep">
														<label class="control-label">Estado</label>
														<div class="controls text_line">
																<select name="estado" id="estado">
																		<option value="1" <?php if(isset($user['estado']) and $user['estado'] == 1) echo 'selected="selected"';?>>Activo</option>
																		<option value="0" <?php if(!isset($user['estado']) or $user['estado'] == 0) echo 'selected="selected"';?>>Inactivo</option>
																</select>
														</div>
												</div>
												<div class="control-group formSep">
														<label class="control-label">Es autor </label>
														<div class="controls text_line">
																<select name="esAutor" id="esAutor">
																		<option value="1" <?php if(isset($user['esAutor']) and $user['esAutor'] == 1) echo 'selected="selected"';?>>Si</option>
																		<option  value="0" <?php if(!isset($user['esAutor']) or $user['esAutor'] == 0) echo 'selected="selected"';?>>No</option>
																</select>
														</div>
												</div>
												<div class="control-group formSep">
														<label class="control-label">Es editorial </label>
														<div class="controls text_line">
																<select name="esEditorial" id="esEditorial">
																		<option value="1" <?php if(isset($user['esEditorial']) and $user['esEditorial'] == 1) echo 'selected="selected"';?>>Si</option>
																		<option  value="0" <?php if(!isset($user['esEditorial']) or $user['esEditorial'] == 0) echo 'selected="selected"';?>>No</option>
																</select>
														</div>
												</div>
										<?php endif;?>

										<div class="control-group formSep">
												<label for="email" class="control-label">Regalias acumuladas (U$D)</label>
												<div class="controls">
														<?php if(checkRol('administrador', $this->session)):?>
																<input type="text" id="regalias" name="regalias" class="input-small" value="<?php if(isset($user['regalias'])) echo $user['regalias'];?>" />
														<?php else:?>
																<input type="text" class="input-small" value="<?php if(isset($user['regalias'])) echo $user['regalias'];?>" readonly="readonly" />
														<?php endif;?>
												</div>
										</div>

										<div class="control-group formSep">
											<label class="control-label">Forma de Pago </label>
											<div class="controls text_line">
													<select name="idFormasPago" id="idFormasPago">
														<?php foreach($formas_pago AS $pago):?>
															<option value="<?php echo $pago['idFormasPago'];?>" <?php if($pago['idFormasPago'] == $user['idFormasPago']) echo 'selected="selected"'; ?> >
																<?php echo $pago['nombre'];?>
															</option>
														<?php endforeach;?>
													</select>
											</div>
										</div>


										<div class="control-group">
												<div class="controls">
												<button class="btn btn-gebo" id="save" type="submit">Guardar Cambios</button>
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
						<?php if(isset($user['idUsuarios'])):?>
										var idUsuarios = <?php echo $user['idUsuarios'];?>;
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
