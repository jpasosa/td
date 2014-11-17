<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="row-fluid">
	<div class="span12">
	<h3 class="heading">
		<a href="<?php echo PUBLIC_FOLDER_ADMIN;?>categorias/listar.html">Categorías</a>	 ::
		<a href="<?php echo PUBLIC_FOLDER_ADMIN;?>categorias/nueva.html">Nueva</a>
		</h3>
		<div class="span12">
			<select id="filter">
				<option value="" <?php if(isset($filter)) echo 'selected="selected"';?>>Filtrar por</option>
				<option value="idCategorias" <?php if(isset($filter) and $filter == "idCategorias") echo 'selected="selected"';?>>ID</option>
				<option value="nombreCategoria" <?php if(isset($filter) and $filter == "nombreCategoria") echo 'selected="selected"';?>>Nombre</option>
			</select>
			<input type="text" name="value" id="value" value="<?php if(isset($value)) echo urldecode($value);?>">
			<button class="btn" type="button" id="btn-filter">Filtrar</button>
			<button class="btn" type="button" id="btn-clean">Limpiar</button>
			<div class="controls">&nbsp;</div>
			<table class="table">
				<thead>
					<tr>
						<th>ID</th>
						<th>Nombre</th>
						<th>Acción</th>
					</tr>
				</thead>
				<tbody>
					<?php if(!isset($categorias) or sizeof($categorias) < 1):?>
						<tr><td colspan="7">No se encontraron resultados</td></tr>
					<?php else:?>
						<?php foreach($categorias as $categoria): ?>
							<tr class="tr_tipo_<?php echo $categoria['idCategorias'];?>">
								<td><?php echo $categoria['idCategorias'];?></td>
								<td><?php echo $categoria['nombreCategoria'];?></td>
								<td>
									<a href="<?php echo PUBLIC_FOLDER_ADMIN;?>categorias/editar/<?php echo $categoria['idCategorias'];?>.html" class="sepV_a btn" title="Editar">
										<i class="icon-pencil"></i>
									</a>
									<button  type="button" title="Eliminar" class="delete-tipo" value="<?php echo $categoria['idCategorias'];?>">
										<i class="icon-trash"></i>
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

