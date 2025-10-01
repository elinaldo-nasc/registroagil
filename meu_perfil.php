<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

require_once 'logica_cep.php';

$host = 'localhost';
$dbname = 'registro_agil';
$username = 'root';
$password = '';

$message = '';

// Verificar mensagem de sucesso
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'atualizado') {
        $message = "✅ Perfil atualizado com sucesso!";
    }
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Buscar dados do usuário logado
    $sql = "SELECT * FROM usuarios WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_SESSION['user_id']]);
    $usuario = $stmt->fetch();
    
    if (!$usuario) {
        $message = "❌ Usuário não encontrado!";
        header('Location: index.php');
        exit();
    }
    
    if ($_POST && isset($_POST['action']) && $_POST['action'] === 'edit') {
        $nome = trim($_POST['nome']);
        $email = trim($_POST['email']);
        $cpf = trim($_POST['cpf']);
        $telefone = trim($_POST['telefone']);
        $cep = trim($_POST['cep']);
        $numero = trim($_POST['numero']);
        $complemento = trim($_POST['complemento']);
        
        // Validar se o email não está sendo usado por outro usuário
        $sql = "SELECT id FROM usuarios WHERE email = ? AND id != ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email, $_SESSION['user_id']]);
        if ($stmt->fetch()) {
            $message = "❌ Este email já está sendo usado por outro usuário!";
        } else {
            // Validar se o CPF não está sendo usado por outro usuário
            $sql = "SELECT id FROM usuarios WHERE cpf = ? AND id != ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$cpf, $_SESSION['user_id']]);
            if ($stmt->fetch()) {
                $message = "❌ Este CPF já está sendo usado por outro usuário!";
            } else {
$_POST['cep'] = $cep;
$endereco = obterEndereco();
                
                if (!isset($endereco->erro) && $endereco->cep !== 'CEP não encontrado' && $endereco->cep !== 'CEP Inválido') {
                    $sql = "UPDATE usuarios SET 
                            nome_completo = ?, email = ?, cpf = ?, telefone = ?, 
                            cep = ?, logradouro = ?, bairro = ?, localidade = ?, 
                            uf = ?, numero = ?, complemento = ?, updated_at = NOW()
                            WHERE id = ?";
                    
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        $nome, $email, $cpf, $telefone, $cep,
                        $endereco->logradouro, $endereco->bairro, $endereco->localidade, $endereco->uf,
                        $numero, $complemento, $_SESSION['user_id']
                    ]);
                    
                    // Atualiza dados da sessão
                    $_SESSION['user_name'] = $nome;
                    $_SESSION['user_email'] = $email;
                    
                    $message = "✅ Perfil atualizado com sucesso!";
                    // Redireciona para atualizar os dados
                    header("Location: meu_perfil.php?msg=atualizado");
                    exit();
                } else {
                    $message = "❌ CEP inválido!";
                }
            }
        }
    }
    
} catch (PDOException $e) {
    $message = "❌ Erro de conexão: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - Registro Ágil</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { 
            background: #1a1a1a; 
            padding: 20px; 
            color: #e9ecef;
        }
        .container { 
            max-width: 800px; 
            margin: 0 auto; 
            background: #2d2d2d; 
            padding: 30px; 
            border-radius: 10px; 
            border: 1px solid #404040; 
        }
        h1 { 
            text-align: center; 
            color: #e9ecef; 
            margin-bottom: 30px; 
        }
        .back-link { 
            display: inline-block; 
            margin-bottom: 20px; 
            padding: 10px 20px; 
            background: linear-gradient(135deg, #0066cc 0%, #004499 100%); 
            color: white; 
            text-decoration: none; 
            border-radius: 5px; 
        }
        .back-link:hover { 
            background: linear-gradient(135deg, #004499 0%, #003366 100%); 
        }
        .message { 
            padding: 15px; 
            margin-bottom: 20px; 
            border-radius: 5px; 
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
        .form-group { 
            margin-bottom: 15px; 
        }
        .form-group label { 
            display: block; 
            margin-bottom: 5px; 
            font-weight: bold; 
            color: #adb5bd;
        }
        .form-group input { 
            width: 100%; 
            padding: 10px; 
            border: 1px solid transparent; 
            border-radius: 5px; 
            background-color: #3d3d3d;
            color: #e9ecef;
            box-sizing: border-box;
        }
        .form-group input:focus {
            outline: none;
            border-color: #0066cc;
        }
        .form-row { 
            display: grid; 
            grid-template-columns: 1fr 1fr; 
            gap: 15px; 
        }
        .btn { 
            padding: 12px 25px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            text-decoration: none;
            display: inline-block;
            margin: 5px;
            font-size: 16px;
        }
        .btn-primary { 
            background: linear-gradient(135deg, #0066cc 0%, #004499 100%); 
            color: white; 
        }
        .btn-primary:hover { 
            background: linear-gradient(135deg, #004499 0%, #003366 100%); 
        }
        .btn-secondary { 
            background: #6c757d; 
            color: white; 
        }
        .btn-secondary:hover { 
            background: #545b62; 
        }
        .user-info {
            background: #3d3d3d;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #404040;
        }
        .user-info h3 {
            color: #0066cc;
            margin-bottom: 15px;
        }
        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #404040;
        }
        .info-item:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: bold;
            color: #adb5bd;
        }
        .info-value {
            color: #e9ecef;
        }
        @media (max-width: 768px) {
            .form-row { 
                grid-template-columns: 1fr; 
            }
            .info-item {
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>👤 Meu Perfil</h1>
        
        <div style="margin-bottom: 20px; text-align: right;">
            <a href="dashboard.php" class="back-link">← Voltar ao Dashboard</a>
        </div>
        
        <?php if ($message): ?>
            <div class="message <?php echo strpos($message, 'sucesso') !== false ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <!-- Informações sobre usuário -->
        <div class="user-info">
            <h3>📋 Informações Atuais</h3>
            <div class="info-item">
                <span class="info-label">ID:</span>
                <span class="info-value"><?php echo $usuario['id']; ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Nome:</span>
                <span class="info-value"><?php echo htmlspecialchars($usuario['nome_completo']); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Email:</span>
                <span class="info-value"><?php echo htmlspecialchars($usuario['email']); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">CPF:</span>
                <span class="info-value"><?php echo htmlspecialchars($usuario['cpf']); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Telefone:</span>
                <span class="info-value"><?php echo htmlspecialchars($usuario['telefone']); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Endereço:</span>
                <span class="info-value"><?php echo htmlspecialchars($usuario['logradouro'] . ', ' . $usuario['numero'] . ' - ' . $usuario['bairro'] . ', ' . $usuario['localidade'] . '/' . $usuario['uf']); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Data de Cadastro:</span>
                <span class="info-value"><?php echo date('d/m/Y H:i', strtotime($usuario['created_at'])); ?></span>
            </div>
        </div>
        
        <!-- Formulário de edição -->
        <form method="POST" style="background: #3d3d3d; padding: 25px; border-radius: 8px; border: 1px solid #404040;">
            <input type="hidden" name="action" value="edit">
            
            <h3 style="color: #0066cc; margin-bottom: 20px;">✏️ Editar Perfil</h3>
            
            <div class="form-group">
                <label for="nome">Nome Completo:</label>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome_completo']); ?>" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="cpf">CPF:</label>
                    <input type="text" id="cpf" name="cpf" value="<?php echo htmlspecialchars($usuario['cpf']); ?>" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="telefone">Telefone:</label>
                <input type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($usuario['telefone']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="cep">CEP:</label>
                <div style="display: flex; gap: 10px; align-items: center;">
                    <input type="text" id="cep" name="cep" value="<?php echo htmlspecialchars($usuario['cep']); ?>" required style="flex: 1;">
                    <button type="button" onclick="buscarCepManual()" class="btn btn-secondary" style="margin: 0;">Buscar</button>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="logradouro">Logradouro:</label>
                    <input type="text" id="logradouro" name="logradouro" value="<?php echo htmlspecialchars($usuario['logradouro']); ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="numero">Número:</label>
                    <input type="text" id="numero" name="numero" value="<?php echo htmlspecialchars($usuario['numero']); ?>" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="bairro">Bairro:</label>
                    <input type="text" id="bairro" name="bairro" value="<?php echo htmlspecialchars($usuario['bairro']); ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="localidade">Cidade:</label>
                    <input type="text" id="localidade" name="localidade" value="<?php echo htmlspecialchars($usuario['localidade']); ?>" readonly>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="uf">UF:</label>
                    <input type="text" id="uf" name="uf" value="<?php echo htmlspecialchars($usuario['uf']); ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="complemento">Complemento:</label>
                    <input type="text" id="complemento" name="complemento" value="<?php echo htmlspecialchars($usuario['complemento']); ?>">
                </div>
            </div>
            
            <div style="text-align: center; margin-top: 25px;">
                <button type="submit" class="btn btn-primary">💾 Salvar Alterações</button>
                <button type="button" class="btn btn-secondary" onclick="window.location.href='dashboard.php'">❌ Cancelar</button>
            </div>
        </form>
    </div>
    
    <script>
        function buscarCepManual() {
            const cep = document.getElementById('cep').value.replace(/\D/g, '');
            if (cep.length === 8) {
                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    if (data && !data.erro) {
                        document.getElementById('logradouro').value = data.logradouro || '';
                        document.getElementById('bairro').value = data.bairro || '';
                        document.getElementById('localidade').value = data.localidade || '';
                        document.getElementById('uf').value = data.uf || '';
                    } else {
                        alert('❌ CEP não encontrado');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('❌ Erro ao buscar CEP');
                });
            } else {
                alert('❌ Digite um CEP válido (8 dígitos)');
            }
        }
    </script>
</body>
</html>