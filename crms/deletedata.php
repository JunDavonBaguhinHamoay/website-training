<?php
    require_once "config.php";
    $id = $_GET["id"];
    $query = "DELETE FROM tblreg WHERE id = '$id'";
    if (mysqli_query($conn, $query)) {
        header("location: crud.html");
    } else {
         echo "Something went wrong. Please try again later.";
    }
?>