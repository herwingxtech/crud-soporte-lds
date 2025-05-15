/*=================================================================================
=                    Ver detalle del mantenimiento                                =
 =================================================================================*/ 
$(".detalle-bitacora").click(function(){
    var idBitacora = $(this).attr("idBitacora");
    $("#icono").removeClass("icon-apple");
    $("#icono").removeClass("icon-tux");
    $("#icono").removeClass("icon-windows8");
    
    var datos = new FormData();
    datos.append("id", idBitacora);
    
    $.ajax({
        url: "/soporte/Inicio/detalles/"+idBitacora,
        method:"POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false, 
        success:function(respuesta){
            var os = JSON.parse(respuesta).OS;
            var ico = os.substr(0,1);

            if(ico == "W"){

                $("#icono").addClass("icon-windows8");
            }else if(ico == "O"){
                $("#icono").addClass("icon-apple");
            }else{
                $("#icono").addClass("icon-tux");
            }
            $(".nomPC").html(JSON.parse(respuesta).nomComputadora);
            $(".marcaPC").html(JSON.parse(respuesta).marcaComputadora);
            $(".osPC").html(os);
            $(".depPC").html(JSON.parse(respuesta).depComputadora);
            $(".sucPC").html(JSON.parse(respuesta).nombreSucursal);
            $(".mantenimientoPC").html(JSON.parse(respuesta).nomMantenimiento);
            $(".diagnostico").html(JSON.parse(respuesta).diagnosticoMantenimiento);
            $(".solucion").html(JSON.parse(respuesta).solucionMantenimiento);
            $(".ingreso").html(JSON.parse(respuesta).fechaIngresoMantenimiento);
            $(".salida").html(JSON.parse(respuesta).fechaSalidaMantenimiento);
        }
    });
})
/*=================================================================================
=                    Eliminar registro de mantenimiento                           =
 =================================================================================*/ 
$(".delete-bitacora").click(function(){
    var idBitacora = $(this).attr("idBitacora");
    
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
                url: "/soporte/Inicio/eliminar/"+idBitacora,
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
                            window.location = "/soporte/inicio/bitacora/";
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
