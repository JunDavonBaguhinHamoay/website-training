<?php
// Include your database configuration
require_once "config.php";

// SQL query to fetch users with the role 'user'
$sql = "SELECT id, username, role FROM users WHERE role = 'user'";

// Execute the query
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Loop through and display each user as a table row
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['id']) . "</td>
                <td>" . htmlspecialchars($row['username']) . "</td>
                <td>" . htmlspecialchars($row['role']) . "</td>
                <td>
                    <button class='btn btn-danger' onclick='confirmDelete(" . $row['id'] . ")'>Delete</button>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='4'>No users found with the role 'user'.</td></tr>";
}

// Close the database connection
$conn->close();
?>
