<?php

session_start();

class Admin {
    public function FormularzLogowania() {
        include('../cfg.php');

        if (isset($_POST['login']) && isset($_POST['pass'])) {
            $login_attempt = $_POST['login'];
            $pass_attempt = $_POST['pass'];

            if ($login_attempt == $login && $pass_attempt == $pass) {
                $_SESSION['logged_in'] = true;
            } else {
                echo "Błąd logowania. Spróbuj ponownie.";
                $this->FormularzLogowania();
            }
        } else {
            echo "<form method='post' action='admin.php'>";
            echo "Login: <input type='text' name='login'><br>";
            echo "Hasło: <input type='password' name='password'><br>";
            echo "<input type='submit' value='Zaloguj'>";
            echo "</form>";
        }
    }

    public function ListaPodstron() {
        // $result = mysqli_query($conn, "SELECT id, tytul FROM page_list");

        echo "<table>";
        echo "<tr><th>ID</th><th>Tytuł</th><th>Akcje</th></tr>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>{$row['ID']}</td><td>{$row['page_title']}</td>";
            echo "<td><button>Edytuj</button><button>Usuń</button></td>";
            echo "</tr>";
        }

        echo "</table>";
    }

    public function EdytujPodstrone() {
        // $result = mysqli_query($conn, "SELECT * FROM page_list WHERE id = $id");

        $row = mysqli_fetch_assoc($result);

        echo "<form method='post' action=''>";
        echo "ID: <input type='ID' name='ID' value='{$row['ID']}'><br>";
        echo "Tytuł: <input type='page_title' name='page_title' value='{$row['page_title']}'><br>";
        echo "Treść: <textarea name='tresc'>{$row['page_content']}</textarea><br>";
        echo "Aktywna: <input type='checkbox' name='aktywna' ";
        echo $row['status'] ? "checked" : "";
        echo "><br>";
        echo "<input type='submit' value='Zapisz'>";
        echo "</form>";
    }

    public function DodajNowaPodstrone() {
        echo "<form method='post' action=''>";
        echo "Tytuł: <input type='text' name='tytul'><br>";
        echo "Treść: <textarea name='tresc'></textarea><br>";
        echo "Aktywna: <input type='checkbox' name='aktywna'><br>";
        echo "<input type='submit' value='Dodaj'>";
        echo "</form>";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tytul = $_POST['page_title'];
            $tresc = $_POST['page_content'];
            $aktywna = isset($_POST['status']) ? 1 : 0;
            $result = mysqli_query($conn, "INSERT INTO page_list (tytul, tresc, aktywna) VALUES ('$tytul', '$tresc', $aktywna)");
        }
    }

    public function UsunPodstrone($id) {
        // $result = mysqli_query($conn, "DELETE FROM podstrony WHERE id = $id LIMIT 1");

        if ($result) {
            echo "Podstrona została pomyślnie usunięta.";
        } else {
            echo "Błąd podczas usuwania podstrony.";
        }
    }
}

$admin = new Admin();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    $admin->FormularzLogowania();
} else {
    if (isset($_GET['action'])) {
        $action = $_GET['action'];
        
        switch ($action) {
            case 'lista':
                $admin->ListaPodstron();
                break;
            
            case 'dodaj':
                $admin->DodajNowaPodstrone();
                break;

            case 'edytuj':
                if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                    $id = $_GET['id'];
                    $admin->EdytujPodstrone($id);
                } else {
                    echo "Nieprawidłowe ID podstrony.";
                }
                break;

            default:
                echo "Nieznana akcja.";
        }
    } else {
        echo "Brak określonej akcji.";
    }
}

?>