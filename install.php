<?php
require_once 'config.php';

$message = '';
$success = false;

if ($_POST && isset($_POST['install'])) {
    $host = $_POST['db_host'];
    $dbname = $_POST['db_name'];
    $username = $_POST['db_user'];
    $password = $_POST['db_pass'];
    
    try {
        $pdo = new PDO("mysql:host=$host", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE $dbname");
        $sql = file_get_contents('database.sql');
        $pdo->exec($sql);
        $configContent = "<?php
define('DB_HOST', '$host');
define('DB_NAME', '$dbname');
define('DB_USER', '$username');
define('DB_PASS', '$password');

define('APP_NAME', 'Registro Ágil');
define('APP_VERSION', '1.0.0');

define('SESSION_LIFETIME', 3600);
define('PASSWORD_MIN_LENGTH', 6);

date_default_timezone_set('America/Sao_Paulo');

error_reporting(E_ALL);
ini_set('display_errors', 1);
?>";
        
        file_put_contents('config.php', $configContent);
        
        $success = true;
        $message = "Sistema instalado com sucesso!";
        
    } catch (Exception $e) {
        $message = "Erro na instalação: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalação - Registro Ágil</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { 
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #e9ecef;
        }
        .install-container { 
            background: #2d2d2d; 
            padding: 40px; 
            border-radius: 15px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            width: 100%;
            max-width: 500px;
            border: 1px solid #404040;
        }
        h1 { 
            text-align: center; 
            color: #e9ecef; 
            margin-bottom: 30px;
            font-size: 2em;
        }
        .form-group { 
            margin-bottom: 20px; 
        }
        label { 
            display: block; 
            margin-bottom: 8px; 
            font-weight: bold; 
            color: #adb5bd; 
        }
        input { 
            width: 100%; 
            padding: 15px; 
            border: 2px solid transparent; 
            border-radius: 8px; 
            font-size: 16px; 
            background-color: #3d3d3d;
            color: #e9ecef;
            transition: border-color 0.3s;
        }
        input:focus { 
            outline: none; 
            border-color: transparent; 
            box-shadow: 0 0 0 2px rgba(0, 102, 204, 0.3);
        }
        button { 
            width: 100%; 
            padding: 15px; 
            background: linear-gradient(135deg, #0066cc 0%, #004499 100%);
            color: white; 
            border: none; 
            border-radius: 8px; 
            font-size: 16px; 
            cursor: pointer; 
            margin-top: 20px;
            transition: transform 0.2s;
        }
        button:hover { 
            transform: translateY(-2px);
        }
        .message { 
            padding: 15px; 
            margin-bottom: 20px; 
            border-radius: 8px; 
            text-align: center; 
        }
        .success { 
            background: #d4edda; 
            color: #155724; 
            border: 1px solid #c3e6cb; 
        }
        .error { 
            background: #f8d7da; 
            color: #721c24; 
            border: 1px solid #f5c6cb; 
        }
        .requirements {
            background: #3d3d3d;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #404040;
        }
        .requirements h3 {
            margin-bottom: 15px;
            color: #e9ecef;
        }
        .requirements ul {
            list-style: none;
            padding: 0;
        }
        .requirements li {
            padding: 5px 0;
            color: #adb5bd;
        }
        .requirements li:before {
            content: "✓ ";
            color: #28a745;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="install-container">
        <h1>🚀 Instalação - Registro Ágil</h1>
        
        <?php if ($message): ?>
            <div class="message <?php echo $success ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($message); ?>
                <?php if ($success): ?>
                    <br><br>
                    <strong>Credenciais do Administrador:</strong><br>
                    Email: elinaldo.oliveira@outlook.com<br>
                    Senha: admin12345
                    <br><br>
                    <a href="login.php" style="color: #155724; font-weight: bold;">Clique aqui para fazer login</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!$success): ?>
            <div class="requirements">
                <h3>📋 Requisitos do Sistema</h3>
                <ul>
                    <li>PHP 7.4 ou superior</li>
                    <li>MySQL 5.7 ou superior</li>
                    <li>Extensão PDO habilitada</li>
                    <li>Extensão cURL habilitada</li>
                </ul>
            </div>
            
            <form method="POST">
                <input type="hidden" name="install" value="1">
                
                <div class="form-group">
                    <label for="db_host">Host do Banco:</label>
                    <input type="text" id="db_host" name="db_host" required value="localhost">
                </div>
                
                <div class="form-group">
                    <label for="db_name">Nome do Banco:</label>
                    <input type="text" id="db_name" name="db_name" required value="registro_agil">
                </div>
                
                <div class="form-group">
                    <label for="db_user">Usuário do Banco:</label>
                    <input type="text" id="db_user" name="db_user" required value="root">
                </div>
                
                <div class="form-group">
                    <label for="db_pass">Senha do Banco:</label>
                    <input type="password" id="db_pass" name="db_pass">
                </div>
                
                <button type="submit">Instalar Sistema</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>