    <body>
		<div class="login_box">			
			<form action="<?php echo $form_validate;?>" method="post" id="login_form">
				<div class="top_b">Ingreso al panel de control</div>    				
				<div class="cnt_b">
					<div class="formRow">
						<div class="input-prepend <?php if(isset($error_login)) echo "f_error" ?>">
							<span class="add-on"><i class="icon-user"></i></span><input type="text" id="username" name="username" placeholder="Usuario" value="" />
																	
						</div>						
					</div>
					<div class="formRow">
						<div class="input-prepend">
							<span class="add-on"><i class="icon-lock"></i></span><input type="password" id="password" name="password" placeholder="Clave" value="" />
						</div>
					</div>										
				</div>
				<div class="btm_b clearfix">
					<button class="btn btn-inverse pull-right" type="submit">Ingresar</button>
					<span class="link_reg">Los datos se han re-enviado con &eacute;xito</span>
				</div>  
			</form>
						
		</div>
		