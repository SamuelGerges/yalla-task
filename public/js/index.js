// properties of map

var marker;
var map;
var geocoder;
var infowindow;

var cairo ;
function initMap() {
    // properties
    cairo = {lat:25.266110416387296,lng:30.627344080078114};
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
    geocodeLatLng(geocoder,map,infowindow);

}



function geocodeLatLng(geocoder, map, infowindow) {
    const input = document.getElementById("address");
    const latlng = {
        lat: parseFloat($("#lat").val()),
        lng: parseFloat($("#long").val()),
    };
    geocoder.geocode({ location: latlng }).
    then((response) => {
        if (response.results[0]) {
            infowindow.setContent(response.results[0].formatted_address);
            $("#address").val(response.results[0].formatted_address);
            infowindow.open(map, marker);
        }
        else {
            window.alert("No results found");
        }
    })
        .catch((e) => window.alert("Geocoder failed due to: " + e));
}

// map loading

window.initMap = initMap;


