<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="row-fluid">
	<div class="span12">




	<h3 class="heading">
		Listado de las regalías pendientes de Pago de los Autores.
	</h3>
		<div class="span12">
			<form class="form-horizontal" method="post"	enctype="multipart/form-data" action="<?php echo $form_action;?>">
				<select id="filter" name="idUsuarios">
					<?php foreach ($select_usuarios AS $user): ?>
						<option value="<?php echo $user['idUsuarios']; ?>">
							<?php echo $user['nombre'] . ' ' . $user['apellido']; ?>
						</option>
					<?php endforeach; ?>
				</select>

				<button class="btn" type="SUBMIT" id="btn-filter">
					Filtrar
				</button>
				<a href="<?php echo PUBLIC_FOLDER_ADMIN;?>pagos/listado_autores.html" class="btn">Limpiar</a>
			</form>

			<div class="controls">&nbsp;</div>
			<?php if ($this->session->flashdata('success')): ?> <!-- TRABAJO CREADO CORRECTAMENTE -->
				<div class="alert alert-success">
					<a class="close" data-dismiss="alert" href="#">&times;</a>
					<?php echo $this->session->flashdata('success'); ?>
				</div>
			<?php endif ?>
			<?php if ($this->session->flashdata('error')): ?> <!-- TRABAJO CREADO CORRECTAMENTE -->
				<div class="alert alert-error">
					<a class="close" data-dismiss="alert" href="#">&times;</a>
					<?php echo $this->session->flashdata('error'); ?>
				</div>
			<?php endif ?>
			<table class="table">
				<thead>
				<tr>
					<th>ID</th>
					<th>Autor</th>
					<th>Regalías</th>
					<th>Acción</th>
				</tr>
				</thead>
				<tbody>
				<?php if(!isset($usuarios_regalias) or sizeof($usuarios_regalias) < 1):?>
					<tr>
						<td colspan="7">No se encontraron resultados</td>
					</tr>
				<?php else:?>
					<?php foreach($usuarios_regalias as $autor): ?>
						<tr class="tr_tipo_<?php echo $autor['idUsuarios'];?>">
							<td><?php echo $autor['idUsuarios'];?></td>
							<td>
								<a href="<?php echo PUBLIC_FOLDER_ADMIN;?>pagos/ver_pagos_pendientes/<?php echo $autor['idUsuarios'];?>">
									<?php echo $autor['apellido'] . ' ' . $autor['nombre'];?>
								</a>
							</td>
							<td><?php echo $autor['regalias'];?></td>
							<td>
								<a href="<?php echo PUBLIC_FOLDER_ADMIN;?>pagos/ver_pagos_pendientes/<?php echo $autor['idUsuarios'];?>" class="sepV_a btn" title="Ver pagos pendientes">
									<i class="icon-search"></i>
								</a>
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





