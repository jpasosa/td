<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
			<div class="row-fluid">
						<div class="span12">
							<h3 class="heading">Listado de Usuarios</h3>
							<table class="table table-bordered table-striped table_vam" id="dt_gal">
								<thead>
									<tr>
										<th class="table_checkbox"></th>
										<th>id</th>
										<th>Nombre</th>
										<th>Apellido</th>
										<th>Email</th>
										<th>Estado</th>
										<th>Grupo</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($usuarios as $usuario): ?>
									<tr id="tr_user_<?php echo $usuario['idUsuarios'];?>">
										<td><input type="checkbox" name="idUsuarios" class="select_row" value="<?php echo $usuario['idUsuarios'];?>"/></td>
										<td><?php echo $usuario['idUsuarios'];?></td>
										<td><?php echo $usuario['nombre'];?></td>
										<td><?php echo $usuario['apellido'];?></td>
										<td><?php echo $usuario['email'];?></td>
										<td><?php echo $usuario['estado'];?></td>
										<td><?php echo $usuario['rolDescripcion'];?></td>
										<td>
											<a href="usuarios/editarPerfil/<?php echo $usuario['idUsuarios'];?>" class="sepV_a btn" title="Editar"><i class="icon-pencil"></i></a>										
											<button title="Eliminar" class="delete-user" value="<?php echo $usuario['idUsuarios'];?>"><i class="icon-trash"></i></button>
										</td>
									</tr>
									<?php endforeach;?>											
								</tbody>
							</table>
							
						</div>
					</div>
					
					<!-- hide elements (for later use) -->
					<div class="hide">						
						<!-- confirmation box -->
						<div id="confirm_dialog" class="cbox_content">
							<div class="sepH_c tac"><strong>Are you sure you want to delete this row(s)?</strong></div>
							<div class="tac">
								<a href="#" class="btn btn-gebo confirm_yes">Yes</a>
								<a href="#" class="btn confirm_no">No</a>
							</div>
						</div>
					</div>
                        
                </div>
