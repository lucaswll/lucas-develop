/**
 * 
 */
$(document).ready(function() {
	
	$(".page.customers.form").each(function() {
		
		var $page = $(this);
		var $form = $(this).find("form");
		
		$form.find("[name=type_id]").change(function() {
			
			var value = $(this).val();
			
			if (value == 1) {
				
				$form.find(".cpf-group").removeClass("hide");
				$form.find(".cpf-group .form-control").attr("required", "required");
				
				$form.find(".cnpj-group").addClass("hide");
				$form.find(".cnpj-group .form-control").val("").removeAttr("required");
				
				$form.find("[name=name]").parent().find("label").text("Nome");
				
			} else if (value == 2) {
				
				$form.find(".cnpj-group").removeClass("hide");
				$form.find(".cnpj-group .form-control").attr("required", "required");
				
				$form.find(".cpf-group").addClass("hide");
				$form.find(".cpf-group .form-control").val("").removeAttr("required");
				
				$form.find("[name=name]").parent().find("label").text("Razão social");
				
			}
			
		});
		
		$form.find("[name=state_id]").change(function() {
			
			var id = $(this).val();
			var $city = $form.find("[name=city_id]");
			
			if (id) {
				
				var url = window.Laravel.url + "/state/" + id + "/cities";
				
				$.get(url, function(cities) {
					
					$city.html("");
					
					cities.forEach(function(city) {
						$city.append(`<option value="${city.id}">${city.name}</option>`);
					});
					
					if ($city.attr("data-id")) {
						$city.val($city.attr("data-id"));
					}

					$city.selectpicker("refresh");
					
				});
				
			} else {
				$city.val("").html('<option value="">Selecione um estado</option>');
				$city.selectpicker("refresh");
			}
			
		}).change();
		
	});
	
});