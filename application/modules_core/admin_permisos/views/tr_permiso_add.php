<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
									<tr id="tr_perm_<?php echo $permiso['idPermisos'];?>">
										<td id="td_perm_descripcion_<?php echo $permiso['idPermisos'];?>"><?php echo $permiso['descripcion'];?></td>
										<td id="td_perm_key_<?php echo $permiso['idPermisos'];?>"><?php echo $permiso['key'];?></td>
										<td id="td_perm_value_<?php echo $permiso['idPermisos'];?>"><?php echo $permiso['valor'];?></td>
										<td>
											<button type="button" title="Editar" class="editar-perm" value="<?php echo $permiso['idPermisos'];?>"><i class="icon-pencil"></i></button>										
											<button type="button"  title="Eliminar" class="delete-perm" value="<?php echo $permiso['idPermisos'];?>"><i class="icon-trash"></i></button>
										</td>
									</tr>

