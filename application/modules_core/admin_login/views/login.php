
<!--
$form_register'] = 'admin/login/register'
$form_forgot'] = 'admin/login/forgot'
$form_validate'] = 'admin/login/validate'
 -->
<body>
    <div class="login_box">

        <!-- LOGUEO DEL USER -->
        <form action="<?php echo $form_validate; ?>" method="post" id="login_form">
            <div class="top_b"><?php echo $title ?></div>
            <div class="cnt_b">

                <div class="formRow">
                    <div class="input-prepend <?php if(isset($error_login)) echo "f_error" ?>">
                        <span class="add-on">@</span>
                        <input type="text" id="username" name="username" placeholder="Usuario" value="" />
                        <?php if(isset($error_login)): ?>
                            <label for="username" generated="true" class="error">
                                Usuario y/o contraseña incorrectos
                            </label>
                        <?php endif;?>
                    </div>
                </div>

                <div class="formRow">
                    <div class="input-prepend">
                        <span class="add-on"><i class="icon-lock"></i></span>
                        <input type="password" id="password" name="password" placeholder="Clave" value="" />
                    </div>
                </div>

            </div>

            <div class="btm_b clearfix">
                <button class="btn btn-inverse pull-right" type="submit">Ingresar</button>
                <!-- <span class="link_reg"><a href="#reg_form">¿No está registrado? Haga clic aquí</a></span> -->
            </div>

        </form>



        <!-- OLVIDO LA CLAVE -->
        <form action="<?php echo $form_forgot;?>" method="post" id="pass_form" style="<?php if(!isset($pass_form)) echo 'display:none'; ?>">
            <div class="top_b">¿No ha podido ingresar?</div>
            <div class="alert alert-info alert-login">
                Por favor ingrese su dirección de email. Le enviaremos una nueva clave.
            </div>
            <div class="cnt_b">
                <div class="formRow clearfix">
                    <div class="input-prepend">
                        <span class="add-on">@</span><input type="text" name="email" placeholder="Su dirección de email" />
                    </div>
                </div>
            </div>
            <div class="btm_b tac">
                <button class="btn btn-inverse" type="submit">Solicitar una nueva clave</button>
            </div>
        </form>



        <!-- FORMULARIO DE REGISTRO DE UN USER NUEVO -->
        <form action="<?php echo $form_register;?>" method="post" id="reg_form" style="display:none">

            <div class="top_b">Registrese</div>

            <div class="alert alert-login">
                Completando el siguiente formulario y haciendo clic en el botón Registrase, usted está aceptando los <a data-toggle="modal" href="#terms">Términos y Condiciones de servicio</a>.
            </div>

            <div id="terms" class="modal hide fade" style="display:none">

                <div class="modal-header">
                    <a class="close" data-dismiss="modal">×</a>
                    <h3>T&eacute;rminos y Condiciones de servicio</h3>
                </div>

                <div class="modal-body">
                    <p>
                        Nulla sollicitudin pulvinar enim, vitae mattis velit venenatis vel. Nullam dapibus est quis lacus tristique consectetur. Morbi posuere vestibulum neque, quis dictum odio facilisis placerat. Sed vel diam ultricies tortor egestas vulputate. Aliquam lobortis felis at ligula elementum volutpat. Ut accumsan sollicitudin neque vitae bibendum. Suspendisse id ullamcorper tellus. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vestibulum at augue lorem, at sagittis dolor. Curabitur lobortis justo ut urna gravida scelerisque. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Aliquam vitae ligula elit.
                        Pellentesque tincidunt mollis erat ac iaculis. Morbi odio quam, suscipit at sagittis eget, commodo ut justo. Vestibulum auctor nibh id diam placerat dapibus. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Suspendisse vel nunc sed tellus rhoncus consectetur nec quis nunc. Donec ultricies aliquam turpis in rhoncus. Maecenas convallis lorem ut nisl posuere tristique. Suspendisse auctor nibh in velit hendrerit rhoncus. Fusce at libero velit. Integer eleifend sem a orci blandit id condimentum ipsum vehicula. Quisque vehicula erat non diam pellentesque sed volutpat purus congue. Duis feugiat, nisl in scelerisque congue, odio ipsum cursus erat, sit amet blandit risus enim quis ante. Pellentesque sollicitudin consectetur risus, sed rutrum ipsum vulputate id. Sed sed blandit sem. Integer eleifend pretium metus, id mattis lorem tincidunt vitae. Donec aliquam lorem eu odio facilisis eu tempus augue volutpat.
                    </p>
                </div>

                <div class="modal-footer">
                    <a data-dismiss="modal" class="btn" href="#">Cerrar</a>
                </div>

            </div>


            <div class="cnt_b">
                <div class="formRow">
                    <div class="input-prepend">
                        <span class="add-on">@</span><input type="text" placeholder="Su dirección de email" />
                    </div>
                </div>
                <div class="formRow">
                    <div class="input-prepend">
                        <span class="add-on"><i class="icon-lock"></i></span><input type="text" placeholder="Clave" />
                    </div>
                </div>
                <div class="formRow">
                    <div class="input-prepend">
                        <span class="add-on">@</span><input type="text" placeholder="Su dirección de email" />
                    </div>
                </div>
            </div>
            <div class="btm_b tac">
                <button class="btn btn-inverse" type="submit">Registrase</button>
            </div>
        </form>

        <div class="links_b links_btm clearfix">
            <span class="linkform"><a href="#pass_form">¿Olvidó su clave?</a></span>
            <span class="linkform" style="display:none"><a href="#login_form">Volver al formulario de ingreso</a></span>
        </div>
</div>
