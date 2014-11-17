<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="row-fluid">
	<div class="span12">
	<h3 class="heading">
		<a href="<?php echo PUBLIC_FOLDER_ADMIN;?>precios/listar.html">Precios</a>	 ::
		<a href="<?php echo PUBLIC_FOLDER_ADMIN;?>precios/nuevo.html">Nuevo</a>
		</h3>
		<div class="span12">
			<table class="table">
				<thead>
					<tr>
						<th>ID</th>
						<th>Precio</th>
						<th>Acción</th>
					</tr>
				</thead>
				<tbody>
					<?php if(!isset($precios) or sizeof($precios) < 1):?>
						<tr><td colspan="7">No se encontraron resultados</td></tr>
					<?php else:?>
						<?php foreach($precios as $precio): ?>
							<tr class="tr_tipo_<?php echo $precio['idPrecios'];?>">
								<td><?php echo $precio['idPrecios'];?></td>
								<td><?php echo $precio['precio'];?></td>
								<td>
									<a href="<?php echo PUBLIC_FOLDER_ADMIN;?>precios/editar/<?php echo $precio['idPrecios'];?>.html" class="sepV_a btn" title="Editar">
										<i class="icon-pencil"></i>
									</a>
									<button  type="button" title="Eliminar" id="<?php echo $precio['idPrecios'];?>" class="delete-tipo" value="<?php echo $precio['idPrecios'];?>">
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

