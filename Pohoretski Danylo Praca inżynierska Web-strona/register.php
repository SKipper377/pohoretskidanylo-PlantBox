<?php
require_once 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['regName']);
    $email    = trim($_POST['regEmail']);
    $password = $_POST['regPassword'];

    if ($name && $email && $password) {
        try {
            $stmt = $pdo->prepare(
                "INSERT INTO users (Name, Email, Password) VALUES (?, ?, ?)"
            );
            $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);
            header('Location: login.php?success=1');
            exit;
        } catch (PDOException $e) {
            $error = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register — PlantBox</title>
  <link rel="stylesheet" href="styles.css?v=2.0" />
  <link rel="icon" type="image/x-icon" href="assets/img/plant.ico" />
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap"
    rel="stylesheet"
  />
</head>
<body>

  <nav class="navbar">
    <div class="container">
      <div class="nav-wrapper">
        <a href="index.html" class="logo">PlantBox</a>
        <div class="language-switcher">
          <button class="lang-btn active" data-lang="en">EN</button>
          <span class="lang-separator">|</span>
          <button class="lang-btn" data-lang="pl">PL</button>
        </div>
      </div>
    </div>
  </nav>

  <main class="container auth-page-main">
    <div class="sidebar-card registration-card">

      <h3
        data-en="BECOME A PART OF OUR COMMUNITY"
        data-pl="DOŁĄCZ DO NASZEJ SPOŁECZNOŚCI"
      >BECOME A PART OF OUR COMMUNITY</h3>

      <?php if (isset($error)): ?>
        <p
          class="form-alert form-alert--error"
          data-en="This email is already registered."
          data-pl="Ten adres e-mail jest już zarejestrowany."
        >This email is already registered.</p>
      <?php endif; ?>

      <form class="registration-form" method="POST">
        <div class="form-group">
          <label for="regName" data-en="Name" data-pl="Imię">Name</label>
          <input
            type="text"
            id="regName"
            name="regName"
            placeholder="Enter your name"
            data-en="Enter your name"
            data-pl="Wpisz imię"
            required
          />
        </div>
        <div class="form-group">
          <label for="regEmail" data-en="Email" data-pl="Email">Email</label>
          <input
            type="email"
            id="regEmail"
            name="regEmail"
            placeholder="your@email.com"
            required
          />
        </div>
        <div class="form-group">
          <label for="regPassword" data-en="Password" data-pl="Hasło">Password</label>
          <input
            type="password"
            id="regPassword"
            name="regPassword"
            placeholder="Enter password"
            data-en="Enter password"
            data-pl="Wpisz hasło"
            required
            minlength="6"
          />
        </div>
        <button
          type="submit"
          class="btn btn-primary btn-block"
          data-en="Join Forum"
          data-pl="Dołącz do Forum"
        >Join Forum</button>
        <p class="auth-footer-link">
          <span data-en="Already have an account?" data-pl="Masz już konto?">Already have an account?</span>
          <a href="login.php" data-en="Login here" data-pl="Zaloguj się tutaj">Login here</a>
        </p>
      </form>

    </div>
  </main>

  <script src="script.js?v=2.0"></script>
</body>
</html>
