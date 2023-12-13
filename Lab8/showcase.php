<?php
// Włącz plik cfg.php, aby uzyskać połączenie z bazą danych
include('cfg.php');

// Funkcja do pobierania treści strony na podstawie ID strony
function getPageContent($pageId) {
    global $conn; // użyj połączenia zdefiniowanego w cfg.php

    // Zapytanie SQL do pobrania treści strony o określonym ID
    $sql = "SELECT * FROM page_list WHERE id = $pageId AND status = 1";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Sprawdź, czy istnieje przynajmniej jeden rekord
        if (mysqli_num_rows($result) > 0) {
            // Pobierz dane z wyniku zapytania
            $row = mysqli_fetch_assoc($result);

            // Zwolnij zasoby zapytania
            mysqli_free_result($result);

            // Sprawdź, czy pole page_content istnieje w wyniku zapytania
            if (isset($row['page_content'])) {
                // Zwróć treść strony
                return $row['page_content'];
            } else {
                return "Pole page_content nie istnieje.";
            }
        } else {
            // Jeżeli nie ma pasującego rekordu, możesz obsłużyć to odpowiednio
            return "Strona o podanym ID nie istnieje.";
        }
    } else {
        // Obsługa błędu, np. wypisz komunikat błędu
        echo "Błąd: " . mysqli_error($conn);
        return false;
    }
}

// Pobierz ID strony z parametru w URL (np. showcase.php?pageId=1)
$pageId = isset($_GET['pageId']) ? $_GET['pageId'] : 1;

// Wywołaj funkcję do pobrania treści strony
$pageContent = getPageContent($pageId);

// Wyświetl treść strony
echo $pageContent;
?>