<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

//Service
$servicePath = __DIR__ . '/../../../backend/services/controlPanelService.php';
if (file_exists($servicePath)) {
    require_once $servicePath;
} else {
    die("Σφάλμα: Το αρχείο controlPanelService.php δεν βρέθηκε στη διαδρομή: " . $servicePath);
}

/** @var mysqli $conn */

//Security
if (!isset($_SESSION['role']) || strcasecmp($_SESSION['role'], 'tutor') !== 0) {
    header("Location: dashboard.php");
    exit();
}

$users = getUsers($conn);
$notifMsg = "";
$notifType = "info";

if (isset($_GET['msg'])) {
    $notifType = "success";
    switch($_GET['msg']) {
        case 'added': $notifMsg = "Ο χρήστης προστέθηκε επιτυχώς!"; break;
        case 'deleted': $notifMsg = "Ο χρήστης διαγράφηκε οριστικά."; break;
    }
} elseif (isset($_GET['error'])) {
    $notifType = "error";
    switch($_GET['error']) {
        case 'duplicate': $notifMsg = "Το email χρησιμοποιείται ήδη!"; break;
        case 'db': $notifMsg = "Σφάλμα στη βάση δεδομένων."; break;
        case 'self': $notifMsg = "Δεν μπορείτε να διαγράψετε τον εαυτό σας!"; break;
    }
}
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Πίνακας Ελέγχου</title>
    <link rel="stylesheet" href="../../style/index.css">
    <link rel="stylesheet" href="../../style/sidebar.css">
    <link rel="stylesheet" href="../../style/footer.css">
    <link rel="stylesheet" href="../../style/homework.css">
    <link rel="stylesheet" href="../../style/controlPanel.css">
</head>
<body>

<div id="sidebar-container">
    <?php include '../components/sidebar.html'; ?>
</div>

<div class="homework_main-layout">
    <main class="homework_content">
        <h1>Πίνακας Ελέγχου Χρηστών</h1>
        <p class="homework_intro">Διαχείριση λογαριασμών χρηστών και δικαιωμάτων πρόσβασης.</p>
        <hr class="homework_divider">

        <button onclick="openUserModal()" class="btn-add-user">
            + Προσθήκη Χρήστη
        </button>

        <table class="admin-table">
            <thead>
            <tr>
                <th>Ονοματεπώνυμο</th>
                <th>Email</th>
                <th>Ρόλος</th>
                <th>Ενέργειες</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($u['firstname'] . " " . $u['lastname']); ?></td>
                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                        <td>
                                <span class="badge <?php echo (strtolower($u['role']) === 'tutor') ? 'badge-tutor' : 'badge-student'; ?>">
                                    <?php echo htmlspecialchars($u['role']); ?>
                                </span>
                        </td>
                        <td>
                            <form action="../../../backend/services/controlPanelService.php" method="POST" onsubmit="return confirm('Οριστική διαγραφή χρήστη;');">
                                <input type="hidden" name="action" value="delete_user">
                                <input type="hidden" name="id" value="<?php echo $u['id']; ?>">
                                <button type="submit" class="btn-danger">Διαγραφή</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4" style="text-align:center;">Δεν βρέθηκαν χρήστες.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </main>

    <div id="addUserModal" class="modal-overlay">
        <div class="modal-content">
            <span class="close-btn" onclick="closeUserModal()">&times;</span>
            <h2>Νέος Χρήστης</h2>
            <form action="../../../backend/services/controlPanelService.php" method="POST">
                <input type="hidden" name="action" value="add_user">

                <div class="form-group">
                    <label class="form-label">Όνομα:</label>
                    <input type="text" name="firstname" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Επώνυμο:</label>
                    <input type="text" name="lastname" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email:</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Κωδικός:</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Ρόλος:</label>
                    <select name="role" class="form-control">
                        <option value="Student">Student</option>
                        <option value="Tutor">Tutor</option>
                    </select>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeUserModal()">Ακύρωση</button>
                    <button type="submit" class="btn-submit">Δημιουργία</button>
                </div>
            </form>
        </div>
    </div>

    <?php include '../components/footer.html'; ?>
</div> <div id="notification-container"></div>
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