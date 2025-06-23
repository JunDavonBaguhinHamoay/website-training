<?php 
require_once "config.php";

// Modify the query to join the tblreg table with the towns table
$sql_query = "SELECT t.dTown, 
                     COUNT(*) AS dog_count, 
                     SUM(CASE WHEN r.dVaccinated = 'Yes' THEN 1 ELSE 0 END) AS vaccinated_count
              FROM tblreg r
              JOIN towns t ON r.dTownID = t.id
              GROUP BY t.dTown";

$towns = [];
$dog_counts = [];
$vaccinated_counts = [];
$non_vaccinated_counts = [];

if ($result = $conn->query($sql_query)) {
    while ($row = $result->fetch_assoc()) {
        $towns[] = $row['dTown'];
        $dog_counts[] = $row['dog_count'];
        $vaccinated_counts[] = $row['vaccinated_count'];
        $non_vaccinated_counts[] = $row['dog_count'] - $row['vaccinated_count']; // Calculate non-vaccinated
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Canines by Town</title>
    <!-- Bootstrap CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="js/bootstrap.bundle.min.js"></script>
    <!-- Google Charts -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart', 'bar']});
        google.charts.setOnLoadCallback(drawCharts);

        function drawCharts() {
            // ✅ Pie Chart Data
            var pieData = google.visualization.arrayToDataTable([
                ['Status', 'Count'],
                ['Vaccinated', <?php echo array_sum($vaccinated_counts); ?>],
                ['Non-Vaccinated', <?php echo array_sum($non_vaccinated_counts); ?>]
            ]);

            var pieOptions = {
                title: 'Vaccination Status of Registered Dogs',
                is3D: true
            };

            var pieChart = new google.visualization.PieChart(document.getElementById('piechart'));
            pieChart.draw(pieData, pieOptions);

            // ✅ Bar Chart Data
            var barData = google.visualization.arrayToDataTable([
                ['Town', 'Registered Dogs', 'Vaccinated Dogs'],
                <?php 
                for ($i = 0; $i < count($towns); $i++) {
                    echo "['" . addslashes($towns[$i]) . "', " . $dog_counts[$i] . ", " . $vaccinated_counts[$i] . "],";
                }
                ?>
            ]);

            var barOptions = {
                title: 'Dog Registration & Vaccination Per Town',
                chartArea: {width: '50%'},
                hAxis: {title: 'Number of Dogs', minValue: 0},
                vAxis: {title: 'Towns'},
                bars: 'horizontal'
            };

            var barChart = new google.visualization.BarChart(document.getElementById('barchart'));
            barChart.draw(barData, barOptions);
        }

        // ✅ Sorting Function
        function sortTable(columnIndex) {
            var table = document.getElementById("dogTable");
            var rows = Array.from(table.rows).slice(1);
            var sortedRows = rows.sort((rowA, rowB) => {
                var a = rowA.cells[columnIndex].innerText.trim();
                var b = rowB.cells[columnIndex].innerText.trim();
                return isNaN(a) || isNaN(b) ? a.localeCompare(b) : a - b;
            });
            sortedRows.forEach(row => table.appendChild(row));
        }
    </script>

    <style>
        th {
            user-select: none; /* Prevent text selection */
        }
        .sort-btn {
            background: none;
            border: none;
            color: white;
            font-size: inherit;
            font-weight: bold;
            cursor: pointer;
            user-select: none;
        }
        .sort-btn:focus {
            outline: none;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">Registered Canines by Town</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center" id="dogTable">
                <thead class="table-dark">
                    <tr>
                        <th><button onclick="sortTable(0)" class="sort-btn">Barangay 🔽</button></th>
                        <th><button onclick="sortTable(1)" class="sort-btn">Registered Dogs 🔽</button></th>
                        <th><button onclick="sortTable(2)" class="sort-btn">Vaccinated Dogs 🔽</button></th>
                        <th><button onclick="sortTable(3)" class="sort-btn">Vaccination Rate (%) 🔽</button></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    for ($i = 0; $i < count($towns); $i++) { 
                        $vaccinationRate = $dog_counts[$i] > 0 ? round(($vaccinated_counts[$i] / $dog_counts[$i]) * 100, 2) : 0;
                    ?>
                    <tr>
                        <td><a href="town_detail.php?town=<?php echo urlencode($towns[$i]); ?>"><?php echo htmlspecialchars($towns[$i]); ?></a></td>
                        <td><?php echo htmlspecialchars($dog_counts[$i]); ?></td>
                        <td><?php echo htmlspecialchars($vaccinated_counts[$i]); ?></td>
                        <td><?php echo $vaccinationRate . "%"; ?></td>
                    </tr>
                    <?php
                    } 
                    ?>
                </tbody>
            </table>
        </div>

        <!-- ✅ Pie Chart for Vaccination Status -->
        <div class="row mt-5">
            <div class="col-md-6">
                <div id="piechart" style="width: 100%; height: 400px;"></div>
            </div>

            <!-- ✅ Bar Chart for Town Trends -->
            <div class="col-md-6">
                <div id="barchart" style="width: 100%; height: 400px;"></div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="home.html" class="btn btn-primary">Back to Home</a>
        </div>
    </div>
</body>
</html> 
