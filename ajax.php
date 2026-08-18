<?php
session_start();
require_once 'db.php'; // adatbázis kapcsolat

if (!isset($_SESSION['user_id'])){
    echo "Nincs bejelentkezve.";
    exit;
}


if (isset($_POST['action']) && $_POST['action'] === 'tipusok_kategoria_szerint') {
    tipusok_kategoria_szerint($conn);
    exit;
}

if (isset($_POST['action']) && $_POST['action'] === 'eszkozok_tipus_szerint') {
    eszkozok_tipus_szerint($conn);
    exit;
}

// a kiadás menüben a mentés gombra kattintva
if (isset($_POST['action']) && $_POST['action'] === 'kiadas_mentes') {
    kiadas_mentes($conn);
    exit;
}






$jog = $_SESSION['jogkor'];
$action = $_POST["action"] ?? "";

// xxxxxxxxxxxxxxxxx
// -=ADMIN MODULOK=-
// xxxxxxxxxxxxxxxxx

if ($jog === "a") {
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
// -----------------------------------------------------------------
        case "uj_dolgozo_form":
            uj_dolgozo_form();
            break;

        case "uj_dolgozo_mentes":
            uj_dolgozo_mentes($conn);
            break;

        case "uj_felhasznalo_form":
            uj_felhasznalo_form($conn);
            break;

        case "uj_felhasznalo_mentes":
            uj_felhasznalo_mentes($conn);
            break;
        case "uj_eszkoz_form":
            uj_eszkoz_form($conn);
            break;
        case "uj_eszkoz_mentes":
            uj_eszkoz_mentes($conn);
            break;
        case "dolgozo_szerkesztes_form": //
            dolgozo_szerkesztes_form($conn);
            break;
        case "update_dolgozo": // Dolgozó adatainak frissítése az adatbázisban
            update_dolgozo($conn);
            break;
        case "felhasznalo_szerkesztes_form":
            felhasznalo_szerkesztes_form($conn);
            break;
        case "update_felhasznalo":
            update_felhasznalo($conn);
            break;
        case "eszkoz_szerkesztes_form":
            eszkoz_szerkesztes_form($conn);
            break;
        case "update_eszkoz":
            update_eszkoz($conn);
            break;
        case "uj_kiadas_form":
            uj_kiadas_form($conn);
            break;
        case "kiadas_mentes":
            kiadas_mentes($conn);
            break;
        case "visszavet_form":
            visszavet_form($conn);
            break;
        default:
            echo "Ismeretlen admin modul.";
    }

