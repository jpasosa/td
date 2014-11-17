<td id="cellBody">
	Resultados para su búsqueda "<?php echo $palabras_clave; ?>"
	<?php foreach ($buscados as $k => $public): ?>
		<h3 class="fadelink">
			<a href="<?=site_url('trabajos/ver/' . $public['idTrabajos']);?>">
				<?php echo $public['titulo']; ?>
			</a>
		</h3>

		<div class="clearfix">
			<div class="articuloDatosAutor">
				<div class="imagenarticulo">
					<?php if ($public['foto'] == ''): ?>
						<img src="<?php echo ASSETS . 'style/images/default_cover.png'; ?>" alt="Autores de WordRev" name="imagenarticulo" width="146" height="146" />
					<?php else: ?>
						<img src=" <?php echo UPLOADS . 'trabajos/portadas/' . $public['foto']; ?>" name="imagenarticulo" width="146" height="146" />
					<?php endif ?>
				</div>
				<p>Por:</p>

				<div class="nombre">
					<a href="<?=site_url('tucuenta/perfil/' . $public['autor']['idUsuarios']);?>">
						<?php if (isset($public['autor']['avatar'])): ?>
							<?php if ( $public['autor']['avatar'] == ''): ?>
								<img src="<?php echo ASSETS . 'style/images/default_avatar_thumb.png'; ?>" alt="Autor" width="52" height="30" />
							<?php else: ?>
								<img src="<?php echo UPLOADS . 'usuarios/avatar/' . $public['autor']['avatar'] ?>" alt="Autor" width="52" height="30" />
							<?php endif ?>
						<?php endif ?>
						<?php if (isset($public['autor']['nombre'])): ?>
								<?php echo $public['autor']['nombre']; ?>
						<?php endif; ?>
						<?php echo  ', '; ?>
						<?php if (isset($public['autor']['apellido'])): ?>
								<?php echo $public['autor']['apellido']; ?>
						<?php endif ?>
					</a>
				</div>
				<p class="italicas">
					<?php if (isset($public['autor']['profesion'])): ?>
						<?php echo $public['autor']['profesion']; ?>
					<?php endif; ?>
				</p>
				<p>Ha publicado <?php echo $public['autor']['publicados'] ?> trabajos.</p>
			</div>

			<div class="articuloDetalles">
				<div class="indexacion">
					<p>
						Sección:
						<span class="resaltado">
							<a href="<?=site_url('temas/ver/' . $public['categoria']['id']);?>">
								<?php echo $public['categoria']['nombre']; ?>
							</a>
						</span>
					</p>

					<p>Subsección:
						<?php if ($public['sub_categoria'] != NULL): ?>
							<?php foreach ($public['sub_categoria'] as $sc): ?>
								<span class="resaltado">
									<a href="<?=site_url('temas/ver/' . $sc['id']);?>">
										<?php echo $sc['nombre']; ?>
									</a>
								</span>
							<?php endforeach ?>
						<?php endif ?>
					</p>
					<p>Palabras clave: <span class="resaltado"><?php echo $public['palabrasClave']; ?></span><br />
					</p>
				</div>
				<div class="información">
					<p class="resaltado">Resúmen</p>
					<p><?php echo $public['resumen']; ?></p>
				</div>
			</div>
		</div>
	<?php endforeach ?>
</td>

