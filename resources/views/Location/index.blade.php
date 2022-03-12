<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Location</title>
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

  <div>
    <select class="form-control" name="" id="selectDis">
      <option value="">Select</option>
      <option value="1">1</option>
      <option value="3">3</option>
      <option value="5">5</option>
      <option value="8">8</option>
      <option value="10">10</option>
      <option value="15">15</option>
      <option value="100">100</option>
      <option value="300">300</option>
    </select>
  </div>

  <br>
  <br>

  <div id="googleMap" style="width: 100%; height: 640px"></div>

</body>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC939FE0TQkI_gw0xHgTF0KKP1gG7Hgi6U&callback=initMap&v=weekly&channel=2" async></script>

<script>
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  let map;

  let selected = 10;

  let markersArray = [];

  let myMarkersArray = [];

  $('#selectDis').change(function() {
    this.selected = $(this).val();

    selected = this.selected;

    myLocation();

  });


  setInterval(() => {

    this.selected = $('#selectDis').val() ? $('#selectDis').val() : 10;

    selected = this.selected;

    getLocation();
    
  }, 10000);

  function haversine_distance(mk1, mk2) {
    var R = 6371; // Radius of the Earth in km , if mile then 3959
    var rlat1 = mk1.position.lat() * (Math.PI / 180); // Convert degrees to radians
    var rlat2 = mk2.position.lat() * (Math.PI / 180); // Convert degrees to radians
    var difflat = rlat2 - rlat1; // Radian difference (latitudes)
    var difflon = (mk2.position.lng() - mk1.position.lng()) * (Math.PI / 180); // Radian difference (longitudes)

    var d = 2 * R * Math.asin(Math.sqrt(Math.sin(difflat / 2) * Math.sin(difflat / 2) + Math.cos(rlat1) * Math.cos(rlat2) * Math.sin(difflon / 2) * Math.sin(difflon / 2)));

    return d;

  }

  function initMap() {
    console.log('initMap');
    $.get('Location/show', function(data) {

      console.log(data, 'data');

      map = new google.maps.Map(document.getElementById("googleMap"), {

        center: {
          lat: parseFloat(data.OwnLocation.lat),
          lng: parseFloat(data.OwnLocation.lng)
        },
        mapTypeId: google.maps.MapTypeId.ROADMAP,

      });

      myLocation();

    });

  }

  function myLocation() {

    $.get('Location/show', function(data) {

      deleteOverlays();

      console.log(data, 'dataMyMy');

      var infoWindow = new google.maps.InfoWindow();

      var latlngbounds = new google.maps.LatLngBounds();

      var destinationDistance = [];

      for (let i = 0; i < data.OtherLocation.length; i++) {

        data2 = data.OwnLocation;
        data3 = data.OtherLocation[i];
        console.log(data2, data3);
        var mk1 = new google.maps.Marker({
          position: {
            lat: parseFloat(data.OwnLocation.lat),
            lng: parseFloat(data.OwnLocation.lng)
          },
          map: map,
          title: 'Your Location',
          icon: 'https://raw.githubusercontent.com/Joyanta1000/map/master/icons/human-location.png'
        });
        (function(mk1, data2) {
          google.maps.event.addListener(mk1, "click", function(e) {
            infoWindow.setContent("<div style = 'width:200px;min-height:40px'>My Location</div>");
            infoWindow.open(map, mk1);
          });
        })(mk1, data2);

        latlngbounds.extend(mk1.position);

        myMarkersArray.push(mk1);

        var mk2 = new google.maps.Marker({
          position: {
            lat: parseFloat(data3.lat),
            lng: parseFloat(data3.lng)
          },
          map: map,
          title: data3.location,
          icon: 'https://img.icons8.com/ios-filled/50/000000/marker-h.png'
        });

        var distance = haversine_distance(mk1, mk2);

        destinationDistance.push(distance);

        console.log(destinationDistance, parseFloat(selected));

        (function(mk2, data3) {
          var icon = {
            url: data3.icon,
            scaledSize: new google.maps.Size(10, 10),
            radius: 50,

          };
          google.maps.event.addListener(mk2, "click", function(e) {
            infoWindow.setContent("<div style='text-align:center;'><div style = 'width:auto; height:auto; font-family:sans-sarif; font-size: 20px;'><img class='center' src='" + icon.url + "' style='width:40%; height:auto; display: block; margin-left: auto; margin-right: auto;'><br><br> " + data3.location + "<br><br><span style='color: blue;'> " + destinationDistance[i] + " Km From My Location </span></div></div>");
            infoWindow.open(map, mk2);
          });
        })(mk2, data3);

        latlngbounds.extend(mk2.position);

        markersArray.push(mk2);

        if (destinationDistance[i] > selected) {
          mk2.setVisible(false)
          mk2.setMap(null);
          break;
        }
        var bounds = new google.maps.LatLngBounds();
        map.setCenter(latlngbounds.getCenter());
        map.fitBounds(latlngbounds);

      }

    });

  }

  function deleteOverlays() {

    if (myMarkersArray) {
      for (i in myMarkersArray) {
        myMarkersArray[i].setMap(null);
      }
      myMarkersArray.length = 0;
    }
    if (markersArray) {
      for (i in markersArray) {
        markersArray[i].setMap(null);
      }
      markersArray.length = 0;
    }
  }

  getLocation();

  function getLocation() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else {
      x.innerHTML = "Geolocation is not supported by this browser.";
    }
  }

  function showPosition(position) {

    $.ajax({
      method: "PUT",
      url: "{{ route('Location.update', 1) }}",
      data: {
        lat: position.coords.latitude,
        lng: position.coords.longitude,
      },
      success: function(result) {
        myLocation();
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