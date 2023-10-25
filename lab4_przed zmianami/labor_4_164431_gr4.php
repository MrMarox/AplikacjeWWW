<?php
include 'colors.php';
$nr_indeksu = '164431';
$nrGrupy = '4';

$name = $_POST['name'];
echo "<h3> Witaj $name </h3>";

echo 'Marceli Sobiecki ' .$nr_indeksu.' grupa ' .$nrGrupy. '<br/><br/>';

echo 'Zastosowanie metody include()<br/> </br>';

echo 'Kolory z include colors.php:</br> 
- <i style="color:green; font-size:25px;"> '.$color1.' </i></br>
- <i style="color:red; font-size:25px;"> '.$color2.' </i> </br>
- <i style="color:blue; font-size:25px;"> '.$color3.' </i> </br> </br>';


echo "Warunki</br></br>";
$a = 15;
$b = 10;

if ($a > $b){
    echo "$a wieksze od $b";
}else{
        echo "$b wieksze od $a";
}

$favcolor = "czerwony";

switch ($favcolor) {
  case "czerwony":
    echo "</br></br>ulubiony kolor czerwony!</br></br>";
    break;
  case "niebieski":
    echo "</br></br>ulubiony kolor niebieski!</br></br>";
    break;
  case "zielony":
    echo "</br></br>ulubiony kolor zieolony!</br></br>";
    break;
  default:
    echo "</br></br>twoj ulubiony kolor jest inny niz czerwoy niebieski zielony</br></br>";
}

echo "</br></br>Petla</br>";
echo "<h4>Wypisz od 1 do 5 funkcja while</h4>";

$i = 1;
while ($i <= 5){
    echo "$i <br/>";
    $i++;
}

echo("<br><button onclick=\"location.href='index.html'\">Powrot do index.html</button>");
?>