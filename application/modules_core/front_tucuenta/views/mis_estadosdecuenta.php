
<td id="cellBody">
	<h3> Mi estado de cuenta </h3>
	<!-- INICIO Presentacion de USUARIO //-->
	<div class="temasSeccion">
		<div class="imagenarticulo">
			<?php if ($author['avatar'] == ''): ?>
				<img src="<?php echo ASSETS . 'style/images/default_avatar.png'; ?>" name="imagenarticulo" alt="Foto del Autor" width="146" height="146" />
			<?php else: ?>
				<img src=" <?php echo UPLOADS . 'usuarios/avatar/' . $author['avatar']; ?>"  width="146" height="146" />
			<?php endif ?>
		</div>
		<!-- <p>Secci&oacute;n: <span class="resaltado"><a href="subtemas_articulos.php">Lengua y Literatura</a></span></p> //-->
	</div>
	<!-- INICIO Panel estado de cuenta //-->
	<div class="estadoCuenta">
		<p> Monto acumulado: U$D <?php echo $monto_acumulado; ?><p/>
		<p> Operaciones realizadas: <p/>
	</div>
	<!-- FINAL Panel estado de cuenta //-->
	<!-- INICIO Grupo de articulos articulo //-->
	<div class="temasArticulosEnCat clearfix">
		<?php foreach ($works_sold AS $ws): ?>
			<div class="misArticulos">
				<div class="imagenarticulosmall">
					<?php if ($ws['foto'] == ''): ?>
						<img src="<?php echo ASSETS . 'style/images/image_work_void.jpg'; ?>" name="imagenarticulo" alt="imágen de la publicación" name="imagenarticulo" width="98" height="98" />
					<?php else: ?>
						<img src=" <?php echo UPLOADS . 'trabajos/portadas/' . $ws['foto']; ?>"  width="98" height="98" />
					<?php endif ?>
				</div>
				<div class="indexacion">
					<p class="temasTitulo">
						<a href="articulo.php"><?php echo $ws['titulo']; ?></a>
					</p>
					<p>
						Sección:
						<span class="resaltado"><?php echo $ws['categoria']['nombre']; ?></span>
					</p>
					<p>&nbsp;</p>
				</div>
				<div class="informacion2">
					<p>
						Fecha:
						<span class="resaltado"><?php echo $ws['fecha']; ?></span>
					</p>
					<p>
						Ingresos generados:
						<span class="resaltado">$ <?php echo $ws['monto_venta_total']; ?></span>
					</p>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
	<p>
		Aquí verás reflejadas tus ganancias, ya sea por ventas finales o ventas por período de suscripción. En cuanto a las ventas finales se liquidarán al mes siguiente, en cuanto a las ventas por suscripción la liquidación puede demorar hasta 3 meses, de acuerdo a la liquidación que nos hagan nuestros proveedores. De todas formas verás acreditadas tus ganancias lo más rápido posible.
		<strong>Importante: No olvide colocar su dirección real, es donde recibirá su pago!</strong>
		<br />
	</p>
	<!-- FINAL Presentacion de CATEGORIA //-->
	<div class="paginador"><?php echo $paginador; ?></div>
</td>