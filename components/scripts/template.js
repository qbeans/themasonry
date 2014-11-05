$(function() {
	var Mustache = require('mustache');

	$.getJSON('', function(data) {
		var template = $('#id').html();
		var html = Mustach.to_html(template, data);
		$('#id').html(html);
	}); //getJSON

});