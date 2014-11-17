$(document).ready(function() {
	$('.del_favorito').on('click',function(){ // Funciona bien el change.
		var id_favorito 	= $(this).attr("id_favorito");
		$.ajax({
			url: RUTA + 'models/delFavoritos/',
			type: 'POST',
			data: {
				id_favorito: id_favorito
			},
			dataType: 'html',
			success: function(option) {
				// console.log(option);
				var selector = "[id_favorito$='" + id_favorito +"']";
				$(selector).css('display', 'none');
			},
			error: function(e) {
				console.log(e);
			}
		});
	});
})