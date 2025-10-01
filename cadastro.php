<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$message = '';
$success = false;
$showLink = false;

require_once 'config.php';
require_once 'logica_cep.php';
if ($_POST && isset($_POST['nome_completo'])) {
    $nome_completo = trim($_POST['nome_completo']);
    $email = trim($_POST['email']);
    $cpf = trim($_POST['cpf']);
    $telefone = trim($_POST['telefone']);
    $cep = trim($_POST['cep']);
    $logradouro = trim($_POST['logradouro']);
    $bairro = trim($_POST['bairro']);
    $localidade = trim($_POST['localidade']);
    $uf = trim($_POST['uf']);
    $complemento = trim($_POST['complemento']);
    $numero = trim($_POST['numero']);
    $senha = $_POST['senha'];
    
    try {
        if (empty($nome_completo) || empty($email) || empty($cpf) || empty($senha)) {
            throw new Exception("Todos os campos obrigatórios devem ser preenchidos!");
        }
        
        if (strlen($nome_completo) < 3) {
            throw new Exception("Nome deve ter pelo menos 3 caracteres!");
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email inválido!");
        }
        
        if (!validarCPF($cpf)) {
            throw new Exception("CPF inválido!");
        }
        
        if (strlen($senha) < 6) {
            throw new Exception("A senha deve ter pelo menos 6 caracteres!");
        }
        
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT id FROM usuarios WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            throw new Exception("Email já cadastrado!");
        }
        
        $sql = "SELECT id FROM usuarios WHERE cpf = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$cpf]);
        
        if ($stmt->fetch()) {
            throw new Exception("CPF já cadastrado!");
        }
        
        $pdo->beginTransaction();
        
        try {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            
            $sql = "INSERT INTO usuarios (nome_completo, email, cpf, telefone, cep, logradouro, bairro, localidade, uf, complemento, numero, senha, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'user')";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nome_completo, $email, $cpf, $telefone, $cep, $logradouro, $bairro, $localidade, $uf, $complemento, $numero, $senha_hash]);
            
            $pdo->commit();
            
            $success = true;
            $message = "✅ Cadastro realizado com sucesso!";
            $showLink = true;
            
        } catch (Exception $e) {
            $pdo->rollback();
            throw $e;
        }
        
    } catch (PDOException $e) {
        $message = "❌ Erro de conexão com o banco de dados. Tente novamente mais tarde.";
        error_log("Erro de banco no cadastro: " . $e->getMessage());
    } catch (Exception $e) {
        $message = "❌ " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Usuário - Registro Ágil</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { 
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            min-height: 100vh;
            padding: 20px;
            color: #e9ecef;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #2d2d2d;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            overflow: hidden;
            border: 1px solid #404040;
        }
        .header {
            background: linear-gradient(135deg, #0066cc 0%, #004499 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            font-size: 2em;
            margin-bottom: 5px;
        }
        .content {
            padding: 25px;
        }
        .form-row {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }
        .form-group {
            flex: 1;
            margin-bottom: 15px;
        }
        .form-group.full {
            flex: 100%;
        }
        .back-link { 
            display: inline-block; 
            margin-bottom: 20px; 
            padding: 10px 20px; 
            background: #0066cc; 
            color: white; 
            text-decoration: none; 
            border-radius: 5px; 
        }
        .back-link:hover { 
            background: #0056b3; 
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #adb5bd;
        }
        input, select {
            width: 100%;
            padding: 15px;
            border: 2px solid transparent;
            border-radius: 8px;
            font-size: 16px;
            background-color: #3d3d3d;
            color: #e9ecef;
            transition: border-color 0.3s;
        }
        input:focus, select:focus {
            outline: none;
            border-color: transparent;
            box-shadow: 0 0 0 2px rgba(0, 102, 204, 0.3);
        }
        .cep-group {
            display: flex;
            gap: 10px;
        }
        .cep-group input:first-child {
            flex: 1;
        }
        .cep-group button {
            padding: 15px 20px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
        }
        .cep-group button:hover {
            background: #218838;
        }
        button[type="submit"] {
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
        button[type="submit"]:hover {
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
        .links {
            text-align: center;
            margin-top: 20px;
        }
        .links a {
            color: #0066cc;
            text-decoration: none;
            margin: 0 10px;
            font-weight: bold;
        }
        .links a:hover {
            text-decoration: underline;
        }
        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
                gap: 0;
            }
            .content {
                padding: 20px;
            }
            .header {
                padding: 20px;
            }
            .header h1 {
                font-size: 1.8em;
            }
            input, select {
                font-size: 16px;
                min-height: 44px;
            }
            button {
                font-size: 16px;
                min-height: 44px;
            }
            
            /* Layout responsivo para o header */
            .container > div:first-child {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .container > div:first-child h1 {
                margin-bottom: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1 style="margin: 0; line-height: 1;">👤 Cadastrar Usuário</h1>
            <a href="dashboard.php" class="back-link" style="margin-bottom: 0;">← Voltar ao Dashboard</a>
        </div>
        
        <div class="content">
            <?php if ($message): ?>
                <div class="message <?php echo $success ? 'success' : 'error'; ?>">
                    <?php echo htmlspecialchars($message); ?>
                    <?php if ($showLink): ?>
                        <br><br>
                        <a href="editar_usuarios.php" style="color: #28a745; font-weight: bold; text-decoration: none; background: rgba(40, 167, 69, 0.1); padding: 8px 15px; border-radius: 5px; display: inline-block; margin-top: 10px;">
                            👥 Gerenciar Usuários
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-row">
                    <div class="form-group full">
                        <label for="nome_completo">Nome Completo:</label>
                        <input type="text" id="nome_completo" name="nome_completo" required
                               value="<?php echo htmlspecialchars($_POST['nome_completo'] ?? ''); ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required
                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="cpf">CPF:</label>
                        <input type="text" id="cpf" name="cpf" required placeholder="000.000.000-00"
                               value="<?php echo htmlspecialchars($_POST['cpf'] ?? ''); ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="telefone">Telefone:</label>
                        <input type="text" id="telefone" name="telefone" required placeholder="(00) 00000-0000"
                               value="<?php echo htmlspecialchars($_POST['telefone'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="senha">Senha:</label>
                        <input type="password" id="senha" name="senha" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="cep">CEP:</label>
                        <div class="cep-group">
                            <input type="text" id="cep" name="cep" required placeholder="00000-000"
                                   value="<?php echo htmlspecialchars($_POST['cep'] ?? ''); ?>">
                            <button type="button" onclick="buscarCEP()">Buscar</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="numero">Número:</label>
                        <input type="text" id="numero" name="numero" required
                               value="<?php echo htmlspecialchars($_POST['numero'] ?? ''); ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="logradouro">Logradouro:</label>
                        <input type="text" id="logradouro" name="logradouro" required
                               value="<?php echo htmlspecialchars($_POST['logradouro'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="bairro">Bairro:</label>
                        <input type="text" id="bairro" name="bairro" required
                               value="<?php echo htmlspecialchars($_POST['bairro'] ?? ''); ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="localidade">Cidade:</label>
                        <input type="text" id="localidade" name="localidade" required
                               value="<?php echo htmlspecialchars($_POST['localidade'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="uf">UF:</label>
                        <select id="uf" name="uf" required>
                            <option value="">Selecione</option>
                            <option value="AC" <?php echo ($_POST['uf'] ?? '') == 'AC' ? 'selected' : ''; ?>>AC</option>
                            <option value="AL" <?php echo ($_POST['uf'] ?? '') == 'AL' ? 'selected' : ''; ?>>AL</option>
                            <option value="AP" <?php echo ($_POST['uf'] ?? '') == 'AP' ? 'selected' : ''; ?>>AP</option>
                            <option value="AM" <?php echo ($_POST['uf'] ?? '') == 'AM' ? 'selected' : ''; ?>>AM</option>
                            <option value="BA" <?php echo ($_POST['uf'] ?? '') == 'BA' ? 'selected' : ''; ?>>BA</option>
                            <option value="CE" <?php echo ($_POST['uf'] ?? '') == 'CE' ? 'selected' : ''; ?>>CE</option>
                            <option value="DF" <?php echo ($_POST['uf'] ?? '') == 'DF' ? 'selected' : ''; ?>>DF</option>
                            <option value="ES" <?php echo ($_POST['uf'] ?? '') == 'ES' ? 'selected' : ''; ?>>ES</option>
                            <option value="GO" <?php echo ($_POST['uf'] ?? '') == 'GO' ? 'selected' : ''; ?>>GO</option>
                            <option value="MA" <?php echo ($_POST['uf'] ?? '') == 'MA' ? 'selected' : ''; ?>>MA</option>
                            <option value="MT" <?php echo ($_POST['uf'] ?? '') == 'MT' ? 'selected' : ''; ?>>MT</option>
                            <option value="MS" <?php echo ($_POST['uf'] ?? '') == 'MS' ? 'selected' : ''; ?>>MS</option>
                            <option value="MG" <?php echo ($_POST['uf'] ?? '') == 'MG' ? 'selected' : ''; ?>>MG</option>
                            <option value="PA" <?php echo ($_POST['uf'] ?? '') == 'PA' ? 'selected' : ''; ?>>PA</option>
                            <option value="PB" <?php echo ($_POST['uf'] ?? '') == 'PB' ? 'selected' : ''; ?>>PB</option>
                            <option value="PR" <?php echo ($_POST['uf'] ?? '') == 'PR' ? 'selected' : ''; ?>>PR</option>
                            <option value="PE" <?php echo ($_POST['uf'] ?? '') == 'PE' ? 'selected' : ''; ?>>PE</option>
                            <option value="PI" <?php echo ($_POST['uf'] ?? '') == 'PI' ? 'selected' : ''; ?>>PI</option>
                            <option value="RJ" <?php echo ($_POST['uf'] ?? '') == 'RJ' ? 'selected' : ''; ?>>RJ</option>
                            <option value="RN" <?php echo ($_POST['uf'] ?? '') == 'RN' ? 'selected' : ''; ?>>RN</option>
                            <option value="RS" <?php echo ($_POST['uf'] ?? '') == 'RS' ? 'selected' : ''; ?>>RS</option>
                            <option value="RO" <?php echo ($_POST['uf'] ?? '') == 'RO' ? 'selected' : ''; ?>>RO</option>
                            <option value="RR" <?php echo ($_POST['uf'] ?? '') == 'RR' ? 'selected' : ''; ?>>RR</option>
                            <option value="SC" <?php echo ($_POST['uf'] ?? '') == 'SC' ? 'selected' : ''; ?>>SC</option>
                            <option value="SP" <?php echo ($_POST['uf'] ?? '') == 'SP' ? 'selected' : ''; ?>>SP</option>
                            <option value="SE" <?php echo ($_POST['uf'] ?? '') == 'SE' ? 'selected' : ''; ?>>SE</option>
                            <option value="TO" <?php echo ($_POST['uf'] ?? '') == 'TO' ? 'selected' : ''; ?>>TO</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group full">
                        <label for="complemento">Complemento:</label>
                        <input type="text" id="complemento" name="complemento"
                               value="<?php echo htmlspecialchars($_POST['complemento'] ?? ''); ?>">
                    </div>
                </div>
                
                <button type="submit">Cadastrar</button>
            </form>
            
        </div>
    </div>

    <script>
        function buscarCEP() {
            const cep = document.getElementById('cep').value.replace(/\D/g, '');
            
            if (cep.length !== 8) {
                alert('CEP deve ter 8 dígitos');
                return;
            }
            
            const button = event.target;
            button.textContent = 'Buscando...';
            button.disabled = true;
            
            fetch('logica_cep.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: 'cep=' + cep
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('logradouro').value = data.logradouro || '';
                    document.getElementById('bairro').value = data.bairro || '';
                    document.getElementById('localidade').value = data.localidade || '';
                    document.getElementById('uf').value = data.uf || '';
                } else {
                    alert('CEP não encontrado: ' + (data.message || 'Erro desconhecido'));
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao buscar CEP');
            })
            .finally(() => {
                button.textContent = 'Buscar';
                button.disabled = false;
            });
        }
        
        document.getElementById('cpf').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            e.target.value = value;
        });
        
        document.getElementById('telefone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{2})(\d)/, '($1) $2');
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
            e.target.value = value;
        });
        
        // Máscara para CEP
        document.getElementById('cep').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
            e.target.value = value;
        });
    </script>
</body>
</html>