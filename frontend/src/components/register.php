<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Εγγραφή Νέου Χρήστη</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body style="display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #2c3e50; margin: 0; font-family: Arial, sans-serif;">

<div class="login-container" style="background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); width: 350px; text-align: center;">

    <div style="margin-bottom: 20px;">
        <img src="../../assets/img/logo.png" alt="JS Logo" style="width: 80px;">
    </div>

    <h2 style="color: #2c3e50; margin-bottom: 20px;">Δημιουργία Λογαριασμού</h2>

    <?php
    if (isset($_GET['error'])) {
        echo '<div style="color: red; margin-bottom: 10px; font-size: 0.9rem;">' . htmlspecialchars($_GET['error']) . '</div>';
    }
    ?>

    <form action="../../../backend/auth/register_handler.php" method="POST">

        <div style="margin-bottom: 15px; text-align: left;">
            <label style="display: block; margin-bottom: 5px; color: #34495e;">Όνομα</label>
            <input type="text" name="firstname" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 15px; text-align: left;">
            <label style="display: block; margin-bottom: 5px; color: #34495e;">Επώνυμο</label>
            <input type="text" name="lastname" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 15px; text-align: left;">
            <label style="display: block; margin-bottom: 5px; color: #34495e;">E-mail (Username)</label>
            <input type="email" name="email" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 20px; text-align: left;">
            <label style="display: block; margin-bottom: 5px; color: #34495e;">Κωδικός Πρόσβασης</label>
            <input type="password" name="password" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;">
        </div>

        <button type="submit" style="width: 100%; padding: 12px; background-color: #3498db; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 1rem; transition: background 0.3s;">
            Εγγραφή
        </button>
    </form>

    <div style="margin-top: 15px;">
        <p>Έχετε ήδη λογαριασμό; <a href="login.php" style="color: #3498db;">Σύνδεση</a></p>
    </div>
</div>

</body>
</html>