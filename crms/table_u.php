<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Canine Data</title>
    <!-- Bootstrap CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- JavaScript Bundle with Popper -->
    <script src="js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="js/jquery-3.7.1.min.js"></script>
    <!-- DataTables JS -->
    <script src="js/jquery.dataTables.min.js"></script>
    
    <style>
        /* Highlight row on hover */
        .table tbody tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }

        /* Highlight selected row */
        .selected-row {
            background-color: #d4edda !important; /* Light green */
        }
    </style>

    <script>
        $(document).ready(function() {
            // Initialize DataTables
            $('.table').DataTable({
                "paging": true,        
                "ordering": true,      
                "info": true           
            });

            // Row Click Highlighting
            $(".table tbody").on("click", "tr", function() {
                $(".table tbody tr").removeClass("selected-row"); // Remove previous selection
                $(this).addClass("selected-row"); // Add class to clicked row
            });
        });
    </script>
</head>

<body>
<section class="my-5">
    <div class="container">
        <h2 class="text-center mb-4">Registered Canines</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Dog Name</th>
                        <th scope="col">Dog Breed</th>
                        <th scope="col">Owner Name</th>
                        <th scope="col">Vaccination Status</th>
                        <th scope="col">Registration Date</th> <!-- Change Status to Registration Date -->
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    require_once "config.php";
                    // Fetch records from tblreg including dRegistrationDate
                    $sql_query = "SELECT * FROM tblreg";
                    if ($result = $conn->query($sql_query)) {
                        while ($row = $result->fetch_assoc()) { 
                            $PetName = htmlspecialchars($row['dName'] ?? 'Unknown');
                            $Breed = htmlspecialchars($row['dBreed'] ?? 'Unknown');
                            $Owner = htmlspecialchars($row['dOwner'] ?? 'Unknown');
                            $Vaccinated = htmlspecialchars($row['dVaccinated'] ?? 'No'); // Default to 'No'
                            $RegistrationDate = htmlspecialchars($row['dRegistrationDate'] ?? 'N/A'); // Get Registration Date
                    ?>
                    <tr>
                        <td><?php echo $PetName; ?></td>
                        <td><?php echo $Breed; ?></td>
                        <td><?php echo $Owner; ?></td>
                        <td><?php echo ($Vaccinated == 'Yes') ? "✅ Yes" : "❌ No"; ?></td>
                        <td><?php echo $RegistrationDate; ?></td> <!-- Display Registration Date -->
                    </tr>
                    <?php
                        } 
                    } 
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Button to go back to home -->
<div class="text-center my-4">
    <button class="btn btn-primary" onclick="window.location.href='user_d.html'">Go to Home</button>
</div>

</body>  
</html>
