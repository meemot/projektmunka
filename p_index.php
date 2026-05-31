
<?php
    session_start();
    session_unset();
    session_destroy();
?>


<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_login.css">
    <title>Login popup ablak</title>
</head>

<body>


<!-- a felugro ablakot tartalmazó réteg -->

<div class="overlay" id="loginBox">
    <div class="popup">
        <h2>Bejelentkezés</h2>
        <form method="POST" action="p_login.php">                                  <!-- A form actionját a saját bejelentkeztető scriptedre állítsd -->
            
            <input type="text" name="fn" placeholder="Felhasználónév"><br><br>     <!-- A felhasználónév mező típusa "text" -->
               
            <input type="password" name="jl" placeholder="Jelszó"><br><br>         <!-- A jelszó mező típusa "password", hogy a beírt karakterek ne látszódjanak -->

            <button type="submit">Belépés</button>                                 <!-- A gomb "submit", hogy elküldje a formot a megadott action URL-re -->
      <!--  <button type="submit" onclick="document.getElementById('loginBox').style.display='none'">Mégse</button>   -->
        </form>
    </div>
</div>

<script>
window.onload = function() {
    document.getElementById('loginBox').style.display = 'flex';
};
</script>

</body>
</html>
