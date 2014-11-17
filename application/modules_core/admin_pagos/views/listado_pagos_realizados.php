<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="row-fluid">
	<div class="span12">




	<h3 class="heading">
		Listado de Pagos Realizados.
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
				<a href="<?php echo PUBLIC_FOLDER_ADMIN;?>pagos/listado_pagos_realizados.html" class="btn">Limpiar</a>
			</form>
			<!-- <select id="filter">
				<option value="" <?php if(isset($filter)) echo 'selected="selected"';?>>Filtrar por</option>
				<!-- <option value="idTrabajos" <?php if(isset($filter) and $filter == "idTrabajos") echo 'selected="selected"';?>>
					ID Trabajos
				</option>

				<option value="idPedidos" <?php if(isset($filter) and $filter == "idPedidos") echo 'selected="selected"';?>>
					ID Pedidos
				</option>

				<option value="titulo" <?php if(isset($filter) and $filter == "titulo") echo 'selected="selected"';?>>
					Titulo del Trabajo
				</option>

				<option value="fecha" <?php if(isset($filter) and $filter == "fecha") echo 'selected="selected"';?>>
					Fecha
				</option>
			</select> -->
			<!-- <input type="text" name="value" id="value" value="<?php if(isset($value)) echo urldecode($value);?>">
			<button class="btn" type="button" id="btn-filter">
				Filtrar
			</button>
			<button class="btn" type="button" id="btn-clean">Limpiar</button> -->
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
					<th>Fecha de Pago</th>
					<th>Autor</th>
					<th>Monto</th>
					<th>Acción</th>
				</tr>
				</thead>
				<tbody>
				<?php if(isset($pagos_realizados) && count($pagos_realizados) == 0):?>
					<tr>
						<td colspan="7">No se encontraron resultados</td>
					</tr>
				<?php else:?>
					<?php foreach($pagos_realizados as $pago): ?>
						<tr class="tr_tipo_<?php echo $pago['idUsuarios'];?>">
							<td><?php echo $pago['idPagos'];?></td>
							<td><?php echo $pago['fecha_pago'];?></td>
							<td>
								<?php echo $pago['nombre'] . ' ' . $pago['apellido'];?>
							</td>
							<td><?php echo $pago['monto_total'];?></td>
							<td>
								<a href="<?php echo PUBLIC_FOLDER_ADMIN;?>pagos/ver_pago_realizado/<?php echo $pago['idPagos'];?>" class="sepV_a btn" title="Ver en detalle">
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





