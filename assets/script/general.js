$(document).ready(function() {
    $('#table_members').dataTable();

		$('#btn-add-members').on('click', function(e) {
	    var $this = $(this) ;
			$('.content_table').before('<div id="include"></div>');
			$this.slideDown("500", function () {
			    $('#include').load('members/edit .form_members', null, function() {}).append('<a class="close">&#215;</a>');;
			});
			e.preventDefault();
	  });
		$('.close').on('click', function(e) {
	    var $this = $(this) ;
			$('#include').remove();
			e.preventDefault();
	  });
	
} );