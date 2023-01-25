<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Map</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
        .form-style-6 {
            font: 95% Arial, Helvetica, sans-serif;
            max-width: 400px;
            margin: 10px auto;
            padding: 16px;
            background: #F7F7F7;
        }

        .form-style-6 h1 {
            background: #43D1AF;
            padding: 20px 0;
            font-size: 140%;
            font-weight: 300;
            text-align: center;
            color: #fff;
            margin: -16px -16px 16px -16px;
        }

        .form-style-6 input[type="text"],
        .form-style-6 input[type="date"],
        .form-style-6 input[type="datetime"],
        .form-style-6 input[type="email"],
        .form-style-6 input[type="number"],
        .form-style-6 input[type="search"],
        .form-style-6 input[type="time"],
        .form-style-6 input[type="url"],
        .form-style-6 textarea,
        .form-style-6 select {
            -webkit-transition: all 0.30s ease-in-out;
            -moz-transition: all 0.30s ease-in-out;
            -ms-transition: all 0.30s ease-in-out;
            -o-transition: all 0.30s ease-in-out;
            outline: none;
            box-sizing: border-box;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            width: 100%;
            background: #fff;
            margin-bottom: 4%;
            border: 1px solid #ccc;
            padding: 3%;
            color: #555;
            font: 95% Arial, Helvetica, sans-serif;
        }

        .form-style-6 input[type="text"]:focus,
        .form-style-6 input[type="date"]:focus,
        .form-style-6 input[type="datetime"]:focus,
        .form-style-6 input[type="email"]:focus,
        .form-style-6 input[type="number"]:focus,
        .form-style-6 input[type="search"]:focus,
        .form-style-6 input[type="time"]:focus,
        .form-style-6 input[type="url"]:focus,
        .form-style-6 textarea:focus,
        .form-style-6 select:focus {
            box-shadow: 0 0 5px #43D1AF;
            padding: 3%;
            border: 1px solid #43D1AF;
        }

        .form-style-6 input[type="submit"],
        .form-style-6 input[type="button"] {
            box-sizing: border-box;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            width: 100%;
            padding: 3%;
            background: #43D1AF;
            border-bottom: 2px solid #30C29E;
            border-top-style: none;
            border-right-style: none;
            border-left-style: none;
            color: #fff;
        }

        .form-style-6 input[type="submit"]:hover,
        .form-style-6 input[type="button"]:hover {
            background: #2EBC99;
        }
    </style>
</head>
<body>
<div class="form-style-6">
    <h1>Register </h1>
    @if(Session::has('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
            @php
                Session::forget('success');
            @endphp
        </div>
    @endif
    <form method="post" enctype="multipart/form-data" action="{{route('store')}}">
        @csrf
        @if ($errors->has('name'))
            <span class="text-danger">{{ $errors->first('name') }}</span>
        @endif
        <input type="text" name="name" placeholder="Your Name"/>

        @if ($errors->has('age'))
            <span class="text-danger">{{ $errors->first('age') }}</span>
        @endif
        <input type="text" name="age" placeholder="Your Age"/>


        @if ($errors->has('latitude'))
            <span class="text-danger">{{ $errors->first('latitude') }}</span>
        @endif
        <input type="text" id="lat" name="latitude" placeholder="Click on Map"/>


        @if ($errors->has('longitude'))
            <span class="text-danger">{{ $errors->first('longitude') }}</span>
        @endif
        <input type="text" id="long" name="longitude" placeholder="Click on Map"/>

        @if ($errors->has('location'))
            <span class="text-danger">{{ $errors->first('location') }}</span>
        @endif
        <input type="text" id="location" name="location" placeholder="Click on Map"/>


        <input type="submit" value="Send"/> <br> <br>

        <div class="full-width" id="googleMap" style="width:400px;height:400px;margin:0 auto"></div>

    </form>
</div>



<script>
    // properties of map

    var marker;
    var map;
    var geocoder;
    var infowindow;

    var cairo;

    function initMap() {
        // properties
        cairo = {lat: 25.266110416387296, lng: 30.627344080078114};
        var mapProperties = {
            center: cairo, // required
            zoom: 5, // required
            mapTypeId: google.maps.MapTypeId.TERRAIN, // required
            // type =>   ROADMAP   || SATELLITE   || HYBRID    || TERRAIN
            disableDefaultUI: false,
            zoomControl: true,
            mapTypeControl: true,
            zoomControlOptions: {
                style: google.maps.ZoomControlStyle.LARGE,
                position: google.maps.ControlPosition.LEFT
            },
            mapTypeControlOptions: {
                style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
            }
        };
        // map object
        // 1

        map = new google.maps.Map(document.getElementById("googleMap"), mapProperties);
        geocoder = new google.maps.Geocoder();
        infowindow = new google.maps.InfoWindow();
        // 2

        marker = new google.maps.Marker({
            position: cairo,
            animation: google.maps.Animation.BOUNCE,
        });

        marker.setMap(map);

        google.maps.event.addListener(map, 'click', function (event) {
            moveMarker({position: event.latLng});
        });
    }

    function moveMarker(props) {
        marker.setPosition(props.position);
        $("#lat").val(props.position.lat());
        $("#long").val(props.position.lng());
        geocodeLatLng(geocoder, map, infowindow);

    }


    function geocodeLatLng(geocoder, map, infowindow) {
        const input = document.getElementById("address");
        const latlng = {
            lat: parseFloat($("#lat").val()),
            lng: parseFloat($("#long").val()),
        };
        geocoder.geocode({location: latlng}).then((response) => {
            if (response.results[0]) {
                infowindow.setContent(response.results[0].formatted_address);
                $("#location").val(response.results[0].formatted_address);
                infowindow.open(map, marker);
            } else {
                window.alert("No results found");
            }
        })
            .catch((e) => window.alert("Geocoder failed due to: " + e));
    }

    // map loading

    window.initMap = initMap;


</script>
<script type="text/javascript"
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAkOYnMZLpXgMX8UqpFpJ2VJ11dWBqCgCk&sensor=false&callback=initMap&language=ar"
        defer></script>
</body>
</html>
