
<?php
session_start();
require_once 'db.php'; // adatbázis kapcsolat

if (!isset($_SESSION['dolgozo_id'])) {
    echo "Nincs bejelentkezve.";
    exit;
}

$jog = $_SESSION['jogkor'];
$action = $_POST["action"] ?? "";

if ($jog === "a") {

    // ADMIN MODULOK
    switch ($action) {
        case "a_dolgozok":
            a_dolgozok_modul($conn);
            break;

        case "a_felhasznalok":
            a_felhasznalok_modul($conn);
            break;

        case "a_eszkozok":
            a_eszkozok_modul($conn);
            break;
        
        case "a_kiadas":
            a_kiadas_modul($conn);
            break;

        case "a_visszavetel":
            a_visszavetel_modul($conn);
            break;

        default:
            echo "Ismeretlen admin modul.";
    }

} elseif ($jog === "o") {

    // OPERÁTOR MODULOK
    switch ($action) {
        case "o_eszkozok":
            operator_eszkozok_modul($conn);
            break;

        case "o_dolgozok":
            operator_dolgozok_modul($conn);
            break;

        case "o_kiadas":
            operator_kiadas_modul($conn);
            break;

        case "o_visszavetel":
            operator_visszavetel_modul($conn);
            break;

        default:
            echo "Ismeretlen operátor modul.";
    }

} else {
    echo "Nincs jogosultság.";
}


function a_dolgozok_modul($conn) {

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

    

    echo "<table class='tabla table table-striped table-hover'>
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

function a_felhasznalok_modul($conn) {

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

    
    echo "<table class='tabla table table-striped table-hover'>
            <tr>
                <th>Név</th>
                <th>Beosztás</th>
                <th>Hozzáférés</th>
                <th>Felhasználónév</th>
                <th>Törölve</th>
            </tr>";

    while ($row = $result->fetch_assoc()) {

        // Jogkör átalakítása
        $jogkor = match($row['jogkor']) {
            "a" => "Admin",
            "o" => "Operátor",
            default => "Nincs hozzáférése",
        };

        // Törölve mező átalakítása
        $torolve = $row['torolve'] 
            ? "Inaktív  ({$row['torolve']})" 
            : "Aktív";


        echo "<tr>
                <td>{$row['dolgozo_nev']}</td>
                <td>{$row['beosztas']}</td>
                <td>{$jogkor}</td>
                <td>{$row['usernev']}</td>
                <td>{$torolve}</td>
              </tr>";
    }

    echo "</table>";
}

function a_eszkozok_modul($conn) {

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
        JOIN eszkoz_kategoria ek ON e.kategoria = ek.kategoria_id
        JOIN eszkoz_tipus et ON e.tipus = et.tipus_id
        WHERE e.allapot != 4;";
    $result = $conn->query($sql);

    
    echo "<table class='tabla table table-striped table-hover'>
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

function a_kiadas_modul($conn) {

    // FELSŐ MŰVELETI SÁV
    echo "
    <div class='module_actions'>
        <button onclick=\"ujKiadast()\">Új kiadás</button>
        <input type='text' id='kereses' placeholder='Keresés...'>
        <button onclick=\"szures()\">Szűrés</button>
    </div>
    ";

    // TÁBLÁZAT
    $sql = 
        "SELECT 
            k.kiadas_datum,
            d.dolgozo_nev AS ki_vette_fel,
            et.megnevezes AS kiadott_eszkoz,
            e.azonosito,
            ea.allapot AS kiadott_allapot,
            e.megjegyzes,
            d2.dolgozo_nev AS ki_adta_ki    
        FROM kiadas k
            JOIN dolgozok d
                ON k.ki_vette_fel = d.dolgozo_id
            JOIN eszkozok e
                ON k.eszkoz_id = e.eszkoz_id
            JOIN eszkoz_tipus et
                ON e.tipus = et.tipus_id
            JOIN eszkoz_allapot ea
                ON e.allapot = ea.allapot_id
            JOIN dolgozok d2
                ON k.ki_adta_ki = d2.dolgozo_id
            LEFT JOIN visszavet v
            	ON k.kiad_id = v.kiadas_id
        WHERE v.kiadas_id IS null
        ORDER BY kiadas_datum ASC;";
    $result = $conn->query($sql);

    
    echo "<table class='tabla table table-striped table-hover'>
            <tr>
                <th>Kiadás dátuma</th>
                <th>Ki vette fel</th>
                <th>Megnevezés</th>
                <th>Eszköz azonosító</th>
                <th>Állapot</th>
                <th>Megjegyzés</th>
                <th>Ki adta ki</th>
            </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['kiadas_datum']}</td>
                <td>{$row['ki_vette_fel']}</td>
                <td>{$row['kiadott_eszkoz']}</td>
                <td>{$row['azonosito']}</td>
                <td>{$row['kiadott_allapot']}</td>
                <td>{$row['megjegyzes']}</td>
                <td>{$row['ki_adta_ki']}</td>
              </tr>";
    }

    echo "</table>";
}

