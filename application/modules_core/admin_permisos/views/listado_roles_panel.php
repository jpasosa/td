<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
			<div class="row-fluid table"><a href="<?php echo PUBLIC_FOLDER.'admin/permisos/agregarRol';?>" role="button" class="btn">Agregar Rol</a></div>
			<div class="row-fluid">			
						<div class="span12">
							<h3 class="heading">Listado de Roles</h3>
							<table class="table table-bordered table-striped table_vam" id="dt_roles">
								<thead>
									<tr>
										<th class="table_checkbox"></th>
										<th>id</th>
										<th>Descripci&oacute;n</th>
										<th>Key</th>							
										<th>Acciones</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($roles as $rol): ?>
									<tr id="tr_rol_<?php echo $rol['idRoles'];?>">
										<td><input type="checkbox" name="idRoles" class="select_row" value="<?php echo $rol['idRoles'];?>"/></td>
										<td><?php echo $rol['idRoles'];?></td>
										<td><?php echo $rol['rolDescripcion'];?></td>
										<td><?php echo $rol['rolKey'];?></td>
										<td>
											<a href="<?php echo PUBLIC_FOLDER. "admin/permisos/editarRol/".$rol['idRoles'];?>" class="sepV_a btn" title="Editar"><i class="icon-pencil"></i></a>										
											<button title="Eliminar" class="delete-rol" value="<?php echo $rol['idRoles'];?>"><i class="icon-trash"></i></button>
										</td>
									</tr>
									<?php endforeach;?>											
								</tbody>
							</table>
							<?php echo($paginas);?>
						</div>
				</div>