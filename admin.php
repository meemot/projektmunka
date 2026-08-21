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
    <title>Admin oldal</title>
</head>

<body>

    <div class="container-fluid">
        <div class="row">

            <!-- Bal oldali menü -->
            <nav class="col-12 col-md-3 col-lg-2 bg-dark text-white p-3 admin_box1 d-flex flex-column">
                
                <div class="logo_category mb-4">LOGO</div>

                <!-- Felső sáv (fix rész) -->
                <div class="admin_top card mb-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="admin_box2"><?php echo $_SESSION["nev"]; ?></div>
                        <span class="badge bg-primary">Admin</span>
                    </div>
                </div>

                <a href="#" class="d-block text-white mb-2 menu_link eszkozok_separator" data-action="kezdolap">Kezdőoldal</a>
                <a href="#" class="d-block text-white mb-2 menu_link" data-action="a_dolgozok">Dolgozók</a>
                <a href="#" class="d-block text-white mb-2 menu_link" data-action="a_felhasznalok">Felhasználók</a>
                <a href="#" class="d-block text-white mb-2 menu_link eszkozok_separator" data-action="a_eszkozok">Eszközök</a>
                <a href="#" class="d-block text-white mb-2 menu_link" data-action="a_osszes_kiadas">Összes eszközmozgás</a>
                <a href="#" class="d-block text-white mb-2 menu_link" data-action="a_kiadas">Eszköz kiadás</a>
                <a href="#" class="d-block text-white mb-2 menu_link" data-action="a_visszavetel">Eszköz visszavétel</a>

                <div class="menu_footer mt-auto text-center">
                    <small class="text-white-50">Verzió: 1.0.3</small>
                </div>

            </nav>

            <!-- Jobb oldali tartalom -->
            <main class="col-12 col-md-9 col-lg-10 p-4 admin_container2 d-flex flex-column h-100">
                <!-- AJAX tartalom - görgethető -->
                <div class="admin_box3 flex-grow-1 overflow-auto">
                    Box3
                </div>

            </main>

        </div>
    </div>

<script src="scripts.js"></script>

</body>
</html>
