<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DB Browser - Conexión MySQL</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background-color: #667eea;
            color: white;
            font-weight: 600;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #5568d3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 DB Browser - Conexión MySQL</h1>
        
        <?php
        // Configuración de conexión
        $host = getenv('MYSQL_HOST') ?: 'mysql';
        $database = getenv('MYSQL_DATABASE') ?: 'db_browser';
        $username = getenv('MYSQL_USER') ?: 'dbuser';
        $password = getenv('MYSQL_PASSWORD') ?: 'dbpassword';
        
        try {
            // Intentar conexión
            $pdo = new PDO(
                "mysql:host=$host;dbname=$database;charset=utf8mb4",
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
            
            echo '<div class="status success">';
            echo '✅ <strong>Conexión exitosa a MySQL!</strong><br>';
            echo 'Host: ' . htmlspecialchars($host) . '<br>';
            echo 'Base de datos: ' . htmlspecialchars($database) . '<br>';
            echo 'Usuario: ' . htmlspecialchars($username);
            echo '</div>';
            
            // Obtener información de la versión de MySQL
            $stmt = $pdo->query("SELECT VERSION() as version");
            $version = $stmt->fetch();
            
            echo '<div class="status info">';
            echo '<strong>Información del servidor:</strong><br>';
            echo 'Versión MySQL: ' . htmlspecialchars($version['version']) . '<br>';
            echo 'Fecha/Hora del servidor: ' . date('Y-m-d H:i:s');
            echo '</div>';
            
            // Listar todas las tablas
            $stmt = $pdo->query("SHOW TABLES");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            if (count($tables) > 0) {
                echo '<h2>Tablas en la base de datos:</h2>';
                echo '<table>';
                echo '<tr><th>Nombre de la tabla</th><th>Filas</th></tr>';
                
                foreach ($tables as $table) {
                    $stmt = $pdo->query("SELECT COUNT(*) as count FROM `$table`");
                    $count = $stmt->fetch();
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($table) . '</td>';
                    echo '<td>' . htmlspecialchars($count['count']) . '</td>';
                    echo '</tr>';
                }
                
                echo '</table>';
            } else {
                echo '<div class="status info">';
                echo 'No hay tablas en la base de datos. Puedes crear tablas usando SQL.';
                echo '</div>';
            }
            
        } catch (PDOException $e) {
            echo '<div class="status error">';
            echo '❌ <strong>Error de conexión:</strong><br>';
            echo htmlspecialchars($e->getMessage());
            echo '</div>';
        }
        ?>
        
        <div style="margin-top: 30px;">
            <h2>Información del entorno PHP:</h2>
            <div class="status info">
                <strong>Versión PHP:</strong> <?php echo phpversion(); ?><br>
                <strong>Extensiones disponibles:</strong> 
                <?php 
                $extensions = get_loaded_extensions();
                echo implode(', ', array_filter($extensions, function($ext) {
                    return in_array($ext, ['pdo', 'pdo_mysql', 'mysqli', 'mbstring']);
                }));
                ?>
            </div>
        </div>
        
        <div style="margin-top: 20px; text-align: center;">
            <a href="mongodb-test.php" class="btn" style="background-color: #11998e;">🍃 Probar MongoDB</a>
            <a href="adminer.php" class="btn">🗂️ Abrir Adminer</a>
        </div>
    </div>
</body>
</html>


