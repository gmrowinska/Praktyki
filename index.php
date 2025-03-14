<?php
session_start();
// Generowanie tokenu CSRF, jeśli jeszcze nie został utworzony
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Codzienne Wyzwanie</title>
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Libre+Franklin:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <script src="script.js" defer></script>
</head>
<body>
  <header>
    <h1>Generator wyzwań codziennych</h1>
    <button class="menu-toggle">
      <div class="bar"></div>
      <div class="bar"></div>
      <div class="bar"></div>
    </button>
    <ul class="menu">
      <li><a href="onas" class="menuu">O nas</a></li>
      <li><a href="galeria" class="menuu">Galeria</a></li>
      <?php if (!isset($_SESSION['user'])): ?>
        <li><button id="log" onclick="showLoginForm()">Logowanie</button></li>
        <li><button id="rej" onclick="showRegisterForm()">Rejestracja</button></li>
      <?php else: ?>
        <li><a href="logout.php" id="wyl"><button id="wyl">Wyloguj się</button></a></li>
      <?php endif; ?>
    </ul>
  </header>
  
  <?php if(isset($_SESSION['user'])): ?>
  <div class="container">
    <div class="generator">
      <button id="losuj" onclick="losuj()">Wylosuj wyzwanie!</button>
      <br><br>
      <p id="wynik" class="animacja-wyzwania"></p>
      <button id="koniec" onclick="mark_finish()">Ukończone!</button>
    </div>
    <div class="historia">
      <h2>Historia wyzwań</h2>
      <div style="text-align: center;">
        <ol id="history-container"></ol>
      </div>
    </div>
  </div>
  <?php else: ?>
  <div class="blok">
    <h1>Zaloguj się, aby skorzystać z naszego generatora wyzwań codziennych.<br>Jeśli jeszcze nie masz konta, załóż je już teraz!</h1>
  </div>
  <?php endif; ?>
  
  <footer>
    <div class="footer-toggle" onclick="toggleFooter()">
      <div class="bar"></div>
      <div class="bar"></div>
      <div class="bar"></div>
    </div>
    <div id="footer-content">
      <div class="stopka-top">
        <!-- Treść stopki-top -->
      </div>
      <div class="stopka-bottom">
        <!-- Treść stopki-bottom -->
      </div>
    </div>
  </footer>
  
  <!-- Modal logowania -->
  <div id="loginModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal('loginModal')">&times;</span>
      <h2>Logowanie</h2>
      <form id="loginForm" action="login.php" method="post">
        <label for="loginEmail">Email:</label>
        <input type="email" id="loginEmail" name="email" required>
        <label for="loginPassword">Hasło:</label>
        <input type="password" id="loginPassword" name="password" required>
        <!-- Dodanie tokenu CSRF -->
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <button type="submit">Zaloguj się</button>
      </form>
    </div>
  </div>
  
  <!-- Modal rejestracji -->
  <div id="registerModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal('registerModal')">&times;</span>
      <h2>Rejestracja</h2>
      <form id="registerForm" action="register.php" method="post">
        <label for="firstName">Imię:</label>
        <input type="text" id="firstName" name="firstName" required>
        <label for="lastName">Nazwisko:</label>
        <input type="text" id="lastName" name="lastName" required>
        <label for="registerEmail">Email:</label>
        <input type="email" id="registerEmail" name="email" required>
        <label for="registerPassword">Hasło:</label>
        <input type="password" id="registerPassword" name="password" required>
        <label for="confirmPassword">Powtórz hasło:</label>
        <input type="password" id="confirmPassword" name="confirmPassword" required>
        <!-- Dodanie tokenu CSRF -->
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <button type="submit">Zarejestruj się</button>
      </form>
    </div>
  </div>
</body>
</html>
