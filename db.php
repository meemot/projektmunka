<?php
    /* adatbázis kapcsolat létrehozása */
    $servername = "localhost"; //127.0.0.1
    $username = "root"; // most, WAMP alatt
    $password = ""; // most, WAMP alatt
    $dbname = "projektmunka";


    /* a kapcsolat létrehozása + ellenőrzése */
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
            die("Kapcsolódási hiba: " . $conn->connect_error);
        }    

    $conn->set_charset("utf8");
    ?>
