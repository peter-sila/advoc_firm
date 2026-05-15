<?php
require_once __DIR__ . '/php/admin_auth.php';

if (is_admin_logged_in()) {
    header('Location: admin.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = 'Please enter both username and password.';
    } elseif (login_admin($username, $password)) {
        header('Location: admin.php');
        exit;
    } else {
        $error = 'Invalid username or password. Please try again.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login - WE GOT YOU Advocates</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="css/global.css">
  <link rel="stylesheet" href="css/admin.css">
</head>
<body>
<div class="auth-page">
  <div class="auth-card">
    <div class="auth-brand">
      <h1>WE GOT YOU Admin</h1>
      <p>Securely sign in to manage blog posts, appointments, and inquiries.</p>
    </div>

    <?php if ($error): ?>
      <div class="admin-alert"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" class="auth-form">
      <label>Username</label>
      <input type="text" name="username" placeholder="Enter username" required>

      <label>Password</label>
      <input type="password" name="password" placeholder="Enter password" required>

      <button type="submit" class="button">Sign In</button>
    </form>
  </div>
</div>
</body>
</html>
