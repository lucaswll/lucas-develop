$(document).ready(function() {
    
    $(".cpf").mask("999.999.999-99");
    $(".cnpj").mask("99.999.999/9999-99");

    $(".money").mask("#.##0,00", { reverse: true });
    $(".percent").mask("##0,00", { reverse: true });
    
    $(".datetime").mask("99/99/9999 99:99");

    // Limitar campos números com valores mínimos e/ou máximos
    $("input[min-value], input[max-value]").change(function() {

        var value = numberFormat($(this).val());
        if (value) {

            var min = numberFormat($(this).attr("min-value"));
            var max = numberFormat($(this).attr("max-value"));
            
            if (!isNaN(min) && value - min < 0) value = min;
            if (!isNaN(max) && value - max > 0) value = max;
            
            $(this).val(numberFormat(value, "br"));

        }

    });
    
    // Botão para remover um registro
	$(".btn-delete").click(function(e) {

	    e.preventDefault();

        var id = $(this).attr('data-id');
        var action = $(this).attr('href');
        var question = $(this).attr('data-question') || "Essa é uma ação <b>irrerversível</b>.<br/>Tem certeza que deseja continuar?";
        
        if (id && action) {
        	
        	
        	var $form = 
        	$('<form method="post" class="hide" action="'+action+'">'+
        		'<input class="hide" name="_method" value="DELETE"/>'+
        		'<input class="hide" name="_token" value="'+window.Laravel.token+'">'+
        		'<input name="id" value="'+id+'"/>'+
        	'</form>');
        	
        	$dialog.confirm(question, function() {
        		$("body").append($form);
        		$form.submit();
        	});
        }
	        
	});

});

/**
 * Formata um número para determinado formato
 * @param {*} value 
 * @param {*} format 
 */
function numberFormat(value, format) {

    format = format || "en";

    if (value) {

        if (format == "en") {
            return parseFloat(value.replace(".", "").replace(",", ".")).toFixed(2);

        } else if (format == "br") {

            var parts = value.toString().split(".");
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            return parts.join(",");
        }

    } else {
        return false;
    }
}