
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

.login-container,
.page-list-container
{
  border: 1px solid #ccc;
  border-radius: 5px;
  padding: 20px;
  width: 48%;
  margin-bottom: 20px;
  margin-left: auto;
  margin-right:auto;
  margin-top:200px;
  width:420px;
}

.edit-page-container,
.add-page-container {
  border: 1px solid #ccc;
  border-radius: 5px;
  padding: 20px;
  width: 48%;
  margin-bottom: 20px;
  margin-left: auto;
  margin-right:auto;
  margin-top:200px;
  width:820px;
}

.form-group {
  margin-bottom: 10px;
}

.form-group label {
  display: block;
  margin-bottom: 5px;
}

.form-group input[type="text"],
.form-group input[type="password"],
.form-group textarea,
.form-group input[type="checkbox"] {
  width: 100%;
  padding: 5px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

.form-group input[type="submit"] {
  width: 100%;
  padding: 5px;
  background-color: #697C9B;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.error-message {
  color: red;
  margin-top: 10px;
  text-align:center;
}

.logged-in-container {
    text-align: center;
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 20px;
    margin: 20px auto;
    width: 60%;
}

.logout-link {
    text-decoration: none;
    color: inherit;
    padding: 5px 10px;
    background-color: gray;
    color: white;
    border-radius: 5px;
    display: inline-block;
}

.logout-link:hover {
    background-color: darkred;
}

.delete-button {
    display: inline-block;
    padding: 2px 10px;
    margin-right: 10px;
    border: 2px solid #B90901;
    border-radius: 5px;
    color: #B90901;
    text-decoration: none;
    transition: background-color 0.3s;
}
.edit-button {
    display: inline-block;
    padding: 2px 10px;
    margin-right: 10px;
    border: 2px solid #541EFF;
    border-radius: 5px;
    color: #541EFF;
    text-decoration: none;
    transition: background-color 0.3s;
}

.edit-button:hover {
    background-color: #541EFF;
    color: white;
}
.delete-button:hover{
    background-color: #B90901;
    color: white;
}
.table-row {
    background-color: #E0EDFF;
}

.table-row td {
    padding: 10px;
    text-align: center;
}

.table-row td:first-child {
    border-right: 2px solid #3498db;
}
</style>
<?php

session_start();
include('../cfg.php');

function LoginForm()
{
    $output = '
    <div class="login-container">
        <h1>Panel Administracyjny</h1>
        <form method="post" name="LoginForm" enctype="multipart/form-data" action="' . $_SERVER['REQUEST_URI'] . '">
            <div class="form-group">
                <label>Login: </label>
                <input type="text" name="username" />
            </div>
            <div class="form-group">
                <label>Hasło: </label>
                <input type="password" name="password" />
            </div>
            <div class="form-group">
                <input type="submit" name="login_submit" value="Zaloguj się" />
            </div>
        </form>
    </div>
    ';

    return $output;
}

function PageList()
{
    global $link;
    if (!isset($_SESSION['status']) || $_SESSION['status'] == 1) {
        $query = "SELECT * FROM page_list ORDER BY id ASC";
        $result = mysqli_query($link, $query);
        echo '<div class="page-list-container"><h1>Lista podstron</h1><center><table>';
        if ($result) {
            while ($row = mysqli_fetch_array($result)) {
                echo '<tr class="table-row">';
                echo '<td><b>' . $row['id'] . '</b></td>';
                echo '<td><b>' . $row['page_title'] . '</b></td>';
                echo '<td><a class="delete-button" href="admin.php?funkcja=usun&id=' . $row['id'] . '"><b>Usuń</b></a></td>';
                echo '<td><a class="edit-button" href="admin.php?funkcja=edytuj&id=' . $row['id'] . '"><b>Edytuj</b></a></td>';
                echo '</tr>';
            }
            echo '</table></center></div><br>';
        } else {
            echo "Brak stron";
        }
    }
    if (isset($_GET['funkcja']) && $_GET['funkcja'] == 'usun') {
        DeletePage();
    }
    if (isset($_GET['funkcja']) && $_GET['funkcja'] == 'edytuj') {
        EditPage();
    }
    AddNewPage();
}

function EditPage()
{
    global $link;
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    }
    $query = "SELECT * FROM page_list WHERE id='$id' ";
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);
    echo '
    <div class="edit-page-container">
        <h1>Edytuj podstronę</h1>
        <form method="post" name="EditForm" enctype="multipart/form-data" action="' . $_SERVER['REQUEST_URI'] . '">
            <div class="form-group">
                <label>Tytuł podstrony: </label>
                <input type="text" name="page_title" size="108" value=' . $row['page_title'] . ' />
            </div>
            <div class="form-group">
                <label>Treść podstrony: </label>
                <textarea rows=20 cols=100 name="page_content">' . $row['page_content'] . '</textarea>
            </div>
            <div class="form-group">
                <label>Status podstrony: </label>
                <input type="checkbox" name="status" checked />
            </div>
            <div class="form-group">
                <input type="submit" name="edit_submit" value="Edytuj" />
            </div>
        </form>
    </div>
    ';
    if (isset($_POST['edit_submit']) && isset($_GET['id'])) {
        $id = $_GET['id'];
        $title = $_POST['page_title'];
        $content = $_POST['page_content'];
        $status = isset($_POST['status']) ? 1 : 0;

        if (!empty($id)) {
            $query = "UPDATE page_list SET page_title = '$title', page_content = '$content', status = $status WHERE id = $id LIMIT 1";

            $result = mysqli_query($link, $query);

            if ($result) {
                echo "<script>window.location.href='admin.php';</script>";
                exit();
            } else {
                echo "<div class='error-message'>Błąd podczas edycji: " . mysqli_error($link) . "</div>";
            }
        }
    }
}

