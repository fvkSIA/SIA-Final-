<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Map of Metro Manila</title>
    <meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />
    <link rel="icon" type="image/png" href="../HanapKITA.png">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        body { margin: 0; padding: 0; }
        #map-container {
            position: relative;
            width: 100vw; 
            height: 70vh;
            margin: auto;
            background-color: #f0f0f0;
            border-radius: 15px;
            overflow: hidden;
        }
        #map { 
            position: absolute; 
            top: 0; 
            bottom: 0; 
            width: 100%; 
        }
    </style>
</head>
<body>
    <div id="map-container">
        <div id="map"></div>
    </div>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        var map = L.map('map', {
            center: [14.5995, 120.9842],
            zoom: 10,
            scrollWheelZoom: true
        });

        var southWest = L.latLng(14.4000, 120.9000);
        var northEast = L.latLng(14.8000, 121.1000);
        var bounds = L.latLngBounds(southWest, northEast);

        map.setMaxBounds(bounds);
        map.on('drag', function() {
            map.panInsideBounds(bounds, { animate: false });
        });

        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://carto.com/">CARTO</a>',
            maxZoom: 18
        }).addTo(map);

        // Function to create a marker
        function createMarker(coords, name, count) {
            var marker = L.marker(coords).addTo(map);
            marker.bindPopup("<b>" + name + "</b><br>" + count + " Job Posted");
            marker.on('mouseover', function() {
                marker.openPopup();
            });
            marker.on('mouseout', function() {
                marker.closePopup();
            });
            return marker;
        }

        // Fetch locations data from PHP file
        fetch('../get_locations.php')
            .then(response => response.json())
            .then(data => {
                console.log('Fetched data:', data);

                const cityCoords = {
                    "Manila": [14.6091, 120.9822],
                    "Quezon City": [14.6760, 121.0437],
                    "Caloocan": [14.7402, 120.9780],
                    "Las Piñas": [14.4334, 120.9935],
                    "Makati": [14.5504, 121.0244],
                    "Malabon": [14.6516, 120.9555],
                    "Mandaluyong": [14.5824, 121.0360],
                    "Marikina": [14.6358, 121.0973],
                    "Muntinlupa": [14.4023, 121.0294],
                    "Navotas": [14.7082, 120.9411],
                    "Parañaque": [14.4911, 121.0190],
                    "Pasay": [14.5538, 120.9830],
                    "Pasig": [14.5734, 121.0605],
                    "San Juan": [14.6096, 121.0204],
                    "Taguig": [14.5355, 121.0545],
                    "Valenzuela": [14.6833, 120.9667],
                    "Pateros": [14.5581, 121.0678]
                };

                data.forEach(item => {
                    if (cityCoords[item.location]) {
                        createMarker(cityCoords[item.location], item.location, item.count);
                    } else {
                        console.log('No coordinates found for:', item.location);
                    }
                });
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
    </script>
</body>
</html>
