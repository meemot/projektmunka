
<?php
session_start();

if (!isset($_SESSION['dolgozo_id']) || $_SESSION['jogkor'] != "a") {
    echo "Hozzáférés megtagadva. Csak adminisztrátorok számára.";
    exit;
}

require_once 'db.php'; // adatbázis kapcsolat

$action = $_POST["action"] ?? "";

switch ($action) {

    case "dolgozok":
        dolgozok_modul($conn);
        break;

    case "felhasznalok":
        felhasznalok_modul($conn);
        break;

    case "eszkozok":
        eszkozok_modul($conn);
        break;

    default:
        echo "Ismeretlen modul.";
}

function dolgozok_modul($conn) {

    // FELSŐ MŰVELETI SÁV
    echo "
    <div class='module_actions'>
        <button onclick=\"ujDolgozo()\">Új dolgozó</button>
        <input type='text' id='kereses' placeholder='Keresés...'>
        <button onclick=\"szures()\">Szűrés</button>
    </div>
    ";

    // TÁBLÁZAT
    $sql = "SELECT `dolgozo_nev`,`beosztas`,`email`,`telefon` FROM `dolgozok` ORDER BY `dolgozo_nev` ASC";
    $result = $conn->query($sql);

    

    echo "<table class='tabla'>
            <tr>
                <th>Név</th>
                <th>Beosztás</th>
                <th>Email</th>
                <th>Telefon</th>
            </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['dolgozo_nev']}</td>
                <td>{$row['beosztas']}</td>
                <td>{$row['email']}</td>
                <td>{$row['telefon']}</td>
              </tr>";
    }

    echo "</table>";
}

function felhasznalok_modul($conn) {

    // FELSŐ MŰVELETI SÁV
    echo "
    <div class='module_actions'>
        <button onclick=\"ujFelhasznalo()\">Új felhasználó</button>
        <input type='text' id='kereses' placeholder='Keresés...'>
        <button onclick=\"szures()\">Szűrés</button>
    </div>
    ";

    // TÁBLÁZAT, INNEN KELL FOLYTATNOM A FELHASZNÁLÓKRA AKTUALIZÁLÁST!!!
    $sql = "SELECT d.dolgozo_nev, d.beosztas, u.jogkor, u.usernev, u.torolve 
            FROM users u JOIN dolgozok d ON u.dolgozo_id = d.dolgozo_id";
    $result = $conn->query($sql);

    
    echo "<table class='tabla'>
            <tr>
                <th>Név</th>
                <th>Beosztás</th>
                <th>Hozzáférés</th>
                <th>Felhasználónév</th>
                <th>Törölve</th>
            </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['dolgozo_nev']}</td>
                <td>{$row['beosztas']}</td>
                <td>{$row['jogkor']}</td>
                <td>{$row['usernev']}</td>
                <td>{$row['torolve']}</td>
              </tr>";
    }

    echo "</table>";
}

function eszkozok_modul($conn) {

    // FELSŐ MŰVELETI SÁV
    echo "
    <div class='module_actions'>
        <button onclick=\"ujEszkozok()\">Új eszköz</button>
        <input type='text' id='kereses' placeholder='Keresés...'>
        <button onclick=\"szures()\">Szűrés</button>
    </div>
    ";

    // TÁBLÁZAT
    $sql = 
        "SELECT et.megnevezes, ek.kategoria, e.azonosito, e.meret, ea.allapot ,e.megjegyzes 
        FROM eszkozok e 
        JOIN eszkoz_allapot ea ON e.allapot = ea.allapot_id 
        JOIN eszkoz_kategoria ek ON e.ketegoria = ek.kategoria_id
        JOIN eszkoz_tipus et ON e.tipus = et.tipus_id
        WHERE e.allapot != 4;";
    $result = $conn->query($sql);

    
    echo "<table class='tabla'>
            <tr>
                <th>Megnevezés</th>
                <th>Kategória</th>
                <th>Azonosító</th>
                <th>Méret</th>
                <th>Állapot</th>
                <th>Megjegyzés</th>
            </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['megnevezes']}</td>
                <td>{$row['kategoria']}</td>
                <td>{$row['azonosito']}</td>
                <td>{$row['meret']}</td>
                <td>{$row['allapot']}</td>
                <td>{$row['megjegyzes']}</td>
              </tr>";
    }

    echo "</table>";
}

?>