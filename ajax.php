
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

/* --- ADMIN MODULOK --- */
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

        case "a_osszes_kiadas":
            a_osszes_kiadas_modul($conn);
            break;

        case "a_visszavetel":
            a_visszavetel_modul($conn);
            break;
        //"ÚJ" MŰVELETEK
        case "uj_dolgozo_form":
            uj_dolgozo_form();
            break;

        case "uj_dolgozo_mentes":
            uj_dolgozo_mentes($conn);
            break;

        case "uj_felhasznalo_form":
            uj_felhasznalo_form();
            break;

        case "uj_felhasznalo_mentes":
            uj_felhasznalo_mentes($conn);
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


/* ADMIN FÜGGVÉNYEK */

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
        JOIN eszkoz_allapot ea ON e.allapot_id = ea.allapot_id 
        JOIN eszkoz_kategoria ek ON e.kategoria_id = ek.kategoria_id
        JOIN eszkoz_tipus et ON e.tipus_id = et.tipus_id
        WHERE e.allapot_id != 4;";
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
            d.dolgozo_nev AS felvette,
            et.megnevezes AS eszkoz_megnevezese,
            e.azonosito AS eszkoz_azonosito,
            e.meret,
            e.megjegyzes,
            d1.dolgozo_nev AS kiadta
        FROM kiadas k
            JOIN dolgozok d
                ON k.ki_vette_fel = d.dolgozo_id
            JOIN reszletek r
                ON r.kiad_id = k.kiad_id
            JOIN eszkozok e
                ON e.eszkoz_id = r.eszkoz_id
            JOIN eszkoz_tipus et
                ON e.tipus_id = et.tipus_id
            JOIN dolgozok d1
                ON k.ki_adta_ki = d1.dolgozo_id
        WHERE r.visszavet_datum is null
        ORDER BY kiadas_datum ASC;";
    $result = $conn->query($sql);

    
    echo "<table class='tabla table table-striped table-hover'>
            <tr>
                <th>Kiadás dátuma</th>
                <th>Ki vette fel</th>
                <th>Eszköz megnevezése</th>
                <th>Eszköz azonosító</th>
                <th>Méret</th>
                <th>Megjegyzés</th>
                <th>Ki adta ki</th>
            </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['kiadas_datum']}</td>
                <td>{$row['felvette']}</td>
                <td>{$row['eszkoz_megnevezese']}</td>
                <td>{$row['eszkoz_azonosito']}</td>
                <td>{$row['meret']}</td>
                <td>{$row['megjegyzes']}</td>
                <td>{$row['kiadta']}</td>
              </tr>";
    }

    echo "</table>";
}

function a_osszes_kiadas_modul($conn) {

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
            et.megnevezes,
            e.azonosito,
            e.meret,
            ea.allapot,
            e.megjegyzes AS megjegyzes_kiadasnal,
            d.dolgozo_nev AS felvette,
            d1.dolgozo_nev AS kiadta,
            r.visszavet_datum,
            ea1.allapot AS visszavet_allapot,
            d2.dolgozo_nev AS visszavette,
            r.megjegyzes AS megjegyzes_visszavetnel
        FROM reszletek r
            JOIN kiadas k
                ON r.kiad_id = k.kiad_id
            JOIN eszkozok e
                ON e.eszkoz_id = r.eszkoz_id
            JOIN eszkoz_tipus et
                ON et.tipus_id = e.tipus_id
            JOIN eszkoz_allapot ea
                ON ea.allapot_id = r.kiadas_allapot
            JOIN dolgozok d
                ON k.ki_vette_fel = d.dolgozo_id
            JOIN dolgozok d1
                ON k.ki_adta_ki = d1.dolgozo_id
            LEFT JOIN eszkoz_allapot ea1
                ON ea1.allapot_id = r.visszavet_allapot
            LEFT JOIN dolgozok d2
                ON d2.dolgozo_id = r.ki_vette_vissza
        ORDER BY kiadas_datum ASC;";

    $result = $conn->query($sql);

    
    echo "<table class='tabla table table-striped table-hover'>
            <tr>
                <th>Kiadás dátuma</th>
                <th>Eszköz megnevezése</th>
                <th>Eszköz azonosító</th>
                <th>Méret</th>
                <th>Állapot kiadáskor</th>
                <th>Megjegyzés</th>
                <th>Felvette</th>
                <th>Kiadta</th>
                <th>Visszavétel dátuma</th>
                <th>Állapot visszavételkor</th>
                <th>Visszavette</th>
                <th>Megjegyzés a visszavételhez</th>
            </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['kiadas_datum']}</td>
                <td>{$row['megnevezes']}</td>
                <td>{$row['azonosito']}</td>
                <td>{$row['meret']}</td>
                <td>{$row['allapot']}</td>
                <td>{$row['megjegyzes_kiadasnal']}</td>
                <td>{$row['felvette']}</td>
                <td>{$row['kiadta']}</td>
                <td>{$row['visszavet_datum']}</td>
                <td>{$row['visszavet_allapot']}</td>
                <td>{$row['visszavette']}</td>
                <td>{$row['megjegyzes_visszavetnel']}</td>
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
            r.visszavet_datum,
            et.megnevezes,
            e.azonosito,
            ea1.allapot AS allapot_kiadaskor,
            ea.allapot AS visszavet_allapot,
            d.dolgozo_nev AS visszavette,
            r.megjegyzes
            FROM reszletek r
            JOIN eszkozok e
            	ON e.eszkoz_id = r.eszkoz_id
            JOIN eszkoz_tipus et
            	ON et.tipus_id = e.tipus_id
            JOIN eszkoz_allapot ea
            	ON ea.allapot_id = r.visszavet_allapot
            JOIN dolgozok d
            	ON d.dolgozo_id = r.ki_vette_vissza
            JOIN eszkoz_allapot ea1
            	ON r.kiadas_allapot = ea1.allapot_id";
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
        echo "<tr>
                <td>{$row['visszavet_datum']}</td>
                <td>{$row['megnevezes']}</td>
                <td>{$row['azonosito']}</td>
                <td>{$row['allapot_kiadaskor']}</td>
                <td>{$row['visszavet_allapot']}</td>
                <td>{$row['visszavette']}</td>
                <td>{$row['megjegyzes']}</td>
              </tr>";
    }

    echo "</table>";
}

