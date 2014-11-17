$(document).ready(function() {
    //##### ELIMINAR PUBLICACION #########
    $("body").on("click", ".del_publicacion", function(delRef) {
        delRef.returnValue = false;
        var id_publicacion  = this.id;
        if (confirm('Seguro de eliminarlo?')) {
                jQuery.ajax({
                        type: "POST",
                        url: RUTA + 'models/delPublicacionAjax/' + id_publicacion,
                        dataType: "text",
                        data: {
                            id_publicacion: id_publicacion
                        },
                        success:function(response, status, xhr){
                            location.reload();
                        },
                        error:function (xhr, ajaxOptions, thrownError){
                            alert(thrownError);
                        }
                });
        // location.reload();
        // $(".observaciones textarea").focus();
    }
    });



});