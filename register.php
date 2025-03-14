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
$first_name = mysqli_real_escape_string($conn, $_POST['firstName']);
$last_name = mysqli_real_escape_string($conn, $_POST['lastName']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Sprawdzanie, czy użytkownik o podanym emailu już istnieje
$sql = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Email już istnieje"]);
} else {
    // Wstawianie nowego użytkownika do bazy
    $sql = "INSERT INTO users (first_name, last_name, email, password) VALUES ('$first_name', '$last_name', '$email', '$password')";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => true, "message" => "Rejestracja udana"]);
    } else {
        echo json_encode(["success" => false, "message" => "Błąd podczas rejestracji"]);
    }
}
$conn->close();
?>
