<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div class="row-fluid">
    <div class="span12">
    <h3 class="heading">Parametrización de Regalías</h3>
    <div class="row-fluid">
        <div class="span8">
            <form class="form-horizontal" method="post"	enctype="multipart/form-data" action="<?php echo $form_action;?>">
                <fieldset>
                    <div class="control-group formSep">
                        <label for="fecha" class="control-label">Monto Regalías</label>
                        <div class="controls">
                            <input type="numeric" name="alarma" class="input-xlarge datepicker"
                                        value="<?php if(isset($alarma_regalia)) echo $alarma_regalia;?>" />
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="controls">
                            <button class="btn btn-gebo" id="save" type="submit">Cargar Parametrización</button>
                            <a href="javascript:history.back(-1);" class="btn">Volver</a>
                        </div>
                    </div>
            </fieldset>
        </form>
    </div>
</div>
</div>
</div>

