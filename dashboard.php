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
    
} catch (PDOException $e) {
    $totalUsuarios = 0;
    $novosHoje = 0;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Registro Ágil</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .header { 
            background: linear-gradient(135deg, #0066cc 0%, #004499 100%);
            color: white; 
            padding: 20px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
        }
        .header h1 { 
            font-size: 1.8em; 
        }
        .user-info { 
            display: flex; 
            align-items: center; 
            gap: 20px; 
        }
        .container { 
            max-width: 1200px; 
            margin: 0 auto; 
            padding: 30px 20px; 
        }
        .stats { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); 
            gap: 20px; 
            margin-bottom: 30px; 
        }
        .stat-card { 
            background: #2d2d2d; 
            padding: 30px; 
            border-radius: 10px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.3); 
            text-align: center; 
            border: 1px solid #404040;
        }
        .stat-number { 
            font-size: 3em; 
            font-weight: bold; 
            color: #0066cc; 
            margin-bottom: 10px;
        }
        .stat-label { 
            color: #adb5bd; 
            font-size: 1.1em;
        }
        .actions { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); 
            gap: 20px; 
            margin-top: 30px; 
        }
        .action-card { 
            background: #2d2d2d; 
            padding: 30px; 
            border-radius: 10px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
            text-align: center;
            text-decoration: none;
            color: #e9ecef;
            transition: transform 0.2s;
            border: 1px solid #404040;
        }
        .action-card:hover { 
            transform: translateY(-5px);
            color: #0066cc;
        }
        .action-icon { 
            font-size: 3em; 
            margin-bottom: 15px; 
        }
        .action-title { 
            font-size: 1.2em; 
            font-weight: bold; 
            margin-bottom: 10px; 
        }
        .action-desc { 
            color: #666; 
        }
        
        /* SIDEBAR MENU INTEGRADO */
        .sidebar-menu {
            position: relative;
            display: inline-block;
        }

        .menu-toggle {
            background: rgba(255,255,255,0.2);
            color: white;
            border: none;
            padding: 12px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .menu-toggle:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }

        .menu-toggle .hamburger {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .menu-toggle .hamburger span {
            width: 20px;
            height: 2px;
            background: white;
            transition: all 0.3s ease;
            border-radius: 1px;
        }

        .menu-toggle.active .hamburger span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .menu-toggle.active .hamburger span:nth-child(2) {
            opacity: 0;
        }

        .menu-toggle.active .hamburger span:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -6px);
        }

        .sidebar-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: #2d2d2d;
            border: 1px solid #404040;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            min-width: 250px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 1000;
            margin-top: 10px;
        }

        .sidebar-dropdown.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .sidebar-dropdown::before {
            content: '';
            position: absolute;
            top: -8px;
            right: 20px;
            width: 0;
            height: 0;
            border-left: 8px solid transparent;
            border-right: 8px solid transparent;
            border-bottom: 8px solid #2d2d2d;
        }

        .sidebar-dropdown .user-profile {
            padding: 20px;
            border-bottom: 1px solid #404040;
            text-align: center;
        }

        .sidebar-dropdown .user-profile .user-avatar {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #0066cc 0%, #004499 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-size: 24px;
            color: white;
            font-weight: bold;
        }

        .sidebar-dropdown .user-profile .user-name {
            color: #e9ecef;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .sidebar-dropdown .user-profile .user-role {
            color: #adb5bd;
            font-size: 14px;
        }

        .sidebar-dropdown .menu-items {
            padding: 10px 0;
        }

        .sidebar-dropdown .menu-item {
            display: block;
            padding: 12px 20px;
            color: #e9ecef;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .sidebar-dropdown .menu-item:hover {
            background: #404040;
            border-left-color: #0066cc;
            color: #0066cc;
            transform: translateX(5px);
        }

        .sidebar-dropdown .menu-item .menu-icon {
            margin-right: 12px;
            font-size: 16px;
        }

        .sidebar-dropdown .menu-divider {
            height: 1px;
            background: #404040;
            margin: 10px 0;
        }

        .sidebar-dropdown .logout-item {
            color: #dc3545;
        }

        .sidebar-dropdown .logout-item:hover {
            background: rgba(220, 53, 69, 0.1);
            border-left-color: #dc3545;
            color: #dc3545;
        }

        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: scale(0);
            animation: ripple-animation 0.6s linear;
            pointer-events: none;
        }

        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }

        @keyframes slideInFromTop {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .sidebar-dropdown.active {
            animation: slideInFromTop 0.3s ease-out;
        }

        .sidebar-dropdown .user-profile .user-avatar {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(0, 102, 204, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(0, 102, 204, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(0, 102, 204, 0);
            }
        }
        @media (max-width: 768px) {
            .header { 
                flex-direction: column; 
                gap: 15px; 
            }
            .user-info { 
                flex-direction: column; 
                gap: 10px; 
            }
            
            .sidebar-dropdown {
                min-width: 200px;
                right: -10px;
            }
            
            .menu-toggle span:last-child {
                display: none;
            }
        }
        
        @media (max-width: 480px) {
            .sidebar-dropdown {
                min-width: 180px;
                right: -20px;
            }
            
            .sidebar-dropdown .user-profile {
                padding: 15px;
            }
            
            .sidebar-dropdown .user-profile .user-avatar {
                width: 50px;
                height: 50px;
                font-size: 20px;
            }
            
            .sidebar-dropdown .menu-item {
                padding: 10px 15px;
                font-size: 14px;
            }
        }
    </style>
</head>
    <body>
        <div class="header">
        <h1>📊 Dashboard - Registro Ágil</h1>
        <div class="user-info">
            <span>Olá, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</span>
            
            <!-- Sidebar Menu -->
            <div class="sidebar-menu">
                <button class="menu-toggle" onclick="toggleSidebar()">
                    <div class="hamburger">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                    <span>Menu</span>
                </button>
                
                <div class="sidebar-dropdown" id="sidebarDropdown">
                    <div class="user-profile">
                        <div class="user-avatar">
                            <?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>
                        </div>
                        <div class="user-name"><?php echo htmlspecialchars($_SESSION['user_name']); ?></div>
                        <div class="user-role"><?php echo $_SESSION['user_role'] === 'admin' ? 'Administrador' : 'Usuário'; ?></div>
                    </div>
                    
                    <div class="menu-items">
                        
                        <?php if ($_SESSION['user_role'] === 'admin'): ?>
                            <a href="cadastro.php" class="menu-item">
                                <span class="menu-icon">👤</span>
                                Cadastrar Usuário
                            </a>
                            
                            <a href="editar_usuarios.php" class="menu-item">
                                <span class="menu-icon">👥</span>
                                Gerenciar Usuários
                            </a>
                        <?php else: ?>
                            <a href="meu_perfil.php" class="menu-item">
                                <span class="menu-icon">👤</span>
                                Meu Perfil
                            </a>
                        <?php endif; ?>
                        
                        <a href="relatorios.php" class="menu-item">
                            <span class="menu-icon">📊</span>
                            Relatórios
                        </a>
                        
                        <div class="menu-divider"></div>
                        
                        <a href="logout.php" class="menu-item logout-item">
                            <span class="menu-icon">🚪</span>
                            Sair
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number"><?php echo $totalUsuarios; ?></div>
                <div class="stat-label">Total de Usuários</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $novosHoje; ?></div>
                <div class="stat-label">Cadastros Hoje</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $_SESSION['user_role'] === 'admin' ? 'Admin' : 'Usuário'; ?></div>
                <div class="stat-label">Seu Tipo</div>
            </div>
        </div>
        
        <div class="actions">
            <?php if ($_SESSION['user_role'] === 'admin'): ?>
                <a href="cadastro.php" class="action-card">
                    <div class="action-icon">👤</div>
                    <div class="action-title">Cadastrar Usuário</div>
                    <div class="action-desc">Adicionar novo usuário ao sistema</div>
                </a>
                
                <a href="editar_usuarios.php" class="action-card">
                    <div class="action-icon">👥</div>
                    <div class="action-title">Gerenciar Usuários</div>
                    <div class="action-desc">Visualizar, editar e exportar usuários</div>
                </a>
                
                <a href="relatorios.php" class="action-card">
                    <div class="action-icon">📊</div>
                    <div class="action-title">Relatórios</div>
                    <div class="action-desc">Estatísticas do sistema</div>
                </a>
            <?php else: ?>
                <a href="meu_perfil.php" class="action-card">
                    <div class="action-icon">👤</div>
                    <div class="action-title">Meu Perfil</div>
                    <div class="action-desc">Editar minhas informações pessoais</div>
                </a>
                
                <a href="relatorios.php" class="action-card">
                    <div class="action-icon">📊</div>
                    <div class="action-title">Relatórios</div>
                    <div class="action-desc">Estatísticas do sistema</div>
                </a>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        // SIDEBAR MENU JAVASCRIPT INTEGRADO
        function toggleSidebar() {
            const dropdown = document.getElementById('sidebarDropdown');
            const toggle = document.querySelector('.menu-toggle');
            
            dropdown.classList.toggle('active');
            toggle.classList.toggle('active');
        }

        // Fechar menu ao clicar fora
        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.sidebar-menu');
            const dropdown = document.getElementById('sidebarDropdown');
            const toggle = document.querySelector('.menu-toggle');
            
            if (!sidebar.contains(event.target)) {
                dropdown.classList.remove('active');
                toggle.classList.remove('active');
            }
        });

        // Fechar menu ao pressionar ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const dropdown = document.getElementById('sidebarDropdown');
                const toggle = document.querySelector('.menu-toggle');
                
                dropdown.classList.remove('active');
                toggle.classList.remove('active');
            }
        });

        // Efeito hover nos itens do menu
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.menu-item').forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateX(5px)';
                });
                
                item.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateX(0)';
                });
            });
            
            // Efeito nos itens do menu
            document.querySelectorAll('.menu-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    ripple.classList.add('ripple');
                    
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
        });
    </script>
</body>
</html>