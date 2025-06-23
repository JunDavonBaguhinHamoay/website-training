<?php
// Include the database connection file
include 'config.php';

// Check if the user ID to delete is provided
if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    // Prepare the SQL statement to delete the user
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);

    // Execute the deletion query
    if ($stmt->execute()) {
        // Redirect back to the user list page
        header("Location: user_list.php");
        exit();
    } else {
        echo "Error deleting user: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    echo "No user ID specified.";
}

// Close the database connection
$conn->close();
?>
