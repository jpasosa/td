<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="row-fluid">
	<div class="span12">

	<?php if ($this->session->flashdata('success')): ?>
		<div class="alert alert-success">
			<a class="close" data-dismiss="alert" href="#">&times;</a>
			<?php echo $this->session->flashdata('success'); ?>
		</div>
	<?php endif ?>


	<h3 class="heading">
		Listado de pagos pendientes de <?php echo $nombre_autor; ?>
	</h3>
		<div class="span12">


			<div class="controls">&nbsp;</div>
			<?php if ($this->session->flashdata('work_success')): ?> <!-- comp CREADO CORRECTAMENTE -->
				<div class="alert alert-success">
					<a class="close" data-dismiss="alert" href="#">&times;</a>
					<?php echo $this->session->flashdata('work_success'); ?>
				</div>
			<?php endif ?>
			<form method="post" class="form-horizontal" enctype="multipart/form-data" action="<?php echo $form_action;?>">

				<table class="table">
					<thead>
					<tr>
						<th>ID</th>
						<th>Fecha</th>
						<th>Titulo</th>
						<th>Regalias</th>
					</tr>
					</thead>
					<tbody>
					<?php if(isset($regalias) && (!$regalias)):?>
						<tr>
							<td colspan="7">No se encontraron resultados</td>
						</tr>
					<?php else:?>
						<?php foreach($regalias as $reg): ?>
							<tr class="tr_tipo_<?php echo $reg['idRegalias'];?>">
								<td><?php echo $reg['idRegalias'];?></td>
								<td><?php echo $reg['fecha'];?></td>
								<td><?php echo $reg['titulo'];?></td>
								<td><?php echo $reg['monto_al_autor'];?></td>
							</tr>
						<?php endforeach;?>
						<tr>
							<td> </td>
							<td> </td>
							<td> </td>
							<td><span style="font-weight: bold;"><?php echo $monto_total; ?></span></td>
						</tr>
						<tr>
							<td> </td>
							<td> </td>
							<td>
								<button type="submit" class="btn">Registrar Pago</button>
								&nbsp;&nbsp;&nbsp;&nbsp;
								<a href="<?php echo PUBLIC_FOLDER_ADMIN;?>pagos/listado_autores.html" class="btn">Volver al listado</a>
							</td>
							<td></td>
						</tr>


					<?php endif;?>
					</tbody>
				</table>
			</form>
		</div>
	</div>
</div>





