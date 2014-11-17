<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="row-fluid">
	<div class="span12">
	<h3 class="heading">
		<a href="<?php echo PUBLIC_FOLDER_ADMIN;?>trabajos/pendientes.html">Trabajos pendientes de aprobación</a>	 :: <a href="<?php echo PUBLIC_FOLDER;?>trabajos/nuevo.html">Nuevo</a>
		</h3>
		<div class="span12">
			<select id="filter">
				<option value="" <?php if(isset($filter)) echo 'selected="selected"';?>>Filtrar por</option>
				<option value="idTrabajos" <?php if(isset($filter) and $filter == "idTrabajos") echo 'selected="selected"';?>>ID</option>
				<option value="userName" <?php if(isset($filter) and $filter == "userName") echo 'selected="selected"';?>>Nombre de Usuario</option>
				<option value="titulo" <?php if(isset($filter) and $filter == "titulo") echo 'selected="selected"';?>>Titulo</option>
			</select>
			<input type="text" name="value" id="value" value="<?php if(isset($value)) echo urldecode($value);?>">
			<button class="btn" type="button" id="btn-filter">Filtrar</button>
			<button class="btn" type="button" id="btn-clean">Limpiar</button>
			<div class="controls">&nbsp;</div>
			<table class="table">
				<thead>
					<tr>
						<th>ID</th>
						<th>Titulo</th>
						<th>Autor</th>
						<th>Fecha</th>
						<th>Precio sin derechos</th>
						<th>Precio con derechos</th>
						<th>Monto al autor</th>
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
								<td>$<?php echo $trabajo['precio_sin_derecho'];?></td>
								<td>$<?php echo $trabajo['precio_con_derecho'];?></td>
								<td>$<?php echo $trabajo['monto_por_venta'];?></td>
								<td>
									<a href="<?php echo PUBLIC_FOLDER_ADMIN;?>trabajos/editar/<?php echo $trabajo['idTrabajos'];?>" class="sepV_a btn" title="Ir">
										<i class="icon-eye-open"></i>
									</a>
									<button  type="button" title="Aprobar" class="aprobar" value="<?php echo $trabajo['idTrabajos'];?>">
										<i class="icon-thumbs-up"></i>
									</button>
									<button  type="button" title="Aprobar y Notificar" class="notify" value="<?php echo $trabajo['idTrabajos']; ?>">
										<i class="icon-ok"></i>
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


