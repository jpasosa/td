<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="row-fluid">
	<div class="span12">
	<h3 class="heading">
		<?php if (isset($section) && $section == 'mis_trabajos'): ?> <!-- LISTADO DE MIS TRABAJOS TODOS -->
			<a href="<?php echo PUBLIC_FOLDER_ADMIN;?>trabajos/mis_trabajos.html">Mis Trabajos</a>::
			<a href="<?php echo PUBLIC_FOLDER_ADMIN;?>trabajos/nuevo.html">Nuevo</a>
		<?php else: ?> <!-- LISTADO DE TRABAJOS TODOS -->
			<a href="<?php echo PUBLIC_FOLDER_ADMIN;?>trabajos/listar.html">Trabajos</a>::
			<a href="<?php echo PUBLIC_FOLDER_ADMIN;?>trabajos/nuevo.html">Nuevo</a>
		<?php endif ?>

	</h3>
		<div class="span12">
			<select id="filter">
				<option value="" <?php if(isset($filter)) echo 'selected="selected"';?>>Filtrar por</option>
				<option value="idTrabajos" <?php if(isset($filter) and $filter == "idTrabajos") echo 'selected="selected"';?>>ID</option>
				<?php if (isset($section) && $section == 'trabajos'): ?>
					<option value="userName" <?php if(isset($filter) and $filter == "userName") echo 'selected="selected"';?>>Nombre de Usuario</option>
				<?php endif ?>
				<option value="titulo" <?php if(isset($filter) and $filter == "titulo") echo 'selected="selected"';?>>Titulo</option>
			</select> <input type="text" name="value" id="value" value="<?php if(isset($value)) echo urldecode($value);?>"> <button class="btn" type="button" id="btn-filter">Filtrar</button><button class="btn" type="button" id="btn-clean">Limpiar</button>
			<div class="controls">&nbsp;</div>
			<?php if ($this->session->flashdata('work_success')): ?> <!-- TRABAJO CREADO CORRECTAMENTE -->
				<div class="alert alert-success">
					<a class="close" data-dismiss="alert" href="#">&times;</a>
					<?php echo $this->session->flashdata('work_success'); ?>
				</div>
			<?php endif ?>
			<table class="table">
				<thead>
				<tr>
					<th>ID</th>
					<th>Titulo</th>
					<th>Autor</th>
					<th>Fecha</th>
					<th>Estado</th>
					<th>Acción</th>
				</tr>
				</thead>
				<tbody>
				<?php if(!isset($trabajos) or sizeof($trabajos) < 1):?>
					<tr>
						<td colspan="7">No se encontraron resultados</td>
					</tr>
				<?php else:?>
					<?php foreach($trabajos as $trabajo): ?>
						<tr class="tr_tipo_<?php echo $trabajo['idTrabajos'];?>">
							<td><?php echo $trabajo['idTrabajos'];?></td>
							<td><?php echo $trabajo['titulo'];?></td>
							<td><?php echo $trabajo['email'];?></td>
							<td><?php echo date('d-m-Y',strtotime($trabajo['fecha']));?></td>
							<td><?php echo $trabajo['estado'];?></td>
							<td>
								<a href="<?php echo PUBLIC_FOLDER_ADMIN;?>trabajos/editar/<?php echo $trabajo['idTrabajos'];?>" class="sepV_a btn" title="Editar">
									<i class="icon-pencil"></i>
								</a>
								<button  type="button" title="Eliminar" class="delete-tipo" value="<?php echo $trabajo['idTrabajos'];?>">
									<i class="icon-trash"></i>
								</button>
								<?php if ($trabajo['idEstados'] == 2): ?>  <!-- SI ESTA APROBADO EL TRABAJO -->
									<button  type="button" title="Notificar por mail" class="notify" value="<?php echo $trabajo['idTrabajos']; ?>">
										<i class="icon-thumbs-up"></i>
									</button>
								<?php endif ?>
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





