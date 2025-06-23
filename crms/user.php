<?php
session_start();
require 'config.php'; // Assuming you already have this file for DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);
    
    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match!'); window.location.href = 'register.html';</script>";
        exit();
    }
    
    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
    // Prepare and execute SQL statement
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'user')");
    $stmt->bind_param("ss", $username, $hashedPassword);
    
    if ($stmt->execute()) {
        echo "<script>alert('Registration successful!'); window.location.href = 'login.html';</script>";
    } else {
        echo "<script>alert('Registration failed. Please try again.'); window.location.href = 'register.html';</script>";
    }
    
    $stmt->close();
    $conn->close();
} else {
    header("Location: index.html");
    exit();
}
?>