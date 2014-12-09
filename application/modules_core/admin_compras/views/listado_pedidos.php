<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="row-fluid">
	<div class="span12">

	<?php if ($this->session->flashdata('success')): ?>
	<?php echo 'okaaaa'; die(); ?>
		<div class="alert alert-success">
			<a class="close" data-dismiss="alert" href="#">&times;</a>
			<?php echo $this->session->flashdata('success'); ?>
		</div>
	<?php endif ?>


	<h3 class="heading">
		<a href="<?php echo PUBLIC_FOLDER_ADMIN;?>compras/listar_pedidos.html">Pedidos</a>::
		<a href="<?php echo PUBLIC_FOLDER_ADMIN;?>compras/cargar_compra.html">Nueva</a>
	</h3>
		<div class="span12">
			<select id="filter">
				<option value="" <?php if(isset($filter)) echo 'selected="selected"';?>>Filtrar por</option>
				<option value="idTrabajos" <?php if(isset($filter) and $filter == "idTrabajos") echo 'selected="selected"';?>>
					ID Trabajos
				</option>

				<option value="idPedidos" <?php if(isset($filter) and $filter == "idPedidos") echo 'selected="selected"';?>>
					ID Pedidos
				</option>

				<option value="titulo" <?php if(isset($filter) and $filter == "titulo") echo 'selected="selected"';?>>
					Titulo del Trabajo
				</option>

				<!-- <option value="fecha" <?php if(isset($filter) and $filter == "fecha") echo 'selected="selected"';?>>
					Fecha
				</option> -->
			</select>
			<input type="text" name="value" id="value" value="<?php if(isset($value)) echo urldecode($value);?>">
			<button class="btn" type="button" id="btn-filter">
				Filtrar
			</button>
			<button class="btn" type="button" id="btn-clean">Limpiar</button>
			<div class="controls">&nbsp;</div>
			<?php if ($this->session->flashdata('work_success')): ?> <!-- comp CREADO CORRECTAMENTE -->
				<div class="alert alert-success">
					<a class="close" data-dismiss="alert" href="#">&times;</a>
					<?php echo $this->session->flashdata('work_success'); ?>
				</div>
			<?php endif ?>
			<table class="table">
				<thead>
				<tr>
					<th>ID</th>
					<th>Fecha</th>
					<th>Comprador</th>
					<th>Publicacion</th>
					<th>Monto Venta</th>
					<!-- <th>Modalidad</th> -->
					<th>Autor</th>
					<!-- <th>Monto al Autor</th> -->
					<!-- <th>Estado</th> -->
					<!-- <th>Notificado</th> -->
					<th>Acción</th>
				</tr>
				</thead>
				<tbody>
				<?php if(!isset($compras) or sizeof($compras) < 1):?>
					<tr>
						<td colspan="7">No se encontraron resultados</td>
					</tr>
				<?php else:?>
					<?php foreach($compras as $comp): ?>
						<tr class="tr_tipo_<?php echo $comp['idPedidos'];?>">
							<td><?php echo $comp['idPedidos'];?></td>
							<td><?php echo date('d-m-Y',strtotime($comp['fecha']));?></td>
							<td><?php echo $comp['email_comprador'];?></td>
							<td><?php echo $comp['titulo'];?></td>
							<td><?php echo 'U$D' . $comp['monto_venta_total'];?></td>
							<td><?php echo $comp['emailUsuariosAutor'];?></td>
							<td>
								<a href="<?php echo PUBLIC_FOLDER_ADMIN;?>compras/editar/<?php echo $comp['idPedidos'];?>" class="sepV_a btn" title="Cargar Compra">
									<i class="icon-shopping-cart"></i>
								</a>
								<!--
								<button  type="button" title="Eliminar" class="delete-tipo" value="<?php echo $comp['idPedidos'];?>">
									<i class="icon-trash"></i>
								</button>
								<?php if ($comp['notificado'] == 0): ?>  si no fue notificado, mostrar el icono para notificarle al comprador
									<button  type="button" title="Notificar por mail" class="notify" value="<?php echo $comp['idPedidos']; ?>">
										<i class="icon-thumbs-up"></i>
									</button>
								<?php endif; ?>
								-->
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





