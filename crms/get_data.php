<?php
require_once "config.php";
header('Content-Type: application/json');

$sql = "SELECT t.id AS town_id, t.dTown, t.dLatitude, t.dLongitude, 
               COUNT(r.id) AS dog_count, 
               SUM(CASE WHEN r.dVaccinated = 'Yes' THEN 1 ELSE 0 END) AS vaccinated_count 
        FROM tblreg r 
        JOIN towns t ON r.dTownID = t.id 
        GROUP BY t.id, t.dTown, t.dLatitude, t.dLongitude";

$result = $conn->query($sql);
$data = [];

while ($row = $result->fetch_assoc()) {
    // Fetch individual dog details for this town
    $town_id = $row['town_id'];
    $dog_sql = "SELECT dName, dOwner, dVaccinated FROM tblreg WHERE dTownID = ?";
    $stmt = $conn->prepare($dog_sql);
    $stmt->bind_param("i", $town_id);
    $stmt->execute();
    $dog_result = $stmt->get_result();

    $dogs = [];
    while ($dog = $dog_result->fetch_assoc()) {
        $dogs[] = [
            "dName" => $dog["dName"],
            "dOwner" => $dog["dOwner"],
            "vaccinated" => ($dog["dVaccinated"] === 'Yes') ? true : false
        ];
    }

    // Add the dog list to the town data
    $row["dogs"] = $dogs;
    $data[] = $row;
}

// Send the data in JSON format
echo json_encode($data);
?>
