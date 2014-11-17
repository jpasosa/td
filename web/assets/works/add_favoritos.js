$(document).ready(function() {
	$('a.add_favoritos').on('click',function(){ // Funciona bien el change.
		var id_user 		= $(this).attr("id_user");
		var id_trabajo 	= $(this).attr("id_trabajo");
		$.ajax({
			url: RUTA + 'models/addFavoritos/',
			type: 'POST',
			data: {
				id_user: id_user,
				id_trabajo: id_trabajo
			},
			dataType: 'html',
			success: function(option) {
				$("a.add_favoritos").remove();
				$("div.favorito").append("<img class='add_favorito' src='' />");
				$( "img.add_favorito" ).attr( "src", RUTA + "web/assets/style/images/mostrar_favorito.png" );
			},
			error: function(e) {
				console.log(e);
			}
		});
	});
})