<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$valid_users = [
    'user' => 'user123'
];

$login_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';


    if (empty($username) || empty($password)) {
        $login_error = "Username dan password harus diisi.";
    } 
    elseif (isset($valid_users[$username]) && $valid_users[$username] === $password) {
        
     
        $_SESSION['user_id'] = $username; 
        header("Location: index.php");
        exit();

    } else {
        $login_error = "Username atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Manajemen Kontak</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <h2>Login Sistem Manajemen Kontak</h2>
        <p>Silakan login untuk melanjutkan.</p>

        <?php if (!empty($login_error)): ?>
            <div class="error-message">
                <?php echo $login_error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password">
            </div>
            <div class="form-group">
                <button type="submit">Login</button>
            </div>
        </form>
        <div class="login-hint">
            <p>User: <code>user</code>, Pass: <code>user123</code></p>
        </div>
    </div>
</body>
</html>