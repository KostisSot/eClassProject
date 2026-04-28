<?php
session_start();

$notifMsg = "";
$notifType = "info";

if (isset($_GET['success']) && $_GET['success'] == 1) {
    $notifMsg = "Το μήνυμα στάλθηκε επιτυχώς!";
    $notifType = "success";
} elseif (isset($_GET['error'])) {
    $notifType = "error";
    $notifMsg = "Σφάλμα κατά την αποστολή.";
    if ($_GET['error'] == 'no_tutors') $notifMsg = "Δεν βρέθηκαν καθηγητές στη βάση.";
    if ($_GET['error'] == 'smtp_failed') $notifMsg = "Πρόβλημα σύνδεσης με το Gmail.";
}

// Headers για να μην κρατάει cache ο browser
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Έλεγχος Login
if (!isset($_SESSION['user_id'])) {
    header("Location:../components/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Επικοινωνία</title>

    <link rel="stylesheet" href="../../style/communication.css">
    <link rel="stylesheet" href="../../style/sidebar.css">
    <link rel="stylesheet" href="../../style/footer.css">
    <link rel="stylesheet" href="../../style/index.css">
</head>

<body>


<div id="sidebar-container">
    <?php include '../components/sidebar.html'; ?>
</div>

<div class="communication_main-layout">
    <main class="communication_content">
        <h1>Επικοινωνία</h1>
        <p class="communication_intro">
            Επικοινωνήστε με τον διδάσκων για οποιαδήποτε απορία:
        </p>

        <hr class="communication_divider">

        <section class="form-section">
            <h2>Αποστολή e-mail μέσω web φόρμας</h2>

            <form action="../../../backend/services/sendEmailService.php" method="post">
                <div class="form-group">
                    <label for="sender">Αποστολέας:</label>
                    <input type="email" id="sender" name="sender"
                           value="<?php echo htmlspecialchars(isset($_SESSION['email']) ? $_SESSION['email'] : ''); ?>" required >
                </div>

                <div class="form-group">
                    <label for="subject">Θέμα:</label>
                    <input type="text" id="subject" name="subject" required>
                </div>

                <div class="form-group">
                    <label for="message">Κείμενο:</label>
                    <textarea id="message" name="message" rows="5" required></textarea>
                </div>

                <button type="submit">Αποστολή</button>
            </form>
        </section>

        <section class="communication_intro">
            <br>
            <p>Ή εναλλακτικά:</p>
            <hr class="communication_divider">
        </section>

        <section class="email-direct">
            <h2>Αποστολή e-mail</h2>
            <p>Εναλλακτικά μπορείτε να αποστείλετε e-mail στην παρακάτω διεύθυνση:</p>
            <p class="email-link">
                <a href="mailto:tutor@csd.auth.test.gr">tutor@csd.auth.test.gr</a>
            </p>
        </section>
    </main>

    <?php include '../components/footer.html'; ?>
</div><div id="notification-container"></div>


<script>
    const globalUserData = {
        fullName: "<?php echo htmlspecialchars($_SESSION['firstname'] . ' ' . $_SESSION['lastname']); ?>",
        role: "<?php echo htmlspecialchars($_SESSION['role']); ?>"
    };
</script>
<script src="../../../index.js"></script>

<?php if ($notifMsg): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof showNotification === 'function') {
                showNotification("<?php echo $notifMsg; ?>", "<?php echo $notifType; ?>");
            }
            window.history.replaceState(null, null, window.location.pathname);
        });
    </script>
<?php endif; ?>

</body>
</html>