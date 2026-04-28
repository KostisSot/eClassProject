<?php
/** @var mysqli $conn */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$mailPath = __DIR__ . '/../libs/PHPMailer-master-2/src/';

require_once $mailPath . 'Exception.php';
require_once $mailPath . 'PHPMailer.php';
require_once $mailPath . 'SMTP.php';

require_once __DIR__ . '/../config/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $sender_email = filter_var($_POST['sender'], FILTER_SANITIZE_EMAIL);
    $sender_name  = $_SESSION['firstname'] . " " . $_SESSION['lastname'];
    $subject_user = htmlspecialchars($_POST['subject']);
    $message_user = htmlspecialchars($_POST['message']);

    // Εύρεση Tutors
    $tutors_emails = [];
    $stmt = $conn->prepare("SELECT email FROM users WHERE role = 'Tutor'");
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) { $tutors_emails[] = $row['email']; }
    $stmt->close();

    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'kostis.sotiriou@gmail.com';
        $mail->Password   = 'vssp fluc iowo xgzh';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom($mail->Username, 'VLE System');
        foreach ($tutors_emails as $email) {
            $mail->addAddress($email);
        }

        $mail->addReplyTo($sender_email, $sender_name);
        $mail->Subject = $subject_user;
        $mail->Body    = "Από: $sender_name ($sender_email)\n\n" . $message_user;

        if($mail->send()) {
            header("Location: ../../frontend/src/pages/communication.php?success=1");
            exit();
        }

    } catch (\Exception $e) {
        die("Σφάλμα: " . $mail->ErrorInfo);
    }
}
