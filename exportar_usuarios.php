<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

require_once 'config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Aplica os filtros da página principal
    $search = $_GET['search'] ?? '';
    $filter_date = $_GET['filter_date'] ?? '';
    $filter_uf = $_GET['filter_uf'] ?? '';
    $order_by = $_GET['order_by'] ?? 'created_at';
    $order_dir = $_GET['order_dir'] ?? 'DESC';
    $format = $_GET['format'] ?? 'csv';
    
    // Validação da ordenação
    $allowed_columns = ['nome_completo', 'email', 'cpf', 'telefone', 'uf', 'created_at'];
    $allowed_directions = ['ASC', 'DESC'];
    
    if (!in_array($order_by, $allowed_columns)) {
        $order_by = 'created_at';
    }
    if (!in_array(strtoupper($order_dir), $allowed_directions)) {
        $order_dir = 'DESC';
    }
    
    $whereClause = "";
    $params = [];
    
    if (!empty($search)) {
        $whereClause = "WHERE (nome_completo LIKE ? OR email LIKE ?)";
        $searchTerm = "%$search%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }
    
    if (!empty($filter_date)) {
        $whereClause .= $whereClause ? " AND DATE(created_at) = ?" : "WHERE DATE(created_at) = ?";
        $params[] = $filter_date;
    }
    
    if (!empty($filter_uf)) {
        $whereClause .= $whereClause ? " AND uf = ?" : "WHERE uf = ?";
        $params[] = $filter_uf;
    }
    
    // Busca todos os usuários
    $sql = "SELECT * FROM usuarios $whereClause ORDER BY $order_by $order_dir";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $usuarios = $stmt->fetchAll();
    
    // Gera nome do arquivo
    $filename = 'usuarios_' . date('Y-m-d_H-i-s') . '.' . $format;
    
    if ($format === 'csv') {
        // Exportar CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Cabeçalhos
        fputcsv($output, [
            'Nome Completo',
            'Email', 
            'CPF',
            'Telefone',
            'CEP',
            'Logradouro',
            'Número',
            'Complemento',
            'Bairro',
            'Cidade',
            'Estado',
            'Data de Cadastro'
        ], ';');
        
        // Dados
        foreach ($usuarios as $usuario) {
            fputcsv($output, [
                $usuario['nome_completo'],
                $usuario['email'],
                $usuario['cpf'],
                $usuario['telefone'],
                $usuario['cep'],
                $usuario['logradouro'],
                $usuario['numero'],
                $usuario['complemento'],
                $usuario['bairro'],
                $usuario['localidade'],
                $usuario['uf'],
                date('d/m/Y H:i', strtotime($usuario['created_at']))
            ], ';');
        }
        
        fclose($output);
        
    } elseif ($format === 'pdf') {
        // Exportar PDF (HTML para PDF)
        header('Content-Type: text/html; charset=utf-8');
        
        echo '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Relatório de Usuários</title>
            <style>
                body { font-family: "Roboto", -apple-system, BlinkMacSystemFont, "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; margin: 20px; }
                h1 { color: #0066cc; text-align: center; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
                tr:nth-child(even) { background-color: #f9f9f9; }
                .info { margin: 10px 0; color: #666; }
            </style>
        </head>
        <body>
            <h1>📊 Relatório de Usuários</h1>
            <div class="info">
                <strong>Data de geração:</strong> ' . date('d/m/Y H:i:s') . '<br>
                <strong>Total de usuários:</strong> ' . count($usuarios) . '<br>';
                
        if (!empty($search)) {
            echo '<strong>Filtro de busca:</strong> ' . htmlspecialchars($search) . '<br>';
        }
        if (!empty($filter_date)) {
            echo '<strong>Filtro de data:</strong> ' . htmlspecialchars($filter_date) . '<br>';
        }
        if (!empty($filter_uf)) {
            echo '<strong>Filtro de estado:</strong> ' . htmlspecialchars($filter_uf) . '<br>';
        }
        
        echo '</div>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>CPF</th>
                        <th>Telefone</th>
                        <th>Endereço</th>
                        <th>Data Cadastro</th>
                    </tr>
                </thead>
                <tbody>';
                
        foreach ($usuarios as $usuario) {
            echo '<tr>
                <td>' . htmlspecialchars($usuario['nome_completo']) . '</td>
                <td>' . htmlspecialchars($usuario['email']) . '</td>
                <td>' . htmlspecialchars($usuario['cpf']) . '</td>
                <td>' . htmlspecialchars($usuario['telefone']) . '</td>
                <td>' . htmlspecialchars($usuario['logradouro'] . ', ' . $usuario['numero'] . ' - ' . $usuario['bairro'] . ', ' . $usuario['localidade'] . '/' . $usuario['uf']) . '</td>
                <td>' . date('d/m/Y H:i', strtotime($usuario['created_at'])) . '</td>
            </tr>';
        }
        
        echo '</tbody>
            </table>
            <script>
                window.print();
            </script>
        </body>
        </html>';
    }
    
} catch (PDOException $e) {
    header('Location: editar_usuarios.php?error=' . urlencode('Erro ao exportar dados: ' . $e->getMessage()));
    exit();
}
?>