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
require_once '../../../backend/services/documentService.php';

$documents = getDocuments($conn);

$isTutor = (isset($_SESSION['role']) && strcasecmp($_SESSION['role'], 'tutor') == 0);
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Έγγραφα Μαθήματος</title>

    <link rel="stylesheet" href="../../style/index.css">
    <link rel="stylesheet" href="../../style/sidebar.css">
    <link rel="stylesheet" href="../../style/footer.css">
    <link rel="stylesheet" href="../../style/documents.css?v=2">
</head>

<body>

<div id="sidebar-container">
    <?php include '../components/sidebar.html'; ?>
</div>

<div class="doc_main-layout">

    <main class="doc_content">
        <h1 id="top">Έγγραφα Μαθήματος</h1>
        <p class="doc_intro">
            Εδώ θα βρείτε τις διαφάνειες και το υλικό των διαλέξεων, που μπορείτε να κατεβάσετε.
        </p>
        <hr class="doc_divider">

        <?php if ($isTutor): ?>
            <button onclick="openAddModal()" class="add-doc-btn">
                + Προσθήκη Νέου Εγγράφου
            </button>
        <?php endif; ?>

        <div class="documents-grid">
            <?php if (empty($documents)): ?>
                <p>Δεν υπάρχουν διαθέσιμα έγγραφα.</p>
            <?php else: ?>
                <?php foreach ($documents as $doc): ?>
                    <section class="document-card">
                        <div class="doc-info">
                            <h3><?php echo htmlspecialchars($doc['title']); ?></h3>
                            <p><strong>Περιγραφή:</strong> <?php echo nl2br(htmlspecialchars($doc['description'])); ?></p>
                        </div>

                        <div class="doc-actions">
                            <a href="../../assets/docs/<?php echo htmlspecialchars($doc['filename']); ?>" class="download-link" download>
                                Download
                            </a>

                            <?php if ($isTutor): ?>
                                <form action="../../../backend/services/documentService.php" method="POST" style="display:inline;" onsubmit="return confirm('Είστε σίγουροι ότι θέλετε να διαγράψετε αυτό το αρχείο;');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $doc['id']; ?>">
                                    <button type="submit" class="btn-delete">Διαγραφή</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </section>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <div id="addDocModal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <span class="close-btn" onclick="closeAddModal()">&times;</span>
            <h2>Ανέβασμα Νέου Εγγράφου</h2>

            <form action="../../../backend/services/documentService.php" method="POST" enctype="multipart/form-data">

                <div class="modal-form-group">
                    <label for="doc-title">Τίτλος Εγγράφου:</label>
                    <input type="text" name="title" id="doc-title" required>
                </div>

                <div class="modal-form-group">
                    <label for="doc-desc">Περιγραφή:</label>
                    <textarea name="description" id="doc-desc" rows="4" required style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;"></textarea>
                </div>

                <div class="modal-form-group">
                    <label for="doc-file">Επιλογή Αρχείου (PDF, DOC, ZIP):</label>
                    <input type="file" name="file" id="doc-file" required>
                </div>

                <div style="text-align: right; margin-top: 20px;">
                    <button type="button" onclick="closeAddModal()" class="btn-cancel">Ακύρωση</button>
                    <button type="submit" class="btn-submit">Ανέβασμα</button>
                </div>
            </form>
        </div>
    </div>

    <div class="back-to-top-container">
        <a href="javascript:void(0)" onclick="scrollToTop()" class="floating-top-btn" title="Επιστροφή στην κορυφή">            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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