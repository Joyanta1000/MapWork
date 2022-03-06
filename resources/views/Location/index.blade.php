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
    .container {
      padding: 0.5%;
    }
  </style>
</head>

<body>

  <div id="googleMap" style="width: 100%; height: 500px"></div>

  <br>

  <div>
    My Location To: 
    <select name="" id="location">
      <option value="">Select</option>
      @foreach ($locations as $location)
      <option value="{{$location->id}}">{{$location->location}}</option>
      @endforeach
    </select>
  </div>

  <br>

  <p id="d"></p>

  <br>

  <!-- <p>Click the button to get your coordinates.</p>

  <button onclick="getLocation()">Try It</button>

  <p id="demo"></p> -->

  

 

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

  function haversine_distance(mk1, mk2) {
    var R = 6371; // Radius of the Earth in km , if mile then 3959
    var rlat1 = mk1.position.lat() * (Math.PI / 180); // Convert degrees to radians
    var rlat2 = mk2.position.lat() * (Math.PI / 180); // Convert degrees to radians
    var difflat = rlat2 - rlat1; // Radian difference (latitudes)
    var difflon = (mk2.position.lng() - mk1.position.lng()) * (Math.PI / 180); // Radian difference (longitudes)

    var d = 2 * R * Math.asin(Math.sqrt(Math.sin(difflat / 2) * Math.sin(difflat / 2) + Math.cos(rlat1) * Math.cos(rlat2) * Math.sin(difflon / 2) * Math.sin(difflon / 2)));
    // return d;

//     Number.prototype.toRad = function() {
//    return this * Math.PI / 180;
// }

//     var R = 6371; // km 
// //has a problem with the .toRad() method below.
// var x1 = mk2.position.lat()-mk1.position.lat();
// var dLat = x1.toRad();  
// var x2 = mk2.position.lng()-mk1.position.lng();
// var dLon = x2.toRad();  
// var a = Math.sin(dLat/2) * Math.sin(dLat/2) + 
//                 Math.cos(mk1.position.lat().toRad()) * Math.cos(mk2.position.lat().toRad()) * 
//                 Math.sin(dLon/2) * Math.sin(dLon/2);  
// var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
// var d = R * c; 
$('#d').html('');
$('#d').html('<caption>'+d+' Km</caption>');

  }

  $('#location').change(function () {
    // alert($(this).val());
    $.ajax({
      type: "GET",
      url: "{{ url('Location/Location/show') }}",
      data: {
        location_id: $('#location').val()
      },
      success: function (data) {
        
        console.log(data);
        var mk1 = new google.maps.Marker({
          position: {
            lat: parseFloat(data.OwnLocation.lat),
            lng: parseFloat(data.OwnLocation.lng)
          },
          map: map,
          title: 'Your Location'
        });
        var mk2 = new google.maps.Marker({
          position: {
            lat: parseFloat(data.OtherLocation.lat),
            lng: parseFloat(data.OtherLocation.lng)
          },
          map: map,
          title: data.OtherLocation.location
        });
        var distance = haversine_distance(mk1, mk2);
        console.log(distance);
        $('#d').html(distance);
      }
    });
  });

  function initMap() {
    console.log('initMap');
    $.get('Location/show', function(data) {

      console.log(data, 'data');

      map = new google.maps.Map(document.getElementById("googleMap"), {

        center: {
          lat: parseFloat(data.OwnLocation.lat),
          lng: parseFloat(data.OwnLocation.lng)
        },
        zoom: 13,

      });

      new google.maps.Marker({
        position: {
          lat: parseFloat(data.OwnLocation.lat),
          lng: parseFloat(data.OwnLocation.lng),
        },
        map,
      });

    });
    // let position = {0:{lat:"22.337345690022172", lng:"91.78881901010477"}, 1:{lat:"22.337757534825446", lng:"91.78882302311422"}};
    // $.get('Location/show', function(data) {
    //   position = data.OtherLocation;
    //   for (let i = 0; i < position.length; i++) {
    //     console.log(parseFloat(position[i].lat), parseFloat(position[i].lng));
    //     new google.maps.Marker({
    //       position: {
    //         lat: parseFloat(position[i].lat),
    //         lng: parseFloat(position[i].lng),
    //       },
    //       map,
    //     });
    //   }
    // })

  }
</script>

<script>
  // var x = document.getElementById("demo");

  setInterval(function() {
    getLocation();
  }, 1000);


  function getLocation() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else {
      x.innerHTML = "Geolocation is not supported by this browser.";
    }
  }

  function showPosition(position) {
    // x.innerHTML = "Latitude: " + position.coords.latitude +
    //   "<br>Longitude: " + position.coords.longitude;

    $.ajax({
      method: "PUT",
      url: "{{ route('Location.update', 1) }}",
      data: {
        lat: position.coords.latitude,
        lng: position.coords.longitude,
      },
      success: function(result) {
        console.log(result);
        // $('#table').html(JSON.stringify(result));
      }
    });

  }

  function showError(error) {
    switch (error.code) {
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