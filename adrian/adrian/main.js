function initMap() {
    var map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: -34.397, lng: 150.644 },
        zoom: 8
    });

    var input = document.getElementById('pac-input');
    var userLocationInput = document.getElementById('user-location'); // New input field

    var searchBox = new google.maps.places.SearchBox(input);
    var userLocationSearchBox = new google.maps.places.SearchBox(userLocationInput); // New SearchBox for user location

    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(userLocationInput); // Add the user location input to the map

    map.addListener('bounds_changed', function () {
        searchBox.setBounds(map.getBounds());
        userLocationSearchBox.setBounds(map.getBounds());
    });

    var markers = [];
    var directionsRenderer = new google.maps.DirectionsRenderer();
    var directionsService = new google.maps.DirectionsService();

    searchBox.addListener('places_changed', function () {
        var places = searchBox.getPlaces();

        if (places.length === 0) {
            return;
        }

        // Clear out the old markers.
        markers.forEach(function(marker) {
            marker.setMap(null);
        });
        markers = [];

        // For each place, get the icon, name and location.
        var bounds = new google.maps.LatLngBounds();
        places.forEach(function(place) {
            if (!place.geometry) {
                console.log("Returned place contains no geometry");
                return;
            }
            var icon = {
                url: place.icon,
                size: new google.maps.Size(71, 71),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(25, 25)
            };

            // Create a marker for each place.
            markers.push(new google.maps.Marker({
                map: map,
                icon: icon,
                title: place.name,
                position: place.geometry.location
            }));

            if (place.geometry.viewport) {
                // Only geocodes have viewport.
                bounds.union(place.geometry.viewport);
            } else {
                bounds.extend(place.geometry.location);
            }
        });
        map.fitBounds(bounds);
    });

    userLocationSearchBox.addListener('places_changed', function () {
        var places = userLocationSearchBox.getPlaces();

        if (places.length === 0) {
            return;
        }

        var userLocation = places[0].geometry.location;

        // Calculate and display the route
        calculateAndDisplayRoute(userLocation);
    });

    function calculateAndDisplayRoute(userLocation) {
        var destination = map.getCenter(); // Get the destination from the center of the map

        directionsService.route(
            {
                origin: userLocation,
                destination: destination,
                travelMode: 'DRIVING'
            },
            function (response, status) {
                if (status === 'OK') {
                    directionsRenderer.setDirections(response);
                } else {
                    window.alert('Directions request failed due to ' + status);
                }
            }
        );
    }
    directionsRenderer.setMap(map);
}

$(document).ready(function() {
    $('#infoModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var name = button.data('name');

        var modal = $(this);
        modal.find('.modal-title').text(name);

        $.ajax({
            url: "https://en.wikipedia.org/w/api.php",
            data: {
                format: "json",
                action: "query",
                prop: "extracts",
                exintro: true,
                explaintext: true,
                titles: name
            },
            dataType: 'jsonp',
            success: function(data) {
                var pages = data.query.pages;
                var extract = '';
                for (var pageId in pages) {
                    extract = pages[pageId].extract;
                    break;
                }
                modal.find('.modal-body').text(extract);
            }
        });
    });
});
// Function to display error messages
function displayErrors(errors) {
    const errorsContainer = document.getElementById('errorsContainer');

    // Clear out any old errors
    errorsContainer.innerHTML = '';

    // Add the new errors
    errors.forEach(error => {
        const errorElement = document.createElement('p');
        errorElement.classList.add('error-message');
        errorElement.textContent = error;
        errorsContainer.appendChild(errorElement);
    });

    // Make the errors container visible
    errorsContainer.style.display = 'block';
}


function showPasswordHelp() {
    $('#passwordHelp').show();
 }
 
 function hidePasswordHelp() {
    $('#passwordHelp').hide();
 }

