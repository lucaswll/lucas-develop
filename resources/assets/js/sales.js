'use strict';

$(document).ready(function() {

	$(".page.sales.form").each(function() {
		
		var $page = $(this);
		var $form = $(this).find("form");
		
		var $table = $page.find(".products-table");
		var $productSelect = $form.find("[name=product]");
		var $productAdd = $page.find(".btn-add-product");
		
		var $total = $form.find("[name=total]");
		var $in = $form.find("[name=in]");
		var $out = $form.find("[name=out]");
		
		var products = {};
		$productSelect.find("option").each(function() {
			
			var id = parseInt($(this).attr("value"));
			products[id] = {
				id: id,
				price: parseFloat($(this).attr("data-price")),
				name: $(this).attr("data-name"),
				stock: $(this).attr("data-stock")
			};
			
		});
		
		$productAdd.click(function() {
			
			var id = $productSelect.val();
			var product = products[id];
			
			if (id && product) {
				
				var $option = $productSelect.find("option:selected");
				$option.attr("disabled", "disabled");
				
				// Adicionar produto à tabela
				var tr = `
				<tr data-id="${ product.id }">
	                <td>${ product.name }
	                	<input type="hidden" name="product_id[]" value="${ product.id }" />
	                	<input type="hidden" name="product_name[]" value="${ product.name }" />
	                </td>
	                <td>
	                	<div class="input-group">
	                		<input type="number" maxlength="3" name="product_qty[]" value="1" class="form-control product-qty" />
	                        <div class="input-group-append">
	                        	<button class="btn btn-light btn-update-qty" data-type="sub" type="button" disabled><i class="fas fa-caret-down"></i></button>
	                            <button class="btn btn-light btn-update-qty" data-type="add" type="button"><i class="fas fa-caret-up"></i></button>
	                        </div>
	                    </div>
	                </td>
	                <td class="subtotal">${ product.price }</td>
	                <td>
	                    <button type="button" class="btn btn-sm btn-link btn-rmv-product" title="Remover produto"><i class="fas fa-times"></i></button>
	                </td>
	            </tr>`;
				
				$table.removeClass("hide").find("tbody").prepend(tr);
				$productSelect.val("").change();
				updateSaleTotal();
				
			}
			
		});
		
		// Atualizar valor total da venda
		function updateSaleTotal() {
			
			var total = 0;
			$table.find("tbody > tr").each(function() {
				
				var id = $(this).attr("data-id");
				var qty = $(this).find(".product-qty").val();
				
				var product = products[id];
				total += (product.price * qty);
				
			});
			
			$total.val(numberFormat(total, "br"));
			$in.change();
		}
		
		// Atualizar dados de total, entrada e saída
		$in.change(function() {
			
			var value = numberFormat($(this).val());
			var total = numberFormat($total.val());
			$out.val("");
			
			if (total && value) $out.val(numberFormat(value - total, "br"));
			
		});
		
		// Remover produto
		$table.on("click", ".btn-rmv-product", function() {
			
			var $tr = $(this).closest("tr");
			$tr.remove();
			
			var id = $tr.attr("data-id");
			
			var $option = $productSelect.find(`option[value="${ id }"]`);
			$option.removeAttr("disabled");
			
			if ($table.find("tbody > tr").length == 0) $table.addClass("hide");
			updateSaleTotal();
			
		});
		
		// Atualizar quantidade do produto
		$table.on("change", ".product-qty", function() {
			
			var $tr = $(this).closest("tr");
			var id = $tr.attr("data-id");
			
			var $sub = $tr.find('.btn-update-qty[data-type="sub"]');
			$sub.attr("disabled", "disabled");
			
			var product = products[id];
			var value = parseInt($(this).val());
			
			if (isNaN(value) || value <= 0) {
				value = 1;
				
			} else if (value > 1) {
				value = value > product.stock ? product.stock : value;
			}
			
			if (value > 1) $sub.removeAttr("disabled");
			$(this).val(value);
			$tr.find(".subtotal").text(value * product.price);
			
			updateSaleTotal();
			
		});
		
		// Atualizar quantidade de terminado produto
		$table.on("click", ".btn-update-qty", function() {
			
			var type = $(this).attr("data-type");
			var $tr = $(this).closest("tr");
			var $qty = $tr.find('.product-qty');
			
			var value = parseInt($qty.val());
			value = (value > 1 && type == "sub") ? (value - 1) : (value + 1);
			
			$qty.val(value).change();
			
		});
		
	});
	
});