// xxxxxxxxxxxxxxxxxxxx
// -=OPERÁTOR MODULOK=-
// xxxxxxxxxxxxxxxxxxxx

} elseif ($jog === "o") {
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

// xxxxxxxxxxxxxxxxxxxx
// -=ADMIN FÜGGVÉNYEK=-
// xxxxxxxxxxxxxxxxxxxx

// ----- Dolgozók -----

function a_dolgozok_modul($conn) {

    // FELSŐ MŰVELETI SÁV
    echo "
    <div class='module_actions'>
        <h3>Dolgozók - (név szerint sorba rendezve)</h3>
        <button onclick=\"ujDolgozo()\">Új dolgozó</button>
        <input type='text' id='kereses' placeholder='Keresés...'>
        <button onclick=\"szures()\">Szűrés</button>
    </div>
    ";

    // TÁBLÁZAT
    $sql = "SELECT dolgozo_id, dolgozo_nev, beosztas, email, telefon, kilepett 
            FROM dolgozok ORDER BY dolgozo_nev ASC";
    $result = $conn->query($sql);

    

    echo "<table class='tabla table table-striped table-hover'>
            <tr>
                <th>Név</th>
                <th>Beosztás</th>
                <th>Email</th>
                <th>Telefon</th>
                <th>Kilépett</th>
            </tr>";

    while ($row = $result->fetch_assoc()) {

        // Kilépett mező átalakítása
        $kilepett = $row['kilepett'] 
            ? "Igen ({$row['kilepett']})" 
            : "";

        echo "<tr ondblclick=\"dolgozoSzerkesztes({$row['dolgozo_id']})\">
                <td>{$row['dolgozo_nev']}</td>
                <td>{$row['beosztas']}</td>
                <td>{$row['email']}</td>
                <td>{$row['telefon']}</td>
                <td>{$kilepett}</td>
              </tr>";
    }

    echo "</table>";
}

function dolgozo_szerkesztes_form($conn) { // Dolgozó adatainak lekérése az adatbázisból és a szerkesztő űrlap megjelenítése

    $id = $_POST["id"];

    $sql = "SELECT dolgozo_nev, beosztas, email, telefon, kilepett
            FROM dolgozok
            WHERE dolgozo_id = $id";

    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    echo "
        <h3>Dolgozó módosítása</h3>

        <form id='modDolgozoForm'>
            <input type='hidden' name='id' value='$id'>

            <label>Név:</label>
            <input type='text' name='nev' value='{$row['dolgozo_nev']}' class='form-control'>

            <label>Beosztás:</label>
            <input type='text' name='beosztas' value='{$row['beosztas']}' class='form-control'>

            <label>Email:</label>
            <input type='text' name='email' value='{$row['email']}' class='form-control'>

            <label>Telefon:</label>
            <input type='text' name='telefon' value='{$row['telefon']}' class='form-control'>

            <label>Kilépett:</label>
            <input type='checkbox' name='kilepett' ".($row['kilepett'] ? "checked" : "").">
            <br>

            <button type='button' onclick='modDolgozoMentes()' class='btn btn-primary mt-3'>Mentés</button>
            <button type='button' onclick='modDolgozoMegse()' class='btn btn-secondary mt-3 ms-2'>Mégse</button>
        </form>
    ";
}

function update_dolgozo($conn) { // Dolgozó adatainak frissítése az adatbázisban

    $id       = $_POST["id"];
    $nev      = $_POST["nev"];
    $beosztas = $_POST["beosztas"];
    $email    = $_POST["email"];
    $telefon  = $_POST["telefon"];
    $kilepett = $_POST["kilepett"];   // dátum vagy üres string

     // -2) Ellenőrzés: minden mező ki van-e töltve?
    if ($nev === "" || $beosztas === "" || $email === "" || $telefon === "") {
        echo "HIBA: Minden mezőt ki kell tölteni!";
        return;
    }

    // -1) Ellenőrzés: minden szó nagybetűs-e?
    $szavak = explode(" ", $nev);

    foreach ($szavak as $szo) {
        if ($szo === "") continue; // ha véletlen dupla space van

        if ($szo[0] !== strtoupper($szo[0])) {
            echo "HIBA: Minden szó nagybetűvel kezdődjön!";
            return;
        }
    }

    // 0) Ellenőrzés: létezik-e már ilyen név másik dolgozónál?
    $ellenorzes = "SELECT dolgozo_id 
                   FROM dolgozok 
                   WHERE dolgozo_nev = '$nev' AND dolgozo_id != $id";

    $result = $conn->query($ellenorzes);

    if ($result->num_rows > 0) {
        echo "HIBA: Már létezik ilyen nevű dolgozó!";
        return;
    }

    // Ha üres → NULL
    if ($kilepett === "") {
        $kilepett_sql = "NULL";
    } else {
        // Dátum → idézőjelbe kell tenni
        $kilepett_sql = "'$kilepett'";
    }

    
    // 1) Dolgozó adatainak frissítése az adatbázisban
    $sql = "UPDATE dolgozok
            SET dolgozo_nev='$nev',
                beosztas='$beosztas',
                email='$email',
                telefon='$telefon',
                kilepett=$kilepett_sql
            WHERE dolgozo_id = $id";

    if ($conn->query($sql)) {
        echo "OK";
    } else {
        echo "Hiba: " . $conn->error;
    }

    exit;
}

function uj_dolgozo_form() {
    echo "
    <h3>Új dolgozó létrehozása</h3>

    <form id='ujDolgozoForm' class='form-control'>

        <label>Név:</label>
        <input type='text' name='nev' class='form-control' required>

        <label>Beosztás:</label>
        <input type='text' name='beosztas' class='form-control' required>

        <label>Email:</label>
        <input type='text' name='email' class='form-control' required>

        <label>Telefon:</label>
        <input type='text' name='telefon' class='form-control' required>

        <button type='button' onclick='ujDolgozoMentes()' class='btn btn-primary mt-3'>Mentés</button>
        <button type='button' onclick='ujDolgozoMegse()' class='btn btn-secondary mt-3 ms-2'>Mégse</button>

    </form>
    ";
}

function uj_dolgozo_mentes($conn) {
    $nev        = $_POST["nev"];
    $beosztas   = $_POST["beosztas"];
    $email      = $_POST["email"];
    $telefon    = $_POST["telefon"];

    // -2) Ellenőrzés: minden mező ki van-e töltve?
    if ($nev === "" || $beosztas === "" || $email === "" || $telefon === "") {
        echo "HIBA: Minden mezőt ki kell tölteni!";
        return;
    }

    // -1) Ellenőrzés: minden szó nagybetűs-e?
    $szavak = explode(" ", $nev);

    foreach ($szavak as $szo) {
        if ($szo === "") continue; // ha véletlen dupla space van

        if ($szo[0] !== strtoupper($szo[0])) {
            echo "HIBA: Minden szó nagybetűvel kezdődjön!";
            return;
        }
    }

    // 0) Ellenőrzés: létezik-e már ilyen név?
        $ellenorzes = "SELECT dolgozo_id FROM dolgozok WHERE dolgozo_nev = '$nev'";
        $result = $conn->query($ellenorzes);

        if ($result->num_rows > 0) {
            echo "HIBA: Már létezik ilyen nevű dolgozó! Adja meg máshogy a nevet, vagy használjon kiegészítő azonosítót a név mellett!";
            return; // fontos: ne fusson tovább a mentés
        }

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

// ----- Felhasználók -----

function a_felhasznalok_modul($conn) {

    // FELSŐ MŰVELETI SÁV
    echo "
    <div class='module_actions'>
        <h3>Felhasználók</h3>
        <button onclick=\"ujFelhasznalo()\">Új felhasználó</button>
        <input type='text' id='kereses' placeholder='Keresés...'>
        <button onclick=\"szures()\">Szűrés</button>
    </div>
    ";

    // TÁBLÁZAT, INNEN KELL FOLYTATNOM A FELHASZNÁLÓKRA AKTUALIZÁLÁST!!!
    $sql = "SELECT d.dolgozo_nev, d.beosztas, u.user_id, u.jogkor, u.usernev, u.torolve 
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

        echo "<tr ondblclick=\"felhasznaloSzerkesztes({$row['user_id']})\">
                <td>{$row['dolgozo_nev']}</td>
                <td>{$row['beosztas']}</td>
                <td>{$jogkor}</td>
                <td>{$row['usernev']}</td>
                <td>{$torolve}</td>
              </tr>";
    }

    echo "</table>";
}

function felhasznalo_szerkesztes_form($conn) {

    $id = $_POST["id"];

    $sql = "SELECT u.usernev, u.jogkor, d.dolgozo_nev, u.jelszo, u.torolve
            FROM users u
            JOIN dolgozok d ON u.dolgozo_id = d.dolgozo_id
            WHERE u.user_id = $id";

    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    echo "
        <h3>Felhasználó módosítása</h3>

        <form id='modFelhasznaloForm'>
            <input type='hidden' name='id' value='$id'>

            <label>Dolgozó neve:</label>
            <input type='text' class='form-control' value='{$row['dolgozo_nev']}' disabled>

            <label>Felhasználónév:</label>
            <input type='text' name='usernev' value='{$row['usernev']}' class='form-control'>

            <label>Jogkör:</label>
            <select name='jogkor' class='form-control'>
                <option value='a' ".($row['jogkor']=='a'?'selected':'').">Admin</option>
                <option value='o' ".($row['jogkor']=='o'?'selected':'').">Operátor</option>
            </select>

            <label>Jelszó:</label>
            <input type='password' name='jelszo' value='{$row['jelszo']}' class='form-control'>

            <label>Jelszó újra:</label>
            <input type='password' name='jelszo2' value='{$row['jelszo']}' class='form-control'>

            <label>Törölve:</label>
            <input type='checkbox' name='torolve' ".($row['torolve'] ? "checked" : "").">
            <br>

            <button type='button' onclick='modFelhasznaloMentes()' class='btn btn-primary mt-3'>Mentés</button>
            <button type='button' onclick='modFelhasznaloMegse()' class='btn btn-secondary mt-3 ms-2'>Mégse</button>
        </form>
    ";
}

function update_felhasznalo($conn) {

    $id      = $_POST["id"];
    $usernev = $_POST["usernev"];
    $jogkor  = $_POST["jogkor"];
    $jelszo  = $_POST["jelszo"];
    $jelszo2 = $_POST["jelszo2"];
    $torolve = $_POST["torolve"];   // dátum vagy üres string

    /* Duplikáció ellenőrzés - kell ez bele??????
    $ellenorzes = "SELECT user_id 
                   FROM users 
                   WHERE usernev = '$usernev' AND user_id != $id";

    $result = $conn->query($ellenorzes); */

     // -2) Ellenőrzés: minden mező ki van-e töltve?
    if ($usernev === "" || $jogkor === "" || $jelszo === "" || $jelszo2 === "") {
        echo "HIBA: Minden mezőt ki kell tölteni!";
        return;
    }

    // Jelszó ellenőrzés
    if ($jelszo !== $jelszo2) {
        echo "A két jelszó nem egyezik!";
        return;
    }

    // Ha üres → NULL
    if ($torolve === "") {
        $torolve_sql = "NULL";
    } else {
        // Dátum → idézőjelbe kell tenni
        $torolve_sql = "'$torolve'";
    }

    // Jelszó hash
    $jelszo_hash = password_hash($jelszo, PASSWORD_DEFAULT);

    // SQL frissítés
    $sql = "
        UPDATE users SET
            usernev = '$usernev',
            jogkor = '$jogkor',
            jelszo = '$jelszo',
            jelszo_hash = '$jelszo_hash',
            torolve = $torolve_sql
        WHERE user_id = $id
    ";

    if ($conn->query($sql)) {
        echo "OK";
    } else {
        echo "Hiba történt: " . $conn->error;
    }
}

function uj_felhasznalo_form($conn) {
    
    // dolgozók lekérése adatbázisból
    $sql = "SELECT dolgozo_nev, dolgozo_id
            FROM dolgozok 
            ORDER BY dolgozo_nev";
    $result = $conn->query($sql);

    echo "
    <h3>Új felhasználó létrehozása</h3>
    <form id='ujFelhasznaloForm' class='form-control'>
        <label>Név:</label>
        <select name='dolgozo_id' id='dolgozo_id' class='form-control' required>
            <option value=''>-- válassz dolgozót --</option>
    ";

    // legördülő lista feltöltése
    while ($row = $result->fetch_assoc()) {
        echo "<option value=\"{$row['dolgozo_id']}\">{$row['dolgozo_nev']}</option>";
    }
    echo "
        </select>

        <label>Jogkör:</label>
        <select name='jogkor' class='form-control'>
            <option value=''>-- válassz jogkört --</option>
            <option value='o'>Operátor</option>
            <option value='a'>Admin</option>
        </select>

        <label>Felhasználónév:</label>
        <input type='text' name='usernev' class='form-control' required>

        <label>Jelszó:</label>
        <input type='password' name='jelszo' class='form-control' required>

        <label>Jelszó újra:</label>
            <input type='password' name='jelszo2' class='form-control'>

        <button type='button' onclick='ujFelhasznaloMentes()' class='btn btn-primary mt-3'>Mentés</button>
        <button type='button' onclick='ujFelhasznaloMegse()' class='btn btn-secondary mt-3 ms-2'>Mégse</button>

    </form>
    ";
}

function uj_felhasznalo_mentes($conn) {
    $dolgozo_id = $_POST["dolgozo_id"];
    $jogkor     = $_POST["jogkor"];
    $usernev    = $_POST["usernev"];
    $jelszo     = $_POST["jelszo"];
    $jelszo2    = $_POST["jelszo2"];

      // -2) Ellenőrzés: minden mező ki van-e töltve?
    if (empty($dolgozo_id) || $jogkor === "" || $usernev === "" || $jelszo === "" || $jelszo2 === "") {
        echo "HIBA: Minden mezőt ki kell tölteni!";
        return;
    }

    // Jelszó ellenőrzés
    if ($jelszo !== $jelszo2) {
        echo "A két jelszó nem egyezik!";
        return;
    }


    // 0) Ellenőrzés: van-e már felhasználó ehhez a dolgozóhoz?
    $ellenorzes = "SELECT user_id FROM users WHERE dolgozo_id = '$dolgozo_id'";
    $result = $conn->query($ellenorzes);

    if ($result->num_rows > 0) {
        echo "HIBA: Ehhez a dolgozóhoz már tartozik felhasználói fiók!";
        return;
    }

    // 1) felhasználó mentése
    //Az SQL parancsot meg kell írni a táblának megfelelően!!!!!!!!!!!

        /*   INSERT INTO `dolgozok`(`dolgozo_nev`, `beosztas`, `email`, `telefon`)
             VALUES ('laca faca','lacafacázó', 'laca@faca.com','06201234567');*/

    $sql = "INSERT INTO users(dolgozo_id, jogkor, usernev, jelszo)
            VALUES ('$dolgozo_id', '$jogkor', '$usernev', '$jelszo')";

    if ($conn->query($sql)) {
        echo "OK";
    } else {
        echo "Hiba: " . $sql . "<br>" . $conn->error;
    }

}

// ----- Eszközök -----

function a_eszkozok_modul($conn) {

    // FELSŐ MŰVELETI SÁV
    echo "
    <div class='module_actions' d-flex justify-content-between align-items-center'>
        <h3 class='m-0'>Eszközök</h3>

        <div class='d-flex align-items-center gap-2'>
            <button onclick=\"ujEszkozok()\">Új eszköz</button>
            <input type='text' id='kereses' placeholder='Keresés...'>
            <button onclick=\"szures()\">Szűrés</button>
        </div>
    </div>
    ";

    // TÁBLÁZAT
    $sql = 
        "SELECT e.eszkoz_id, et.megnevezes, ek.kategoria, e.azonosito, e.meret, ea.allapot ,e.megjegyzes 
        FROM eszkozok e 
        JOIN eszkoz_allapot ea ON e.allapot_id = ea.allapot_id 
        JOIN eszkoz_kategoria ek ON e.kategoria_id = ek.kategoria_id
        JOIN eszkoz_tipus et ON e.tipus_id = et.tipus_id
       /* WHERE e.allapot_id != 4;*/";
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
        echo "<tr ondblclick=\"eszkozSzerkesztes({$row['eszkoz_id']})\">
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

function eszkoz_szerkesztes_form($conn) {

    // ID beolvasása az AJAX POST-ból
    if (!isset($_POST['id'])) {
        echo "Hiba: nincs eszköz ID!";
        return;
    }

    $id = intval($_POST['id']);

    // A helyes oszlopnév: eszkoz_id
    $sql = "SELECT * FROM eszkozok WHERE eszkoz_id = $id";
    $result = $conn->query($sql);
    $eszkoz = $result->fetch_assoc();


    echo "
    <h3>Eszköz módosítása</h3>
    
    <form id='modEszkozForm' class='form-control'>
        <input type='hidden' name='id' value='$id'>
    ";


    // ===============================
    // 1. KATEGÓRIA – teljes lista + selected
    // ===============================

    echo "
    <label>Eszköz kategória:</label>
    <select name='eszkoz_kategoria' id='eszkoz_kategoria' class='form-control' required>
        <option value=''>-- Válaszd ki az eszköz kategóriáját! --</option>
    ";

    $sql = "SELECT * FROM eszkoz_kategoria ORDER BY kategoria";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $selected = ($eszkoz['kategoria_id'] == $row['kategoria_id']) ? "selected" : "";
        echo "<option value=\"{$row['kategoria_id']}\" $selected>{$row['kategoria']}</option>";
    }

    echo "</select>";


    // ===============================
    // 2. TÍPUS – csak az adott kategória típusai + selected
    // ===============================

    echo "
    <label>Eszköz típus:</label>
    <select name='tipus' id='tipus' class='form-control' required>
        <option value=''>-- Válaszd ki az eszköz típusát! --</option>
    ";

    $kategoria = $eszkoz['kategoria_id'];

    $sql = "SELECT * 
            FROM eszkoz_tipus
            WHERE kategoria_id = $kategoria
            ORDER BY megnevezes";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $selected = ($eszkoz['tipus_id'] == $row['tipus_id']) ? "selected" : "";
        echo "<option value=\"{$row['tipus_id']}\" $selected>{$row['megnevezes']}</option>";
    }

    echo "</select>";


    // ===============================
    // 3. AZONOSÍTÓ – előtöltve
    // ===============================

    echo "
    <label>Eszköz azonosító:</label>
    <input type='text' name='azonosito' class='form-control' value='{$eszkoz['azonosito']}' required>
    ";


    // ===============================
    // 4. ÁLLAPOT – teljes lista + selected
    // ===============================


    /*    $order = "allapot_id";

    $sql = "SELECT allapot, allapot_id
                FROM eszkoz_allapot
                ORDER BY " . ($order) ; */


    $sql = "SELECT allapot, allapot_id
            FROM eszkoz_allapot
            ORDER BY allapot_id";
    $result = $conn->query($sql);

    echo "
    <label>Eszköz állapota:</label>
    <select name='allapot' id='allapot' class='form-control' required>
        <option value=''>-- Válaszd ki az eszköz állapotát! --</option>
    ";

    while ($row = $result->fetch_assoc()) {
        $selected = ($eszkoz['allapot_id'] == $row['allapot_id']) ? "selected" : "";
        echo "<option value=\"{$row['allapot_id']}\" $selected>{$row['allapot']}</option>";
    }

    echo "</select>";


    // ===============================
    // 5. MÉRET – előtöltve
    // ===============================

    echo "
    <label>Eszköz méret:</label>
    <input type='text' name='meret' class='form-control' value='{$eszkoz['meret']}' required>
    ";


    // ===============================
    // 6. MEGJEGYZÉS – előtöltve
    // ===============================

    echo "
    <label>Megjegyzés:</label>
    <input type='text' name='megjegyzes' class='form-control' value='{$eszkoz['megjegyzes']}' required>
    <br>
    
    <button type='button' onclick='modEszkozMentes()' class='btn btn-primary mt-3'>Mentés</button>
    <button type='button' onclick='modEszkozMegse()' class='btn btn-secondary mt-3 ms-2'>Mégse</button>

    </form>
    ";


}

