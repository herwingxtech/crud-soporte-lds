$(document).ready(function(){
	var lugar = function(){
		$.post('/soporte/tiendas/municipio', 'estado=' + $("#estado").val(), function(datos){
			$("#municipios").html('')
			$('#municipios').append('<option value="0"> Seleccione una opción</option>');
			for(var i = 0; i<datos.length; i++){

				$("#municipios").append(

					'<option value="' + (datos[i].idMunicipio) + '">' + datos[i].municipio +  '</option>' 
			     );
			}
		}, 'json');
	}

	var localidades = function(){
		$.post('/soporte/tiendas/localidades', 'municipio=' + $("#municipios").val(), function(datos){
			$("#localidades").html('')
			$('#localidades').append('<option value="0"> Seleccione una opción</option>');
			for(var i=0; i<datos.length; i++){
				$("#localidades").append('<option value="'+ (datos[i].asentamiento) +'">' + datos[i].asentamiento + '</option>');
			}
		}, 'json');
	}
	

	$("#estado").change(function(){
		if(!$("#estado").val()){
			$("#municipios").html('');
		}
		else{
			lugar();
		}
	});

	$("#municipios").change(function(){
		localidades();
	});
})