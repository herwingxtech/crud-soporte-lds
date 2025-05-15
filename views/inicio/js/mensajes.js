
/*=====================================================================
=            Alerta de evento realizado al guardar la nota            =
=====================================================================*/
var mensaje = $("#mensaje").val();
if(mensaje=='error'){
    swal("No se pudieron guardar los datos!");
}else if(mensaje=="ok"){
    swal({
        title: "¡Ok!",
        text: "¡La nota se ha guardado correctamente.!",
        type: "success",
        confirmButtonText: "Cerrar",
        closeOnConfirm: false
        },

        function(isConfirm){
            if(isConfirm){
                window.location="/soporte/"; 
            }
        }
    )
}
