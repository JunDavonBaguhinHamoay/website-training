<?php
// Include the database configuration
include('config.php');

// Fetch dog breeds from tblreg
$dog_breeds = [];
$breed_result = $conn->query("SELECT DISTINCT dName FROM tblreg");
if ($breed_result && $breed_result->num_rows > 0) {
    while ($row = $breed_result->fetch_assoc()) {
        $dog_breeds[] = $row['dName'];
    }
}

// Fetch locations from towns table
$locations = [];
$towns_result = $conn->query("SELECT DISTINCT dTown FROM towns");
if ($towns_result && $towns_result->num_rows > 0) {
    while ($row = $towns_result->fetch_assoc()) {
        $locations[] = $row['dTown'];
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $victim_name = $_POST['victim_name'];
    $dog_breed = $_POST['dog_breed'];
    $date_of_incident = $_POST['date_of_incident'];
    $location = $_POST['location'];
    $severity = $_POST['severity'];
    $description = $_POST['description'];

    $sql = "INSERT INTO dog_bite_cases (victim_name, dog_breed, date_of_incident, location, severity, description) 
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $victim_name, $dog_breed, $date_of_incident, $location, $severity, $description);

    if ($stmt->execute()) {
        header("Location: cases.php");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Error adding record.</div>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Dog Bite Case</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">Add Dog Bite Case</h2>

    <form method="POST">
        <div class="form-group row">
            <div class="col-md-6">
                <label for="victim_name">Victim Name</label>
                <input type="text" id="victim_name" name="victim_name" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label for="dog_breed">Dog Breed</label>
                <select id="dog_breed" name="dog_breed" class="form-control" required>
                    <option value="" disabled selected>Select a dog breed</option>
                    <?php foreach ($dog_breeds as $breed): ?>
                        <option value="<?= htmlspecialchars($breed); ?>"><?= htmlspecialchars($breed); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-4">
                <label for="date_of_incident">Date of Incident</label>
                <input type="date" id="date_of_incident" name="date_of_incident" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label for="location">Location</label>
                <select id="location" name="location" class="form-control" required>
                    <option value="" disabled selected>Select a location</option>
                    <?php foreach ($locations as $town): ?>
                        <option value="<?= htmlspecialchars($town); ?>"><?= htmlspecialchars($town); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="severity">Severity</label>
                <select id="severity" name="severity" class="form-control" required>
                    <option value="" disabled selected>Select severity</option>
                    <option value="Mild">Mild</option>
                    <option value="Severe">Severe</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

</body>
</html>

<?php
$conn->close();
?>
