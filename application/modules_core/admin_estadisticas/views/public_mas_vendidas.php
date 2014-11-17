<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="row-fluid">
	<div class="span12">

	<h3 class="heading">
		Listado de las publicaciones más vendidas.
	</h3>
		<div class="span12">
			<div class="controls">&nbsp;</div>
			<table class="table">
				<thead>
					<tr>
						<th>ID</th>
						<th>Publicación</th>
						<th>Autor</th>
						<th>Cant Vendidas</th>
						<th>Cant Regalías</th>
					</tr>
				</thead>
				<tbody>
					<?php if(!isset($mas_vendidas) || count($mas_vendidas) == 0):?>
						<tr>
							<td colspan="7">No se encontraron resultados</td>
						</tr>
					<?php else:?>
						<?php foreach($mas_vendidas as $public): ?>
							<tr class="tr_tipo_<?php echo $public['idTrabajos'];?>">
								<td>
									<?php echo $public['idTrabajos'];?>
								</td>
								<td>
									<a href="<?php echo PUBLIC_FOLDER_ADMIN;?>trabajos/editar/<?php echo $public['idTrabajos'];?>">
										<?php echo $public['titulo'];?>
									</a>
								</td>
								<td>
									<a href="<?php echo PUBLIC_FOLDER_ADMIN;?>usuarios/editarPerfil/<?php echo $public['idUsuarios'];?>">
										<?php echo $public['autor'];?>
									</a>
								</td>
								<td>
									<?php echo $public['cantidad'];?>
								</td>
								<td>
									<?php echo $public['regalias'];?>
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