// "ÚJ" MŰVELETEK
function uj_dolgozo_form() {
    echo "
    <h3>Új dolgozó létrehozása</h3>

    <form id='ujDolgozoForm'>

        <label>Név:</label>
        <input type='text' name='nev' required>

        <label>Beosztás:</label>
        <input type='text' name='beosztas' required>

        <label>Email:</label>
        <input type='text' name='email' required>

        <label>Telefon:</label>
        <input type='text' name='telefon' required>

        <button type='button' onclick='ujDolgozoMentes()'>Mentés</button>

    </form>
    ";
}

function uj_dolgozo_mentes($conn) {
    $nev        = $_POST["nev"];
    $beosztas   = $_POST["beosztas"];
    $email      = $_POST["email"];
    $telefon    = $_POST["telefon"];

    // 1) dolgozó mentése
    //Az SQL parancsot meg kell írni a táblának megfelelően!!!!!!!!!!!

        /*   INSERT INTO `dolgozok`(`dolgozo_nev`, `beosztas`, `email`, `telefon`)
             VALUES ('laca faca','lacafacázó', 'laca@faca.com','06201234567');*/

    $sql = "INSERT INTO dolgozok(dolgozo_nev, beosztas, email, telefon)
            VALUES ('$nev', '$beosztas', '$email', '$telefon')";
    

    if ($conn->query($sql)) {
        echo "OK";
    } else {
        echo "Hiba: " . $sql . "<br>" . $conn->error;
    }

}


function uj_felhasznalo_form() {
    echo "
    <h3>Új felhasználó létrehozása</h3>

    <form id='ujFelhasznaloForm'>

        <label>Név:</label>
        <input type='text' name='nev' required>

        <label>Beosztás:</label>
        <input type='text' name='beosztas' required>

        <label>Jogkör:</label>
        <select name='jogkor'>
            <option value='a'>Admin</option>
            <option value='o'>Operátor</option>
        </select>

        <label>Felhasználónév:</label>
        <input type='text' name='usernev' required>

        <label>Jelszó:</label>
        <input type='password' name='jelszo' required>

        <button type='button' onclick='ujFelhasznaloMentes()'>Mentés</button>

    </form>
    ";
}

function uj_felhasznalo_mentes($conn) {
    $nev        = $_POST["nev"];
    $beosztas   = $_POST["beosztas"];
    $jogkor     = $_POST["jogkor"];
    $usernev    = $_POST["usernev"];
    $jelszo     = password_hash($_POST["jelszo"], PASSWORD_DEFAULT);

    // 1) dolgozó mentése
    //Az SQL parancsot meg kell írni a táblának megfelelően!!!!!!!!!!!
    $sql1 = "INSERT INTO dolgozok (dolgozo_nev, beosztas)
             VALUES ('$nev', '$beosztas')";
    $conn->query($sql1);

    $dolgozo_id = $conn->insert_id;

    // 2) felhasználó mentése
    $sql2 = "INSERT INTO users (dolgozo_id, jogkor, usernev, jelszo)
             VALUES ($dolgozo_id, '$jogkor', '$usernev', '$jelszo')";
    $conn->query($sql2);

    echo "OK";
}


/* OPERÁTOR FÜGGVÉNYEK */

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
        JOIN eszkoz_allapot ea ON e.allapot_id = ea.allapot_id 
        JOIN eszkoz_kategoria ek ON e.kategoria_id = ek.kategoria_id
        JOIN eszkoz_tipus et ON e.tipus_id = et.tipus_id
        WHERE e.allapot_id != 4;";
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