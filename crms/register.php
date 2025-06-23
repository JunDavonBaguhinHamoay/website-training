<?php
// Include the database configuration file to establish a connection
include('config.php');

// Check if the request method is POST (form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve data from the form
    $dName = $_POST['petname'];
    $dBreed = $_POST['breed'];
    $dFirstName = $_POST['firstName'];
    $dMiddleInitial = $_POST['middleInitial'];  // Middle name/initial (optional)
    $dLastName = $_POST['lastName'];

    // Full owner name can be combined for storage or further use
    // Check if the middle name is provided, and only include it if it's not empty
    if (!empty($dMiddleInitial)) {
        $dOwnerFullName = $dFirstName . ' ' . $dMiddleInitial . ' ' . $dLastName;
    } else {
        $dOwnerFullName = $dFirstName . ' ' . $dLastName;
    }

    // Get the current date in 'YYYY-MM-DD' format
    $currentDate = date('Y-m-d');  // Current date (e.g., 2025-03-18)

    // Prepare the SQL query to insert the data into the database
    $stmt = $conn->prepare("INSERT INTO tblreg (dName, dBreed, dOwner, dRegistrationDate) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $dName, $dBreed, $dOwnerFullName, $currentDate); // Bind parameters to the query

    // Execute the query
    if ($stmt->execute()) {
        // If the insertion is successful, return a success message
        echo "Registration successful!";
    } else {
        // If there is an error, output the error
        echo "Error: " . $stmt->error;
    }

    // Close the prepared statement and the database connection
    $stmt->close();
    $conn->close();
} else {
    // If the request is not POST, return an error message
    echo "Invalid request method.";
}
?>
