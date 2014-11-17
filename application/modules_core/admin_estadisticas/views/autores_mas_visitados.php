<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="row-fluid">
	<div class="span12">




	<h3 class="heading">
		Listado de los autores más visitados.
	</h3>
		<div class="span12">
			<div class="controls">&nbsp;</div>
			<table class="table">
				<thead>
					<tr>
						<th>ID</th>
						<th>Autor</th>
						<th>Visitas</th>
					</tr>
				</thead>
				<tbody>
					<?php if(!isset($mas_visitados) || sizeof($mas_visitados) < 1):?>
						<tr>
							<td colspan="7">No se encontraron resultados</td>
						</tr>
					<?php else:?>
						<?php foreach($mas_visitados as $autor): ?>
							<tr class="tr_tipo_<?php echo $autor['id'];?>">
								<td>
									<?php echo $autor['id'];?>
								</td>
								<td>
									<a href="<?php echo PUBLIC_FOLDER_ADMIN;?>usuarios/editarPerfil/<?php echo $autor['id'];?>">
										<?php echo $autor['apellido'] . ' ' . $autor['nombre'];?>
									</a>
								</td>
								<td>
									<?php echo $autor['visitas'];?>
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





