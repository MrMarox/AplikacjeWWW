<style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

.container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    margin: 20px;
}

.page-list-container {
    border: 2px solid #3498db;
    border-radius: 8px;
    padding: 20px;
    margin: 20px auto;
    width: 60%;
}

.page-list-container h1 {
    color: #3498db;
}
.info{
	font-size: 16px;
    margin:50px;
}

.table-row td {
    padding: 10px;
    text-align: center;
}

.table-row td:first-child {
    border-right: 2px solid #3498db;
}

.table-cell {
    font-weight: bold;
    padding: 8px;
}

</style>
<?php
session_start();
include('cfg.php');

function PageList()
{
    global $link;
    if (!isset($_SESSION['status']) || $_SESSION['status'] == 1) {
        $query = "SELECT * FROM page_list ORDER BY id ASC";
        $result = mysqli_query($link, $query);
        echo '<div class="page-list-container"><h1>Lista dostępnych podstron</h1><table>';
        if ($result) {
            while ($row = mysqli_fetch_array($result)) {
                echo '<tr class="table-row"><td class="table-cell">' . $row['id'] . '</td><td class="table-cell">' . $row['page_title'];
            }
            echo '</table></div><br>';
        } else {
            echo "Brak stron";
        }
    }
}
// Funkcja do pobierania treści strony na podstawie ID strony
function getPageContent($pageId)
{
    global $link; // użyj połączenia zdefiniowanego w cfg.php
	$id_clear = htmlspecialchars($pageId);

    // Zapytanie SQL do pobrania treści strony o określonym ID
    $sql = "SELECT * FROM page_list WHERE id = $id_clear AND status = 1";
    $result = mysqli_query($link, $sql);

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
            echo '<p class="info">Strona o podanym ID nie istnieje.</p>';
			PageList();
        }
    } else {
        // Obsługa błędu, np. wypisz komunikat błędu
        echo "Błąd: " . mysqli_error($link);
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