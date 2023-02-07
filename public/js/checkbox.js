//funcion para capturar el id del checkbox seleccionado en la seccion "¿De qué temas hablas?""
$("body").on("click", "label", function(e) {
  var getValue = $(this).attr("for");
  var goToParent = $(this).parents(".select-size");
  var getInputRadio = goToParent.find("input[id = " + getValue + "]");
  //console.log(getInputRadio.attr("id"));  
});


//funcion para habilitar o desabilitar los inputs de las redes sociales
function smInputState(checkbox_id, input_id) {
	//console.log("checkbox_id "+checkbox_id+" input_id "+input_id);
			
	$(".chb").change(function()
	{
		//the checkbox is unchecked add the properties to the input
		$(".chb").prop('checked',false);
		$(".inp").val('');
		$(".inp").prop('disabled', true);
		$(".inp").addClass('disable-sm-input');

		//check the box and enable the input
		$(this).prop('checked',true);
		$('#'+this.value).removeClass('disable-sm-input');
		$('#'+this.value).prop('disabled', false);
	});
 
}


/*
$(function(){
    $(".container .form-check-input").change(function(){
		console.log("yyup");
        $(this).siblings().attr("disabled", $(this).is(":checked"));  
    });
});


var selected = [];
$('#container input:checked').each(function() {
    selected.push($(this).attr('name'));
});*/