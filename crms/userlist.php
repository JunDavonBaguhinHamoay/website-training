<?php
// Include the database connection file
include 'config.php';

// Fetch users with role 'user'
$sql = "SELECT id, username, role FROM users WHERE role = 'user'";

// Execute the query
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <!-- Include Bootstrap CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h3 class="mb-4">Users List</h3>

        <!-- Home Button -->
        <a href="home.html" class="btn btn-primary mb-3">Back to Home</a>

        <?php
        if ($result->num_rows > 0) {
            // Start of Bootstrap Table
            echo "<table class='table table-bordered table-striped'>";
            echo "<thead class='table-dark'>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                  </thead>";
            echo "<tbody>";

            // Loop through and display the users
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['id']) . "</td>
                        <td>" . htmlspecialchars($row['username']) . "</td>
                        <td>" . htmlspecialchars($row['role']) . "</td>
                        <td>
                            <form method='POST' action='delete_user.php' style='display:inline;'>
                                <input type='hidden' name='user_id' value='" . htmlspecialchars($row['id']) . "'>
                                <button type='submit' class='btn btn-danger' onclick='return confirmDelete();'>Delete</button>
                            </form>
                        </td>
                      </tr>";
            }

            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<div class='alert alert-warning'>No users found with the role 'user'.</div>";
        }

        // Close the database connection
        $conn->close();
        ?>
    </div>

    <!-- Include Bootstrap JS (optional but recommended for responsiveness) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Confirmation before deletion
        function confirmDelete() {
            return confirm('Are you sure you want to delete this user?');
        }
    </script>
</body>
</html>