function update_eszkoz($conn) {

    $id             = $_POST["id"];
    $azonosito      = $_POST["azonosito"];
    $kategoria_id   = $_POST["eszkoz_kategoria"];
    $tipus_id       = $_POST["tipus"];
    $allapot_id     = $_POST["allapot"];
    $meret          = $_POST["meret"];
    $megjegyzes     = $_POST["megjegyzes"];

      // -2) Ellenőrzés: minden mező ki van-e töltve?
    if ($azonosito === "" || $kategoria_id === "" || $tipus_id === "" || $allapot_id === "" || $meret === "" || $megjegyzes === "") {
        echo "HIBA: Minden mezőt ki kell tölteni!";
        return;
    }

    // Adatok frissítése az adatbázisban
    $sql = "UPDATE eszkozok
            SET azonosito = '$azonosito',
                kategoria_id = $kategoria_id,
                tipus_id = $tipus_id,
                allapot_id = $allapot_id,
                meret = '$meret',
                megjegyzes = '$megjegyzes'
            WHERE eszkoz_id = $id";

    if ($conn->query($sql)) {
        echo "OK";
    } else {
        echo "Hiba: " . $conn->error;
    }

    exit;
}

function uj_eszkoz_form($conn) {
    echo "
    <h3>Új eszköz létrehozása</h3>
    
    <form id='ujEszkozForm' class='form-control'>

        <label>Eszköz kategória:</label>
        <select name='eszkoz_kategoria' id='eszkoz_kategoria' class='form-control' required>
            <option value=''>-- Válaszd ki az eszköz kategóriáját! --</option>
    ";


    // Eszköz KATEGORIA legördülő lista létrehozása
    $sql = "SELECT * 
            FROM eszkoz_kategoria
            ORDER BY kategoria";
    $result = $conn->query($sql);


    // legördülő lista feltöltése
    while ($row = $result->fetch_assoc()) {
         echo "<option value=\"{$row['kategoria_id']}\">{$row['kategoria']}</option>";
    }

    echo "
        </select>
    ";


    // Eszköz TIPUS legördülő lista létrehozása
    $kategoria = $_GET['kategoria'] ?? null;

    if ($kategoria) {
        $sql = "SELECT * 
                FROM eszkoz_tipus
                WHERE kategoria_id = $kategoria
                ORDER BY megnevezes";
    } else {
        $sql = "SELECT * 
                FROM eszkoz_tipus
                ORDER BY megnevezes";
    }

    $result = $conn->query($sql);

    echo "
        <label>Eszköz típus:</label>
        <select name='tipus' id='tipus' class='form-control' required>
            <option value=''>-- Válaszd ki az eszköz típusát! --</option>
    ";
    
    // legördülő lista feltöltése
    while ($row = $result->fetch_assoc()) {
        echo "<option value=\"{$row['tipus_id']}\">{$row['megnevezes']}</option>";
    }

    echo "
        </select>
    
        <label>Eszköz azonosító:</label>
        <input type='text' name='azonosito' class='form-control' required>
        ";

        // Eszköz ÁLLAPOT legördülő lista létrehozása
    $sql = "SELECT allapot, allapot_id
            FROM eszkoz_allapot
            ORDER BY allapot_id";
    $result = $conn->query($sql);

    echo "
        <label>Eszköz állapota:</label>
        <select name='allapot' id='allapot' class='form-control' required>
            <option value=''>-- Válaszd ki az eszköz állapotát! --</option>
    ";
    
    // legördülő lista feltöltése
    while ($row = $result->fetch_assoc()) {
        echo "<option value=\"{$row['allapot_id']}\">{$row['allapot']}</option>";
    }

        
    echo "
        </select>
    
        <label>Eszköz méret:<br> (<i>Kijelző méret megadása: colban, Cipő méret: EU számozás szerint, Ruha méret: S, M, L, XL, XXL</i>):</label>
        <input type='text' name='meret' class='form-control' required>
    
        <label>Megjegyzés:</label>
        <input type='text' name='megjegyzes' class='form-control' required>
        <br>
    
        <button type='button' onclick='ujEszkozMentes()' class='btn btn-primary mt-3'>Mentés</button>
        <button type='button' onclick='ujEszkozMegse()' class='btn btn-secondary mt-3 ms-2'>Mégse</button>

    </form>
    ";
}

