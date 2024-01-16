<!DOCTYPE html>
<html lang="pl">
<?php
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = '';
    $dbname = 'moja_strona';

    $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

    if ($conn->connect_error) {
        die("Błąd połączenia z bazą danych: " . $conn->connect_error);
    }

    class ZarzadzanieProduktami {
        private $conn;
        
        public function __construct($conn) {
            $this->conn = $conn;
        }
    
        public function dodajProdukt($tytul, $opis, $dataUtworzenia, $dataModyfikacji, $dataWyg, $cenaNetto, $podatekVAT, $iloscWMagazynie, $statusDostepnosci, $gabaryt, $zdjecie, $kategoria) {
            $sql = "INSERT INTO produkty (tytuł, opis, data_utworzenia, data_modyfikacji, data_wygaśnięcia, cena_netto, podatek_vat, ilość_w_magazynie, status_dostępności, gabaryt, zdjęcie, kategoria) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sssssssssssi", $tytul, $opis, $dataUtworzenia, $dataModyfikacji, $dataWyg, $cenaNetto, $podatekVAT, $iloscWMagazynie, $statusDostepnosci, $gabaryt, $zdjecie, $kategoria);
            $stmt->execute();
            $stmt->close();
        }
    
        public function usunProdukt($produktId) {
            $sql = "DELETE FROM produkty WHERE id = ? LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $produktId);
            $stmt->execute();
            $stmt->close();
        }
    
        public function edytujProdukt($produktId, $nowyTytul, $nowyOpis, $dataUtworzenia, $dataModyfikacji, $dataWyg,$cenaNetto,$podatekVAT,$iloscWMagazynie,$statusDostepnosci,$gabaryt,$noweZdjecie,$kategoria) {
            $sql = "UPDATE produkty SET tytuł = ?, opis = ?, data_utworzenia = ?, data_modyfikacji = ?, data_wygaśnięcia = ?, cena_netto = ?, podatek_vat = ?, ilość_w_magazynie = ?, status_dostępności = ?, gabaryt = ?, zdjęcie = ?, kategoria = ? WHERE id = ? LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sssssssssssis", $nowyTytul, $nowyOpis, $dataUtworzenia, $dataModyfikacji, $dataWyg, $cenaNetto, $podatekVAT, $iloscWMagazynie, $statusDostepnosci, $gabaryt, $noweZdjecie, $kategoria, $produktId);
            $stmt->execute();
            $stmt->close();
        }
    
        public function pokazProdukty() {
            $sql = "SELECT * FROM produkty";
            $result = $this->conn->query($sql);
    
            if ($result->num_rows > 0) {
                echo "<table border='1'>";
                
                // Nagłówki kolumn
                echo "<tr>";
                $headerPrinted = false;
                while ($row = $result->fetch_assoc()) {
                    if (!$headerPrinted) {
                        foreach ($row as $key => $value) {
                            echo "<th>" . ucfirst($key) . "</th>";
                        }
                        echo "</tr><tr>";
                        $headerPrinted = true;
                    }
                    // Drukowanie danych wiersza
                    foreach ($row as $key => $value) {
                        echo "<td>";
                        if ($key === 'zdjęcie') {
                            echo "<img src='data:image/*;base64," . base64_encode($value) . "' alt='Obraz'>";
                        } else {
                            echo $value;
                        }
                        echo "</td>";
                    }
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "Brak produktów w bazie danych.";
            }
        }
    }
    $zarzadzanieProduktami = new ZarzadzanieProduktami($conn);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['dodajj'])) {
            // Pobranie danych z formularza dodawania produktu
            $tytul = $_POST['tytul'];
            $opis = $_POST['opis'];
            $dataUtworzenia = $_POST['data_utworzenia'];
            $dataModyfikacji = $_POST['data_modyfikacji'];
            $dataWyg = $_POST['data_wygasniecia'];
            $cenaNetto = $_POST['cena_netto'];
            $podatekVAT = $_POST['podatek_vat'];
            $iloscWMagazynie = $_POST['ilosc_w_magazynie'];
            $statusDostepnosci = $_POST['status_dostepnosci'];
            $gabaryt = $_POST['gabaryt'];
            $kategoria = $_POST['kategoria'];
        
            $zdjecie = file_get_contents($_FILES['zdjecie']['tmp_name']);

            // Wywołanie funkcji dodającej produkt
            $zarzadzanieProduktami->dodajProdukt($tytul, $opis, $dataUtworzenia, $dataModyfikacji, $dataWyg, $cenaNetto, $podatekVAT, $iloscWMagazynie, $statusDostepnosci, $gabaryt, $zdjecie, $kategoria);
        } elseif (isset($_POST['usunn'])) {
            // Usuwanie produktu
            $produktId = $_POST['usun_id'];
            $zarzadzanieProduktami->usunProdukt($produktId);
        } elseif (isset($_POST['edytujj'])) {
            $produktId = $_POST['edytuj_id'];
            $nowyTytul = $_POST['nowy_tytul'];
            $nowyOpis = $_POST['nowy_opis'];
            $nowaDataUtworzenia = $_POST['nowa_data_utworzenia'];
            $nowaDataModyfikacji = $_POST['nowa_data_modyfikacji'];
            $nowaDataWygasniecia = $_POST['nowa_data_wygasniecia'];
            $nowaCenaNetto = $_POST['nowa_cena_netto'];
            $nowyPodatekVAT = $_POST['nowy_podatek_vat'];
            $nowaIloscWMagazynie = $_POST['nowa_ilosc_w_magazynie'];
            $nowyStatusDostepnosci = $_POST['nowy_status_dostepnosci'];
            $nowyGabaryt = $_POST['nowy_gabaryt'];
            $noweZdjecie = file_get_contents($_FILES['nowe_zdjecie']['tmp_name']);
            $nowaKategoria = $_POST['nowa_kategoria'];

            $zarzadzanieProduktami->edytujProdukt($produktId,$nowyTytul,$nowyOpis,$nowaDataUtworzenia,
            $nowaDataModyfikacji,$nowaDataWygasniecia,$nowaCenaNetto,$nowyPodatekVAT,
            $nowaIloscWMagazynie,$nowyStatusDostepnosci,$nowyGabaryt,$noweZdjecie,$nowaKategoria);
        }
    }
