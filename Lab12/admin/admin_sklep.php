<!DOCTYPE html>
<html lang="pl">

<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
?>
<head>
    <meta charset="UTF-8">
    <title>Zarządzanie kategoriami</title>
    <style>
        .main-title {
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
            width: 800px;
            margin-left:auto;
            margin-right:auto;
        }

        input, select {
            margin-bottom: 10px;
            padding: 5px;
        }

        input[type="submit"] {
            background-color: #697C9B;
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color:##697C9B;
        }

        .section-title {
            margin-top: 20px;
            color: #555;
        }

        .category-list {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .main-category {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .sub-category {
            margin-left: 20px;
            color: #777;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top:20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            width:max-content;
            background-color:white;
        }

        th {
            background-color: #697C9B;
            color: #fff;
        }

        td img {
            max-width: 100px;
            max-height: max-content;
            
        }
    </style>
</head>
<body>
    <h1 class="main-title">Zarządzanie kategoriami</h1>

    <?php
 

    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = '';
    $dbname = 'moja_strona';

    $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

    if ($conn->connect_error) {
        die("Błąd połączenia z bazą danych: " . $conn->connect_error);
    }
    class ZarzadzanieKategoriami {

        private $conn;
    
        public function __construct($conn) {
            $this->conn = $conn;
        }
    
        public function dodajKategorie($nazwa, $matka = 0) {
            $sql = "INSERT INTO kategorie (matka, nazwa) VALUES (?, ?) LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("is", $matka, $nazwa);
            $stmt->execute();
            $stmt->close();
        }
    
        public function usunKategorie($kategoriaId) {
            $sql = "DELETE FROM kategorie WHERE id = ? LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $kategoriaId);
            $stmt->execute();
            $stmt->close();
        }
    
        public function edytujKategorie($kategoriaId, $nowaNazwa) {
            $sql = "UPDATE kategorie SET nazwa = ? WHERE id = ? LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("si", $nowaNazwa, $kategoriaId);
            $stmt->execute();
            $stmt->close();
        }
    
        public function pokazKategorie() {
            $sql = "SELECT * FROM kategorie";
            $result = $this->conn->query($sql);
    
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    if ($row['matka'] == 0) {
                        echo "Kategoria główna: " . $row['nazwa'] . " (ID: " . $row['id'] . ")<br>";
                        $this->_pokazPodkategorie($row['id'], 1);
                    }
                }
            } else {
                echo "Brak kategorii w bazie danych.";
            }
        }
    
        private function _pokazPodkategorie($matkaId, $indent) {
            $sql = "SELECT * FROM kategorie WHERE matka = $matkaId";
            $result = $this->conn->query($sql);
    
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo str_repeat("&nbsp;", $indent * 4) . "Podkategoria: " . $row['nazwa'] . " (ID: " . $row['id'] . ")<br>";
                    $this->_pokazPodkategorie($row['id'], $indent + 1);
                }
            }
        }
    }

    // Tworzymy obiekt ZarzadzanieKategoriami i wywołujemy metody
    $zarzadzanie = new ZarzadzanieKategoriami($conn);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['dodaj'])) {
            $nazwa = $_POST['nazwa'];
            $matka = $_POST['matka'];
            $zarzadzanie->dodajKategorie($nazwa, $matka);
        } elseif (isset($_POST['usun'])) {
            $kategoriaId = $_POST['usun_id'];
            $zarzadzanie->usunKategorie($kategoriaId);
        } elseif (isset($_POST['edytuj'])) {
            $kategoriaId = $_POST['edytuj_id'];
            $nowaNazwa = $_POST['nowa_nazwa'];
            $zarzadzanie->edytujKategorie($kategoriaId, $nowaNazwa);
        }
    }

   // Wyświetlamy formularz dodawania kategorii
   echo '<h2 class="section-title">Dodaj nową kategorię:</h2>';
   echo '<form method="post" action="" class="add-form">';
   echo 'Nazwa kategorii: <input type="text" name="nazwa" required><br>';
   echo 'ID kategorii nadrzędnej (opcjonalne): <input type="number" name="matka"><br>';
   echo '<input type="submit" name="dodaj" value="Dodaj kategorię">';
   echo '</form>';

   // Wyświetlamy formularz usuwania kategorii
   echo '<h2 class="section-title">Usuń kategorię:</h2>';
   echo '<form method="post" action="" class="delete-form">';
   echo 'ID kategorii do usunięcia: <input type="number" name="usun_id" required><br>';
   echo '<input type="submit" name="usun" value="Usuń kategorię">';
   echo '</form>';

   // Wyświetlamy formularz edycji kategorii
   echo '<h2 class="section-title">Edytuj kategorię:</h2>';
   echo '<form method="post" action="" class="edit-form">';
   echo 'ID kategorii do edycji: <input type="number" name="edytuj_id" required><br>';
   echo 'Nowa nazwa kategorii: <input type="text" name="nowa_nazwa" required><br>';
   echo '<input type="submit" name="edytuj" value="Edytuj kategorię">';
   echo '</form>';

   // Wyświetlamy listę kategorii
   echo '<h2 class="section-title">Lista kategorii:</h2>';
   echo '<div class="category-list">';
   $zarzadzanie->pokazKategorie();
   echo '</div>';
   ?>

</body>
</html>