$(document).ready(function(){
    lat = "0";
    lng =  "0";

  //  actualizar(lat, lng, 4);
})

var mensaje = $("#mensaje").val();
if(mensaje=='error'){
    swal("No se pudieron guardar los datos!");
}else if(mensaje=="ok"){
    swal({
		title: "",
        text: "¡La datos se ha guardado correctamente.!",
        type: "success",
        confirmButtonText: "ok",
        confirmButtonColor: "#123456",
        closeOnConfirm: false
        }).then(function(isConfirm){
                        
            window.location = "/soporte/tiendas/";
        });
    
}

var lugar = function(){
	$.post('/soporte/Tiendas/municipio', 'estado=' + $("#estado").val(), function(datos){
		$("#municipios").html('')
		$('#municipios').append('<option value="0"> Seleccione una opción</option>');
		for(var i = 0; i<datos.length; i++){

			$("#municipios").append(

				'<option value="' + (datos[i].id) + '">' + datos[i].nombre +  '</option>' 
				);
		}
	}, 'json');
}

$("#estado").change(function(){
	//$('#ubicacion').show();
	
	
	if(!$("#estado").val()){
		$("#municipios").html('');
	}
	else{
		lugar();
	}
});