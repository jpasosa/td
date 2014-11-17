<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="row-fluid">
	<div class="span12">
		<h3 class="heading">
			<a href="<?php echo PUBLIC_FOLDER_ADMIN;?>categorias/listar.html">Categorias</a> :: <?php echo $titulo;?>
		</h3>
		<div class="row-fluid">
			<div class="span8">
				<form method="post" id="form" class="form-horizontal" action="<?php echo $form_action;?>" enctype="multipart/form-data">
					<?php if(isset($idCategorias) and  $idCategorias > 0):?>
						<input type="hidden" name="idCategorias" value="<?php echo $idCategorias;?>">
					<?php endif;?>
					<fieldset>
						<div class="control-group form-sep">
							<label class="control-label">Nombre &nbsp;</label>
							<div class="controls">
								<input type="text" autocomplete="off" name="nombreCategoria" id="nombreCategoria" class="input-medium" value="<?php if(isset($categoria['nombreCategoria']) ) echo $categoria['nombreCategoria'];?>"> <a href="#" class="required" rel="tooltip" data-placement="right" title="El nombre es requerido"><span class="f_req">*</span> </a>
							</div>
							<div class="controls">&nbsp;</div>
							<label class="control-label">Nueva subcategoría &nbsp;</label>
							<div class="controls">
								<input type="text" autocomplete="off" name="nombreSubCategoria" id="nombreSubCategoria" class="input-medium" value="<?php if(isset($categoria['nombreSubCategoria']) ) echo $categoria['nombreSubCategoria'];?>">
							</div>
							<div class="controls">&nbsp;</div>
							<label class="control-label"> &nbsp;</label>
							<div class="controls">
								<select multiple="multiple" name="parentId[]" class="multiselect" id="optgroup">
								<?php if(isset($idCategorias)):?>
									<?php foreach($categorias as $_categoria): ?>
										<?php if(isset($idCategorias) and $idCategorias != $_categoria['idCategorias']):?>
												<option value="<?php echo $_categoria['idCategorias'];?>" >
													<?php echo $_categoria['nombreCategoria'];?>
												</option>
										<?php endif;?>
									<?php endforeach;?>
								<?php else:?>
									<?php foreach($categorias as $_categoria): ?>
												<option value="<?php echo $_categoria['idCategorias'];?>" >
													<?php echo $_categoria['nombreCategoria'];?>
												</option>
									<?php endforeach;?>
								<?php endif;?>
								</select>
							</div>

							<!-- IMAGEN DE CATEGORIA -->
							<?php if (isset($parent_category) && $parent_category): ?>
								<div class="controls">&nbsp;</div>
								<div class="formSep">
									<label class="control-label">Imagen de Categoria&nbsp;</label>
									<div class="control-group">
										<input type="file" name="image"><br />
										<?php if (isset($categoria['imagen']) && $categoria['imagen'] != ''): ?>
											<input type="hidden" name="image_name" value="<?php echo $categoria['imagen'];?>" />
											<a href="<?php echo RAIZ . UPLOADS. 'categorias/iconos/' . $categoria['imagen']; ?>" target="_blank">
												<?php echo $categoria['imagen']; ?>
											</a>
										<?php endif; ?>

										<?php if ($section == 'admin_categorias.editar' && $show_del_image): ?>
											<input class="del_imagen" type="checkbox" name="del_imagen" value="0" />
                            							Eliminar imágen
										<?php endif; ?>


										<!--
										<?php if(isset($categoria['image']) and !empty($categoria['image'])):?>
											<input type="hidden" name="image" value="<?php echo $categoria['imagen'];?>">
											<label class="control-label">
												<a href="<?php echo '/' . UPLOADS. "trabajos/portadas/".$categoria['image'];?>" target="_blank">
													<?php echo $categoria['image']; ?>
												</a>
												<img align="left" src="<?php echo PUBLIC_FOLDER_FC. "trabajos/portadas/".$categoria['image'];?>" />
												<input type="checkbox" name="image_del" value="<?php echo $categoria['image'];?>" />
												Eliminar
											</label> <br />
										<?php endif;?>
									-->
									</div>
								</div>
							<?php endif ?>



							<div class="controls">&nbsp;</div>
							<div class="controls">
								<button class="btn btn-gebo submit" type="submit"><?php echo $boton;?></button>
								<a href="<?php echo PUBLIC_FOLDER_ADMIN;?>categorias/listar.html" class="btn">Volver al listado</a>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
$(document).ready(function(){
	$('.required').tooltip();
	$('.multiselect').multiSelect({
		selectableHeader	: '<h4>Categorías sin utilizar</h4>',
		selectedHeader		: '<h4>Subcategorias seleccionadas</h4>'
		});

	<?php if(isset($categoria['subCategorias']) and sizeof($categoria['subCategorias']) > 0 ):?>
	<?php foreach($categoria['subCategorias'] as $subCategoria):?>
		$('.multiselect').multiSelect('select', ["<?php echo $subCategoria['idCategorias'];?>"] );
	<?php endforeach;?>

<?php endif;?>
});
</script>