function uj_eszkoz_mentes($conn) {
    $kategoria_id = $_POST["eszkoz_kategoria"];
    $tipus_id     = $_POST["tipus"];
    $azonosito    = $_POST["azonosito"];
    $meret     = strtoupper($_POST["meret"]);
    $allapot     = $_POST["allapot"];
    $megjegyzes     = $_POST["megjegyzes"];

    // -2) Ellenőrzés: minden mező ki van-e töltve?
    if ($azonosito === "" || $kategoria_id === "" || $tipus_id === "" || $allapot === "" || $meret === "" || $megjegyzes === "") {
        echo "HIBA: Minden mezőt ki kell tölteni!";
        return;
    }

    // 1) eszköz mentése
    //Az SQL parancsot meg kell írni a táblának megfelelően!!!!!!!!!!!

        /*   INSERT INTO `dolgozok`(`dolgozo_nev`, `beosztas`, `email`, `telefon`)
             VALUES ('laca faca','lacafacázó', 'laca@faca.com','06201234567');*/

    $sql = "INSERT INTO eszkozok(kategoria_id, tipus_id, azonosito, meret, allapot_id, megjegyzes)
            VALUES ('$kategoria_id', '$tipus_id', '$azonosito', '$meret', '$allapot', '$megjegyzes')";

    if ($conn->query($sql)) {
        echo "OK";
    } else {
        echo "Hiba: " . $sql . "<br>" . $conn->error;
    }

}


