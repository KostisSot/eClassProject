<?php
if (isset($_GET['error']) && $_GET['error'] == 'unauthorized') {
    echo '<div class="alert-warning">
            Πρέπει να συνδεθείτε για να δείτε αυτή τη σελίδα!
          </div>';
}

if (isset($_GET['error']) && $_GET['error'] == 1) {
    echo '<div class="alert-danger">
             Λάθος email ή κωδικός πρόσβασης.
          </div>';
}

if (isset($_GET['success']) && $_GET['success'] == 'created') {
    echo '<div style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 10px; text-align: center; border: 1px solid #c3e6cb;">
            Ο λογαριασμός δημιουργήθηκε! <br> Μπορείτε να συνδεθείτε.
          </div>';
}
?>


<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Σύνδεση - Πλατφόρμα Μαθήματος</title>
    <link rel="stylesheet" href="/../4263partB/frontend/style/login.css">
</head>
<body class="login-page">

<div class="login-card">
    <div class="login-header">
        <img src="../../assets/img/logo.png" alt="Logo" class="login-logo">
        <h2>Καλωσήρθατε</h2>

        <?php if(isset($_GET['error'])): ?>
            <p class="error-message">Λάθος email ή κωδικός.</p>
        <?php else: ?>
            <p>Παρακαλώ συνδεθείτε για πρόσβαση στο υλικό</p>
        <?php endif; ?>
    </div>

    <form action="../../../backend/auth/authenticate.php" method="POST" class="login-form">
        <div class="input-group">
            <label for="email">E-mail (Username)</label>
            <input type="email" id="email" name="email" placeholder="π.χ. user@mail.com" required>
        </div>

        <div class="input-group">
            <label for="password">Κωδικός Πρόσβασης</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit" class="login-btn">Είσοδος</button>

        <div style="margin-top: 15px; text-align: center;">
            <p>
                Δεν έχετε λογαριασμό;
                <a href="register.php" style="color: #3498db;">Εγγραφή</a>
            </p>
        </div>
    </form>
</div>

<div class="login-footer-container">
    <?php include 'footer.html'; ?>
</div>

</body>
</html>