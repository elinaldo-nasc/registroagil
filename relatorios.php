<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}
$host = 'localhost';
$dbname = 'registro_agil';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT COUNT(*) as total FROM usuarios";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $totalUsuarios = $stmt->fetch()['total'];
    
    $sql = "SELECT COUNT(*) as total FROM usuarios WHERE DATE(created_at) = CURDATE()";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $novosHoje = $stmt->fetch()['total'];
    
    $sql = "SELECT COUNT(*) as total FROM usuarios WHERE DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $novosSemana = $stmt->fetch()['total'];
    
    $sql = "SELECT COUNT(*) as total FROM usuarios WHERE DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $novosMes = $stmt->fetch()['total'];
    $sql = "SELECT uf, COUNT(*) as total FROM usuarios GROUP BY uf ORDER BY total DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $porEstado = $stmt->fetchAll();
    $sql = "SELECT DATE_FORMAT(created_at, '%Y-%m') as mes, COUNT(*) as total 
            FROM usuarios 
            WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
            ORDER BY mes DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $porMes = $stmt->fetchAll();
    
} catch (PDOException $e) {
    $totalUsuarios = 0;
    $novosHoje = 0;
    $novosSemana = 0;
    $novosMes = 0;
    $porEstado = [];
    $porMes = [];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios - Registro Ágil</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { 
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            min-height: 100vh;
            padding: 20px;
            color: #e9ecef;
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
        .container { 
            max-width: 1200px; 
            margin: 0 auto; 
            padding: 30px 20px; 
            background: #2d2d2d;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            border: 1px solid #404040;
        }
        .stats-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); 
            gap: 20px; 
            margin-bottom: 30px; 
        }
        .stat-card { 
            background: #2d2d2d; 
            padding: 25px; 
            border-radius: 10px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
            text-align: center;
            border: 1px solid #404040;
        }
        .stat-number { 
            font-size: 2.5em; 
            font-weight: bold; 
            color: #0066cc; 
            margin-bottom: 10px;
        }
        .stat-label { 
            color: #adb5bd; 
            font-size: 1em;
        }
        .report-section { 
            background: #2d2d2d; 
            padding: 25px; 
            border-radius: 10px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
            margin-bottom: 20px;
            border: 1px solid #404040;
        }
        .report-section h2 { 
            color: #e9ecef; 
            margin-bottom: 20px; 
            border-bottom: 2px solid #0066cc;
            padding-bottom: 10px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
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
        .chart-container { 
            height: 300px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            background: #3d3d3d; 
            border-radius: 5px; 
            margin-top: 20px;
            border: 1px solid #404040;
        }
        .chart-placeholder { 
            color: #adb5bd; 
            font-size: 1.2em;
        }
        @media (max-width: 768px) {
            .stats-grid { 
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); 
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
            <h1 style="margin: 0; line-height: 1;">📊 Relatórios</h1>
            <a href="dashboard.php" class="back-link" style="margin-bottom: 0;">← Voltar ao Dashboard</a>
        </div>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?php echo $totalUsuarios; ?></div>
                <div class="stat-label">Total de Usuários</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $novosHoje; ?></div>
                <div class="stat-label">Cadastros Hoje</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $novosSemana; ?></div>
                <div class="stat-label">Esta Semana</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $novosMes; ?></div>
                <div class="stat-label">Este Mês</div>
            </div>
        </div>
        
        <div class="report-section">
            <h2>📍 Cadastros por Estado</h2>
            <?php if (empty($porEstado)): ?>
                <p>Nenhum dado disponível.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Estado</th>
                            <th>Quantidade</th>
                            <th>Percentual</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($porEstado as $estado): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($estado['uf']); ?></td>
                            <td><?php echo $estado['total']; ?></td>
                            <td><?php echo round(($estado['total'] / $totalUsuarios) * 100, 1); ?>%</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        
        <div class="report-section">
            <h2>📅 Cadastros por Mês (Últimos 6 meses)</h2>
            <?php if (empty($porMes)): ?>
                <p>Nenhum dado disponível.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Mês</th>
                            <th>Quantidade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($porMes as $mes): ?>
                        <tr>
                            <td><?php echo date('m/Y', strtotime($mes['mes'] . '-01')); ?></td>
                            <td><?php echo $mes['total']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <div class="chart-container">
                    <div class="chart-placeholder">
                        📈 Gráfico de evolução mensal<br>
                        <small>(Implementação futura)</small>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="report-section">
            <h2>📋 Resumo</h2>
            <p><strong>Total de usuários cadastrados:</strong> <?php echo $totalUsuarios; ?></p>
            <p><strong>Crescimento hoje:</strong> <?php echo $novosHoje; ?> novos cadastros</p>
            <p><strong>Crescimento semanal:</strong> <?php echo $novosSemana; ?> novos cadastros</p>
            <p><strong>Crescimento mensal:</strong> <?php echo $novosMes; ?> novos cadastros</p>
            
            <?php if (!empty($porEstado)): ?>
                <p><strong>Estado com mais cadastros:</strong> <?php echo $porEstado[0]['uf']; ?> (<?php echo $porEstado[0]['total']; ?> usuários)</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>