<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="row-fluid">
	<div class="span12">

	<h3 class="heading">
		Listado de los Autores más vendidos.
	</h3>
		<div class="span12">
			<?php if (isset($regalias_totales_pagas)): ?>
				<span>
					Total Regalías No Pagadas:
					<strong><?php echo $regalias_totales_no_pagas; ?></strong> <br />
				</span>
				<span>
					Total Regalías Pagadas:
					<strong><?php echo $regalias_totales_pagas; ?></strong>
				</span>
			<?php endif; ?>

			<div class="controls">&nbsp;</div>
			<table class="table">
				<thead>
					<tr>
						<th>ID</th>
						<th>Autor</th>
						<th>Cant Publicaciones Vendidas</th>
						<th>Regalías No Pagadas</th>
						<th>Regalías Pagadas</th>
						<th>Total Regalías</th>
					</tr>
				</thead>
				<tbody>
					<?php if(!isset($autores_mas_vendidos) || count($autores_mas_vendidos) == 0):?>
						<tr>
							<td colspan="7">No se encontraron resultados</td>
						</tr>
					<?php else:?>
						<?php foreach($autores_mas_vendidos as $public): ?>
							<tr class="tr_tipo_<?php echo $public['idUsuarios'];?>">
								<td>
									<?php echo $public['idUsuarios'];?>
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
								<td>
									<?php echo $public['regalias_pagadas'];?>
								</td>
								<td>
									<?php $tot = $public['regalias'] + $public['regalias_pagadas'];?>
									<?php echo $tot;?>
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





