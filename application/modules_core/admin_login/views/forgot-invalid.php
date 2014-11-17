    <body>
		<div class="login_box">						
			<form action="<?php echo $form_forgot;?>" method="post" id="pass_form" >
				<div class="top_b">&iquest;No ha podido ingresar?</div>    
					<div class="alert alert-info alert-login">
					Por favor ingrese su direcci&oacute;n de email. Le enviaremos una nueva clave. 
					</div>
				<div class="cnt_b">
					<div class="formRow clearfix">
						<div class="input-prepend <?php if(isset($error)) echo "f_error";?>">
							<span class="add-on">@</span><input type="text" name="email" placeholder="Su direcci&oacute;n de email" />
							<label for="username" generated="true" class="error">
							<?php if(isset($error)) echo $error;?>
						</label>
						</div>						
					</div>
				</div>
				<div class="btm_b tac">
					<button class="btn btn-inverse" type="submit">Solicitar una nueva clave</button>
				</div>  
			</form>
		</div>
		