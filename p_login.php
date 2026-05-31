<?php
    /* adatbázis kapcsolat létrehozása */
    $servername = "localhost"; //127.0.0.1
    $username = "root"; // most, WAMP alatt
    $password = ""; // most, WAMP alatt
    $dbname = "projectmunka";

    /* a bejelentkezési adatok lekérése a POST tömbből (p_index.php form-ból) */
    $fh = $_POST["fn"];
    $jl = $_POST["jl"];

    /* a kapcsolat létrehozása + ellenőrzése */
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
            die("Kapcsolódási hiba: " . $conn->connect_error);
        }    

    $conn->set_charset("utf8");

    
    //SELECT u.dolgozo_id, u.jogkor, d.dolgozo_nev, d.beosztas FROM users u JOIN dolgozok d ON u.dolgozo_id = d.dolgozo_id WHERE u.usernev = 'memot' AND u.jelszo = 'jelszo11' AND u.torolve IS NULL
    $sql = "SELECT u.dolgozo_id, u.jogkor, d.dolgozo_nev, d.beosztas FROM users u JOIN dolgozok d ON u.dolgozo_id = d.dolgozo_id WHERE u.usernev = ? AND u.jelszo = ? AND (u.torolve IS NULL or u.torolve = 0)";
    $stmt = $conn->prepare($sql);

    $stmt->bind_param("ss", $fh, $jl);

    $stmt->execute();
    $result = $stmt->get_result();


    if ($result && $result->num_rows > 0) // helyes és ad vissza eredménysort, azaz van ilyen felhasználó
        {
            $row = $result->fetch_assoc();
            session_start();
            session_unset();
            session_destroy();
            session_start();
            $_SESSION["dolgozo_id"] = $row["dolgozo_id"];
            $_SESSION["jogkor"] = $row["jogkor"];
            $_SESSION["nev"] = $row["dolgozo_nev"];
            $_SESSION["beosztas"] = $row["beosztas"];
            if ($_SESSION["jogkor"] == "a")
                {
                    $stmt->close();
                    $conn->close();
                    header("Location:admin.php");
                }
            else
                {
                    $stmt->close();
                    $conn->close();
                    header("Location:operator.php");
                }

        }
    else{
        $stmt->close();
        $conn->close();
        header("Location:sikertelen.php");
    }


    $stmt->close();
    $conn->close();

?>