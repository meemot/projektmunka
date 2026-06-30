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
            <nav class="col-12 col-md-3 col-lg-2 bg-dark text-white min-vh-100 p-3 admin_box1">
                
                <div class="logo_category mb-4">LOGO</div>

                <a href="#" class="d-block text-white mb-2 menu_link" data-action="a_dolgozok">Dolgozók</a>
                <a href="#" class="d-block text-white mb-2 menu_link" data-action="a_felhasznalok">Felhasználók</a>
                <a href="#" class="d-block text-white mb-2 menu_link eszkozok_separator" data-action="a_eszkozok">Eszközök</a>
                <a href="#" class="d-block text-white mb-2 menu_link" data-action="a_osszes_kiadas">Összes kiadás</a>
                <a href="#" class="d-block text-white mb-2 menu_link" data-action="a_kiadas">Kiadott, még nem visszavett</a>
                <a href="#" class="d-block text-white mb-2 menu_link" data-action="a_visszavetel">Visszavett</a>

            </nav>

            <!-- Jobb oldali tartalom -->
            <main class="col-12 col-md-9 col-lg-10 p-4 admin_container2">

                <!-- Felső sáv -->
                <div class="card mb-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="admin_box2"><?php echo $_SESSION["nev"]; ?></div>
                        <span class="badge bg-primary">Admin</span>
                    </div>
                </div>

                <!-- AJAX tartalom -->
                <div class="admin_box3">
                    Box3
                </div>

            </main>

        </div>
    </div>

<script src="scripts.js"></script>

</body>
</html>
