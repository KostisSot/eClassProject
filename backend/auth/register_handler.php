<?php
session_start();

require_once '../config/db_connect.php';
/** @var mysqli $conn */

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: ../../frontend/src/components/register.php");
    exit();
}

$firstname = trim($_POST['firstname']);
$lastname = trim($_POST['lastname']);
$email = trim($_POST['email']);
$password = $_POST['password'];
$role = 'student';

//Έλεγχος αν υπάρχει ήδη το email
$checkQuery = "SELECT id FROM users WHERE email = ?";
$stmt = $conn->prepare($checkQuery);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $error_msg = "Το email χρησιμοποιείται ήδη";
    header("Location: ../../frontend/src/components/register.php?error=" . urlencode($error_msg));
    exit();
}
$stmt->close();

//Κρυπτογράφηση με Hashing
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

//Εισαγωγή
$insertQuery = "INSERT INTO users (firstname, lastname, email, password, role) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($insertQuery);
$stmt->bind_param("sssss", $firstname, $lastname, $email, $hashed_password, $role);

if ($stmt->execute()) {
    header("Location: ../../frontend/src/components/login.php?success=created");
} else {
    $error_msg = "Σφάλμα συστήματος. Δοκιμάστε ξανά.";
    header("Location: ../../frontend/src/components/register.php?error=" . urlencode($error_msg));
}

$stmt->close();
$conn->close();
