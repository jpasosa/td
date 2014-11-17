<td id="cellBody">
	<?php foreach ($destacados as $k => $dest): ?>
		<h3 class="fadelink">
			<a href="<?=site_url('trabajos/ver/' . $dest['idTrabajos']);?>">
				<?php echo $dest['titulo']; ?>
			</a>
		</h3>

		<div class="clearfix">
			<div class="articuloDatosAutor">
				<div class="imagenarticulo">
					<?php if ($dest['foto'] == ''): ?>
						<img src="<?php echo ASSETS . 'style/images/default_cover.png'; ?>" alt="Autores de WordRev" name="imagenarticulo" width="146" height="146" />
					<?php else: ?>
						<img src=" <?php echo UPLOADS . 'trabajos/portadas/' . $dest['foto']; ?>" name="imagenarticulo" width="146" height="146" />
					<?php endif ?>
				</div>
				<p>Por:</p>

				<div class="nombre">
					<a href="#">
						<?php if (isset($dest['autor']['avatar'])): ?>
							<?php if ( $dest['autor']['avatar'] == ''): ?>
								<img src="<?php echo ASSETS . 'style/images/default_avatar_thumb.png'; ?>" alt="Autor" width="52" height="30" />
							<?php else: ?>
								<img src="<?php echo UPLOADS . 'usuarios/avatar/' . $dest['autor']['avatar'] ?>" alt="Autor" width="52" height="30" />
							<?php endif ?>
						<?php endif ?>
						<?php if (isset($dest['autor']['idUsuarios'])): ?>
							<a href="<?=site_url('tucuenta/perfil/' . $dest['autor']['idUsuarios']);?>">
								<?php if (isset($dest['autor']['nombre'])): ?>
										<?php echo $dest['autor']['nombre']; ?>
								<?php endif; ?>
								<?php echo  ', '; ?>
								<?php if (isset($dest['autor']['apellido'])): ?>
										<?php echo $dest['autor']['apellido']; ?>
								<?php endif; ?>
							</a>
						<?php endif; ?>
					</a>
				</div>
				<p class="italicas">
					<?php if (isset($dest['autor']['profesion'])): ?>
						<?php echo $dest['autor']['profesion']; ?>
					<?php endif; ?>
				</p>
				<p>Ha publicado <?php echo $dest['autor']['publicados'] ?> trabajos.</p>
			</div>

			<div class="articuloDetalles">
				<div class="indexacion">
					<p>
						Sección:
						<span class="resaltado">
							<a href="<?=site_url('temas/ver/' . $dest['categoria']['id']);?>">
								<?php echo $dest['categoria']['nombre']; ?>
							</a>
						</span>
					</p>

					<p>Subsección:
						<?php if ($dest['sub_categoria'] != NULL): ?>
							<?php foreach ($dest['sub_categoria'] as $sc): ?>
								<span class="resaltado">
									<a href="<?=site_url('temas/ver/' . $sc['id']);?>">
										<?php echo $sc['nombre']; ?>
									</a>
								</span>
							<?php endforeach ?>
						<?php endif ?>
					</p>
					<p>Palabras clave: <span class="resaltado"><?php echo $dest['palabrasClave']; ?></span><br />
					</p>
				</div>
				<div class="información">
					<p class="resaltado">Resúmen</p>
					<p><?php echo $dest['resumen']; ?></p>
				</div>
			</div>
		</div>
	<?php endforeach ?>
</td>