function a_visszavetel_modul($conn) {

    // FELSŐ MŰVELETI SÁV
    echo "
    <div class='module_actions'>
        <button onclick=\"ujVisszavetel()\">Új visszavétel</button>
        <input type='text' id='kereses' placeholder='Keresés...'>
        <button onclick=\"szures()\">Szűrés</button>
    </div>
    ";

    // TÁBLÁZAT
    $sql = 
        "SELECT
            v.visszavet_datum,
            et.megnevezes,
            e.azonosito,
            ea.allapot AS allapot_kiadaskor,
            ea1.allapot AS allapot_visszavet,
            d.dolgozo_nev AS ki_vette_vissza,
            v.megjegyzes
            FROM visszavet v
            JOIN eszkozok e
                ON v.eszkoz_id =  e.eszkoz_id
            JOIN eszkoz_tipus et
                ON e.tipus = et.tipus_id
            JOIN eszkoz_allapot ea
                ON v.allapot_kiadas = ea.allapot_id
            JOIN eszkoz_allapot ea1
                ON v.allapot_visszavet = ea1.allapot_id
            JOIN dolgozok d
            ON v.ki_vette_vissza = d.dolgozo_id
        ORDER BY visszavet_datum ASC;";
    $result = $conn->query($sql);

    
    echo "<table class='tabla table table-striped table-hover'>
            <tr>
                <th>Visszavétel dátuma</th>
                <th>Megnevezés</th>
                <th>Eszköz azonosító</th>
                <th>Állapot kiadáskor</th>
                <th>Állapot visszavételkor</th>
                <th>Visszavette</th>
                <th>Megjegyzés</th>
            </tr>";

    while ($row = $result->fetch_assoc()) {
        // a visszavétel állapota még nem jó adatot ír ki, mert az eszköz állapot azonosítójából veszi az adatot. JAVÍTANI KELL!!!
        echo "<tr>
                <td>{$row['visszavet_datum']}</td>
                <td>{$row['megnevezes']}</td>
                <td>{$row['azonosito']}</td>
                <td>{$row['allapot_kiadaskor']}</td>
                <td>{$row['allapot_visszavet']}</td>
                <td>{$row['ki_vette_vissza']}</td>
                <td>{$row['megjegyzes']}</td>
              </tr>";
    }

    echo "</table>";
}




function operator_eszkozok_modul($conn) {

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
        JOIN eszkoz_kategoria ek ON e.kategoria = ek.kategoria_id
        JOIN eszkoz_tipus et ON e.tipus = et.tipus_id
        WHERE e.allapot != 4;";
    $result = $conn->query($sql);

    
    echo "<table class='tabla table table-striped table-hover'>
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

function operator_dolgozok_modul($conn) {

    // FELSŐ MŰVELETI SÁV
    echo "
    <div class='module_actions'>
        <button onclick=\"ujDolgozo()\">Új dolgozó</button>
        <input type='text' id='kereses' placeholder='Keresés...'>
        <button onclick=\"szures()\">Szűrés</button>
    </div>
    ";

    // TÁBLÁZAT
    $sql = "SELECT `dolgozo_nev`,`beosztas`,`email` FROM `dolgozok` ORDER BY `dolgozo_nev` ASC";
    $result = $conn->query($sql);


    echo "<table class='tabla table table-striped table-hover'>
            <tr>
                <th>Név</th>
                <th>Beosztás</th>
                <th>Email</th>
            </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['dolgozo_nev']}</td>
                <td>{$row['beosztas']}</td>
                <td>{$row['email']}</td>
              </tr>";
    }

    echo "</table>";
}

?>