function AddNewPage()
{
    global $link;
    echo '
    <div class="add-page-container">
        <h1>Dodaj podstronę</h1>
        <form method="post" name="AddForm" enctype="multipart/form-data" action="' . $_SERVER['REQUEST_URI'] . '">
            <div class="form-group">
                <label>Tytuł podstrony: </label>
                <input type="text" name="page_title_add" size="108"/>
            </div>
            <div class="form-group">
                <label>Treść podstrony: </label>
                <textarea rows=20 cols=100 name="page_content_add" ></textarea>
            </div>
            <div class="form-group">
                <label>Status podstrony: </label>
                <input type="checkbox" name="status_add" checked />
            </div>
            <div class="form-group">
                <input type="submit" name="add_submit" value="Dodaj" />
            </div>
        </form>
    </div>
    ';
    if (isset($_POST['add_submit'])) {
        $title = $_POST['page_title_add'];
        $content = $_POST['page_content_add'];
        $status = isset($_POST['status_add']) ? 1 : 0;

        $query = "INSERT INTO page_list (page_title, page_content, status) VALUES ('$title', '$content', $status) LIMIT 1";
        $result = mysqli_query($link, $query);
        if ($result) {
            echo "<script>window.location.href='admin.php';</script>";
            exit();
        } else {
            echo "<div class='error-message'>Błąd podczas dodawania podstrony: " . mysqli_error($link) . "</div>";
        }
    }
}

function DeletePage()
{
    global $link;
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $query = "DELETE FROM page_list WHERE id = $id LIMIT 1";
        $result = mysqli_query($link, $query);

        if ($result) {
            echo "<script>window.location.href='admin.php';</script>";
            exit();
        } else {
            echo "<div class='error-message'>Błąd podczas usuwania podstrony: " . mysqli_error($link) . "</div>";
        }
    }
}

if (isset($_SESSION['status_logowania']) && $_SESSION['status_logowania'] == 1) {
    echo '<div class="logged-in-container">';
    echo '<h1>Jesteś zalogowany</h1>';
    PageList();
    echo '<h2><a class="logout-link" href="logout.php">Wyloguj się</a></h2>';
    include('admin_sklep.php');
    include('admin_sklep_produkty.php');
    echo '</div>';
} else {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        if($_POST['username'] == $username && $_POST['username'] == $password){
            $_SESSION['status_logowania'] = 1;
            header("Location: admin.php");
        }
        else{
            echo LoginForm();
            echo "<div class='error-message'>Błędne dane logowania</div>";
        }
    } else {
        echo LoginForm();
    }
}

?>
