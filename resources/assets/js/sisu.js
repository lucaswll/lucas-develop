/**
 * 
 */
$(document).ready(function() {
	
	$(".page.sisu").each(function() {
		
		var $tabs = $(".nav.nav-tabs");
		var $panes = $(".tab-content");
		
		var gruposCotas = [];
		
		var url = "http://sisu.mec.gov.br/cursos?tipo=ies&valor=584";
		$.get(url, function(html) {
			
			var $obj = $('<div/>');
			$obj.html(html);
			
			var tables = {};
			
			var $tables = $obj.find("table");
			$tables.each(function(index, dom) {
				
				var $table = $(dom);
				
				var cursos = [];
				var dados = [];
				
				var $tr = $table.find("tr");
				$tr.each(function(index, dom) {
					
					var $tr = $(dom);
						
					if ($tr.hasClass("linha-curso")) {
						cursos.push($tr);
						
					} else if ($tr.hasClass("tr_cursos_desc")) {
						dados.push($tr);
					}
					
				});
				
				var rows = [];
				for (var i = 0; i < cursos.length; i++) {

					var $tr1 = cursos[i];
					var $tr2 = dados[i];
					
					var row = {
						curso: $tr1.find(".nome_curso").text().trim(),
						turno: $tr1.find(".curso_turno").text().trim(),
						cotas: []
					}
					
					$tr2.find(".lista-vagas li").each(function() {
						
						var $group = $(this).find(".vagas");
						if ($group.length > 0) {
							
							$group.find("em").remove();
							var group = $group.text().replace(/\s+/g, " ");
							
						}
						
					});
					
					$tr2.find(".nota_antiga_curso").each(function(index, trDom) {
						
						

						var string = $(this).text().replace(/\s+/g, " ");
						var parts = string.split(" ");
						
						if (string.indexOf("não havia nota de corte") == -1) {
							
							parts.forEach(function(v, k) {
								if (v == "era") row.cotas[index] = parts[k+1];
							});
							
						} else {
							row.cotas[index] = null;
						}								
						
					});
					
					// Adicionar apenas bacharelado
					if ($tr1.find("td:nth-child(2)").text().indexOf("Bacharelado") >= 0) {
						rows.push(row);
					}
				}
				
				var title = $table.find("span.campus").text().trim();
				title = title.substring(0, title.indexOf("("));
				
				tables[title] = rows;
				
			});
			
			var id = 0;
			$.each(tables, function(name, table) {
				
				// Mostrar apenas Goiânia
				if (name.indexOf("GOIÂNIA") >= 0 && name.indexOf("APARECIDA") < 0) {
					
					var tab = "tab-" + id;
					
					var $tab = $('<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#'+ tab +'">'+ name.toLowerCase() +'</a></li>');
					var $pane = $('<div class="tab-pane" id="'+ tab +'"></div>');
					
					var $table = $('<table class="table table-striped"><thead><tr><th>Curso</th><th>Turno</th><th>Cota</th><th>AC</th/></tr></thead><tbody></tbody></table>');
					
					table.forEach(function(row) {
						
						var $tr = $("<tr/>");
						$tr.append(`<td>${ row.curso.toLowerCase() }</td>`);
						$tr.append(`<td>${ row.turno.toLowerCase() }</td>`);
						
						row.cotas.forEach(function(nota, index) {
							if (index == 2 || index == row.cotas.length - 1) $tr.append(`<td>${ nota || '-' }</td>`);
						});
						
						$table.find("tbody").append($tr);
						
					});
					
					$table.DataTable({
						fixedHeader: true,
						paging: false
					});
					
					$pane.append($table);
					$tabs.append($tab);
					$panes.append($pane);
					id++;
				}
				
			});
			
		});
		
	});
	
});