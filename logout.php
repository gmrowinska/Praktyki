<?php
session_start();
// Usuwamy wszystkie dane sesji
session_destroy();
// Przekierowanie na stronę główną (możesz zmienić ścieżkę w zależności od struktury projektu)
header("Location: index.php");
exit();
?>
