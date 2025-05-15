/*=================================================================================
=                    Eliminar registro den inmueble                               =
 =================================================================================*/ 
 $(".libera-ip").click(function(){
    var idPC = $(this).attr("idPc");
    console.log(idPC)
    var URLactual = window.location;
    swal({
		title: "¡Estás seguro(a) de liberar la IP!",
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
                url: "/soporte/Computadora/liberarIP/"+idPC,
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
                window.location="/soporte/computadoras"; 
            }
        }
    )
}

//===== Setting Datatable defaults =====//

$.extend( $.fn.dataTable.defaults, {
    autoWidth: false,
    pagingType: 'full_numbers',
    dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
    language: {
        search: '<span>Filter:</span> _INPUT_',
        lengthMenu: '<span>Show:</span> _MENU_',
        paginate: { 'first': 'First', 'last': 'Last', 'next': '>', 'previous': '<' }
    }
});

$('.table-ajax').dataTable({
    columnDefs: [{
        orderable: false,
        targets: [ 1, 6 ]
    }],
    order: [[ 0, 'desc' ]],
    ajax: 'soporte/computadoras/catalogoAjax/',
    
    "columns": [
        {data:"nomComputadora"},
        {data:"numIP"},
        {data:"mac"},
        {data:"serieComputadora"},
        
    ]
});
