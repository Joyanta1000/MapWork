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

  <!-- <br>

  <div>
    My Location To:
    <select name="" id="location">
      <option value="">Select</option>
      @foreach ($locations as $location)
      <option value="{{$location->id}}">{{$location->location}}</option>
      @endforeach
    </select>
  </div>

  <br> -->

  <!-- <p>Click the button to get your coordinates.</p>

  <button onclick="getLocation()">Try It</button>

  <p id="demo"></p> -->





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

  $('#selectDis').change(function() {
    this.selected = $(this).val();
    // console.log($(this).val());

    selected = this.selected;


    initMap();


  });


  function r() {
    console.log(selected);
  }




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
        // zoom: 13,

      });

      // details


      //     const request = {
      //   placeId: "ChIJN1t_tDeuEmsRUsoyG83frY4",
      //   fields: ["name", "formatted_address", "place_id", "geometry"],
      // };
      // const infowindow = new google.maps.InfoWindow();
      // const service = new google.maps.places.PlacesService(map);

      // service.getDetails(request, (place, status) => {
      //   if (
      //     status === google.maps.places.PlacesServiceStatus.OK &&
      //     place &&
      //     place.geometry &&
      //     place.geometry.location
      //   ) {
      //     const marker = new google.maps.Marker({
      //       map,
      //       position: place.geometry.location,
      //     });

      //     google.maps.event.addListener(marker, "click", () => {
      //       const content = document.createElement("div");
      //       const nameElement = document.createElement("h2");

      //       nameElement.textContent = place.name;
      //       content.appendChild(nameElement);

      //       const placeIdElement = document.createElement("p");

      //       placeIdElement.textContent = place.place_id;
      //       content.appendChild(placeIdElement);

      //       const placeAddressElement = document.createElement("p");

      //       placeAddressElement.textContent = place.formatted_address;
      //       content.appendChild(placeAddressElement);
      //       infowindow.setContent(content);
      //       infowindow.open(map, marker);
      //     });
      //   }
      // });

      // details

      var array = [];

      // function dataURLtoFile(dataurl, icon) {

      //   var arr = dataurl.split(','),
      //     mime = arr[0].match(/:(.*?);/)[1],
      //     bstr = atob(arr[1]),
      //     n = bstr.length,
      //     u8arr = new Uint8Array(n);

      //   while (n--) {
      //     u8arr[n] = bstr.charCodeAt(n);
      //   }

      //   return new File([u8arr], icon, {type:mime});
      // }


      // imageMy = function(icon) {


      //   let imageFile = icon;
      //   var reader = new FileReader();

      //   // reader.onload = function (e) {
      //   var img = url(icon);

      //   // console.log(img);
      //   // img.onload = function (event) {
      //   // Dynamically create a canvas element
      //   var canvas = document.createElement("canvas");

      //   // var canvas = document.getElementById("canvas");
      //   var ctx = canvas.getContext("2d");

      //   // Actual resizing
      //   ctx.drawImage(img, 1000, 1000);

      //   // Show resized image in preview element
      //   var dataurl = canvas.toDataURL(imageFile.type);
      //   // document.getElementById("preview").src = dataurl;

      //   console.log(img, 'img');

      //   console.log(dataURLtoFile(img, icon));

      //   // base 64 decoding



      //   return dataURLtoFile(dataurl, icon);

      //   // console.log();
      //   // }

      //   // }
      //   // return dataurl;
      //   // img.src = e.target.result;

      // }

      var infoWindow = new google.maps.InfoWindow();

      var latlngbounds = new google.maps.LatLngBounds();

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
          title: 'Your Location'
        });
        (function (mk1, data2) {
                google.maps.event.addListener(mk1, "click", function (e) {
                    infoWindow.setContent("<div style = 'width:200px;min-height:40px'>My Location</div>");
                    infoWindow.open(map, mk1);
                });
            })(mk1, data2);
 
            //Extend each marker's position in LatLngBounds object.
            latlngbounds.extend(mk1.position);
        // window.ctx = canvas.getContext("2d");

        // var mk2 = new google.maps.Marker({
        //   position: {
        //     lat: parseFloat(data.OtherLocation[i].lat),
        //     lng: parseFloat(data.OtherLocation[i].lng)
        //   },
        //   map: map,
        //   title: data.OtherLocation[i].location,
        //   icon: data.OtherLocation[i].icon
        //   // imageMy(data.OtherLocation[i].icon).name
        //   // data.OtherLocation[i].icon
        // });

        var mk2 = new google.maps.Marker({
          position: {
            lat: parseFloat(data3.lat),
            lng: parseFloat(data3.lng)
          },
          map: map,
          title: data3.location,
          icon: 'https://img.icons8.com/ios-filled/50/000000/marker-h.png'
        });
        (function (mk2, data3) {
                google.maps.event.addListener(mk2, "click", function (e) {
                    infoWindow.setContent("<div style = 'width:auto;min-height:auto; font-family:sans-sarif; font-size: 30px;'><img class='center' src='"+ data3.icon +"'  width='auto' height='auto'><br><br> " + data3.location + "</div>");
                    infoWindow.open(map, mk2);
                });
            })(mk2, data3);
 
            //Extend each marker's position in LatLngBounds object.
            latlngbounds.extend(mk2.position);
        var distance = haversine_distance(mk1, mk2);


        console.log(distance, parseFloat(selected));



        if (distance > selected) {
          mk2.setMap(null);
          break;
        }

        array.push(mk2);

        var markers = mk2; //some array

      // console.log(markers.length, 'ma');
      // var bounds = new google.maps.LatLngBounds();
      // for (var j = 0; j < 10; j++) {
      //   bounds.extend(markers[j]);
      // }

      // map.fitBounds(bounds);

        

      }

      var bounds = new google.maps.LatLngBounds();
 
        //Center map and adjust Zoom based on the position of all markers.
        map.setCenter(latlngbounds.getCenter());
        map.fitBounds(latlngbounds);


      



    });

  }

  // setInterval(function() {
    getLocation();
  // }, 1000);


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