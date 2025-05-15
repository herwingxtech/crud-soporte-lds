/*========================================
=            formatear campos            =
========================================*/
$("input").focus(function(){
	$(".alert").remove();
});

/*========================================
=            Validar Usuario            =
========================================*/
var nomUsuario = false;
$("#nombre_user").change(function(){
   var user = $("#nombre_user").val();
    if(user != ""){
        var expresion = /^[a-zA-ZñáéíóúÁÉÍÓÚ]*$/;
        if(!expresion.test(user)){
            $("#nombre_user").parent().before('<div class="alert alert-warning">Error: No se permiten números ni caracteres especiales n<strong></strong></div>');
			return false;
        }
    }
});

$("#registro").click(function(){
    console.log("HOlas");
})

function controlLogin()
{
    /*----------  Validar nombre de usuario  ----------*/
    var nomUsuario = $("#nombre_user").val();
    console.log(nomUsuario);
}
