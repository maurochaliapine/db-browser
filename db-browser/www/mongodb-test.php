<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DB Browser - Conexión MongoDB</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            padding: 30px;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        .status {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #11998e;
            color: white;
            font-weight: 600;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #11998e;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            margin-right: 10px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #0d7a6f;
        }
        pre {
            background-color: #f4f4f4;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🍃 DB Browser - Conexión MongoDB</h1>
        
        <?php
        // Nota: MongoDB requiere la extensión mongodb de PHP
        // Esta página muestra información sobre la conexión
        
        $host = getenv('MONGODB_HOST') ?: 'mongodb';
        $database = getenv('MONGODB_DATABASE') ?: 'db_browser';
        $username = getenv('MONGODB_ROOT_USER') ?: 'admin';
        $password = getenv('MONGODB_ROOT_PASSWORD') ?: 'adminpassword';
        
        // Verificar si la extensión MongoDB está instalada
        if (!extension_loaded('mongodb')) {
            echo '<div class="status error">';
            echo '❌ <strong>Extensión MongoDB no instalada</strong><br>';
            echo 'Para usar MongoDB desde PHP, necesitas instalar la extensión mongodb.<br>';
            echo 'Por ahora, puedes usar Mongo Express (http://localhost:8081) para administrar MongoDB.';
            echo '</div>';
            
            echo '<div class="status info">';
            echo '<strong>Información de conexión:</strong><br>';
            echo 'Host: ' . htmlspecialchars($host) . '<br>';
            echo 'Base de datos: ' . htmlspecialchars($database) . '<br>';
            echo 'Usuario: ' . htmlspecialchars($username) . '<br>';
            echo 'Puerto: 27017';
            echo '</div>';
        } else {
            try {
                // Intentar conexión con MongoDB
                $connectionString = "mongodb://{$username}:{$password}@{$host}:27017/{$database}?authSource=admin";
                $client = new MongoDB\Client($connectionString);
                
                // Seleccionar la base de datos
                $db = $client->selectDatabase($database);
                
                echo '<div class="status success">';
                echo '✅ <strong>Conexión exitosa a MongoDB!</strong><br>';
                echo 'Host: ' . htmlspecialchars($host) . '<br>';
                echo 'Base de datos: ' . htmlspecialchars($database) . '<br>';
                echo 'Usuario: ' . htmlspecialchars($username);
                echo '</div>';
                
                // Listar colecciones
                $collections = $db->listCollections();
                $collectionNames = [];
                foreach ($collections as $collection) {
                    $collectionNames[] = $collection->getName();
                }
                
                if (count($collectionNames) > 0) {
                    echo '<h2>Colecciones en la base de datos:</h2>';
                    echo '<table>';
                    echo '<tr><th>Nombre de la colección</th><th>Documentos</th></tr>';
                    
                    foreach ($collectionNames as $collectionName) {
                        $collection = $db->selectCollection($collectionName);
                        $count = $collection->countDocuments();
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($collectionName) . '</td>';
                        echo '<td>' . htmlspecialchars($count) . '</td>';
                        echo '</tr>';
                    }
                    
                    echo '</table>';
                } else {
                    echo '<div class="status info">';
                    echo 'No hay colecciones en la base de datos.';
                    echo '</div>';
                }
                
            } catch (Exception $e) {
                echo '<div class="status error">';
                echo '❌ <strong>Error de conexión:</strong><br>';
                echo htmlspecialchars($e->getMessage());
                echo '</div>';
            }
        }
        ?>
        
        <div style="margin-top: 30px;">
            <h2>🔗 Acceso a MongoDB</h2>
            <div class="status info">
                <strong>Mongo Express (Administrador Web):</strong><br>
                URL: <a href="http://localhost:8081" target="_blank">http://localhost:8081</a><br>
                Usuario: admin (o el definido en .env)<br>
                Contraseña: admin (o la definida en .env)<br><br>
                
                <strong>Conexión directa:</strong><br>
                Host: localhost<br>
                Puerto: 27017<br>
                Usuario: admin<br>
                Contraseña: adminpassword
            </div>
        </div>
        
        <div style="margin-top: 20px;">
            <a href="index.php" class="btn">← Volver a MySQL</a>
            <a href="http://localhost:8081" target="_blank" class="btn">Abrir Mongo Express →</a>
        </div>
    </div>
</body>
</html>


