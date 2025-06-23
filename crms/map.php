<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hazard Map of Lila, Bohol</title>

    <!-- Bootstrap CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/leaflet.css" />
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/leaflet.js"></script>

    <style>
        #map {
            height: 600px;
            width: 100%;
            border-radius: 10px;
        }
        .legend {
            background: white;
            padding: 8px;
            border-radius: 5px;
            font-size: 12px;
            position: absolute;
            bottom: 20px;
            left: 20px;
            box-shadow: 0px 0px 5px rgba(0,0,0,0.5);
        }
        .legend span {
            display: inline-block;
            width: 14px;
            height: 14px;
            margin-right: 5px;
        }
        #townInfo {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            background: #f8f9fa;
            margin-top: 10px;
            max-height: 400px;
            overflow-y: auto;
        }
    </style>
</head>
<body>

    <!-- Bootstrap Navbar -->
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid d-flex justify-content-between">
            <a class="navbar-brand" href="#">Lila, Bohol Hazard Map</a>
            <a href="home.html" class="btn btn-light btn-sm">Home</a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">Hazard Map</h5>
                    </div>
                    <div class="card-body">
                        <div id="map"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <!-- Toggle Buttons -->
                <div class="mb-3">
                    <button class="btn btn-sm btn-outline-primary" onclick="updateMap('all')">Show All</button>
                    <button class="btn btn-sm btn-outline-success" onclick="updateMap('vaccinated')">Vaccinated</button>
                    <button class="btn btn-sm btn-outline-warning" onclick="updateMap('registered')">Registered</button>
                    <button class="btn btn-sm btn-outline-danger" onclick="updateMap('non-vaccinated')">Non-Vaccinated</button>
                </div>

                <!-- Town Information -->
                <div id="townInfo">
                    <h6><b>Town Info</b></h6>
                    <hr>
                    <p>Select a town zone to see details.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        var map = L.map('map').setView([9.5984, 124.0937], 13);

        // Load OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        let allZones = [];

        // Fetch CRMS data and draw zones
        fetch('get_data.php')
            .then(response => response.json())
            .then(data => {
                console.log("Data received:", data);
                if (!Array.isArray(data)) {
                    console.error("Invalid data format");
                    return;
                }
                allZones = data;
                updateMap('all'); // Initialize with all zones
            })
            .catch(error => console.error('Error loading data:', error));

            function updateMap(filter) {
                // Remove existing zones
                map.eachLayer(layer => { if (layer instanceof L.Circle) map.removeLayer(layer); });

                allZones.forEach(zone => {
                    let show = 
                        filter === "all" || 
                        (filter === "vaccinated" && zone.vaccinated_count > 0) || 
                        (filter === "non-vaccinated" && zone.vaccinated_count === 0) ||
                        (filter === "registered" && zone.dog_count > 0);

                    if (show) {
                        let color;
                        let isHighRisk = (zone.vaccinated_count / zone.dog_count) < 0.3; // Less than 30% vaccinated
                        let radius = zone.dog_count > 50 ? 750 : zone.dog_count > 20 ? 500 : 250;

                        if (isHighRisk) {
                            color = "orange"; // High-Risk Area
                        } else if (zone.vaccinated_count > 0) {
                            color = "green"; // Vaccinated
                        } else {
                            color = "red"; // Non-Vaccinated
                        }

                        let circle = L.circle([zone.dLatitude, zone.dLongitude], {
                            color: color,
                            fillColor: color,
                            fillOpacity: 0.5,
                            radius: radius
                        }).addTo(map);

                        // ✅ Add Warning for High-Risk Areas
                        let warningMessage = isHighRisk ? "<br><strong style='color: orange;'>⚠ High-Risk Area: Low vaccination rate!</strong>" : "";

                        circle.bindPopup(`
                            <b>${zone.dTown}</b><br>
                            Registered Dogs: ${zone.dog_count}<br>
                            Vaccinated Dogs: ${zone.vaccinated_count}
                            ${warningMessage}
                        `);

                        circle.on('click', function() {
                            let dogList = zone.dogs.map(dog => `
                                <li><strong>${dog.dName}</strong> - Owner: ${dog.dOwner} (${dog.vaccinated ? "✅ Vaccinated" : "❌ Not Vaccinated"})</li>
                            `).join('');

                            document.getElementById("townInfo").innerHTML = `
                                <h6><b>${zone.dTown}</b></h6>
                                <hr>
                                <p><strong>Registered Dogs:</strong> ${zone.dog_count}</p>
                                <p><strong>Vaccinated Dogs:</strong> ${zone.vaccinated_count}</p>
                                ${isHighRisk ? "<p class='alert alert-danger'>⚠ High-Risk: Less than 30% dogs vaccinated!</p>" : ""}
                                <hr>
                                <h6>Dog List:</h6>
                                <ul>${dogList}</ul>
                            `;
                        });
                    }
                });
            }


        // Legend
        var legend = L.control({ position: "bottomleft" });

        legend.onAdd = function(map) {
            var div = L.DomUtil.create("div", "legend");
            div.innerHTML += "<strong>Dog Vaccination Zones</strong><br>";
            div.innerHTML += '<span style="background: green"></span> Vaccinated<br>';
            div.innerHTML += '<span style="background: yellow"></span> Registered<br>';
            div.innerHTML += '<span style="background: red"></span> Non-Vaccinated<br>';
            return div;
        };

        legend.addTo(map);
    </script>

</body>
</html>
