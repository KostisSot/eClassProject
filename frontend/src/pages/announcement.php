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
require_once '../../../backend/services/announcementService.php';

$announcements_list = getAnnouncements($conn);

$isTutor = (isset($_SESSION['role']) && strcasecmp($_SESSION['role'], 'tutor') == 0);
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ανακοινώσεις</title>

    <link rel="stylesheet" href="../../style/index.css">
    <link rel="stylesheet" href="../../style/sidebar.css">
    <link rel="stylesheet" href="../../style/footer.css">
    <link rel="stylesheet" href="../../style/announcement.css?v=3">
</head>

<body>

<div id="sidebar-container">
    <?php include '../components/sidebar.html'; ?>
</div>

<div class="announcement_main-layout">

    <main class="announcement_content">
        <h1 id="top">Ανακοινώσεις</h1>
        <p class="announcement_intro">Ενημερώσεις και ειδήσεις για τη διεξαγωγή του μαθήματος.</p>
        <hr class="announcement_divider">

        <?php if ($isTutor): ?>
            <div class="tutor-actions">
                <button onclick="document.getElementById('add-form-container').style.display='block'" class="btn-add-new">
                    + Προσθήκη νέας ανακοίνωσης
                </button>
            </div>

            <div id="add-form-container" style="display:none; background:#f9f9f9; padding:20px; border:1px solid #ddd; margin-bottom:20px;">
                <h3>Νέα Ανακοίνωση</h3>
                <form action="../../../backend/services/announcementService.php" method="POST">
                    <input type="hidden" name="action" value="add">

                    <div style="margin-bottom:10px;">
                        <label>Θέμα:</label><br>
                        <input type="text" name="subject" required style="width:100%; padding:5px;">
                    </div>

                    <div style="margin-bottom:10px;">
                        <label>Κείμενο:</label><br>

                        <button type="button" onclick="insertLink('add-content')" class="insert-link-btn">
                            + Προσθήκη Συνδέσμου
                        </button>

                        <textarea name="content" id="add-content" rows="5" required class="add-content"></textarea>
                    </div>

                    <button type="submit" class="btn-add-new">Δημοσίευση</button>
                    <button type="button" onclick="document.getElementById('add-form-container').style.display='none'" class="btn-cancel">Ακύρωση</button>
                </form>
            </div>
        <?php endif; ?>

        <div class="news-feed">
            <?php if (empty($announcements_list)): ?>
                <p>Δεν υπάρχουν ανακοινώσεις.</p>
            <?php else: ?>

                <?php foreach ($announcements_list as $item):
                    $time = strtotime($item['date']);
                    $day = date('d', $time);
                    $monthYear = date('m/Y', $time);
                    ?>
                    <article class="news-item">
                        <div class="news-date">
                            <span class="day"><?php echo $day; ?></span>
                            <span class="month-year"><?php echo $monthYear; ?></span>
                        </div>
                        <div class="news-content">
                            <h3>
                                Θέμα: <?php echo htmlspecialchars($item['subject']); ?>

                                <?php if ($isTutor): ?>
                                    <span class="tutor-controls">
                                        <form action="../../../backend/services/announcementService.php" method="POST" style="display:inline;" onsubmit="return confirm('Είστε σίγουροι;');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                            <button type="submit" class="btn-delete" style="background:none; border:none; cursor:pointer;">[διαγραφή]</button>
                                        </form>

                                        <button onclick="openEditModal(
                                                '<?php echo $item['id']; ?>',
                                                '<?php echo htmlspecialchars($item['subject'], ENT_QUOTES); ?>',
                                                '<?php echo htmlspecialchars($item['content'], ENT_QUOTES); ?>'
                                                )" class="btn-edit" style="background:none; border:none; cursor:pointer;">[επεξεργασία]
                                        </button>
                                    </span>
                                <?php endif; ?>
                            </h3>
                            <p>
                                <?php
                                echo nl2br(strip_tags(html_entity_decode($item['content']), '<a><b><i><u><strong>'));
                                ?>
                            </p>
                        </div>
                    </article>
                <?php endforeach; ?>

            <?php endif; ?>
        </div>

        <div class="back-to-top-container">
            <a href="javascript:void(0)" onclick="scrollToTop()" class="floating-top-btn" title="Επιστροφή στην κορυφή">            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="19" x2="12" y2="5"></line>
                        <polyline points="5 12 12 5 19 12"></polyline>
                    </svg>
            </a>
        </div>
    </main>

    <div id="editModal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <span class="close-btn" onclick="closeEditModal()">&times;</span>
            <h2>Επεξεργασία Ανακοίνωσης</h2>

            <form action="../../../backend/services/announcementService.php" method="POST">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" id="edit-id">

                <div class="modal-form-group" style="margin-bottom: 15px;">
                    <label for="edit-subject" style="font-weight:bold;">Θέμα:</label>
                    <input type="text" name="subject" id="edit-subject" required style="width:100%; padding:8px;">
                </div>

                <div class="modal-form-group" style="margin-bottom: 15px;">
                    <label for="edit-content" style="font-weight:bold;">Κείμενο:</label>

                    <button type="button" onclick="insertLink('edit-content')" class="btn-add-new">
                        + Προσθήκη Συνδέσμου
                    </button>

                    <textarea name="content" id="edit-content" rows="6" required class="add-content"></textarea>
                </div>

                <div style="text-align: right;">
                    <button type="button" onclick="closeEditModal()" class="btn-cancel">Ακύρωση</button>
                    <button type="submit" class="btn-add-new">Αποθήκευση Αλλαγών</button>
                </div>
            </form>
        </div>
    </div>

    <?php include '../components/footer.html'; ?>
</div>

<script>
    const globalUserData = {
        fullName: "<?php echo htmlspecialchars($_SESSION['firstname'] . ' ' . $_SESSION['lastname']); ?>",
        role: "<?php echo htmlspecialchars($_SESSION['role']); ?>"
    };
</script>

<script src="../../../index.js"></script>

</body>
</html>