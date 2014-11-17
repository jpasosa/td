<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php if(checkRol('administrador', $this->session)):?>
								</div>
						</div>

<div class="sidebar">
	<div class="antiScroll" >
		<div class="antiscroll-inner" style="min-width:260px;">
			<div class="antiscroll-content">
				<div class="sidebar_inner"> <br/ ><br/ >
					<div id="side_accordion" class="accordion">
					<div class="accordion-group">
						<div class="accordion-heading sdb_h_active">
							<a href="#Config" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
								<i class="icon-cog"></i> ESTADÍSTICAS
							</a>
						</div>
						<div class="accordion-body in collapse" id="Config">
							<div class="accordion-inner">
								<ul class="nav nav-list">
									<!-- <li class="nav-header">Estadísticas</li> -->
									<li>
			                                        <a href="<?php echo site_url('admin/estadisticas/public_mas_visitadas');?>">Publicaciones más visitadas</a>
			                                    </li>
			                                    <li>
			                                        <a href="<?php echo site_url('admin/estadisticas/autores_mas_visitados');?>">Autores más visitados</a>
			                                    </li>
			                                    <li>
			                                        <a href="<?php echo site_url('admin/estadisticas/public_mas_vendidas');?>">Publicaciones más vendidas</a>
			                                    </li>
			                                    <li>
			                                        <a href="<?php echo site_url('admin/estadisticas/autores_mas_vendidos');?>">Autores que más venden</a>
			                                    </li>
									<!--  POR AHORA NO VA LA LISTA DE PRECIOS
									<li class="nav-header">Precios</li>
									<li><a href="<?php echo site_url('admin/precios/nuevo');?>">Nuevos precios</a></li>
									<li><a href="<?php echo site_url('admin/precios/listar');?>">Listar todos</a></li>
								 		-->
								</ul>
							</div>
						</div>
					</div>
					</div>
				<div class="push"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endif;?>