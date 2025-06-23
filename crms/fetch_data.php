<?php
// Include your database configuration
require_once "config.php";

// SQL query to fetch data from both the registration and town tables
$sql_query = "SELECT r.*, t.dTown FROM tblreg r
              LEFT JOIN towns t ON r.dTownID = t.id";  // LEFT JOIN ensures we get all records, even if dTownID is null

// Execute the query
if ($result = $conn->query($sql_query)) {
    // Loop through the result and generate HTML rows dynamically
    while ($row = $result->fetch_assoc()) { 
        $Id = $row['id']; // Primary key ID
        $PetName = htmlspecialchars($row['dName'] ?? 'N/A');
        $Breed = htmlspecialchars($row['dBreed'] ?? 'N/A');
        $Owner = htmlspecialchars($row['dOwner'] ?? 'N/A');
        $Vacc = htmlspecialchars($row['dVaccinated'] ?? 'N/A');
        $RegistrationDate = htmlspecialchars($row['dRegistrationDate'] ?? 'N/A'); // Registration Date from the table
        $Town = htmlspecialchars($row['dTown'] ?? 'N/A'); // Town name from the joined table

        // Output each row of data as HTML
        echo "<tr>
                <td>{$PetName}</td>
                <td>{$Breed}</td>
                <td>{$Owner}</td>
                <td>" . (($Vacc == 'Yes') ? "✅ Yes" : "❌ No") . "</td>
                <td>{$RegistrationDate}</td> <!-- Display Registration Date -->
                <td>{$Town}</td>
                <td><a href='editdata.php?id={$Id}' class='btn btn-warning btn-sm'>Edit</a></td>
                <td><a href='deletedata.php?id={$Id}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this record?\");'>Delete</a></td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='8'>No records found</td></tr>";
}
?>
