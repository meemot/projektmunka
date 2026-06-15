<?php
    session_start();
    if (!isset($_SESSION["dolgozo_id"])){
        header("Location:p_index.php");
        exit;
    }
    if ($_SESSION["jogkor"] != "a"){
        header("Location:p_index.php");
        exit;
    }
?>



<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_adm.css">
    <title>Admin oldal</title>
</head>

<body>

<div class="admin_container">
    <div class="admin_box1">
        <div class="logo_category">LOGO</div>
        <div>
            <a href="#" class="menu_link" data-action="a_dolgozok">Dolgozók</a>
            <a href="#" class="menu_link" data-action="a_felhasznalok">Felhasználók</a>
            <a href="#" class="menu_link" data-action="a_eszkozok">Eszközök</a>
        </div>
    </div>
    <div class="admin_container2">
        <div class="admin_box2"><?php echo $_SESSION["nev"];?></div>
        <div class="admin_box3">Box3</div>
    </div>
</div>


<script src="scripts.js"></script>

</body>
</html>
