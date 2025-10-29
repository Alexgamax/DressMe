<?php
echo '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado de Conexión</title>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap");

        body {
            font-family: "Poppins", Arial, sans-serif;
            background-color: #fff0f5;
            color: #333;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            padding: 30px 40px;
            max-width: 600px;
            width: 100%;
            border-top: 5px solid #ec4899;
        }

        h2 {
            font-weight: 600;
            margin-top: 0;
            display: flex;
            align-items: center;
        }

        .success {
            color: #16a34a;
        }

        .error {
            color: #dc2626;
        }
        
        h2 .icon {
            font-size: 1.5em;
            margin-right: 10px;
        }

        h3 {
            font-weight: 600;
            color: #d81b60;
            border-bottom: 2px solid #fce7f3;
            padding-bottom: 5px;
            margin-top: 30px;
        }

        ul {
            list-style: none;
            padding-left: 0;
        }

        li {
            background-color: #fdf2f8;
            border-left: 4px solid #ec4899;
            padding: 12px 15px;
            margin-bottom: 8px;
            border-radius: 6px;
            font-weight: 300;
        }
        
        .error-message {
            background-color: #fee2e2;
            border: 1px solid #fca5a5;
            color: #b91c1c;
            padding: 15px;
            border-radius: 8px;
            font-family: "Courier New", Courier, monospace;
            word-wrap: break-word;
        }
    </style>
</head>
<body>
    <div class="container">
';

$uri = getenv('MONGO_URI') ?: 'mongodb://db:27017';
try {
    $manager = new MongoDB\Driver\Manager($uri);

    echo "<h2 class='success'><span class='icon'>✅</span>Conexión a MongoDB exitosa</h2>";

    $query = new MongoDB\Driver\Query([]);
    $cursor = $manager->executeQuery('DBProyecto.usuarios', $query);

    echo "<h3>Usuarios:</h3><ul>";
    foreach ($cursor as $doc) {
        echo "<li>" . htmlspecialchars($doc->nombre) . "</li>";
    }
    echo "</ul>";

} catch (Exception $e) {
    echo "<h2 class='error'><span class='icon'>❌</span>Error de conexión</h2>";
    echo "<div class='error-message'>" . htmlspecialchars($e->getMessage()) . "</div>";
}

echo '
    </div>
</body>
</html>
';
?>