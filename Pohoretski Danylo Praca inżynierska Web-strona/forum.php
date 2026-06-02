<?php
require_once 'db.php';
session_start();

define('UPLOAD_DIR',    'assets/uploads/');
define('ALLOWED_TYPES', ['jpg', 'jpeg', 'png', 'gif']);

function handleImageUpload(array $file): ?string
{
    if (empty($file['name'])) {
        return null;
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, ALLOWED_TYPES, true)) {
        return null;
    }

    $path = UPLOAD_DIR . time() . '_' . basename($file['name']);

    return move_uploaded_file($file['tmp_name'], $path) ? $path : null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'], $_SESSION['user_email'])) {
    $text      = trim($_POST['message_text'] ?? '');
    $imagePath = handleImageUpload($_FILES['image'] ?? []);

    if ($text !== '' || $imagePath !== null) {
        $stmt = $pdo->prepare(
            "INSERT INTO forum (name, email, text, image_path) VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([
            $_SESSION['user_name'],
            $_SESSION['user_email'],
            $text ?: null,
            $imagePath,
        ]);
    }

    header('Location: forum.php#forum');
    exit;
}

$messages = $pdo
    ->query("SELECT * FROM forum ORDER BY created_at DESC")
    ->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta
    name="description"
    content="PlantBox Gardening Forum — share tips, ask questions, and connect with fellow gardeners."
  />
  <meta name="keywords" content="gardening forum, plant care community, gardening tips, plant questions" />
  <meta name="author" content="PlantBox" />
  <meta name="robots" content="index, follow" />
  <meta name="theme-color" content="#2f6b4f" />

  <title>Forum — PlantBox</title>

  <link rel="stylesheet" href="styles.css?v=2.0" />
  <link rel="icon" type="image/x-icon" href="assets/img/plant.ico" />
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap"
    rel="stylesheet"
  />
