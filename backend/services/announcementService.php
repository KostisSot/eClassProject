<?php
/** @var mysqli $conn */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/db_connect.php';

function getAnnouncements($conn) {
    $announcements = [];
    $sql = "SELECT * FROM announcements ORDER BY date DESC";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $announcements[] = $row;
        }
    }
    return $announcements;
}

// ---------------------------------------------------------
// LOGIC
// ---------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_SESSION['role']) || strcasecmp($_SESSION['role'], 'tutor') !== 0) {
        die("Unauthorized Access");
    }

    $action = isset($_POST['action']) ? $_POST['action'] : '';

    // Βρίσκουμε αυτόματα τη σελίδα από την οποία ήρθε το αίτημα
    $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../../frontend/src/pages/announcements.php';
    $base_url = strtok($referer, '?');

    // --- ΠΡΟΣΘΗΚΗ ---
    if ($action === 'add') {
        $subject = htmlspecialchars($_POST['subject']);
        $content = $_POST['content'];

        $stmt = $conn->prepare("INSERT INTO announcements (subject, content) VALUES (?, ?)");
        $stmt->bind_param("ss", $subject, $content);

        if ($stmt->execute()) {
            header("Location: " . $base_url . "?msg=added");
        } else {
            header("Location: " . $base_url . "?error=db");
        }
        $stmt->close();
    }

    // --- ΔΙΑΓΡΑΦΗ ---
    elseif ($action === 'delete') {
        $id = intval($_POST['id']);

        $stmt = $conn->prepare("DELETE FROM announcements WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            header("Location: " . $base_url . "?msg=deleted");
        } else {
            header("Location: " . $base_url . "?error=db");
        }
        $stmt->close();
    }

    // --- ΕΠΕΞΕΡΓΑΣΙΑ ---
    elseif ($action === 'edit') {
        $id = intval($_POST['id']);
        $subject = htmlspecialchars($_POST['subject']);
        $content = $_POST['content'];

        $stmt = $conn->prepare("UPDATE announcements SET subject = ?, content = ? WHERE id = ?");
        $stmt->bind_param("ssi", $subject, $content, $id);

        if ($stmt->execute()) {
            header("Location: " . $base_url . "?msg=updated");
        } else {
            header("Location: " . $base_url . "?error=db");
        }
        $stmt->close();
    }

    exit();
}
