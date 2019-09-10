'use strict';

var $dialog;

(function() {
	
	$dialog = {
			
		confirm: function(message, callback) {
			
			bootbox.dialog({
				title: "Atenção!",
				message: message,
				buttons: {
					cancel: { label: "Cancelar", className: "btn-light" },
				    confirm: { label: "Ok", className: "btn-primary", callback: callback }
				}
			});
		},
		
		message: function(message, title, callback) {
			
			var buttons = { confirm: { label: "Ok", className: "btn-primary" } };
			
			if (callback) {
				buttons.confirm.callback = callback;
			}
			
			bootbox.dialog({
				title: title || "Atenção!",
				message: message,
				buttons: buttons
			});
		}
		
	};

})();
