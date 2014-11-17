<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="row-fluid">
	<div class="span12">




	<h3 class="heading">
		Listado de las publicaciones más visitadas.
	</h3>
		<div class="span12">
			<div class="controls">&nbsp;</div>
			<table class="table">
				<thead>
					<tr>
						<th>ID</th>
						<th>Publicación</th>
						<th>Autor</th>
						<th>Visitas</th>
					</tr>
				</thead>
				<tbody>
					<?php if(!isset($mas_visitadas) || sizeof($mas_visitadas) < 1):?>
						<tr>
							<td colspan="7">No se encontraron resultados</td>
						</tr>
					<?php else:?>
						<?php foreach($mas_visitadas as $public): ?>
							<tr class="tr_tipo_<?php echo $public['idUsuarios'];?>">
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
										<?php echo $public['apellido'] . ' ' . $public['nombre'];?>
									</a>
								</td>
								<td>
									<?php echo $public['visitas'];?>
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





