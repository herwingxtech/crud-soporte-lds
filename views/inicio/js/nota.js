/*=================================================================================
=                    Ver detalle de la nota                                       =
 =================================================================================*/ 
$(".ver-nota").click(function(){
    var idNota = $(this).attr("idNota");
    var datos = new FormData();
    datos.append("id", idNota);
    
    $.ajax({
        url: "/soporte/Inicio/ver/"+idNota,
        method:"POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false, 
        success:function(respuesta){
            $(".nomNota").html(JSON.parse(respuesta).nameNote);
            $(".fecha").html(JSON.parse(respuesta).fechaNote);
            $(".descripcion").html(JSON.parse(respuesta).descNote);
            $(".urlNota").html(JSON.parse(respuesta).urlNote);    
        }
    });
});


/*=================================================================================
=                    Eliminar registro de la nota                                 =
 =================================================================================*/ 
$(".delete-nota").click(function(){
    var idNota = $(this).attr("idNota");
    
	swal({
		title: "¡Estás seguro(a) de borrar el registro!",
		text: "¡Si borras el registro ya no se puede recuperar!",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "¡Si!",
		closeOnConfirm: false
	},

	function(isConfirm){
		if(isConfirm){
            
            $.ajax({
                url: "/soporte/Inicio/eliminarNota/"+idNota,
                method:"POST",
                data: false,
                cache: false,
                contentType: false,
                processData: false, 
                success:function(respuesta){
                    if(respuesta==1){
                        swal({
				            title: "",
				            text: "¡El registro se ha borrado correctamente!",
				            type: "success",
				            confirmButtonColor: "#123456",
				            confirmButtonText: "¡ok!",
				            closeOnConfirm: false
			             },
			             function(isConfirm){
				            if(isConfirm){
                            window.location = "/soporte/inicio/notas/";
				        }
		              });
                       
                    }else{
                        swal("No se pudo eliminar!");
                    }
                }
            });
		}
	});
    
});


