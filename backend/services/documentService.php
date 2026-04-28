<?php
// backend/services/documentService.php
/** @var mysqli $conn */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/db_connect.php';

function getDocuments($conn) {
    $docs = [];
    $sql = "SELECT * FROM documents ORDER BY uploaded_at DESC";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $docs[] = $row;
        }
    }
    return $docs;
}

// ---------------------------------------------------------
// MAIN LOGIC (ADD & DELETE)
// ---------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Έλεγχος Tutor (Κοινός για όλα)
    if (!isset($_SESSION['role']) || strcasecmp($_SESSION['role'], 'tutor') !== 0) {
        die("Unauthorized Access");
    }

    // Ελέγχουμε ποια ενέργεια ζητήθηκε
    // Αν δεν υπάρχει action, θεωρούμε ότι είναι 'add' (για συμβατότητα με την παλιά φόρμα)
    $action = isset($_POST['action']) ? $_POST['action'] : 'add';

    // ==========================
    // ΔΙΑΓΡΑΦΗ (DELETE)
    // ==========================
    if ($action === 'delete') {
        $id = intval($_POST['id']);

        // Πρώτα βρίσκουμε το όνομα του αρχείου για να το σβήσουμε από τον δίσκο
        $stmt = $conn->prepare("SELECT filename FROM documents WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($filename);
        $stmt->fetch();
        $stmt->close();

        if ($filename) {
            // Διαδρομή αρχείου στον δίσκο
            $filePath = __DIR__ . '/../../frontend/assets/docs/' . $filename;

            // Σβήσιμο αρχείου (αν υπάρχει)
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Σβήσιμο από τη βάση
            $delStmt = $conn->prepare("DELETE FROM documents WHERE id = ?");
            $delStmt->bind_param("i", $id);

            if ($delStmt->execute()) {
                header("Location: ../../frontend/src/pages/documents.php?msg=deleted");
            } else {
                header("Location: ../../frontend/src/pages/documents.php?error=db_delete");
            }
            $delStmt->close();
        } else {
            header("Location: ../../frontend/src/pages/documents.php?error=not_found");
        }
        exit();
    }

    // ==========================
    // ΠΡΟΣΘΗΚΗ (ADD / UPLOAD)
    // ==========================
    elseif ($action === 'add') {

        $title = htmlspecialchars($_POST['title']);
        $description = htmlspecialchars($_POST['description']);

        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {

            $targetDir = __DIR__ . '/../../frontend/assets/docs/';
            $originalName = basename($_FILES['file']['name']);
            $cleanName = preg_replace("/[^a-zA-Z0-9\._-]/", "", $originalName);
            $fileName = time() . '_' . $cleanName;
            $targetFilePath = $targetDir . $fileName;

            // Path για το HTML
            $dbPath = '../../assets/docs/' . $fileName;

            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
            $allowedTypes = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'zip', 'rar', 'txt', 'png', 'jpg', 'jpeg'];

            if (in_array($fileType, $allowedTypes)) {
                if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFilePath)) {
                    $stmt = $conn->prepare("INSERT INTO documents (title, description, filename, filepath) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("ssss", $title, $description, $fileName, $dbPath);

                    if ($stmt->execute()) {
                        header("Location: ../../frontend/src/pages/documents.php?msg=uploaded");
                    } else {
                        header("Location: ../../frontend/src/pages/documents.php?error=db");
                    }
                    $stmt->close();
                } else {
                    header("Location: ../../frontend/src/pages/documents.php?error=upload_failed");
                }
            } else {
                header("Location: ../../frontend/src/pages/documents.php?error=invalid_type");
            }
        } else {
            header("Location: ../../frontend/src/pages/documents.php?error=no_file");
        }
        exit();
    }
}
