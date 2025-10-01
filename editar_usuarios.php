<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

require_once 'logica_cep.php';

$host = 'localhost';
$dbname = 'registro_agil';
$username = 'root';
$password = '';

$message = '';
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'excluido') {
        $message = "✅ Usuário excluído com sucesso!";
    } elseif ($_GET['msg'] === 'atualizado') {
        $message = "✅ Usuário atualizado com sucesso!";
    }
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $search = $_GET['search'] ?? '';
    $filter_date = $_GET['filter_date'] ?? '';
    $filter_uf = $_GET['filter_uf'] ?? '';
    $order_by = $_GET['order_by'] ?? 'id';
    $order_dir = $_GET['order_dir'] ?? 'ASC';
    $allowed_columns = ['id', 'nome_completo', 'email', 'cpf', 'telefone', 'uf', 'created_at'];
    $allowed_directions = ['ASC', 'DESC'];
    
    if (!in_array($order_by, $allowed_columns)) {
        $order_by = 'id';
    }
    if (!in_array(strtoupper($order_dir), $allowed_directions)) {
        $order_dir = 'DESC';
    }
    
    if ($_POST && isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action === 'delete' && isset($_POST['id'])) {
            $id = (int)$_POST['id'];
            if ($id === (int)$_SESSION['user_id']) {
                $message = "❌ Você não pode excluir seu próprio usuário!";
            } else {
                $sql = "DELETE FROM usuarios WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute([$id]);
                
                if ($stmt->rowCount() > 0) {
                    $message = "✅ Usuário excluído com sucesso!";
                    header("Location: editar_usuarios.php?msg=excluido");
                    exit();
                } else {
                    $message = "❌ Erro: Usuário não encontrado!";
                }
            }
        }
        
        if ($action === 'edit' && isset($_POST['id'])) {
            $id = $_POST['id'];
            $nome = trim($_POST['nome']);
            $email = trim($_POST['email']);
            $cpf = trim($_POST['cpf']);
            $telefone = trim($_POST['telefone']);
            $cep = trim($_POST['cep']);
            $numero = trim($_POST['numero']);
            $complemento = trim($_POST['complemento']);
            
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
                    $numero, $complemento, $id
                ]);
                
                $message = "✅ Usuário atualizado com sucesso!";
                header("Location: editar_usuarios.php?msg=atualizado");
                exit();
            } else {
                $message = "CEP inválido!";
            }
        }
    }
    $whereClause = "";
    $params = [];
    
    if (!empty($search)) {
        $whereClause = " WHERE (nome_completo LIKE ? OR email LIKE ? OR cpf LIKE ? OR id = ?)";
        $searchTerm = "%$search%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $search;
    }
    
    if (!empty($filter_date)) {
        $whereClause .= $whereClause ? " AND DATE(created_at) = ?" : " WHERE DATE(created_at) = ?";
        $params[] = $filter_date;
    }
    
    if (!empty($filter_uf)) {
        $whereClause .= $whereClause ? " AND uf = ?" : " WHERE uf = ?";
        $params[] = $filter_uf;
    }
    
    $sql = "SELECT * FROM usuarios $whereClause ORDER BY $order_by $order_dir";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $usuarios = $stmt->fetchAll();
    
} catch (PDOException $e) {
    $usuarios = [];
    $message = "Erro de conexão: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Usuários - Registro Ágil</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { 
            background: #1a1a1a; 
            padding: 20px; 
            color: #e9ecef;
        }
        .container { 
            max-width: 1200px; 
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
            background: #007bff; 
            color: white; 
            text-decoration: none; 
            border-radius: 5px; 
        }
        .back-link:hover { 
            background: #0056b3; 
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
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
            background: #3d3d3d;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #404040;
        }
        th, td { 
            padding: 12px; 
            text-align: left; 
            border-bottom: 1px solid #404040; 
            color: #e9ecef;
        }
        th { 
            background: linear-gradient(135deg, #0066cc 0%, #004499 100%); 
            font-weight: bold; 
            color: white;
        }
        tr:hover { 
            background: #404040; 
        }
        .btn { 
            padding: 8px 15px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            text-decoration: none;
            display: inline-block;
            margin: 2px;
        }
        .btn-edit { 
            background: #28a745; 
            color: white; 
        }
        .btn-delete { 
            background: #dc3545; 
            color: white; 
        }
        
        /* Modal de confirmação */
        .confirm-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
        }
        
        .confirm-modal-content {
            background-color: #2d2d2d;
            margin: 15% auto;
            padding: 30px;
            border: 1px solid #404040;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            text-align: center;
        }
        
        .confirm-modal h3 {
            color: #e9ecef;
            margin-bottom: 20px;
            font-size: 1.5em;
        }
        
        .confirm-modal p {
            color: #adb5bd;
            margin-bottom: 30px;
            font-size: 1.1em;
        }
        
        .confirm-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }
        
        .confirm-buttons .btn {
            min-width: 120px;
        }
        .modal { 
            display: none; 
            position: fixed; 
            top: 0; 
            left: 0; 
            width: 100%; 
            height: 100%; 
            background: rgba(0,0,0,0.5); 
            z-index: 1000; 
        }
        .modal-content { 
            background: #2d2d2d; 
            margin: 2% auto; 
            padding: 0; 
            width: 90%; 
            max-width: 600px; 
            max-height: 90vh;
            border-radius: 10px; 
            border: 1px solid #404040;
            display: flex;
            flex-direction: column;
            position: relative;
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
        }
        .form-row { 
            display: grid; 
            grid-template-columns: 1fr 1fr; 
            gap: 15px; 
        }
        
        /* Conteúdo do formulário com scroll */
        .modal-form-content {
            padding: 30px;
            overflow-y: auto;
            flex: 1;
        }
        
        /* Botões do modal sempre no fundo */
        .modal-buttons {
            background: #2d2d2d;
            padding: 20px 30px;
            border-top: 1px solid #404040;
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-shrink: 0;
        }
        
        /* Melhorar scroll do modal */
        .modal-form-content::-webkit-scrollbar {
            width: 8px;
        }
        
        .modal-form-content::-webkit-scrollbar-track {
            background: #3d3d3d;
            border-radius: 4px;
        }
        
        .modal-form-content::-webkit-scrollbar-thumb {
            background: #0066cc;
            border-radius: 4px;
        }
        
        .modal-form-content::-webkit-scrollbar-thumb:hover {
            background: #004499;
        }
        @media (max-width: 768px) {
            .form-row { 
                grid-template-columns: 1fr; 
            }
            table { 
                font-size: 14px; 
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
            <h1 style="margin: 0; line-height: 1;">✏️ Gerenciar Usuários</h1>
            <a href="dashboard.php" class="back-link" style="margin-bottom: 0;">← Voltar ao Dashboard</a>
        </div>
        
        <!-- Filtros e busca -->
        <div style="margin: 20px 0; background-color: #2d2d2d; padding: 20px; border-radius: 10px; border: 1px solid #404040;">
            <form method="GET" style="display: grid; gap: 15px; grid-template-columns: 2fr 1fr 1fr auto auto;">
                <input type="text" name="search" placeholder="🔍 Buscar por ID, nome, email ou CPF..." 
                       value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" 
                       style="padding: 10px; border: 1px solid transparent; border-radius: 5px; background-color: #3d3d3d; color: #e9ecef; font-size: 14px;">
                
                <input type="date" name="filter_date" placeholder="Filtrar por data"
                       value="<?php echo htmlspecialchars($_GET['filter_date'] ?? ''); ?>"
                       style="padding: 10px; border: 1px solid transparent; border-radius: 5px; background-color: #3d3d3d; color: #e9ecef; font-size: 14px;">
                
                <select name="filter_uf" style="padding: 10px; border: 1px solid transparent; border-radius: 5px; background-color: #3d3d3d; color: #e9ecef; font-size: 14px;">
                    <option value="">Todos os estados</option>
                    <option value="AC" <?php echo ($_GET['filter_uf'] ?? '') === 'AC' ? 'selected' : ''; ?>>Acre</option>
                    <option value="AL" <?php echo ($_GET['filter_uf'] ?? '') === 'AL' ? 'selected' : ''; ?>>Alagoas</option>
                    <option value="AP" <?php echo ($_GET['filter_uf'] ?? '') === 'AP' ? 'selected' : ''; ?>>Amapá</option>
                    <option value="AM" <?php echo ($_GET['filter_uf'] ?? '') === 'AM' ? 'selected' : ''; ?>>Amazonas</option>
                    <option value="BA" <?php echo ($_GET['filter_uf'] ?? '') === 'BA' ? 'selected' : ''; ?>>Bahia</option>
                    <option value="CE" <?php echo ($_GET['filter_uf'] ?? '') === 'CE' ? 'selected' : ''; ?>>Ceará</option>
                    <option value="DF" <?php echo ($_GET['filter_uf'] ?? '') === 'DF' ? 'selected' : ''; ?>>Distrito Federal</option>
                    <option value="ES" <?php echo ($_GET['filter_uf'] ?? '') === 'ES' ? 'selected' : ''; ?>>Espírito Santo</option>
                    <option value="GO" <?php echo ($_GET['filter_uf'] ?? '') === 'GO' ? 'selected' : ''; ?>>Goiás</option>
                    <option value="MA" <?php echo ($_GET['filter_uf'] ?? '') === 'MA' ? 'selected' : ''; ?>>Maranhão</option>
                    <option value="MT" <?php echo ($_GET['filter_uf'] ?? '') === 'MT' ? 'selected' : ''; ?>>Mato Grosso</option>
                    <option value="MS" <?php echo ($_GET['filter_uf'] ?? '') === 'MS' ? 'selected' : ''; ?>>Mato Grosso do Sul</option>
                    <option value="MG" <?php echo ($_GET['filter_uf'] ?? '') === 'MG' ? 'selected' : ''; ?>>Minas Gerais</option>
                    <option value="PA" <?php echo ($_GET['filter_uf'] ?? '') === 'PA' ? 'selected' : ''; ?>>Pará</option>
                    <option value="PB" <?php echo ($_GET['filter_uf'] ?? '') === 'PB' ? 'selected' : ''; ?>>Paraíba</option>
                    <option value="PR" <?php echo ($_GET['filter_uf'] ?? '') === 'PR' ? 'selected' : ''; ?>>Paraná</option>
                    <option value="PE" <?php echo ($_GET['filter_uf'] ?? '') === 'PE' ? 'selected' : ''; ?>>Pernambuco</option>
                    <option value="PI" <?php echo ($_GET['filter_uf'] ?? '') === 'PI' ? 'selected' : ''; ?>>Piauí</option>
                    <option value="RJ" <?php echo ($_GET['filter_uf'] ?? '') === 'RJ' ? 'selected' : ''; ?>>Rio de Janeiro</option>
                    <option value="RN" <?php echo ($_GET['filter_uf'] ?? '') === 'RN' ? 'selected' : ''; ?>>Rio Grande do Norte</option>
                    <option value="RS" <?php echo ($_GET['filter_uf'] ?? '') === 'RS' ? 'selected' : ''; ?>>Rio Grande do Sul</option>
                    <option value="RO" <?php echo ($_GET['filter_uf'] ?? '') === 'RO' ? 'selected' : ''; ?>>Rondônia</option>
                    <option value="RR" <?php echo ($_GET['filter_uf'] ?? '') === 'RR' ? 'selected' : ''; ?>>Roraima</option>
                    <option value="SC" <?php echo ($_GET['filter_uf'] ?? '') === 'SC' ? 'selected' : ''; ?>>Santa Catarina</option>
                    <option value="SP" <?php echo ($_GET['filter_uf'] ?? '') === 'SP' ? 'selected' : ''; ?>>São Paulo</option>
                    <option value="SE" <?php echo ($_GET['filter_uf'] ?? '') === 'SE' ? 'selected' : ''; ?>>Sergipe</option>
                    <option value="TO" <?php echo ($_GET['filter_uf'] ?? '') === 'TO' ? 'selected' : ''; ?>>Tocantins</option>
                </select>
                
                <button type="submit" style="padding: 10px 20px; background: linear-gradient(135deg, #0066cc 0%, #004499 100%); color: white; border: none; border-radius: 5px; cursor: pointer; white-space: nowrap;">🔍 Filtrar</button>
                
                <?php if (!empty($_GET['search']) || !empty($_GET['filter_date']) || !empty($_GET['filter_uf'])): ?>
                    <a href="editar_usuarios.php" style="padding: 10px 15px; background: #6c757d; color: white; text-decoration: none; border-radius: 5px; white-space: nowrap;">❌ Limpar</a>
                <?php endif; ?>
            </form>
        </div>
        
        <!-- Botões de Exportação -->
        <div style="margin: 20px 0; text-align: right;">
            <a href="exportar_usuarios.php?format=csv<?php echo !empty($_GET) ? '&' . http_build_query($_GET) : ''; ?>" 
               style="padding: 10px 20px; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; text-decoration: none; border-radius: 5px; margin-right: 10px;">
                📊 Exportar CSV
            </a>
            <a href="exportar_usuarios.php?format=pdf<?php echo !empty($_GET) ? '&' . http_build_query($_GET) : ''; ?>" 
               target="_blank"
               style="padding: 10px 20px; background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white; text-decoration: none; border-radius: 5px;">
                📄 Exportar PDF
            </a>
        </div>
        
        <?php if ($message): ?>
            <div class="message <?php echo strpos($message, 'sucesso') !== false ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <?php if (empty($usuarios)): ?>
            <p style="text-align: center; color: #666; margin-top: 50px;">
                Nenhum usuário cadastrado.
            </p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>CPF</th>
                        <th>Telefone</th>
                        <th>Cidade</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?php echo $usuario['id']; ?></td>
                        <td><?php echo htmlspecialchars($usuario['nome_completo']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['cpf']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['telefone']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['localidade'] . '/' . $usuario['uf']); ?></td>
                        <td>
                            <button class="btn btn-edit" onclick="editUser(<?php echo htmlspecialchars(json_encode($usuario)); ?>)">
                                Editar
                            </button>
                            <?php if ($usuario['id'] != $_SESSION['user_id']): ?>
                            <button class="btn btn-delete" onclick="deleteUser(<?php echo $usuario['id']; ?>)">
                                Excluir
                            </button>
                            <?php else: ?>
                            <span style="color: #6c757d; font-size: 0.9em;">Próprio usuário</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    
    <!-- Modal de Edição -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-form-content">
                <h2>Editar Usuário</h2>
                <form method="POST" id="editForm">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" id="editId">
                
                <div class="form-group">
                    <label for="editNome">Nome Completo:</label>
                    <input type="text" id="editNome" name="nome" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="editEmail">Email:</label>
                        <input type="email" id="editEmail" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="editCpf">CPF:</label>
                        <input type="text" id="editCpf" name="cpf" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="editTelefone">Telefone:</label>
                    <input type="text" id="editTelefone" name="telefone" required>
                </div>
                
                <div class="form-group">
                    <label for="editCep">CEP:</label>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <input type="text" id="editCep" name="cep" required style="flex: 1;">
                        <button type="button" onclick="buscarCepManual()" style="padding: 8px 15px; background: #0066cc; color: white; border: none; border-radius: 5px; cursor: pointer;">Buscar</button>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="editLogradouro">Logradouro:</label>
                        <input type="text" id="editLogradouro" name="logradouro">
                    </div>
                    <div class="form-group">
                        <label for="editNumero">Número:</label>
                        <input type="text" id="editNumero" name="numero" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="editBairro">Bairro:</label>
                        <input type="text" id="editBairro" name="bairro">
                    </div>
                    <div class="form-group">
                        <label for="editLocalidade">Cidade:</label>
                        <input type="text" id="editLocalidade" name="localidade">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="editUf">UF:</label>
                        <input type="text" id="editUf" name="uf" readonly>
                    </div>
                    <div class="form-group">
                        <label for="editComplemento">Complemento:</label>
                        <input type="text" id="editComplemento" name="complemento">
                    </div>
                </div>
                </form>
            </div>
            
            <div class="modal-buttons">
                <button type="submit" form="editForm" class="btn btn-edit">Salvar</button>
                <button type="button" class="btn btn-delete" onclick="closeModal()">Cancelar</button>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div id="confirmModal" class="confirm-modal">
        <div class="confirm-modal-content">
            <h3>⚠️ Confirmar Exclusão</h3>
            <p>Tem certeza que deseja excluir este usuário?</p>
            <p style="color: #dc3545; font-weight: bold; margin-top: 10px;">Esta ação não pode ser desfeita!</p>
            <div class="confirm-buttons">
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">Sim, Excluir</button>
                <button type="button" class="btn btn-secondary" onclick="closeConfirmModal()">Cancelar</button>
            </div>
        </div>
    </div>

    <script>
        let userIdToDelete = null;
        
        function editUser(user) {
            document.getElementById('editId').value = user.id;
            document.getElementById('editNome').value = user.nome_completo;
            document.getElementById('editEmail').value = user.email;
            document.getElementById('editCpf').value = user.cpf;
            document.getElementById('editTelefone').value = user.telefone;
            document.getElementById('editCep').value = user.cep;
            document.getElementById('editLogradouro').value = user.logradouro;
            document.getElementById('editBairro').value = user.bairro;
            document.getElementById('editLocalidade').value = user.localidade;
            document.getElementById('editUf').value = user.uf;
            document.getElementById('editNumero').value = user.numero;
            document.getElementById('editComplemento').value = user.complemento;
            
            document.getElementById('editModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
        
        function deleteUser(id) {
            userIdToDelete = id;
            document.getElementById('confirmModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
        
        function confirmDelete() {
            if (userIdToDelete) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = '<input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="' + userIdToDelete + '">';
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        
        function closeConfirmModal() {
            document.getElementById('confirmModal').style.display = 'none';
            userIdToDelete = null;
            document.body.style.overflow = 'auto';
        }
        
        function buscarCepManual() {
            const cep = document.getElementById('editCep').value.replace(/\D/g, '');
            if (cep.length === 8) {
                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    if (data && !data.erro) {
                        document.getElementById('editLogradouro').value = data.logradouro || '';
                        document.getElementById('editBairro').value = data.bairro || '';
                        document.getElementById('editLocalidade').value = data.localidade || '';
                        document.getElementById('editUf').value = data.uf || '';
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