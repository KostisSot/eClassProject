<?php
/** @var $conn mysqli */
session_start();

// ------------------------------------------------------------------
// ΡΥΘΜΙΣΕΙΣ ΒΑΣΗΣ ΔΕΔΟΜΕΝΩΝ
// ------------------------------------------------------------------
require_once '../config/db_connect.php';

// Έλεγχος σύνδεσης
if ($conn->connect_error) {
    die("Αποτυχία σύνδεσης στη βάση: " . $conn->connect_error);
}

// Έλεγχος αν στάλθηκαν δεδομένα από τη φόρμα
if (isset($_POST['email'], $_POST['password'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Προετοιμασία ερωτήματος
    $stmt = $conn->prepare("SELECT id, firstname, lastname, password, role FROM users WHERE email = ?");

    if ($stmt === false) {
        die("Σφάλμα στο query: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Αν βρέθηκε χρήστης με αυτό το email
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        //Hash verification)
        if (password_verify($password, $user['password'])) {

            // Αποθήκευση στο Session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['firstname'] = $user['firstname'];
            $_SESSION['lastname'] = $user['lastname'];
            $_SESSION['role'] = $user['role']; // π.χ. 'admin', 'student'

            // ----------------------------------------------------------
            // ΕΠΙΤΥΧΙΑ: ΑΝΑΚΑΤΕΥΘΥΝΣΗ ΣΤΗΝ ΑΡΧΙΚΗ
            // ----------------------------------------------------------
            header("Location: ../../frontend/src/pages/index.php");
            exit();
        }
    }

    // ----------------------------------------------------------
    // ΑΠΟΤΥΧΙΑ: ΑΝΑΚΑΤΕΥΘΥΝΣΗ ΠΙΣΩ ΣΤΟ LOGIN
    // ----------------------------------------------------------
    header("Location: ../../frontend/src/components/login.php?error=1");
    exit();
} else {
    // Αν κάποιος προσπαθήσει να ανοίξει το αρχείο απευθείας χωρίς POST
    header("Location: ../../frontend/src/components/login.php");
    exit();
}

$conn->close();
