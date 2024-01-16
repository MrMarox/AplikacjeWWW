<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f5f5f5;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 1200px;
    margin: 20px auto;
    display: flex;
    flex-direction: column;
}

.cart,
.products {
    width: 100%;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

h1 {
    color: #333;
}

h2 {
    color: #555;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

table, th, td {
    border: 1px solid #ddd;
}

th, td {
    padding: 10px;
    text-align: left;
}

th {
    background-color: #f2f2f2;
}

form {
    margin-top: 10px;
}

label {
    display: block;
    margin-bottom: 5px;
}

select, input {
    padding: 8px;
    margin-bottom: 10px;
}

button {
    padding: 8px 12px;
    background-color: #4caf50;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.total-amount {
    margin-top: 20px;
}

.place-order-form {
    margin-top: 20px;
}

.products img {
    max-width: 100px;
    max-height: 100px;
}

</style>
<?php
session_start();

$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'moja_strona';

$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Funkcja dodająca produkt do koszyka
function addToCart($conn, $productId, $quantity) {
    $sql = "SELECT * FROM produkty WHERE id = $productId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Sprawdź dostępność produktu w magazynie
        if ($row['ilość_w_magazynie'] >= $quantity && $quantity > 0) {
            // Oblicz cenę brutto
            $cena_brutto = $row['cena_netto'] + ($row['cena_netto'] * $row['podatek_vat']);

            $productData = [
                'id' => $row['id'],
                'tytuł' => $row['tytuł'],
                'quantity' => $quantity,
                'cena_netto' => $row['cena_netto'],
                'podatek_vat' => $row['podatek_vat'],
                'cena_brutto' => $cena_brutto,
            ];

            // Dodaj produkty do koszyka w sesji
            $_SESSION['cart'][$productId] = $productData;
        } else {
            if ($quantity <= 0) {
                echo "Nieprawidłowa ilość produktu.";
            } else {
                echo "Nie wystarczająca ilość produktu w magazynie.";
            }
        }
    }
}

// Funkcja usuwająca produkt z koszyka
function removeFromCart($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

// Wyświetl formularz usuwania produktów z koszyka
function showRemoveForm() {
    echo "<h2>Usuń z koszyka:</h2>";
    echo "<form method='post'>";
    echo "<table border='1'>";
    echo "<tr><th>ID Produktu</th><th>Tytuł</th><th>Usuń</th></tr>";

    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $productId => $productData) {
            echo "<tr>";
            echo "<td>" . $productData['id'] . "</td>";
            echo "<td>" . $productData['tytuł'] . "</td>";
            echo "<td><button type='submit' name='removeId' value='$productId'>Usuń</button></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='3'>Koszyk jest pusty</td></tr>";
    }

    echo "</table>";
    echo "</form>";
}

// Funkcja wyświetlająca koszyk
function showCartDetails($conn) {
    echo "<h2>Koszyk:</h2>";
    echo "<form method='post'>";
    echo "<table border='1'>";
    echo "<tr><th>ID Produktu</th><th>Tytuł</th><th>Opis</th><th>Cena netto</th><th>Podatek Vat</th><th>Cena brutto</th><th>Gabaryt</th><th>Zdjęcie</th><th>Kategoria</th><th>Ilość</th><th>Należność</th><th>Akcja</th></tr>";

    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $productId => $productData) {
            $productId = $productData['id'];

            $sql = "SELECT * FROM produkty WHERE id = $productId";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['tytuł'] . "</td>";
                    echo "<td>" . $row['opis'] . "</td>";
                    echo "<td>" . $row['cena_netto'] . "</td>";
                    echo "<td>" . $row['podatek_vat'] . "</td>";
                    echo "<td>" . $productData['cena_brutto'] . "</td>";
                    echo "<td>" . $row['gabaryt'] . "</td>";
                    echo "<td><img src='data:image/jpeg;base64," . base64_encode($row['zdjęcie']) . "' width='100' height='100'></td>";
                    echo "<td>" . $row['kategoria'] . "</td>";
                    echo "<td>" . $productData['quantity'] . "</td>";
                    echo "<td>" . (floatval($productData['cena_brutto']) * intval($productData['quantity'])) . "</td>";
                    echo "<td><button type='submit' name='removeId' value='$productId'>Usuń</button></td>";
                    echo "</tr>";
                }
            }
        }
    } else {
        echo "<tr><td colspan='12'>Koszyk jest pusty</td></tr>";
    }

    echo "</table>";
    echo "</form>";
}

