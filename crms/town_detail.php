<?php 
require_once "config.php";

// Check if a town is passed via the URL
if (isset($_GET['town'])) {
    $town = htmlspecialchars($_GET['town']); // Get the town name from the URL

    // Query to get details of registered dogs and owners for the specific town
    $sql_query = "SELECT r.dName, r.dBreed, r.dOwner, r.dVaccinated, r.dStatus
                  FROM tblreg r
                  JOIN towns t ON r.dTownID = t.id
                  WHERE t.dTown = '$town'"; // Using town name in query

    if ($result = $conn->query($sql_query)) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Dogs in <?php echo $town; ?></title>
    <!-- Bootstrap CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS (including Popper for tooltips and modals) -->
    <script src="js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">Registered Dogs in <?php echo $town; ?></h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Dog Name</th>
                        <th scope="col">Dog Breed</th>
                        <th scope="col">Owner Name</th>
                        <th scope="col">Vaccination Status</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    while ($row = $result->fetch_assoc()) {
                        $PetName = htmlspecialchars($row['dName'] ?? 'Unknown');
                        $Breed = htmlspecialchars($row['dBreed'] ?? 'Unknown');
                        $Owner = htmlspecialchars($row['dOwner'] ?? 'Unknown');
                        $Vaccinated = htmlspecialchars($row['dVaccinated'] ?? 'No');
                        $Status = htmlspecialchars($row['dStatus'] ?? 'Inactive');
                    ?>
                    <tr>
                        <td><?php echo $PetName; ?></td>
                        <td><?php echo $Breed; ?></td>
                        <td><?php echo $Owner; ?></td>
                        <td><?php echo ($Vaccinated == 'Yes') ? "✅ Yes" : "❌ No"; ?></td>
                        <td><?php echo ($Status == 'Active') ? "<span class='text-success'>🟢 Active</span>" : "<span class='text-danger'>🔴 Inactive</span>"; ?></td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="text-center">
            <a href="town.php" class="btn btn-primary">Back to Towns List</a>
        </div>
    </div>
</body>
</html>
<?php
    }
}
?>
