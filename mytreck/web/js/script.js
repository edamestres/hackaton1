function initMap() {
  var paris = {lat: 48.85, lng: 2.3};
  var chartres = {lat: 48.439758, lng: 1.468494};
//parametres de la carte: centrage, scroll autorisé et zoom
  var map = new google.maps.Map(document.getElementById('map'), {
    center: paris,
    scrollwheel: true,
    zoom: 6
  });

  var directionsDisplay = new google.maps.DirectionsRenderer({
    map: map
  });

  // Set destination, origin and travel mode.
  var request = {
    destination: chartres,
    origin: paris,
    travelMode: google.maps.TravelMode.BICYCLING
  };

  // Pass the directions request to the directions service.
  var directionsService = new google.maps.DirectionsService();
  directionsService.route(request, function(response, status) {
    if (status == google.maps.DirectionsStatus.OK) {
      // Display the route on the map.
      directionsDisplay.setDirections(response);
    }
  });
}