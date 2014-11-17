$(document).ready(function() {
	$('#autor').on('change',function(){ // Funciona bien el change.
		var id_autor = $(this).val();
		//alert(parentId);
		$.ajax({
			url: RUTA + 'models/getPublicacionesAjax/' + id_autor,
			type: 'POST',
			data: {id_autor:
				id_autor
			},
			dataType: 'html',
			success: function(option) {
				// console.log(option);
				$("#publicaciones option").remove();
				$('#publicaciones').append(option);
			},
			error: function(e) {
				console.log(e);
			}
		});
	});
})