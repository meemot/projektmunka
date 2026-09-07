<?php
    session_start();
    if (!isset($_SESSION["dolgozo_id"])){
        header("Location:p_index.php");
        exit;
    }
    if ($_SESSION["jogkor"] != "o"){
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
    <link rel="stylesheet" href="style_op.css">
    <title>Operátor oldal</title>
</head>

<body>

    <div class="layout">
        <!-- Bal oldali menü -->
        <nav class="sidebar">
            <div class="logo_category mb-4"><img src="logo_o.png" alt="Céglogó" class="app-logo"></div>
            <br>
            <a href="#" class="d-block text-white mb-2 menu_link eszkozok_separator" data-action="o_eszkozok">Eszközök</a>
            <a href="#" class="d-block text-white mb-2 menu_link" data-action="a_kiadas">Eszköz kiadás</a>
            <a href="#" class="d-block text-white mb-2 menu_link" data-action="a_visszavetel">Visszavett eszközök</a>
            <a href="#" class="d-block text-white mb-2 menu_link" data-action="a_osszes_kiadas">Összes eszközmozgás</a>

            <!-- Felhasználó mező a menük alatt -->
            <div class="operator_top card">
                <div class="card-body">
                    <div class="operator_box2"><?php echo $_SESSION["nev"]; ?></div>
                    <span class="badge bg-danger">Operator</span>
                </div>
            </div>

            <div class="menu_footer">Verzió: 1.0.3</div>

        </nav>

        <!-- Jobb oldali tartalom -->
        <main class="content">
            <!-- Táblázat feletti fix rész -->
            <div class="cimsor"></div>
            <!-- AJAX tartalom - görgethető -->
            <div class="operator_box3">Box3</div>
        </main>
    </div>

<script src="scripts.js"></script>

<!--<script>
document.addEventListener("DOMContentLoaded", () => {
    const kezdolapLink = document.querySelector('.menu_link[data-action="a_osszes_kiadas"]');
    if (kezdolapLink) {
        kezdolapLink.click();   // ugyanaz a logika fut, mint kézi kattintáskor
    }
});
</script>
-->

</body>
</html>

