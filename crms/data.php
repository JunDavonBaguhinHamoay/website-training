<?php
require_once "config.php";

// Modify the query to join the tblreg table with the towns table
$sql_query = "SELECT t.dTown, 
                     COUNT(*) AS dog_count, 
                     SUM(CASE WHEN r.dVaccinated = 'Yes' THEN 1 ELSE 0 END) AS vaccinated_count
              FROM tblreg r
              JOIN towns t ON r.dTownID = t.id
              GROUP BY t.dTown";

$data = [];

if ($result = $conn->query($sql_query)) {
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'town' => $row['dTown'],
            'dog_count' => $row['dog_count'],
            'vaccinated_count' => $row['vaccinated_count'],
        ];
    }
}

// Output data as JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
