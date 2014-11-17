<tr>
	<td id="cellFooterRandom">
		<div id="footerRandomDiv">
			<!-- Divisor entre contenidos principales y bloque de Articulos Relacionados //-->
			<img src="<?php echo ASSETS . '/style/images/lineadoble.png';?>" width="100%" height="5" />

			<!-- Tabla  bloque de Articulos Relacionados //-->



			<div id="tablaRandomPie">
				<div class="box_rand">
					<?php foreach ($works_random AS $wr): ?>
						<div class="box">
							<div class="image">
								<?php if ($wr['categoria']['imagen'] == ''): ?>
									<img src="<?php echo ASSETS . '/style/images/imagen_categoria_default_tmb.png' ?>" alt="Articulo Publicado" width="70" height="70" />
								<?php else: ?>
									<img class="cat_imagen_random" src="<?php echo RAIZ . UPLOADS . 'categorias/iconos/' . $wr['categoria']['imagen']; ?>"
										alt="Categoria de WordRev" width="70" height="70" />
								<?php endif; ?>
							</div>
							<div class="texto">
								<a href="<?=site_url('trabajos/ver/' . $wr['idTrabajos']);?>">
									<?php echo $wr['titulo']; ?>
								</a>
								<span>(<?php echo $wr['categoria']['nombre']; ?>) </span>
							</div>
						</div>

					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</td>
</tr>