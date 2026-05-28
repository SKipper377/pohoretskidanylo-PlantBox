<?php
require_once 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['regName']);
    $email    = trim($_POST['regEmail']);
    $password = $_POST['regPassword'];

    if ($name && $email && $password) {
        try {
            $stmt = $pdo->prepare("INSERT INTO users (Name, Email, Password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);
            header("Location: login.php?success=1");
            exit;
        } catch (PDOException $e) {
            $error = "This email is already registered.";
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
    <div class="sidebar-card registration-card">
      <h3
        data-en="BECOME A PART OF OUR COMMUNITY"
        data-pl="DOŁĄCZ DO NASZEJ SPOŁECZNOŚCI"
      >BECOME A PART OF OUR COMMUNITY</h3>

      <?php if (isset($error)): ?>
        <p class="form-alert form-alert--error"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <form class="registration-form" method="POST">
        <div class="form-group">
          <label for="regName">Name</label>
          <input type="text" id="regName" name="regName" placeholder="Enter your name" required />
        </div>
        <div class="form-group">
          <label for="regEmail">Email</label>
          <input type="email" id="regEmail" name="regEmail" placeholder="your@email.com" required />
        </div>
        <div class="form-group">
          <label for="regPassword">Password</label>
          <input type="password" id="regPassword" name="regPassword" placeholder="Enter password" required minlength="6" />
        </div>
        <button type="submit" class="btn btn-primary btn-block">Join Forum</button>
        <p class="auth-footer-link">Already have an account? <a href="login.php">Login here</a></p>
      </form>
    </div>
  </main>

  <script src="script.js?v=2.0"></script>
</body>
</html>
