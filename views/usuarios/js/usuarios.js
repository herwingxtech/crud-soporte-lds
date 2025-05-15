/*===========================================
=            Cambiar foto perfil            =
===========================================*/
//ALLAS GALLI YNOMINAM
$("#btnCambiarFoto").click(function(){
    $("#img-perfil").toggle();
    $("#subirImagen").toggle();
})

$("#datosImagen").change(function(){
    var imagen = this.files[0];
    /*----------  Validando formato  ----------*/
    if(imagen["type"] != "image/jpeg"){
        $("#datosImagen").val("");
        swal({
            title: "Error al subir la imagen",
            text: "¡La imagen debe estar en formato JPG!",
            type: "error",
            confirmButtonText: "text",
            closeOnConfirm:false
        });
    }else if(Number(imagen["size"]>2000000)){
        $("#datosImagen").val("");
        swal({
            title: "Error al subir la imagen",
            text: "¡La imagen no debe pesar más de 2 mb!",
            type: "error",
            confirmButtonText: "¡Cerrar!",
            closeOnConfirm: false
        });
     } else {
         var datosImagen = new FileReader;
         datosImagen.readAsDataURL(imagen);
         $(datosImagen).on("load", function(event){
            var rutaImagen = event.target.result;
            $(".previsualizar").attr("src", rutaImagen);
         })
     }
})

/*=================================================================================
=                    Eliminar registro den Usuario                                =
 =================================================================================*/ 
 $(".delete-user").click(function(){
    var idUsuario = $(this).attr("idUser");
    var URLactual = window.location;
    swal({
		title: "¡Borrar registro!",
		text: "¡El registro se dará de baja!",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "¡Si!",
		closeOnConfirm: false
	},

	function(isConfirm){
		if(isConfirm){
            
            $.ajax({
                url: "/soporte/Usuarios/baja/"+idUsuario,
                method:"POST",
                data: false,
                cache: false,
                contentType: false,
                processData: false, 
                success:function(respuesta){
                    console.log(respuesta)
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
                            window.location = URLactual;
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