// Obsługa dodawania produktu do koszyka
if (isset($_POST['addId']) && isset($_POST['quantity'])) {
    $productId = $_POST['addId'];
    $quantity = $_POST['quantity'];
    addToCart($conn, $productId, $quantity);
}

if (isset($_POST['removeId'])) {
    removeFromCart($_POST['removeId']);
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

function resetCart() {
    $_SESSION['cart'] = [];
}

// Obsługa złożenia zamówienia
if (isset($_POST['placeOrder'])) {
    resetCart();
    echo "<script>showPopup();</script>";
}
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Koszyk</title>
</head>

<body>
    <a href="./index.php"><h1>Sklep internetowy</h1></a>
    <!-- Wyświetlanie koszyka -->
<div class="container">
    <div class="cart">
    <?php
    showCartDetails($conn);
    ?>
    <!-- Formularz dodawania produktu do koszyka -->
    <form method="post" action="", class="add-to-cart-form">
        <label for="addId">Wybierz produkt:</label>
        <select name="addId" id="addId">
            <?php
            // Zapytanie SQL pobierające produkty
            $sql = "SELECT id, tytuł FROM produkty";

            // Wykonanie zapytania
            $result = $conn->query($sql);

            // Wyświetlenie opcji wyboru produktów
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['tytuł'] . "</option>";
                }
            }
            ?>
        </select>
        <label for="quantity">Ilość:</label>
        <input type="number" id="quantity" name="quantity" min="1">
        <input type="submit" value="Dodaj do koszyka">
    </form>
    <div class="total-amount">
    <?php
        $totalAmount = 0;

        // Obliczanie łącznej kwoty do zapłaty za wszystkie produkty w koszyku
        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $productId => $productData) {
                $totalAmount += $productData['cena_brutto'] * $productData['quantity'];
            }
        }

        echo "<h2>Łączna kwota do zapłaty: $totalAmount</h2>";
    ?>
    </div>
    <div>
    <!-- Przycisk "Złóż zamówienie" -->
    <form method="post" action="" class="place-order-form">
        <input type="submit" name="placeOrder" value="Złóż zamówienie">
    </form>
    </div>

    </div>
<div class="products">
<?php
function pokazProdukty($conn) {
    $query = "SELECT p.*, k.nazwa AS nazwa_kategorii FROM produkty p INNER JOIN kategorie k ON p.kategoria = k.id ORDER BY p.id ASC";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>Tytuł</th><th>Opis</th><th>Cena netto</th><th>Podatek VAT</th><th>Ilość w magazynie</th><th>Zdjęcie</th><th>Kategoria</th></tr>";
        
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['tytuł'] . "</td>";
            echo "<td>" . $row['opis'] . "</td>";
            echo "<td>" . $row['cena_netto'] . "</td>";
            echo "<td>" . $row['podatek_vat'] . "</td>";
            echo "<td>" . $row['ilość_w_magazynie'] . "</td>";
            echo "<td><img src='data:image/*;base64," . base64_encode($row['zdjęcie']) . "' alt='Obraz'></td>";
            echo "<td>" . $row['nazwa_kategorii'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Brak produktów w bazie danych.";
    }
}
pokazProdukty($conn);
?>
</div>

</div>

</body>

</html>
