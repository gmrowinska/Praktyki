<?php
session_start();

// Weryfikacja tokenu CSRF
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo json_encode(["success" => false, "message" => "Nieprawidłowy token CSRF"]);
    exit;
}

// Ustawienia bazy danych
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Generator";

// Nawiązywanie połączenia z bazą
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Błąd bazy danych"]);
    exit;
}

// Pobieranie i zabezpieczanie danych przesłanych metodą POST
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = $_POST['password'];

// Sprawdzanie, czy użytkownik o podanym adresie email istnieje
$sql = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Weryfikacja hasła – porównanie hasła przesłanego z formularza z zahaszowanym hasłem w bazie
    if (password_verify($password, $row['password'])) {
        $_SESSION['user'] = $row['email'];
        echo json_encode(["success" => true, "message" => "Zalogowano pomyślnie"]);
    } else {
        echo json_encode(["success" => false, "message" => "Nieprawidłowe hasło"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Nie znaleziono użytkownika"]);
}
$conn->close();
?>
