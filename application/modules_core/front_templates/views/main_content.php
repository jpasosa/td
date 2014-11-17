
<!-- CONTENEDOR PRINCIPAL DE TODAS LAS VISTAS -->


<!-- HOMEPAGE -->
<?php if ($section == 'front_home.homepage'): ?>
	<tr>
		<?php $this->load->view('front_home/homepage');?>
		<?php $this->load->view('right_menu_home');?>
	</tr>
<?php endif; ?>



<!-- TEMAS :: LISTAR -->
<?php if ($section == 'front_topics.listar'): ?>
	<tr>
		<?php $this->load->view('front_topics/lista_topics');?>
		<?php $this->load->view('right_menu_home');?>
	</tr>
	<?php $this->load->view('footer_random');?>
<?php endif; ?>
<!-- TEMAS :: LISTAR POR CATEGORIA, hicimos click en ver mas-->
<?php if ($section == 'front_topics.listarPorCategoria'): ?>
	<tr>
		<?php $this->load->view('front_topics/lista_topics_porcategoria');?>
		<?php $this->load->view('right_menu_home');?>
	</tr>
	<?php $this->load->view('footer_random');?>
<?php endif; ?>
<!-- TEMAS :: VER -->
<?php if ($section == 'front_topics.ver'): ?>
	<tr>
		<?php $this->load->view('front_topics/ver_topics');?>
		<?php $this->load->view('right_menu_home');?>
	</tr>
	<?php $this->load->view('footer_random');?>
<?php endif; ?>



<!-- DESTACADOS -->
<?php if ($section == 'front_destacados.listar'): ?>
	<tr>
		<?php $this->load->view('front_destacados/articulos_destacados');?>
		<?php $this->load->view('right_menu_home');?>
	</tr>
	<?php $this->load->view('footer_random');?>
<?php endif; ?>

<!-- BUSCADOS -->
<?php if ($section == 'front_works.buscar'): ?>
	<tr>
		<?php $this->load->view('front_works/lista_buscados');?>
		<?php $this->load->view('right_menu_home');?>
	</tr>
	<?php $this->load->view('footer_random');?>
<?php endif; ?>


<!-- TEMPLATES :: PREGUNTAS FRECUENTES-->
<?php if ($section == 'front_templates.preguntas_frecuentes'): ?>
	<tr>
		<?php $this->load->view('front_templates/preguntas_frecuentes');?>
		<?php $this->load->view('right_menu_home');?>
	</tr>
	<tr></tr>
<?php endif; ?>

<!-- TEMPLATES :: acerca de nosotros-->
<?php if ($section == 'front_templates.acerca_de_nosotros'): ?>
	<tr>
		<?php $this->load->view('front_templates/acerca_de_wordrev');?>
		<?php $this->load->view('right_menu_home');?>
	</tr>
	<tr></tr>
<?php endif; ?>

<!-- TEMPLATES :: COPYRIGHT-->
<?php if ($section == 'front_templates.copyright'): ?>
	<tr>
		<?php $this->load->view('front_templates/copyright');?>
		<?php $this->load->view('right_menu_home');?>
	</tr>
	<tr></tr>
<?php endif; ?>

<!-- TEMPLATES :: PROVACIDAD-->
<?php if ($section == 'front_templates.privacidad'): ?>
	<tr>
		<?php $this->load->view('front_templates/privacidad');?>
		<?php $this->load->view('right_menu_home');?>
	</tr>
	<tr></tr>
<?php endif; ?>

<!-- BENEFICIOS -->
<?php if ($section == 'front_templates.view'): ?>
	<tr>
		<?php $this->load->view('front_templates/beneficios');?>
		<?php $this->load->view('right_menu_home');?>
	</tr>
	<?php $this->load->view('footer_random');?>
<?php endif; ?>

<!-- FORMULARIO DE CONTACTO-->
<?php if ($section == 'front_templates.contacto'): ?>
	<tr>
		<?php $this->load->view('front_templates/contacto');?>
		<?php $this->load->view('right_menu_home');?>
	</tr>
	<tr></tr>
<?php endif; ?>



<!-- LOGIN -->
<?php if ($section == 'front_login.index' || $section == 'front_login.validate'): ?>
	<tr>
		<?php $this->load->view('front_login/login');?>
		<?php $this->load->view('right_menu_home');?>
	</tr>
	<tr></tr>
<?php endif; ?>
<!-- LOGIN :: CREAR CUENTA-->
<?php if ($section == 'front_login.crear_cuenta'): ?>
	<tr>
		<?php $this->load->view('front_login/crear_cuenta');?>
		<?php $this->load->view('right_menu_home');?>
	</tr>
	<tr></tr>
