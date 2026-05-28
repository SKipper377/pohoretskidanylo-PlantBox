<?php
require_once 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['loginEmail']);
    $password = $_POST['loginPassword'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE Email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['Password'])) {
        $_SESSION['user_email'] = $user['Email'];
        $_SESSION['user_name']  = $user['Name'];
        header('Location: forum.php');
        exit;
    }

    $error = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login — PlantBox</title>
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
        <ul class="nav-menu" id="navMenu">
          <li>
            <a href="index.html" class="nav-link" data-en="Home" data-pl="Strona Główna">Home</a>
          </li>
          <li>
            <a href="forum.php" class="nav-link" data-en="Forum" data-pl="Forum">Forum</a>
          </li>
          <li>
            <a href="about.html" class="nav-link" data-en="About Us" data-pl="O Nas">About Us</a>
          </li>
          <li>
            <a href="index.html#faq" class="nav-link" data-en="FAQ" data-pl="FAQ">FAQ</a>
          </li>
          <li>
            <a href="info.html" class="nav-link" data-en="Plant Guide" data-pl="Przewodnik Roślin">Plant Guide</a>
          </li>
          <li>
            <a
              href="https://mail.google.com/mail/?view=cm&fs=1&to=etiap22@gmail.com"
              target="_blank"
              rel="noopener noreferrer"
              class="btn btn-primary"
              data-en="Get in touch"
              data-pl="Skontaktuj się"
            >Get in touch</a>
          </li>
          <li class="language-switcher">
            <button class="lang-btn active" data-lang="en">EN</button>
            <span class="lang-separator">|</span>
            <button class="lang-btn" data-lang="pl">PL</button>
          </li>
        </ul>
        <button class="mobile-toggle" id="mobileToggle" aria-label="Toggle navigation">
          <span></span>
          <span></span>
          <span></span>
        </button>
      </div>
    </div>
  </nav>

  <main class="container auth-page-main">
    <div class="sidebar-card login-card">

      <h3 data-en="LOGIN" data-pl="LOGOWANIE">LOGIN</h3>

      <?php if (isset($_GET['success'])): ?>
        <p
          class="form-alert form-alert--success"
          data-en="Registration successful! Please log in."
          data-pl="Rejestracja zakończona! Zaloguj się."
        >Registration successful! Please log in.</p>
      <?php endif; ?>

      <?php if (isset($error)): ?>
        <p
          class="form-alert form-alert--error"
          data-en="Invalid email or password."
          data-pl="Nieprawidłowy adres e-mail lub hasło."
        >Invalid email or password.</p>
      <?php endif; ?>

      <form class="login-form" method="POST">
        <div class="form-group">
          <label for="loginEmail" data-en="Email" data-pl="Email">Email</label>
          <input
            type="email"
            id="loginEmail"
            name="loginEmail"
            placeholder="your@email.com"
            required
          />
        </div>
        <div class="form-group">
          <label
            for="loginPassword"
            data-en="Password"
            data-pl="Hasło"
          >Password</label>
          <input
            type="password"
            id="loginPassword"
            name="loginPassword"
            placeholder="Enter password"
            data-en="Enter password"
            data-pl="Wpisz hasło"
            required
          />
        </div>
        <button
          type="submit"
          class="btn btn-primary btn-block"
          data-en="Login"
          data-pl="Zaloguj"
        >Login</button>
        <p class="auth-footer-link">
          <span data-en="Don't have an account?" data-pl="Nie masz konta?">Don't have an account?</span>
          <a href="register.php" data-en="Register here" data-pl="Zarejestruj się">Register here</a>
        </p>
      </form>

    </div>
  </main>

  <script src="script.js?v=2.0"></script>
</body>
</html>
