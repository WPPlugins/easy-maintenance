$(document).ready(function(){
	$(function() {
		$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
	});
	$("#show_os").click(function(){
		$("#optionalsettings").toggle();
		$(this).text(function(i, v){
			return v === 'Show optional settings' ? 'Hide optional settings' : 'Show optional settings'
		})
	});
	$('.checkbox_enabler').change(function () {
		$('#em_enable').text(this.checked ? 'Activated' : 'Deactivated');
	}).change();
});