<?php endif; ?>
<!-- LOGIN :: CREAR CUENTA :: VALIDACION DE CUENTA CORRECTA-->
<?php if ($section == 'front_login.validate_crearcuenta'): ?>
	<tr>
		<?php $this->load->view('front_home/homepage');?>
		<?php $this->load->view('right_menu_home');?>
	</tr>
	<tr></tr>
<?php endif; ?>
<!-- LOGIN :: CREAR CUENTA :: VALIDACION DE CUENTA CORRECTA-->
<?php if ($section == 'front_login.recuperar_clave'): ?>
	<tr>
		<?php $this->load->view('front_login/recuperar_clave');?>
		<?php $this->load->view('right_menu_home');?>
	</tr>
	<tr></tr>
<?php endif; ?>



<!-- TU CUENTA :: MIS PUBLICACIONES  -->
<?php if ($section == 'front_tucuenta.mis_publicaciones'): ?>
	<tr>
		<?php $this->load->view('front_tucuenta/mispublicaciones');?>
		<?php $this->load->view('right_menu_admin');?>
	</tr>
<?php endif; ?>
<!-- TU CUENTA :: MIS FAVORITOS  -->
<?php if ($section == 'front_tucuenta.mis_favoritos'): ?>
	<tr>
		<?php $this->load->view('front_tucuenta/misfavoritos');?>
		<?php $this->load->view('right_menu_admin');?>
	</tr>
<?php endif; ?>
<!-- TU CUENTA :: PERFIL  -->
<?php if ($section == 'front_tucuenta.perfil'): ?>
	<tr>
		<?php $this->load->view('front_tucuenta/perfil');?>
		<?php $this->load->view('right_menu_home');?>
	</tr>
	<tr></tr> <!-- agrego por que si no pincha el maquetado -->
<?php endif; ?>
<!-- TU CUENTA :: PERFIL, VER MAS  -->
<?php if ($section == 'front_tucuenta.perfilVerMas'): ?>
	<tr>
		<?php $this->load->view('front_tucuenta/perfil_vermas');?>
		<?php $this->load->view('right_menu_home');?>
	</tr>
	<tr></tr> <!-- agrego por que si no pincha el maquetado -->
<?php endif; ?>
<!-- TU CUENTA :: EDITAR PERFIL  -->
<?php if ($section == 'front_tucuenta.editarPerfil'): ?>
	<tr>
		<?php $this->load->view('front_tucuenta/edit_perfil');?>
		<?php $this->load->view('right_menu_admin');?>
	</tr>
	<tr></tr> <!-- agrego por que si no pincha el maquetado -->
<?php endif; ?>
<!-- TU CUENTA :: EDITAR PERFIL  -->
<?php if ($section == 'front_tucuenta.mis_estadosdecuenta'): ?>
	<tr>
		<?php $this->load->view('front_tucuenta/mis_estadosdecuenta');?>
		<?php $this->load->view('right_menu_admin');?>
	</tr>
	<tr></tr> <!-- agrego por que si no pincha el maquetado -->
<?php endif; ?>

<!-- TU CUENTA :: ACCESO PARA EDITORIALES  -->
<?php if ($section == 'front_tucuenta.acceso_editorial'): ?>
	<tr>
		<?php $this->load->view('front_tucuenta/acceso_editorial');?>
		<?php $this->load->view('right_menu_admin');?>
	</tr>
	<tr></tr> <!-- agrego por que si no pincha el maquetado -->
<?php endif; ?>




<!-- PUBLICACIONES :: AGREGAR  -->
<?php if ($section == 'front_works.add'): ?>
	<tr>
		<?php $this->load->view('front_works/new');?>
		<?php $this->load->view('right_menu_admin');?>
	</tr>
<?php endif; ?>
<!-- PUBLICACIONES :: EDITAR  -->
<?php if ($section == 'front_works.edit'): ?>
	<tr>
		<?php $this->load->view('front_works/edit');?>
		<?php $this->load->view('right_menu_admin');?>
	</tr>
<?php endif; ?>
<!-- PUBLICACIONES :: VER  -->
<?php if ($section == 'front_works.show'): ?>
	<tr>
		<?php $this->load->view('front_works/view');?>
		<?php $this->load->view('right_menu_home');?>
	</tr>
	<?php $this->load->view('footer_random');?>
<?php endif; ?>
<!-- PUBLICACIONES :: COMPRAR  -->
<?php if ($section == 'front_works.comprar'): ?>
	<tr>
		<?php $this->load->view('front_works/comprar');?>
		<?php $this->load->view('right_menu_home');?>
	</tr>
	<?php $this->load->view('footer_random');?>
<?php endif; ?>