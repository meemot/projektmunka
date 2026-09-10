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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="style_adm.css">
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
    <title>Admin oldal</title>
</head>

<body>

    <div class="layout">
        <!-- Bal oldali menü -->
        <nav class="sidebar">
            <div class="logo_category mb-4"><img src="logo_a.png" alt="Céglogó" class="app-logo"></div>
            <br>
            <a href="#" class="d-block text-white mb-2 menu_link eszkozok_separator" data-action="kezdolap">Kezdőoldal</a>
            <a href="#" class="d-block text-white mb-2 menu_link" data-action="a_dolgozok">Dolgozók</a>
            <a href="#" class="d-block text-white mb-2 menu_link" data-action="a_felhasznalok">Felhasználók</a>
            <a href="#" class="d-block text-white mb-2 menu_link eszkozok_separator" data-action="a_eszkozok">Eszközök</a>
            <a href="#" class="d-block text-white mb-2 menu_link" data-action="a_kiadas">Eszköz kiadás</a>
            <a href="#" class="d-block text-white mb-2 menu_link" data-action="a_visszavetel">Visszavett eszközök</a>
            <a href="#" class="d-block text-white mb-2 menu_link" data-action="a_osszes_kiadas">Összes eszközmozgás</a>

            <!-- Felhasználó mező a menük alatt -->
            <div class="admin_top card">
                <div class="card-body">
                    <div class="admin_box2"><?php echo $_SESSION["nev"]; ?></div>
                    <span class="badge bg-primary">Admin</span>
                </div>
            </div>

            <a href="logout.php" class="logout_btn">Kijelentkezés</a>
            <div class="menu_footer">Verzió: 1.0.3</div>

        </nav>

        <!-- Jobb oldali tartalom -->
        <main class="content">
            <!-- Táblázat feletti fix rész -->
            <div class="cimsor"></div>
            <!-- AJAX tartalom - görgethető -->
            <div class="admin_box3">Box3</div>
        </main>
    </div>

<script src="scripts.js"></script>

</body>
</html>
