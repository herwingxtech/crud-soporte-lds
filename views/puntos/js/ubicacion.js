$(document).ready(function(){


	/*=====================================================================
	=            Alerta de evento realizado al guardar la nota            =
	=====================================================================*/
	var mensaje = $("#mensaje").val();
	if(mensaje=='error'){
		swal("No se pudieron guardar los datos!");
	}else if(mensaje=="ok"){
		swal({
			title: "¡Ok!",
			text: "¡Los datos se ha guardado correctamente.!",
			type: "success",
			confirmButtonText: "Cerrar",
			closeOnConfirm: false
			},

			function(isConfirm){
				if(isConfirm){
					window.location="/soporte/punto"; 
				}
			}
		)
	}

	var lugar = function(){
		$.post('/soporte/puntos/municipio', 'estado=' + $("#estado").val(), function(datos){
			$("#municipios").html('')
			$('#municipios').append('<option value="0"> Seleccione una opción</option>');
			for(var i = 0; i<datos.length; i++){

				$("#municipios").append(

					'<option value="' + (datos[i].c_mnpio) + '">' + datos[i].D_mnpio +  '</option>' 
			     );
			}
		}, 'json');
	}

	var localidades = function(){
		$.post('/soporte/puntos/localidades', 'municipio=' + $("#municipios").val()+"&estado="+ $("#estado").val(), function(datos){
			$("#localidades").html('')
			$('#localidades').append('<option value="0"> Seleccione una opción</option>');
			for(var i=0; i<datos.length; i++){
				$("#localidades").append('<option municipio="'+ $("#municipios").val() +'" estado="'+ $("#estado").val() +'" id_asenta="'+ (datos[i].id_asenta_cpcons) +'" value="'+ (datos[i].id_asenta_cpcons) +'">' + datos[i].d_asenta + '</option>');
			}
		}, 'json');
	}

	var cp =function(){
		var id_asenta = $('#localidades').val();
		var municipio = $('#localidades>option:selected').attr("municipio");
		var estado = $('#localidades>option:selected').attr("estado");
		$.post('/soporte/puntos/cp', 'id_asenta=' + id_asenta + '&municipio='+municipio + '&estado='+estado, function(datos){
			$("#cp").html('')
			$("#cp").attr('value', datos.d_codigo);
			$("#ciudad").attr('value', datos.c_cve_ciudad);
			$("#colonia").attr('value', datos.id_asenta_cpcons);
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

	$("#localidades").change(function(){
		
		cp();
	});
})


/*========================================
=            formatear campos            =
========================================*/
$("input").focus(function(){
	$(".alert").remove();
});

jQuery("#numExterior").on('input', function (evt) {
	// Allow only numbers.
	jQuery(this).val(jQuery(this).val().replace(/[^0-9]/g, ''));
});
jQuery("#numInterior").on('input', function (evt) {
	// Allow only numbers.
	jQuery(this).val(jQuery(this).val().replace(/[^0-9]/g, ''));
});
jQuery("#iva").on('input', function (evt) {
	// Allow only numbers.
	jQuery(this).val(jQuery(this).val().replace(/[^0-9]/g, ''));
});
/*--=================================== 
=     Vaidar registro Punto      =
======================================*/
function registroPuntoVenta(){

	/*----------  Validar Apikey  ----------*/

	var apikey = $("#apikey").val();

	if(apikey !=""){
		if(apikey.length!=32){
			$("#message").addClass("error alert");
			$("#apikey").parent().after('<label for="apikey" class="error alert">Debe contener 32 carácteres!</label>');
			return false;
		}else{
			/*--=====================================
		=        Validar email repetido         =
		========================================*/
		var apikeyRepet = false;
		$("#apikey").change(function(){
			var apikey =$("#apikey").val();
			console.log(apikey);
			var datos = new FormData();
			datos.append("validarApikey", apikey);

			$.ajax({
				url:"soporte/Puntos/apikey",
				method: "POST",
				data: datos,
				cache: false,
				contentType: false,
				processData: false,
				success:function(respuesta){
					console.log(respuesta)
					if(respuesta == "false"){
						$(".alert").remove();
						apikeyRepet = false;
					}else{
						
						$("#apikey").parent().before('<div class="alert alert-warning"><strong>¡Error!<strong> El Api Key está registrado, fue registrado a través de '+modo+', por favor ingrese otro diferente</div>')
						apikeyRepet = true;
					}
				}

			});
		});
		}
		
		/*
		var expresion = /^[a-zA-ZñáéíóúÁÉÍÓÚ ]*$/;
		if(!expresion.test(nombre)){
			$("#regUsuario").parent().before('<div class="alert alert-warning">Error: No se permiten números ni caracteres especiales n<strong></strong></div>');
			return false;
		} */

	}else{

		$("#apikey").parent().after('<label for="apikey" class="error error alert">No puede estar vacío!</label>');
		return false;
	}

	/*----------  Validar Identicador  ----------*/
	var identificador = $("#identificador").val();
	//console.log(identificador);
	if(identificador != ""){
		if(identificador.length != 5){
			$("#message").addClass("error alert");
			$("#identificador").parent().after('<label for="apikey" class="error error alert">Debe contener 5 caracteres</label>');
			return false;
		}
	}else{
		$("#identificador").parent().after('<label for="apikey" class="error error alert">No puede estar vacío el identifacao!</label>');
		return false;
	}

	/*----------  Validar nombre tienda  ----------*/
	var nomTienda = $("#nombreTienda").val();
	//console.log(nomTienda);
	if(nomTienda != ""){

		var expresion = /^[a-zA-Z ]*$/;
		
		if(!expresion.test(nomTienda)){
			$("#nombreTienda").parent().after('<label for="apikey" class="error error alert">tiene ñ</label>');
			return false;
		}
	}else{
		$("#nombreTienda").parent().after('<label for="apikey" class="error error alert">No puede estar vacío el identifacao!</label>');
		return false;
	}
	/*----------  Validar comercio ----------*/
	var comercio = $("#comercio").val();
	//console.log(comercio);
	if(comercio != ""){

		var expresion = /^[a-zA-Z]*$/;
		if(!expresion.test(comercio)){
			$("#comercio").parent().after('<label for="apikey" class="error error alert"></label>');
			return false;
		}
	}else{
		$("#comercio").parent().after('<label for="apikey" class="error error alert">No puede estar vacío el identifacao!</label>');
		return false;
	}

	/*----------  Validar calle ----------*/
	var calle = $("#calle").val();
	//console.log(calle);
	if(calle != ""){

		var expresion = /^[a-zA-Z]*$/;
		if(!expresion.test(calle)){
			$("#calle").parent().after('<label for="apikey" class="error error alert"></label>');
			return false;
		}
	}else{
		$("#calle").parent().after('<label for="apikey" class="error error alert">No puede estar vacío el identifacao!</label>');
		return false;
	}

	/*----------  Validar detalleDireccion ----------*/
	var detalleDireccion = $("#detalleDireccion").val();
	//console.log(detalleDireccion);
	if(detalleDireccion != ""){

		var expresion = /^[a-zA-Z]*$/;
		if(!expresion.test(detalleDireccion)){
			$("#detalleDireccion").parent().after('<label for="apikey" class="error error alert"></label>');
			return false;
		}
	}else{
		$("#detalleDireccion").parent().after('<label for="apikey" class="error error alert">No puede estar vacío el identifacao!</label>');
		return false;
	}

	/*----------  Validar comercio ----------*/
	var comercio = $("#comercio").val();
	//console.log(comercio);
	if(comercio != ""){

		var expresion = /^[a-zA-Z]*$/;
		if(!expresion.test(comercio)){
			$("#comercio").parent().after('<label for="apikey" class="error error alert"></label>');
			return false;
		}
	}else{
		$("#comercio").parent().after('<label for="apikey" class="error error alert">No puede estar vacío el identifacao!</label>');
		return false;
	}



	return true;

}


