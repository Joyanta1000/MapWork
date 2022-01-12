<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>laravel 8 Get Data From Database using Ajax - Tutsmake.com</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
 
 <style>
   .container{
    padding: 0.5%;
   } 
</style>
</head>
<body>

<div id="googleMap" style="width: 100%; height: 500px"></div>

<p>Click the button to get your coordinates.</p>

<button onclick="getLocation()">Try It</button>

<p id="demo"></p>
</body>

  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC939FE0TQkI_gw0xHgTF0KKP1gG7Hgi6U&callback=initMap&v=weekly&channel=2" async></script>

  <script>

// $(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
      
  // });
    
    let map;

    function initMap() {
      console.log('initMap');
      $.get('Location/show', function (data) {

      map = new google.maps.Map(document.getElementById("googleMap"), {
        
        center: {lat:parseFloat(data.OwnLocation.lat), lng:parseFloat(data.OwnLocation.lng)},
        zoom: 8,
      
      });
    });
      // let position = {0:{lat:"22.337345690022172", lng:"91.78881901010477"}, 1:{lat:"22.337757534825446", lng:"91.78882302311422"}};
      $.get('Location/show', function (data) {
        console.log(data.UserLocation);
        position = data.UserLocation;
        for (let i = 0; i < position.length; i++) {
          console.log(parseFloat(position[i].lat), parseFloat(position[i].lng));
        new google.maps.Marker({
          position: {
            lat: parseFloat(position[i].lat),
            lng: parseFloat(position[i].lng),
          },
          map,
        });
      }
      })
        
    }
  </script>

<script>
var x = document.getElementById("demo");

function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition, showError);
  } else { 
    x.innerHTML = "Geolocation is not supported by this browser.";
  }
}

function showPosition(position) {
  x.innerHTML = "Latitude: " + position.coords.latitude + 
  "<br>Longitude: " + position.coords.longitude;
}

function showError(error) {
  switch(error.code) {
    case error.PERMISSION_DENIED:
      x.innerHTML = "User denied the request for Geolocation."
      break;
    case error.POSITION_UNAVAILABLE:
      x.innerHTML = "Location information is unavailable."
      break;
    case error.TIMEOUT:
      x.innerHTML = "The request to get user location timed out."
      break;
    case error.UNKNOWN_ERROR:
      x.innerHTML = "An unknown error occurred."
      break;
  }
}
</script>

</html>

