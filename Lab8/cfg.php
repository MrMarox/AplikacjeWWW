<?php

$servername = "localhost";
$username = "root"; 
$password = ""; 
$database = "moja_strona"; 

// Tworzenie połączenia
$conn = mysqli_connect($servername, $username, $password, $database);

// Sprawdzenie połączenia
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Ustawienie kodowania znaków na UTF-8
mysqli_set_charset($conn, "utf8");

?>