?>
<head>
    <meta charset="UTF-8">
    <title>Zarządzanie produktami</title>
</head>
<body>
    <h1>Zarządzanie produktami</h1>

    <!-- Formularz dodawania produktu -->
    <h2>Dodaj nowy produkt:</h2>
    <form method="post" action="" enctype="multipart/form-data">
    Tytuł: <input type="text" name="tytul" required><br>
    Opis: <input type="text" name="opis" required><br>
    Data utworzenia: <input type="date" name="data_utworzenia" required><br>
    Data modyfikacji: <input type="date" name="data_modyfikacji" required><br>
    Data wygaśnięcia: <input type="date" name="data_wygasniecia" required><br>
    Cena netto: <input type="number" step="0.01" name="cena_netto" required><br>
    Podatek VAT: <input type="number" step="0.01" name="podatek_vat" required><br>
    Ilość w magazynie: <input type="number" name="ilosc_w_magazynie" required><br>
    Status dostępności: <input type="number" name="status_dostepnosci" required><br>
    Gabaryt: <input type="text" name="gabaryt" required><br>
    Zdjęcie: <input type="file" name="zdjecie" accept="image/*" required><br>
    Kategoria: <input type="number" name="kategoria" required><br>
    <input type="submit" name="dodajj" value="Dodaj produkt">
    </form>

    <!-- Formularz usuwania produktu -->
    <h2>Usuń produkt:</h2>
    <form method="post" action="">
        ID produktu do usunięcia: <input type="number" name="usun_id" required><br>
        <input type="submit" name="usunn" value="Usuń produkt">
    </form>

    <!-- Formularz edycji produktu -->
    <h2>Edytuj produkt:</h2>
    <form method="post" action="", enctype="multipart/form-data">
    ID produktu do edycji: <input type="number" name="edytuj_id" required><br>
    Nowy tytuł: <input type="text" name="nowy_tytul" required><br>
    Nowy opis: <input type="text" name="nowy_opis" required><br>
    Data utworzenia: <input type="date" name="nowa_data_utworzenia" required><br>
    Data modyfikacji: <input type="date" name="nowa_data_modyfikacji" required><br>
    Data wygaśnięcia: <input type="date" name="nowa_data_wygasniecia" required><br>
    Cena netto: <input type="number" step="0.01" name="nowa_cena_netto" required><br>
    Podatek VAT: <input type="number" step="0.01" name="nowy_podatek_vat" required><br>
    Ilość w magazynie: <input type="number" name="nowa_ilosc_w_magazynie" required><br>
    Status dostępności: <input type="number" name="nowy_status_dostepnosci" required><br>
    Gabaryt: <input type="text" name="nowy_gabaryt" required><br>
    Zdjęcie: <input type="file" name="nowe_zdjecie" accept="image/*" ><br>
    Nowa kategoria: <input type="number" name="nowa_kategoria" required><br>
    <input type="submit" name="edytujj" value="Edytuj produkt">
</form>

    <!-- Wyświetlanie listy produktów -->
    <h2>Lista produktów:</h2>
    <?php
        $zarzadzanieProduktami->pokazProdukty();
    ?>

</body>
</html>