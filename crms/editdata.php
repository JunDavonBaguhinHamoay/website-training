<?php
include('config.php');

$PetName = $Breed = $Owner = $Vaccinated = $RegistrationDate = $TownID = "";
$isEdit = false; // Check if this is an edit action

// Fetch available towns for the select dropdown
$townQuery = "SELECT id, dTown FROM towns";
$townResult = $conn->query($townQuery);

// Check if editing
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    $isEdit = true;

    // Fetch existing record
    $stmt = $conn->prepare("SELECT * FROM tblreg WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $PetName = $row['dName'];
        $Breed = $row['dBreed'];
        $Owner = $row['dOwner'];
        $Vaccinated = $row['dVaccinated'];
        $RegistrationDate = $row['dRegistrationDate']; // Fetch the registration date for editing
        $TownID = $row['dTownID']; // Fetch the town ID for editing
    } else {
        echo "<script>alert('Record not found!'); window.location.href='table.php';</script>";
        exit();
    }
}

// Handle Form Submission (Insert or Update)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dName = $_POST['petname'];
    $dBreed = $_POST['breed'];
    $dOwner = $_POST['owner'];
    $vaccinated = $_POST['vaccinated'];
    $dTownID = $_POST['town']; // Get the town ID selected by the user
    $dRegistrationDate = date("Y-m-d"); // Get the current date for registration

    if ($isEdit) {
        // Update existing record
        $stmt = $conn->prepare("UPDATE tblreg SET dName = ?, dBreed = ?, dOwner = ?, dVaccinated = ?, dRegistrationDate = ?, dTownID = ? WHERE id = ?");
        $stmt->bind_param("ssssssi", $dName, $dBreed, $dOwner, $vaccinated, $dRegistrationDate, $dTownID, $id);
    } else {
        // Insert new record
        $stmt = $conn->prepare("INSERT INTO tblreg (dName, dBreed, dOwner, dVaccinated, dRegistrationDate, dTownID) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $dName, $dBreed, $dOwner, $vaccinated, $dRegistrationDate, $dTownID);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Operation successful!'); window.location.href='crud.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isEdit ? "Edit Pet" : "Register Pet"; ?></title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h2><?php echo $isEdit ? "Edit Pet Information" : "Register Your Pet"; ?></h2>
    <form action="" method="POST" class="mt-3">
        <div class="mb-3">
            <label for="petname" class="form-label">Pet Name:</label>
            <input type="text" id="petname" name="petname" class="form-control" required placeholder="Enter pet's name" value="<?php echo htmlspecialchars($PetName); ?>">
        </div>
        <div class="mb-3">
            <label for="breed" class="form-label">Breed:</label>
            <input type="text" id="breed" name="breed" class="form-control" required placeholder="Enter breed" value="<?php echo htmlspecialchars($Breed); ?>">
        </div>
        <div class="mb-3">
            <label for="owner" class="form-label">Owner:</label>
            <input type="text" id="owner" name="owner" class="form-control" required placeholder="Enter owner's name" value="<?php echo htmlspecialchars($Owner); ?>">
        </div>

        <!-- Vaccination Status -->
        <div class="mb-3">
            <label class="form-label">Vaccination Status:</label><br>
            <input type="radio" id="vaccinated_yes" name="vaccinated" value="Yes" required <?php echo ($Vaccinated == "Yes") ? "checked" : ""; ?>>
            <label for="vaccinated_yes">Yes</label>
            <input type="radio" id="vaccinated_no" name="vaccinated" value="No" required <?php echo ($Vaccinated == "No") ? "checked" : ""; ?>>
            <label for="vaccinated_no">No</label>
        </div>

        <!-- Registration Date (Automatically set to the current date) -->
        <div class="mb-3">
            <label for="registrationDate" class="form-label">Registration Date:</label>
            <input type="date" id="registrationDate" name="registrationDate" class="form-control" required value="<?php echo $isEdit ? $RegistrationDate : date('Y-m-d'); ?>" readonly>
        </div>

        <!-- Town Selection -->
        <div class="mb-3">
            <label for="town" class="form-label">Select Town:</label>
            <select id="town" name="town" class="form-select" required>
                <option value="">Select Town</option>
                <?php while ($town = $townResult->fetch_assoc()) { ?>
                    <option value="<?php echo $town['id']; ?>" <?php echo ($town['id'] == $TownID) ? "selected" : ""; ?>>
                        <?php echo htmlspecialchars($town['dTown']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary"><?php echo $isEdit ? "Update" : "Register"; ?></button>
        <a href="table.php" class="btn btn-secondary">View Registered Pets</a>
    </form>
</div>
</body>
</html>
