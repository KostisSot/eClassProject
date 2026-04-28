<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/** @var mysqli $conn */
require_once __DIR__ . '/../config/db_connect.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//Μόνο ο Tutor έχει πρόσβαση
if (!isset($_SESSION['role']) || strcasecmp($_SESSION['role'], 'tutor') !== 0) {
    die("Πρόσβαση απαγορεύεται.");
}

/**
 * @return users τους εγγεγραμμένους χρήστες
 */
function getUsers($conn) {
    $users = [];
    $sql = "SELECT id, firstname, lastname, email, role FROM users ORDER BY lastname ASC";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }
    return $users;
}

//(POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    // ΠΡΟΣΘΗΚΗ ΧΡΗΣΤΗ
    if ($action === 'add_user') {
        $fname = htmlspecialchars($_POST['firstname']);
        $lname = htmlspecialchars($_POST['lastname']);
        $uname = htmlspecialchars($_POST['email']);
        $pass  = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role  = $_POST['role'];

        try {
            $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, email, password, role) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $fname, $lname, $uname, $pass, $role);

            if ($stmt->execute()) {
                header("Location: ../../frontend/src/pages/controlPanel.php?msg=added");
            }
            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            // Κωδικός 1062 = Duplicate entry
            if ($e->getCode() === 1062) {
                header("Location: ../../frontend/src/pages/controlPanel.php?error=duplicate");
            } else {
                header("Location: ../../frontend/src/pages/controlPanel.php?error=db");
            }
        }
        exit();
    }

    // ΔΙΑΓΡΑΦΗ ΧΡΗΣΤΗ
    if ($action === 'delete_user') {
        $id = intval($_POST['id']);
        if ($id === $_SESSION['user_id']) {
            header("Location: ../../frontend/src/pages/controlPanel.php?error=self");
            exit();
        }

        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        header("Location: ../../frontend/src/pages/controlPanel.php?msg=deleted");
    }
    exit();
}