</head>
<body id="top">

     <nav class="navbar">
    <div class="container">
      <div class="nav-wrapper">
        <a href="index.html" class="logo">PlantBox</a>
        <ul class="nav-menu" id="navMenu">
          <li>
            <a href="index.html" class="nav-link active" data-en="Home" data-pl="Strona Główna">Home</a>
          </li>
          <li>
            <a href="forum.php" class="nav-link" data-en="Forum" data-pl="Forum">Forum</a>
          </li>
          <li>
            <a href="about.html" class="nav-link" data-en="About Us" data-pl="O Nas">About Us</a>
          </li>
          <li>
            <a href="#faq" class="nav-link" data-en="FAQ" data-pl="FAQ">FAQ</a>
          </li>
          <li>
            <a href="info.html" class="nav-link" data-en="Plant Guide" data-pl="Przewodnik Roślin">Plant Guide</a>
          </li>
          <li>
            <a
              href="https://mail.google.com/mail/?view=cm&fs=1&to=bratvateam.pl@gmail.com"
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

  <main id="main-content">

    <!-- ===== FORUM ===== -->
    <section class="page-header">
      <div class="container">
        <h1 data-en="PlantBox Gardening Forum" data-pl="Forum Ogrodnicze PlantBox">
          PlantBox Gardening Forum
        </h1>
        <p 
        data-en="Share your gardening tips, ask plant care questions, and connect with fellow plant lovers."
        data-pl="Dziel się poradami ogrodniczymi, zadawaj pytania o pielęgnację roślin i łącz się z innymi miłośnikami roślin.">
        Share your gardening tips, ask plant care questions, and connect with fellow plant lovers.
        </p>
      </div>
    </section>

        <div class="reply-form-container">
          <?php if (isset($_SESSION['user_email'])): ?>
            <form method="POST" enctype="multipart/form-data" class="topic-form">
              <div class="form-group">
                <label>
                  <span data-en="Logged in as:" data-pl="Zalogowany jako:">Logged in as:</span>
                  <strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong>
                </label>
                <textarea
                  name="message_text"
                  rows="3"
                  placeholder="Write your message..."
                  data-en="Write your message..."
                  data-pl="Napisz swoją wiadomość..."
                ></textarea>
              </div>
              <div class="form-group forum-file-group">
                <label for="file-upload" class="btn btn-secondary">
                  📷 <span data-en="Attach Photo" data-pl="Dołącz Zdjęcie">Attach Photo</span>
                </label>
                <input
                  id="file-upload"
                  type="file"
                  name="image"
                  accept="image/*"
                  class="file-input-hidden"
                  onchange="document.getElementById('file-name').textContent = this.files[0].name"
                />
                <span id="file-name" class="forum-file-name"></span>
              </div>
              <button type="submit" name="send_message" class="btn btn-primary mt-sm">
                <span data-en="Post Message" data-pl="Opublikuj Wiadomość">Post Message</span>
              </button>
              <a href="logout.php" class="ml-sm" data-en="Logout" data-pl="Wyloguj">Logout</a>
            </form>
          <?php else: ?>
            <div class="auth-notice">
              <p data-en="Want to join the discussion?" data-pl="Chcesz dołączyć do dyskusji?">
                Want to join the discussion?
              </p>
              <a
                href="login.php"
                class="btn btn-primary"
                data-en="Login"
                data-pl="Zaloguj"
              >Login</a>
              <a
                href="register.php"
                class="btn btn-secondary ml-sm"
                data-en="Create Account"
                data-pl="Utwórz Konto"
              >Create Account</a>
            </div>
          <?php endif; ?>
        </div>

        <div class="forum-topics">
          <?php if (empty($messages)): ?>
            <div class="forum-empty-state">
              <div class="empty-state-icon">🌱</div>
              <h2
                class="empty-state-title"
                data-en="Be the first to post!"
                data-pl="Bądź pierwszy!"
              >Be the first to post!</h2>
              <p
                class="empty-state-text"
                data-en="Our gardening community is waiting for you. Share your plants, ask questions, and connect with fellow gardeners."
                data-pl="Nasza społeczność ogrodnicza czeka na Ciebie. Podziel się swoimi roślinami, zadaj pytania i połącz się z innymi ogrodnikami."
              >
                Our gardening community is waiting for you. Share your plants, ask questions, and connect with fellow gardeners.
              </p>
            </div>
          <?php else: ?>
            <?php foreach ($messages as $message): ?>
              <div class="topic-card">
                <div class="topic-author-info">
                  <strong><?= htmlspecialchars($message['name']) ?></strong>
                  <small><?= htmlspecialchars($message['created_at']) ?></small>
                </div>
                <?php if ($message['text']): ?>
                  <p class="topic-text"><?= nl2br(htmlspecialchars($message['text'])) ?></p>
                <?php endif; ?>
                <?php if ($message['image_path']): ?>
                  <div class="topic-image">
                    <img
                      src="<?= htmlspecialchars($message['image_path']) ?>"
                      alt="<?= htmlspecialchars($message['name']) ?>"
                    />
                  </div>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

      </div>
    </section>

    <!-- ===== FAQ ===== -->
    <section class="faq-section" id="faq">
      <div class="container">
        <h2 class="section-title" data-en="FAQ" data-pl="FAQ">FAQ</h2>
        <div class="faq-list">

          <div class="faq-item">
            <button class="faq-question">
              <span data-en="How does PlantBox help with plant care?" data-pl="Jak PlantBox pomaga w pielęgnacji roślin?">
                How does PlantBox help with plant care?
              </span>
              <span class="faq-icon">+</span>
            </button>
            <div class="faq-answer">
              <p data-en="PlantBox provides personalized care schedules, reminders, and expert tips tailored to each plant in your garden." data-pl="PlantBox zapewnia spersonalizowane harmonogramy pielęgnacji, przypomnienia i porady dostosowane do każdej rośliny.">
                PlantBox provides personalized care schedules, reminders, and expert tips tailored to each plant in your garden.
              </p>
            </div>
          </div>

          <div class="faq-item">
            <button class="faq-question">
              <span data-en="Is PlantBox free to use?" data-pl="Czy PlantBox jest darmowy?">
                Is PlantBox free to use?
              </span>
              <span class="faq-icon">+</span>
            </button>
            <div class="faq-answer">
              <p data-en="Yes! PlantBox offers a free tier with essential features. Premium features are available with our Pro subscription." data-pl="Tak! PlantBox oferuje darmowy poziom z podstawowymi funkcjami. Funkcje premium są dostępne w ramach subskrypcji Pro.">
                Yes! PlantBox offers a free tier with essential features. Premium features are available with our Pro subscription.
              </p>
            </div>
          </div>

          <div class="faq-item">
            <button class="faq-question">
              <span data-en="Can I use PlantBox offline?" data-pl="Czy mogę używać PlantBox offline?">
                Can I use PlantBox offline?
              </span>
              <span class="faq-icon">+</span>
            </button>
            <div class="faq-answer">
              <p data-en="Many features work offline, including your saved plants and schedules. Sync when you're back online." data-pl="Wiele funkcji działa offline, w tym zapisane rośliny i harmonogramy. Synchronizuj po powrocie do sieci.">
                Many features work offline, including your saved plants and schedules. Sync when you're back online.
              </p>
            </div>
          </div>

          <div class="faq-item">
            <button class="faq-question">
              <span data-en="Does PlantBox work for indoor and outdoor plants?" data-pl="Czy PlantBox działa dla roślin domowych i ogrodowych?">
                Does PlantBox work for indoor and outdoor plants?
              </span>
              <span class="faq-icon">+</span>
            </button>
            <div class="faq-answer">
              <p data-en="Absolutely! PlantBox supports all types of plants — indoor, outdoor, vegetables, flowers, and more." data-pl="Absolutnie! PlantBox obsługuje wszystkie rodzaje roślin — domowe, ogrodowe, warzywa, kwiaty i więcej.">
                Absolutely! PlantBox supports all types of plants — indoor, outdoor, vegetables, flowers, and more.
              </p>
            </div>
          </div>

        </div>
      </div>
    </section>

    <!-- ===== CTA ===== -->
    <section class="cta-section">
      <div class="container">
        <div class="cta-content">
          <h2 data-en="Start Your Gardening Journey Today" data-pl="Zacznij Swoją Przygodę Ogrodniczą Już Dziś">
            Start Your Gardening Journey Today
          </h2>
          <p data-en="Join thousands of happy gardeners using PlantBox" data-pl="Dołącz do tysięcy zadowolonych ogrodników korzystających z PlantBox">
            Join thousands of happy gardeners using PlantBox
          </p>
          <a
            href="https://mail.google.com/mail/?view=cm&fs=1&to=bratvateam.pl@gmail.com"
            target="_blank"
            rel="noopener noreferrer"
            class="btn btn-large btn-light"
            data-en="Get in touch"
            data-pl="Skontaktuj się"
          >Get in touch</a>
        </div>
      </div>
    </section>

  </main>

  <!-- ===== FOOTER ===== -->
  <footer class="footer" id="contacts">
    <div class="container">
      <div class="footer-content">
        <div class="footer-section">
          <h3>PlantBox</h3>
          <p data-en="Your smart gardening companion" data-pl="Twój inteligentny towarzysz ogrodniczy">
            Your smart gardening companion
          </p>
        </div>
        <div class="footer-section">
          <h4 data-en="Product" data-pl="Produkt">Product</h4>
          <ul>
            <li><a href="#" data-en="Features" data-pl="Funkcje">Features</a></li>
            <li><a href="#" class="footer-pricing-btn" data-en="Pricing" data-pl="Cennik">Pricing</a></li>
            <li><a href="PlantBox.apk" download="PlantBox.apk" data-en="Download App" data-pl="Pobierz Aplikację">Download App</a></li>
          </ul>
        </div>
        <div class="footer-section">
          <h4 data-en="Community" data-pl="Społeczność">Community</h4>
          <ul>
            <li><a href="forum.php" data-en="Forum" data-pl="Forum">Forum</a></li>
            <li><a href="info.html" data-en="Plant Guide" data-pl="Przewodnik Roślin">Plant Guide</a></li>
          </ul>
        </div>
        <div class="footer-section">
          <h4 data-en="Contact" data-pl="Kontakt">Contact</h4>
          <ul>
            <li><a href="mailto:bratvateam.pl@gmail.com">bratvateam.pl@gmail.com</a></li>
          </ul>
        </div>
      </div>
      <div class="footer-bottom">
        <p data-en="© 2026 PlantBox. All rights reserved." data-pl="© 2026 PlantBox. Wszelkie prawa zastrzeżone.">
          © 2026 PlantBox. All rights reserved.
        </p>
      </div>
    </div>
  </footer>

  <script src="script.js?v=2.0"></script>
</body>
</html>
