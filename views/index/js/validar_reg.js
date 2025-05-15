  v
var user=false, pass=false;
$("#enviar").attr("disabled", true);
$('[data-toggle="popover"]').popover();
$("#nombre_user").blur(function(){
    var username = $("#nombre_user").val();
    if (username.length > 0) {                         +
        $("#nombre_user").removeClass("form-control-error");
        $("#alertUsername").attr("data-content", "");
        var expresion = /^[a-zA-Z]*$/;
        
        if(!expresion.test(username)){
            $("#nombre_user").addClass("form-control-error");
            $("#alertUsername").attr("data-content", "No se permiten caracteres especiales");
            user=false;
            /*$(".alert").remove();
            $("#alertUsername").parent().before('<div class="alert alert-danger fade in block-inner"><button type="button" class="close" data-dismiss="alert"><i>x</i></button>¡Caracteres no permitido!<i class="icon-cancel-circle"></i></div>');
            user=false;
            return false;*/
        }else{
            user = true;
        }
    } else {
        $("#nombre_user").addClass("form-control-error");
        user = false;
        $("#alertUsername").attr("data-content", "Usuario requerido");
        
    }
});
$("#passwd").blur(function(){
    var passwd =  $("#passwd").val();
    if(passwd.length>0){
        $("#passwd").removeClass("form-control-error");
        $("#alertPass").attr("data-content", "");
       var expresion = /^[a-zA-Z0-9]*$/;
        if(!expresion.test(passwd)){
            
            $("#passwd").addClass("form-control-error");
            $("#alertPass").attr("data-content", "No se permiten carácteres especiales");
            /*$(".alert").remove();
            $("#alertUsername").parent().before('<div class="alert alert-danger fade in block-inner"><button type="button" class="close" data-dismiss="alert"><i>x</i></button>¡Caracteres no permitido!<i class="icon-cancel-circle"></i></div>');*/
            pass= false;
        }else{
            pass=true;
        }
        
    }else{
        
        $("#passwd").addClass("form-control-error");
        pass=false;
        $("#alertPass").attr("data-content", "Contraseña requerida");  
    }
    validarCampos(user, pass);
   
});
function validarCampos(user, pass){
    
    if(user ==true && pass == true){
                $(".alert").remove();

        var username = $("#nombre_user").val();
        var passwd = $("#passwd").val();
         var datos = new FormData();
        datos.append("name", username);
        datos.append("pass", passwd);
     $.ajax({
        url: "/soporte/Index/validarUsuario/",
        method:"POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false, 
        success:function(respuesta){        
                if(respuesta == "nah"){
                   $("#alertUsername").parent().before('<div class="alert alert-danger fade in block-inner"><button type="button" class="close" data-dismiss="alert"><i>x</i></button>¡Verifica tus datos!<i class="icon-cancel-circle"></i></div>');
                     $("#passwd").val('');
                   return false;
                }else if(respuesta == "inactivo"){
                   $("#alertUsername").parent().before('<div class="alert alert-warning fade in block-inner"><button type="button" class="close" data-dismiss="alert"><i>x</i></button>¡Contacta al administrador!<i class="icon-warning"></i></div>');
                    $("#passwd").val('');
                    return false;
                }
                else if(respuesta=="yastas"){
                    $("#enviar").removeClass("btn-warning")
                    $("#enviar").addClass("btn-success");
                     $("#enviar").attr("disabled", false);
                    return true;
                }
            }
        });
    }else{
        $("#enviar").attr("disabled", true);
    }
}
