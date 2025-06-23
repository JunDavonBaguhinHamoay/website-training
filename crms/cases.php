<?php
// Include the database configuration
include('config.php'); // Assuming config.php contains the database connection code

$sql = "SELECT * FROM dog_bite_cases";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dog Bite Case List</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">Dog Bite Cases</h2>
    
    <!-- Button to redirect to the form for adding new records -->
    <a href="add_case.php" class="btn btn-primary mb-4">Add New Case</a>
    <a href="home.html" class="btn btn-primary mb-4">Return</a>
    
    <?php if ($result->num_rows > 0): ?>
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Victim Name</th>
                    <th>Dog Breed</th>
                    <th>Date of Incident</th>
                    <th>Location</th>
                    <th>Severity</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['victim_name']); ?></td>
                        <td><?= htmlspecialchars($row['dog_breed']); ?></td>
                        <td><?= $row['date_of_incident']; ?></td>
                        <td><?= htmlspecialchars($row['location']); ?></td>
                        <?php
                            $severity = strtolower($row['severity']);
                            $bgColor = '';
                            $textColor = 'text-dark';

                            if ($severity === 'severe') {
                                $bgColor = 'bg-danger text-white';
                            } elseif ($severity === 'mild') {
                                $bgColor = 'bg-warning';
                            } else {
                                $bgColor = 'bg-light';
                            }
                        ?>
                        <td class="<?= $bgColor; ?>">
                            <?= htmlspecialchars($row['severity']); ?>
                        </td>
                        <td><?= htmlspecialchars($row['description']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No dog bite cases found.</div>
    <?php endif; ?>
</div>

</body>
</html>

<?php
$conn->close();
?>