function a_kiadas_modul($conn) {

    // FELSŐ MŰVELETI SÁV
    echo "
    <div class='module_actions'>
        <h3>Kiadott, még nem visszavett eszközök</h3>
        <button onclick=\"ujKiadas()\">Új kiadás</button>
        <input type='text' id='kereses' placeholder='Keresés...'>
        <button onclick=\"szures()\">Szűrés</button>
    </div>
    ";

    // TÁBLÁZAT
    $sql = 
        "SELECT
            r.reszletek_id,
            k.kiad_id,
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
                <th></th>
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
                <td><button type='button' class='btn btn-outline-primary btn-sm btn-visszavet' data-id='{$row['reszletek_id']}'>Visszavesz</button></td>
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
        <h3>Összes eszközmozgás</h3>
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
        <h3>Visszavett eszközök</h3>
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

//kiegészítés az ÚJ ESZKÖZ felvitelhez, hogy a típuok csak az adott kategóriához tartozóak legyenek
function tipusok_kategoria_szerint($conn) {

    $kat = intval($_POST['kategoria']);

    $sql = "SELECT tipus_id, megnevezes 
            FROM eszkoz_tipus 
            WHERE kategoria_id = $kat
            ORDER BY megnevezes";

    $result = $conn->query($sql);

    echo "<option value=''>-- Válaszd ki az eszköz típusát! --</option>";

    while ($row = $result->fetch_assoc()) {
        echo "<option value='{$row['tipus_id']}'>{$row['megnevezes']}</option>";
    }

    exit;
}


// KIADÁS
// ------

function uj_kiadas_form($conn) {

   // $tipus = $_POST['tipus'] ?? null; // AJAX-ból érkező típus

    // dolgozók lekérése
    $sql = "SELECT dolgozo_nev, dolgozo_id
            FROM dolgozok
            WHERE kilepett IS NULL
            ORDER BY dolgozo_nev";
    $result = $conn->query($sql);

    echo "
    <h3>Új eszköz kiadása (A selejtezett tételek nem jelennek meg!)</h3>

    <form id='ujKiadasForm' class='form-control'>
        
        <label>Dolgozó:</label>
        <select name='dolgozo_id' id='dolgozo_id' class='form-control' required>
            <option value=''>-- Válaszd ki a dolgozót! --</option>
    ";

    while ($row = $result->fetch_assoc()) {
        echo "<option value=\"{$row['dolgozo_id']}\">{$row['dolgozo_nev']}</option>";
    }

    echo "</select><br>";


    // ESZKÖZ TÍPUS LISTA
    $sql = "SELECT tipus_id, megnevezes 
            FROM eszkoz_tipus
            ORDER BY megnevezes";
    $result = $conn->query($sql);

    echo "
        <label>Eszköz típus:</label>
        <select name='tipus' id='tipus' class='form-control' required>
            <option value=''>-- Válaszd ki az eszköz típusát! --</option>
    ";

    while ($row = $result->fetch_assoc()) {
        echo "<option value=\"{$row['tipus_id']}\">{$row['megnevezes']}</option>";
    }

    echo "</select><br>";


    // ESZKÖZ LISTA – AJAX fogja frissíteni
    echo "
        <label>Eszköz azonosító:</label>
        <select name='eszkoz_id' id='eszkoz_id' class='form-control'>
        <option value=''>--Előbb válassz típust!--</option>
        </select>
    ";


    echo "</select><br>";

    // A következő eszköz felvitele
    echo "
        <button type='button' id='hozzaadEszkozBtn' class='btn btn-success mb-3'>
            Eszköz hozzáadása
        </button><br><br>
        <h3>Kiadásra előkészített eszközök</h3><br>

        <table id='kiadottEszkozok' class='table table-bordered'>
            <thead>
                <tr>
                    <th>Típus</th>
                    <th>Azonosító</th>
                    <th>Törlés</th> 
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    ";


    // GOMBOK
    echo "
        <button type='button' onclick='ujKiadasMentes()' class='btn btn-primary mt-3'>Kiadás mentése</button>
        <button type='button' onclick='ujKiadasMegse()' class='btn btn-secondary mt-3 ms-2'>Mégse</button>
    ";

    echo "</form>";
}


function kiadas_mentes($conn) {

    // 0) Dolgozó kiválasztva?
    if (!isset($_POST['dolgozo_id']) || $_POST['dolgozo_id'] === "") {
        echo "HIBA: Válaszd ki a dolgozót!";
        return;
    }

    // 1) Van-e legalább 1 eszköz?
    if (!isset($_POST['eszkozok']) || count($_POST['eszkozok']) === 0) {
        echo "HIBA: Legalább egy eszközt ki kell választani!";
        return;
    }

    // 2) A bejelentkezett user ID-ja
    if (!isset($_SESSION['user_id'])) {
        echo "HIBA: Nincs bejelentkezve!";
        return;
    }

    $dolgozo_id = intval($_POST['dolgozo_id']);
    $user_id = $_SESSION['user_id'];   // bejelentkezett user
    $datum = date('Y-m-d H:i:s');

    // 1) új kiadás létrehozása
    $sql = "INSERT INTO kiadas (ki_vette_fel, ki_adta_ki, kiadas_datum)
            VALUES ($dolgozo_id, $user_id, '$datum')";

    if (!$conn->query($sql)) {
        echo "SQL hiba: " . $conn->error;
        return;
    }

    // 2) az új kiadás ID-ja
    $kiad_id = $conn->insert_id;

    // 3) eszközök mentése a reszletek táblába
    if (!empty($_POST['eszkozok'])) {

        foreach ($_POST['eszkozok'] as $eszkoz_id) {

            $eszkoz_id = intval($eszkoz_id);

            // lekérjük az eszköz aktuális állapotát
            $sql = "SELECT allapot_id FROM eszkozok WHERE eszkoz_id = $eszkoz_id";
            $res = $conn->query($sql);
            $row = $res->fetch_assoc();
            $allapot = intval($row['allapot_id']);

            // beszúrjuk a részletet
            $sql2 = "INSERT INTO reszletek (kiad_id, eszkoz_id, kiadas_allapot)
                     VALUES ($kiad_id, $eszkoz_id, $allapot)";
            $conn->query($sql2);
        }
    }

    echo "A kiadás sikeresen mentve (ID: $kiad_id)";
}

function visszavet_form($conn) {

    // ID beolvasása
    if (!isset($_POST["reszletek_id"])) {
        echo "<p class='text-danger'>Hiba: nincs kiadás ID!</p>";
        return;
    }

    $reszletek_id = intval($_POST["reszletek_id"]);
    echo "<p>DEBUG: kapott kiad_id = $reszletek_id</p>";

    // A kiadás részleteinek lekérése
    $sql = "SELECT
                r.reszletek_id,
                r.kiadas_allapot,
                k.kiad_id,
                d.dolgozo_nev AS felvette,
                et.megnevezes AS eszkoz_megnevezes,
                e.azonosito AS eszkoz_azonosito
            FROM reszletek r
                JOIN kiadas k ON r.kiad_id = k.kiad_id
                JOIN dolgozok d ON k.ki_vette_fel = d.dolgozo_id
                JOIN eszkozok e ON e.eszkoz_id = r.eszkoz_id
                JOIN eszkoz_tipus et ON e.tipus_id = et.tipus_id
            WHERE r.reszletek_id = $reszletek_id";

    $result = $conn->query($sql);

    if (!$result || $result->num_rows === 0) {
        echo "<p class='text-danger'>Hiba: a kiadás nem található!</p>";
        return;
    }

    $row = $result->fetch_assoc();

    // FORM KIÍRÁSA
    echo "
    <h3>Eszköz visszavétele</h3>

    <form id='VisszavetForm' class='form-control'>

        <label>Ki vette fel:</label>
        <input type='text' class='form-control' value='{$row['felvette']}' readonly>

        <label>Eszköz megnevezése:</label>
        <input type='text' class='form-control' value='{$row['eszkoz_megnevezes']}' readonly>

        <label>Eszköz azonosítója:</label>
        <input type='text' class='form-control' value='{$row['eszkoz_azonosito']}' readonly>

        <label>Eszköz állapota visszavételkor:</label>
        <select name='visszavett_allapot' class='form-control' required>
            <option value=''>--Milyen az eszköz állapota visszavételkor?--</option>
    ";
           
        $sql2 = "SELECT
                    allapot_id,
                    allapot
                FROM eszkoz_allapot
                WHERE allapot_id >= {$row['kiadas_allapot']}
                ORDER BY allapot_id";
        $res2 = $conn->query($sql2);

    while ($sor = $res2->fetch_assoc()) {
        echo "<option value='{$sor['allapot_id']}'>{$sor['allapot']}</option>";
    }

    echo "</select>

        <label>Megjegyzés:</label>
        <input type='text' name='visszavett_megjegyzes' class='form-control' required>

        <input type='hidden' name='kiad_id' value='{$row['reszletek_id']}'>

        <button type='button' onclick='VisszavetMentes()' class='btn btn-primary mt-3'>Mentés</button>
        <button type='button' onclick='VisszavetMegse()' class='btn btn-secondary mt-3 ms-2'>Mégse</button>

    </form>
    ";
}




//kiegészítés az ÚJ KIADÁS-hoz, az eszköz azonosítójának kiválasztásához (korábban kiválasztott típus alapján)
function eszkozok_tipus_szerint($conn) {

    $tipus = intval($_POST['tipus']);

    $sql = "SELECT e.eszkoz_id,
                    e.azonosito,
                    e.allapot_id,
                    e.meret,
                    et.megnevezes AS tipus_nev,
                    megjegyzes
                FROM eszkozok e
                    JOIN eszkoz_tipus et ON e.tipus_id = et.tipus_id
                WHERE e.tipus_id = $tipus
                    AND e.allapot_id != 4
                    AND e.eszkoz_id NOT IN (
                        SELECT eszkoz_id 
                        FROM reszletek
                        WHERE visszavet_datum IS NULL
                )
                ORDER BY e.azonosito";

    $result = $conn->query($sql);

    echo "<option value=''>-- Válaszd ki az eszközt! --</option>";

    while ($row = $result->fetch_assoc()) {
        echo "<option value=\"{$row['eszkoz_id']}\" 
                    data-tipus=\"{$row['tipus_nev']}\"
                    data-allapot=\"{$row['allapot_id']}\"
                    data-meret=\"{$row['meret']}\">
                    {$row['azonosito']} (Méret: {$row['meret']})</option>";
    }
}


// xxxxxxxxxxxxxxxxxxxxxxx
// -=OPERÁTOR FÜGGVÉNYEK=-
// xxxxxxxxxxxxxxxxxxxxxxx

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