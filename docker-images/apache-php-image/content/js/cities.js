$(function() {
	function loadCities() {
		$.getJSON( "/api/students/", function( cities ) {
			console.log(cities);
			var message = cities[0].city;

			$(".addr").text(message);
		});
	};

	loadCities();
	setInterval( loadCities, 2000 );
});