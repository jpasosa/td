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
		<a href="<?php echo PUBLIC_FOLDER_ADMIN;?>compras/listar_compras.html">Compras</a>::
		<a href="<?php echo PUBLIC_FOLDER_ADMIN;?>compras/cargar_compra.html">Nueva</a>
	</h3>
		<div class="span12">
			<select id="filter">
				<option value="" <?php if(isset($filter)) echo 'selected="selected"';?>>Filtrar por</option>
				<option value="idCompra" <?php if(isset($filter) and $filter == "idCompra") echo 'selected="selected"';?>>
					ID Compra
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
					<th>ID pedido</th>
					<th>Publicación</th>
					<th>Autor</th>
					<th>Monto al Autor</th>
					<th>Monto de Venta</th>
					<th>Regalias</th>
					<th>Notificado</th>
					<th>Acción</th>
				</tr>
				</thead>
				<tbody>
				<?php if(!isset($compras) or sizeof($compras) < 1): ?>
					<tr>
						<td colspan="7">No se encontraron resultados</td>
					</tr>
				<?php else: ?>
					<?php foreach($compras as $comp): ?>
						<tr class="tr_tipo_<?php echo $comp['idRegalias'];?>">
							<td><?php echo $comp['idRegalias'];?></td>
							<td><?php echo date('d-m-Y',strtotime($comp['fecha']));?></td>
							<td>
								<a href="<?php echo PUBLIC_FOLDER_ADMIN;?>compras/ver_pedido/<?php echo $comp['idPedidos'];?>" >
									<?php echo $comp['idPedidos'];?>
								</a>
							</td>
							<td><?php echo $comp['titulo'];?></td>
							<td><?php echo $comp['emailAutor'];?></td>
							<td><?php echo $comp['monto_al_autor'];?></td>
							<td><?php echo $comp['monto_de_venta'];?></td>
							<td>
								<?php if ($comp['estado_regalias'] == 0): ?>
									NO ASIGNADAS
								<?php else: ?>
									ASIGNADAS
								<?php endif; ?>
							</td>
							<td>
								<?php if ($comp['notificado'] == 0): ?>
									NO
								<?php else: ?>
									SI
								<?php endif; ?>
							</td>
							<td>
								<?php if ($comp['estado_regalias'] == 0): ?>
									<button  type="button" title="Asignar Regalías" class="asignar_regalia" value="<?php echo $comp['idRegalias']; ?>">
										<i class="icon-user"></i>
									</button>
								<?php endif; ?>
								<?php if ($comp['notificado'] == 0): ?>
									<button  type="button" title="Notificar al Autor" class="notify" value="<?php echo $comp['idRegalias']; ?>">
										<i class="icon-envelope"></i>
									</button>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach;?>
				<?php endif; ?>
				</tbody>
			</table>
			<?php echo $paginas;?>
		</div>
	</div>
</div>





