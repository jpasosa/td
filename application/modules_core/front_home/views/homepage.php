<?php if ($this->session->flashdata('work_success')): ?> <!-- TRABAJO CREADO CORRECTAMENTE -->
	<div class="alert-message alert-success" style="height: 30px;font-size: 16px;text-align:center;">
		<a class="close" data-dismiss="alert" href="#">&times;</a>
		<?php echo $this->session->flashdata('work_success'); ?>
	</div>
<?php endif ?>
<?php if ($this->session->flashdata('message_success')): ?> <!-- MENSAJE DE NOTICIA EXITOSO -->
	<div class="alert-message alert-success" style="height: 30px;font-size: 16px;text-align:center;">
		<a class="close" data-dismiss="alert" href="#">&times;</a>
		<?php echo $this->session->flashdata('message_success'); ?>
	</div>
<?php endif ?>
<?php if ($this->session->flashdata('message_error')): ?> <!-- MENSAJE DE NOTICIA CON ERROR -->
	<div class="alert-message alert-success" style="height: 30px;font-size: 16px;text-align:center;">
		<a class="close" data-dismiss="alert" href="#">&times;</a>
		<?php echo $this->session->flashdata('message_error'); ?>
	</div>
<?php endif ?>
<?php if ($this->session->flashdata('flash_error')): ?> <!-- TRABAJO CREADO CORRECTAMENTE -->
	<div class="alert-message alert-success" style="height: 30px;font-size: 16px;text-align:center;">
		<a class="close" data-dismiss="alert" href="#">&times;</a>
		<?php echo $this->session->flashdata('flash_error'); ?>
	</div>
<?php endif ?>


	<td id="cellBody">
		<div align="center">
			<img src=" <?php echo ASSETS . 'front/templates/images/homecenter1.png'; ?>" alt="WordRev" name="homecenter1" width="733" height="270" id="homecenter1" />
		</div>
		<div class="TablaCategorias">
			<?php foreach ($categorias AS $cat): ?>
				<div class="box_categoria">
					<?php if ($cat['categoria_imagen'] != ''): ?>
						<img class="cat_imagen" src="<?php echo RAIZ . UPLOADS . 'categorias/iconos/' . $cat['categoria_imagen']; ?>"
							alt="Categoria de WordRev" width="70" height="70" />
					<?php else: ?>
						<img src="<?php echo ASSETS . 'front/templates/images/imagen_categoria_default_tmb.png'; ?>" width="107" height="107" />
					<?php endif ?>
					<div class="filaCategoria">
						<span class="categoria">
							<a href="<?=site_url('temas/ver/' . $cat['categoria_id']);?>">
								<?php echo $cat['categoria_nombre']; ?>
							</a>
						</span><br />
						<?php if (isset($cat['subcategorias'])): ?>
							<?php foreach ($cat['subcategorias'] AS $sc): ?>
								<span class="subcategorias">
									<a href="<?=site_url('temas/ver/' . $sc['sub_cat_id']);?>">
										<?php echo $sc['sub_cat_nombre']; ?>
									</a>
								</span>
							<?php endforeach ?>
						<?php endif ?>
					</div>
				</div>
			<?php endforeach ?>
		</div>
	</td>



