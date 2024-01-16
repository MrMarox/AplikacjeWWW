<?php

$servername = "localhost";
$DBusername = "root"; 
$DBpassword = ""; 
$database = "moja_strona"; 

// Tworzenie połączenia
$link = mysqli_connect($servername, $DBusername, $DBpassword, $database);

// Sprawdzenie połączenia
if (!$link) {
    echo '<b>przerwane połączenie </b>';
}
$username  = 'admin';
$password = 'admin';

?>