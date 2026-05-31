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
        <div class="menu_category">Dolgozók</div>
        <div class="menu_items">
            <a href="#">dolgozók</a>
            <a href="#">új</a>
        </div>
        <div class="menu_category">Felhasználók</div>
        <div class="menu_items">
            <a href="#">felhasználók</a>
            <a href="#">új</a>
        </div>
        <div class="menu_category">Eszközök</div>
        <div class="menu_items">
            <a href="#">eszközök</a>
            <a href="#">új</a>
            <a href="#">módosítás</a>
        </div>
        <div class="menu_category">Lekérdezések</div>
        <div class="menu_items">
            <a href="#">(később)</a>
        </div>
    </div>
    <div class="admin_container2">
        <div class="admin_box2"><?php echo $_SESSION["nev"];?></div>
        <div class="admin_box3">Box3</div>
    </div>
</div>
    

</body>
</html>
