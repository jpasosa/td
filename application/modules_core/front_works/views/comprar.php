<td id="cellBody">
	<div class="breadcrum">
		Ubicación:
		<a href="<?=site_url('temas/listar');?>">Temas</a> >
		<a href="<?=site_url('temas/ver/' . $work['categoria']['id']);?>">
			<?php echo $work['categoria']['nombre']; ?>
		</a>
		<!-- Si existen sub-categorias -->
		<?php if ($work['sub_categoria'] != NULL): ?>
			>
			<?php foreach($work['sub_categoria'] AS $sub_cat): ?>
				<a href="<?=site_url('temas/ver/' . $sub_cat['id']);?>">
					<?php echo $sub_cat['nombre'] . ' '; ?>
				</a>
			<?php endforeach; ?>
		<?php endif ?>
		>
		<?php echo $work['titulo']; ?>
	</div>
	<h3><?php echo $work['titulo'] ?></h3>
	<div class="clearfix publicacion">
		<div class="articuloDatosAutor">
			<div class="imagenarticulo">
				<?php if ($work['foto'] == ''): ?>
					<img src="<?php echo ASSETS . 'style/images/image_work_void.jpg'; ?>" alt="Autores de WordRev" name="imagenarticulo" width="146" height="146" />
				<?php else: ?>
					<img src=" <?php echo UPLOADS . 'trabajos/portadas/' . $work['foto']; ?>" name="imagenarticulo" width="146" height="146" />
				<?php endif ?>
			</div>
			<!--
			<p>Por:</p>
			<div class="nombre">
				<a href="#">
					<?php if (isset($work['autor']['avatar']) && $work['autor']['avatar'] == ''): ?>
						<img src="<?php echo ASSETS . 'style/images/avatar_tmb.jpg'; ?>" alt="Autor" width="52" height="30" />
					<?php elseif(isset($work['autor']['avatar']) && $work['autor']['avatar'] != ''): ?>
						<img src="<?php echo UPLOADS . 'usuarios/avatar/' . $work['autor']['avatar'] ?>" alt="Autor" width="52" height="30" />
					<?php endif; ?>
					<?php if (isset($work['autor']['apellido']) && isset($work['autor']['apellido'])): ?>
						<?php echo $work['autor']['apellido'] . ', ' . $work['autor']['nombre']; ?>
					<?php endif ?>
				</a>
			</div>
			<p class="italicas">&quot;
				<?php if (isset($work['autor']['profesion'])): ?>
					<?php echo $work['autor']['profesion']; ?>
				<?php endif ?>
			</p>
			<p>Ha publicado <?php echo $work['autor']['publicados'] ?> trabajos.</p>
			-->
		</div>

		<div class="articuloDetalles">
			<div class="favorito">
				<!--
				<?php if ($login_user && !$is_favorito): ?>
					<a class="add_favoritos" id_user="<?php echo $id_user; ?>" id_trabajo="<?php echo $work['idTrabajos']; ?>" href="#" title="Agregar a Favoritos">
						<img src="<?php echo ASSETS . 'style/images/favorito.png'; ?>" />
					</a>
				<?php elseif ($login_user && $is_favorito): ?>
					<img src="<?php echo ASSETS . 'style/images/mostrar_favorito.png'; ?>" />
				<?php endif; ?>
				-->
			</div>
			<div class="indexacion">
				<p>
					Sección:
					<span class="resaltado">
						<a href="<?=site_url('temas/ver/' . $work['categoria']['id']);?>">
							<?php echo $work['categoria']['nombre']; ?>
						</a>
					</span>
				</p>
				<?php if ($work['sub_categoria'] != NULL): ?>
					<p>
						Subsección:
						<span class="resaltado">
							<?php foreach ($work['sub_categoria'] as $key => $sub_cat): ?>
								<a href="<?=site_url('temas/ver/' . $sub_cat['id']);?>">
									<?php echo $sub_cat['nombre'] . ' '; ?>
								</a>
							<?php endforeach; ?>
						</span>
					</p>
				<?php endif; ?>
				<p>
					Páginas:
					<span class="resaltado">
						<?php echo $work['cantidad_paginas']; ?>
					</span>
				</p>
				<p>
					Palabras clave:
					<span class="resaltado"><?php echo $work['palabrasClave']; ?></span>
				</p>
				<p>Precio: <span class="resaltado">$<?php echo $work['precio_con_derecho']; ?></span></p>
			</div>

			<div class="informacion">
			      <p class="resaltadoGrande">Solicitar este artículo</p>
			      <p>
			      	Haga click en "Enviar Pedido" para que un representante se comunique con Ud.
		      		y así coordinar la operación de forma totalmente personalizada.
			      </p>
        		</div>

		</div>
	</div>
	<p class="btn" id="btcomprar">
		<a href="<?=site_url('front_works/confirmar_comprar/' . $id_publicacion);?>">
			Enviar Pedido
		</a>
	</p>
</td>
