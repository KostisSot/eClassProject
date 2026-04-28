<?php
session_start();
/** @var mysqli $conn */

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!isset($_SESSION['user_id'])) {
    header("Location:../components/login.php?error=unauthorized");
    exit();
}
//Service
require_once '../../../backend/services/homeworkService.php';

$homeworkList = getHomework($conn);
$isTutor = (isset($_SESSION['role']) && strcasecmp($_SESSION['role'], 'tutor') == 0);
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Εργασίες</title>

    <link rel="stylesheet" href="../../style/homework.css?v=3">
    <link rel="stylesheet" href="../../style/index.css">
    <link rel="stylesheet" href="../../style/sidebar.css">
    <link rel="stylesheet" href="../../style/footer.css">
</head>

<body>

<div id="sidebar-container">
    <?php include '../components/sidebar.html'; ?>
</div>

<div class="homework_main-layout">

    <main class="homework_content">
        <h1 id="top">Εργασίες</h1>
        <p class="homework_intro">
            Εδώ θα βρείτε τις εργασίες του μαθήματος.
        </p>
        <hr class="homework_divider">

        <?php if ($isTutor): ?>
            <button onclick="openHomeworkModal()" class="btn-add-hw">
                + Προσθήκη Νέας Εργασίας
            </button>
        <?php endif; ?>

        <?php if (empty($homeworkList)): ?>
            <p>Δεν υπάρχουν αναρτημένες εργασίες.</p>
        <?php else: ?>

            <?php foreach ($homeworkList as $hw): ?>
                <article class="homework-item">
                    <h2>Εργασία <?php echo $hw['id']; ?></h2>

                    <section class="hw-section">
                        <h3>Στόχοι:</h3>
                        <p>Οι στόχοι της εργασίας είναι:</p>
                        <div style="padding-left: 20px;">
                            <ul>
                                <?php foreach (explode("\n", $hw['goals']) as $goal): ?>
                                    <li><?php echo htmlspecialchars($goal); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </section>

                    <section class="hw-section">
                        <h3>Εκφώνηση:</h3>
                        <p>Κατεβάστε την εκφώνηση της εργασίας από εδώ:
                            <a href="../../assets/homework/<?php echo htmlspecialchars($hw['filename']); ?>" class="hw-download" download>
                                Download
                            </a>
                        </p>
                    </section>

                    <section class="hw-section">
                        <h3>Παραδοτέα:</h3>
                        <div style="padding-left: 20px;">
                            <ul>
                                <?php foreach (explode("\n", $hw['deliverables']) as $deliverable): ?>
                                    <li><?php echo htmlspecialchars($deliverable); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </section>

                    <div class="hw-deadline">
                        <strong>Ημερομηνία παράδοσης:</strong> <?php echo date("d/m/Y", strtotime($hw['due_date'])); ?>
                    </div>

                    <?php if ($isTutor): ?>
                        <form action="../../../backend/services/homeworkService.php" method="POST" onsubmit="return confirm('Είστε σίγουροι για τη διαγραφή;');" style="text-align: right;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $hw['id']; ?>">
                            <button type="submit" class="btn-delete">[Διαγραφή]</button>
                        </form>
                    <?php endif; ?>

                </article>
            <?php endforeach; ?>

        <?php endif; ?>

    </main>

    <div id="addHomeworkModal" class="modal-overlay">
        <div class="modal-content">
            <span class="close-btn" onclick="closeHomeworkModal()">&times;</span>
            <h2 style="margin-top:0; color:#2c3e50;">Προσθήκη Νέας Εργασίας</h2>

            <form action="../../../backend/services/homeworkService.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="add">

                <div class="form-group">
                    <label class="form-label">Στόχοι (ένας ανά γραμμή):</label>
                    <label>
                        <textarea name="goals" rows="4" class="form-control" required></textarea>
                    </label>
                </div>

                <div class="form-group">
                    <label class="form-label">Αρχείο Εκφώνησης:</label>
                    <input type="file" name="file" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Παραδοτέα (ένα ανά γραμμή):</label>
                    <label>
                        <textarea name="deliverables" rows="3" class="form-control" required></textarea>
                    </label>
                </div>

                <div class="form-group">
                    <label class="form-label">Ημερομηνία Παράδοσης:</label>
                    <label>
                        <input type="date" name="due_date" class="form-control" required>
                    </label>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeHomeworkModal()">Ακύρωση</button>
                    <button type="submit" class="btn-submit">Ανάρτηση</button>
                </div>
            </form>
        </div>
    </div>

    <div class="back-to-top-container">
        <a href="javascript:void(0)" onclick="scrollToTop()" class="floating-top-btn" title="Επιστροφή στην κορυφή">            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="19" x2="12" y2="5"></line>
                <polyline points="5 12 12 5 19 12"></polyline>
            </svg>
        </a>
    </div>
    <?php include '../components/footer.html'; ?>

</div>

<script>
    const globalUserData = {
        fullName: "<?php echo htmlspecialchars($_SESSION['firstname'] . ' ' . $_SESSION['lastname']); ?>",
        role: "<?php echo htmlspecialchars($_SESSION['role']); ?>"
    };
</script>

<script src="../../../index.js?v=<?php echo time(); ?>"></script>
</body>
</html>