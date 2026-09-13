<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sikertelen belépés</title>
    <style>
        body {
            background: #f5f5f5;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .error-box {
            background: white;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.15);
            text-align: center;
            max-width: 400px;
        }

        .error-box h2 {
            color: #c0392b;
            margin-bottom: 15px;
        }

        .error-box p {
            font-size: 16px;
            margin-bottom: 25px;
        }

        .error-box a {
            display: inline-block;
            padding: 10px 20px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.2s;
        }

        .error-box a:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
    <div class="error-box">
        <h2>Sikertelen belépés</h2>
        <p>Hibás név vagy jelszó, vagy nincs joga belépni.</p>
        <a href="p_index.php">Vissza a belépéshez</a>
    </div>
</body>
</html>