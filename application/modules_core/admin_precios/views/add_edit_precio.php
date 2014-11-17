<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="row-fluid">
	<div class="span12">
		<h3 class="heading"><a href="<?php echo PUBLIC_FOLDER;?>precios/listar.html">Precios</a></h3>
		<div class="row-fluid">
			<div class="span8">
				<?php if(isset($errors)):?> <!-- ERRORES DE VALIDACIÓN -->
				<?php endif;?>
				<?php if(isset($success)):?> <!-- EN EL UPDATE, AVISA QUE MODIFICÓ CON ÉXITO -->
					<div class="alert alert-success">
						<a class="close" data-dismiss="alert" href="#">&times;</a>
						<?php echo $success;?>
					</div>
				<?php endif;?>
				<form method="post" class="form-horizontal" enctype="multipart/form-data" action="<?php echo $form_action;?>">
					<fielset>
						<div class="control-group">
							<label class="control-label">Precio</label>
							<div class="controls">
								<input type="text" name="precio" id="precio" value="<?php if(isset($precio['precio'])) echo $precio['precio'];?>">
							</div>
							<div class="controls">&nbsp;</div>
						</div>
						<?php if(isset($errors)):?>
						<div class="alert alert-error">
							<?php echo $errors;?>
						</div>
						<?php endif;?>
						<?php if(isset($success)):?>
						<div class="alert alert-success">
							<?php echo $success;?>
						</div>
						<?php endif;?>
						<div class="controls">&nbsp;</div>
						<div class="control-group formSep">
							<button type="submit" class="btn">Guardar</button>
						</div>
					</fielset>
				</form>
			</div>
		</div>
	</div>
</div>
