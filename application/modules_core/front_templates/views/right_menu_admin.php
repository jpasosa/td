<td id="cellSide">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" id="sidebar">
		<tr>
			<td>
				<div align="center"><img src="<?php echo ASSETS . 'style/images/logorojo.png'; ?>" alt="WordRev" width="128" height="31" /></div>
				<div id="sidebarLogin">
					<p class="tituloPanel">Tu Cuenta </p>
					<p> <a href="<?=site_url('tucuenta/editarPerfil/' . $id_user);?>">Editar perfil</a></p>
					<p> <a href="<?=site_url('tucuenta/mis_favoritos/' . $id_user . '/pagina/1');?>">Favoritos</a></p>
					<p> <a href="<?=site_url('tucuenta/mis_publicaciones/' . $id_user . '/pagina/1');?>">Publicaciones</a></p>
					<p> <a href="<?=site_url('trabajos/alta');?>">Nueva publicación</a></p>
					<p> <a href="<?=site_url('tucuenta/mis_estadosdecuenta/' . $id_user . '/pagina/1');?>">Estado de cuenta</a></p>
					<p> <a href="<?=site_url('tucuenta/acceso_editorial');?>">Acceso para Editoriales</a></p>
				</div>
			</td>
		</tr>
	</table>
</td>