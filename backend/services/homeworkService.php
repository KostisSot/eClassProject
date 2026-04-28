<?php
/** @var mysqli $conn */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/db_connect.php';

//Λήψη Εργασιών
function getHomework($conn) {
    $homeworks = [];
    $sql = "SELECT * FROM homework ORDER BY due_date ASC"; // Ταξινόμηση βάσει ημερομηνίας παράδοσης
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $homeworks[] = $row;
        }
    }
    return $homeworks;
}

//(Add & Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Έλεγχος Tutor
    if (!isset($_SESSION['role']) || strcasecmp($_SESSION['role'], 'tutor') !== 0) {
        die("Unauthorized Access");
    }

    $action = isset($_POST['action']) ? $_POST['action'] : 'add';

    // --- ΔΙΑΓΡΑΦΗ ---
    if ($action === 'delete') {
        $id = intval($_POST['id']);

        // Βρίσκουμε το αρχείο για να το σβήσουμε
        $stmt = $conn->prepare("SELECT filename FROM homework WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($filename);
        $stmt->fetch();
        $stmt->close();

        if ($filename) {
            $filePath = __DIR__ . '/../../frontend/assets/homework/' . $filename;
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $delStmt = $conn->prepare("DELETE FROM homework WHERE id = ?");
            $delStmt->bind_param("i", $id);
            $delStmt->execute();
            $delStmt->close();
        }
        header("Location: ../../frontend/src/pages/homework.php?msg=deleted");
        exit();
    }

    //ΠΡΟΣΘΗΚΗ ΝΕΑΣ ΕΡΓΑΣΙΑΣ
    elseif ($action === 'add') {
        $goals = htmlspecialchars($_POST['goals']);
        $deliverables = htmlspecialchars($_POST['deliverables']);
        $due_date = $_POST['due_date']; // YYYY-MM-DD

        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {

            // Διαδρομή: backend/services -> ... -> frontend/assets/homework/
            $targetDir = __DIR__ . '/../../frontend/assets/homework/';

            $originalName = basename($_FILES['file']['name']);
            $cleanName = preg_replace("/[^a-zA-Z0-9\._-]/", "", $originalName);
            $fileName = time() . '_' . $cleanName;
            $targetFilePath = $targetDir . $fileName;

            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
            $allowedTypes = ['pdf', 'doc', 'docx', 'zip', 'txt'];

            if (in_array($fileType, $allowedTypes)) {
                if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFilePath)) {

                    //ΕΙΣΑΓΩΓΗ ΣΤΟΝ ΠΙΝΑΚΑ HOMEWORK
                    $stmt = $conn->prepare("INSERT INTO homework (goals, deliverables, due_date, filename) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("ssss", $goals, $deliverables, $due_date, $fileName);

                    if ($stmt->execute()) {
                        $newHomeworkId = $conn->insert_id;

                        // ΑΥΤΟΜΑΤΗ ΕΙΣΑΓΩΓΗ ΣΤΟΝ ΠΙΝΑΚΑ ANNOUNCEMENTS

                        $annSubject = "Ανακοινώθηκε η εργασία " . $newHomeworkId ;

                        // Μορφοποίηση ημερομηνίας για να φαίνεται ωραία (π.χ. 12/05/2026)
                        $dateObj = new DateTime($due_date);
                        $formattedDate = $dateObj->format('d/m/Y');

                        $annContent = "Η ημερομηνία παράδοσης της εργασίας είναι " . $formattedDate;

                        // Η ημερομηνία της ανακοίνωσης είναι η τρέχουσα (NOW())
                        $stmtAnn = $conn->prepare("INSERT INTO announcements (subject, content, date) VALUES (?, ?, NOW())");
                        $stmtAnn->bind_param("ss", $annSubject, $annContent);
                        $stmtAnn->execute();
                        $stmtAnn->close();

                        header("Location: ../../frontend/src/pages/homework.php?msg=uploaded");
                    } else {
                        header("Location: ../../frontend/src/pages/homework.php?error=db");
                    }
                    $stmt->close();
                } else {
                    header("Location: ../../frontend/src/pages/homework.php?error=upload_failed");
                }
            } else {
                header("Location: ../../frontend/src/pages/homework.php?error=invalid_type");
            }
        } else {
            header("Location: ../../frontend/src/pages/homework.php?error=no_file");
        }
        exit();
    }
}
