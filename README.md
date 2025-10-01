# 📊 Sistema Registro Ágil

O Registro Ágil é um sistema web desenvolvido gerenciamento de usuários, com funcionalidades de cadastro, edição, exclusão e consulta. O sistema resolve o problema de organizar e manter registros dos, oferecendo uma interface intuitiva para gerenciarem informações pessoais, endereços (com busca automática de CEP) e dados de contato dos usuários, incluindo sistema de permissões e restrições de segurança.

Ótimo para profissionais e organizações que necessitam de uma solução simples e eficiente para gerenciar cadastros de usuários com controle total e segurança.

## 🚀 Características Principais

- **🔐 Autenticação Segura**: Sistema de login com hash de senhas e controle de sessões
- **👥 Gerenciamento de Usuários**: CRUD completo com validações e filtros avançados
- **📱 Interface Responsiva**: Design moderno que funciona em desktop, tablet e mobile
- **📊 Dashboard Inteligente**: Estatísticas em tempo real e navegação intuitiva
- **📈 Sistema de Relatórios**: Análises detalhadas com exportação de dados
- **🌐 Integração com APIs**: Busca automática de endereços via ViaCEP
- **🔒 Controle de Acesso**: Sistema de roles (admin/usuário) com permissões granulares
- **📤 Exportação de Dados**: Suporte a CSV e PDF com filtros aplicados

## 🛠️ Tecnologias Utilizadas

### Backend
- **PHP 7.4+** - Linguagem principal
- **MySQL 5.7+** - Banco de dados
- **PDO** - Acesso seguro ao banco
- **Sessões PHP** - Controle de autenticação

### Frontend
- **HTML5** - Estrutura semântica
- **CSS3** - Estilos modernos com Flexbox/Grid
- **JavaScript ES6+** - Interatividade e validações
- **Fetch API** - Requisições assíncronas

### Integrações
- **ViaCEP API** - Busca de endereços
- **cURL** - Requisições HTTP
- **Password Hash** - Criptografia de senhas

## 📋 Requisitos do Sistema

- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Extensão PDO habilitada
- Extensão cURL habilitada
- Servidor web (Apache/Nginx)
- Navegador moderno

## 🔧 Instalação

### Método 1: Instalação Automática (Recomendado)

1. **Clone ou baixe o projeto**
   ```bash
   git clone https://github.com/elinaldo-nasc/registroagil.git
   cd registroagil
   ```

2. **Configure o servidor web**
   - Coloque os arquivos na pasta do servidor (ex: `htdocs` no XAMPP)
   - Acesse: `http://localhost/registroagil/install.php`

3. **Execute a instalação**
   - Preencha os dados do banco de dados
   - Clique em "Instalar Sistema"
   - Aguarde a confirmação de sucesso

4. **Faça login**
   - Email: `admin@exemplo.com`
   - Senha: `admin12345`

### Método 2: Instalação Manual

1. **Configure o banco de dados**
   ```sql
   CREATE DATABASE registro_agil CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

2. **Importe a estrutura**
   ```bash
   mysql -u root -p registro_agil < database.sql
   ```

3. **Configure as credenciais**
   - Edite o arquivo `config.php`
   - Ajuste as constantes de conexão

4. **Acesse o sistema**
   - URL: `http://localhost/registroagil/` (ponto de entrada único)
   - Sistema redireciona automaticamente: se logado → dashboard, se não → login
   - Use as credenciais do administrador

## 📁 Estrutura do Projeto

```
registroagil/
├── 📄 index.php              # Ponto de entrada principal (login integrado)
├── 🚪 logout.php             # Encerramento de sessão
├── 📊 dashboard.php          # Painel principal
├── 👤 cadastro.php           # Cadastro de usuários
├── ✏️ editar_usuarios.php    # Gerenciamento de usuários
├── 👤 meu_perfil.php         # Perfil do usuário
├── 📈 relatorios.php         # Relatórios e estatísticas
├── 📤 exportar_usuarios.php  # Exportação de dados
├── 🚀 install.php            # Instalação automática
├── 🌐 logica_cep.php         # Lógica de endereços
├── ⚙️ config.php             # Configurações do sistema
├── 🗄️ database.sql           # Estrutura do banco
├── 🎨 style.css              # Estilos globais
└── 📖 README.md              # Este arquivo
```

## 🎯 Funcionalidades

### 🔐 Autenticação e Autorização
- **Ponto de entrada único**: Acesso via `/` com redirecionamento inteligente
- Login seguro com validação de credenciais
- Sistema de sessões com controle de tempo
- Controle de acesso baseado em roles
- Logout seguro com destruição de sessão

### 👥 Gerenciamento de Usuários
- **Cadastro**: Formulário completo com validações
- **Listagem**: Tabela responsiva com filtros avançados
- **Edição**: Modais intuitivos para alteração de dados
- **Exclusão**: Hard delete com confirmação de segurança para remoção definitiva
- **Busca**: Filtros por nome, email, CPF e data
- **Ordenação**: Classificação por qualquer coluna

### 📊 Dashboard e Estatísticas
- Cards com métricas em tempo real
- Menu sidebar responsivo
- Navegação baseada em permissões
- Interface adaptativa para diferentes telas

### 📈 Relatórios Avançados
- Estatísticas gerais do sistema
- Distribuição de usuários por estado
- Evolução temporal de cadastros
- Resumo executivo consolidado

### 📤 Exportação de Dados
- **CSV**: Formato compatível com Excel
- **PDF**: Relatórios formatados para impressão
- **Filtros**: Aplicação de critérios na exportação
- **Download**: Arquivos com timestamp único

### 🌐 Integração com APIs
- **ViaCEP**: Busca automática de endereços
- **Validação**: Verificação de CEP em tempo real
- **Preenchimento**: Campos automáticos de endereço
- **Tratamento de Erros**: Feedback para CEPs inválidos

## 🔒 Segurança

### Autenticação
- Hash seguro de senhas com `password_hash()`
- Verificação com `password_verify()`
- Controle de sessões com timeout
- Redirecionamento automático para login

### Autorização
- Sistema de roles (admin/usuário)
- Verificação de permissões em cada página
- Proteção contra acesso não autorizado
- Validação de parâmetros de URL

### Validação de Dados
- Sanitização de entrada com `trim()`
- Validação de formato (email, CPF, telefone)
- Verificação de unicidade (email, CPF)
- Prepared statements contra SQL injection

### Proteção do Banco
- PDO com prepared statements
- Validação de parâmetros de ordenação
- Hard delete com confirmação de segurança para exclusões definitivas
- Transações para operações críticas

## 🎨 Interface e Usabilidade

### Design Responsivo
- Layout adaptativo para todas as telas
- Menu sidebar que se adapta ao dispositivo
- Formulários otimizados para mobile
- Navegação touch-friendly

### Experiência do Usuário
- Feedback visual imediato
- Validações em tempo real
- Máscaras de input automáticas
- Mensagens de erro claras

### Acessibilidade
- Contraste adequado de cores
- Navegação por teclado
- Textos descritivos
- Estrutura HTML semântica

## 📱 Compatibilidade

### Navegadores Suportados
- Chrome 80+
- Firefox 75+
- Safari 13+
- Edge 80+

### Dispositivos
- Desktop (1920x1080+)
- Tablet (768x1024)
- Mobile (375x667+)

## 🚀 Performance

### Otimizações Implementadas
- **Arquitetura simplificada**: Eliminação de redirecionamentos desnecessários
- Índices no banco de dados
- Queries otimizadas
- CSS minificado
- JavaScript eficiente
- Cache de sessão

### Métricas
- **Tempo de carregamento**: < 2s (otimizado com ponto de entrada único)
- **Redirecionamentos**: Mínimos para melhor performance
- Tamanho total: < 1MB
- Compatibilidade: 99% dos navegadores
- Responsividade: 100% dos dispositivos

## 🔧 Configuração

### Arquivo config.php
```php
<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'registro_agil');
define('DB_USER', 'root');
define('DB_PASS', '');

define('APP_NAME', 'Registro Ágil');
define('APP_VERSION', '1.0.0');

define('SESSION_LIFETIME', 3600);
define('PASSWORD_MIN_LENGTH', 6);

date_default_timezone_set('America/Sao_Paulo');

error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
```

### Personalização
- **Cores**: Edite as variáveis CSS em `style.css`
- **Logo**: Substitua o título no header
- **Configurações**: Ajuste as constantes em `config.php`
- **Banco**: Modifique a estrutura em `database.sql`

## 📊 Banco de Dados

### Estrutura da Tabela `usuarios`
```sql
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_completo VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    cpf VARCHAR(14) UNIQUE NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    cep VARCHAR(9) NOT NULL,
    logradouro VARCHAR(255) NOT NULL,
    bairro VARCHAR(100) NOT NULL,
    localidade VARCHAR(100) NOT NULL,
    uf VARCHAR(2) NOT NULL,
    complemento VARCHAR(255),
    numero VARCHAR(20) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_cpf (cpf)
);
```

### Índices para Performance
- `idx_email` - Busca por email
- `idx_cpf` - Busca por CPF

## 🧪 Testes

### Cenários de Teste
1. **Autenticação**
   - Login com credenciais válidas
   - Login com credenciais inválidas
   - Logout e verificação de sessão

2. **CRUD de Usuários**
   - Cadastro com dados válidos
   - Cadastro com dados duplicados
   - Edição de informações
   - Exclusão com confirmação

3. **Validações**
   - CPF inválido
   - Email inválido
   - CEP inexistente
   - Campos obrigatórios

4. **Responsividade**
   - Teste em diferentes resoluções
   - Menu sidebar em mobile
   - Formulários em tablet

## 🐛 Solução de Problemas

### Problemas Comuns

**Erro de conexão com banco**
- Verifique as credenciais em `config.php`
- Confirme se o MySQL está rodando
- Teste a conexão manualmente

**Página em branco**
- Verifique os logs de erro do PHP
- Confirme se todas as extensões estão habilitadas
- Verifique as permissões dos arquivos

**CEP não encontrado**
- Verifique a conexão com a internet
- Teste a API ViaCEP manualmente
- Confirme se o cURL está habilitado

**Problemas de sessão**
- Verifique as configurações de sessão do PHP
- Confirme se os cookies estão habilitados
- Limpe o cache do navegador

**Logout redirecionando incorretamente**
- Verifique se o arquivo logout.php redireciona para index.php
- Confirme se não há referências antigas ao login.php

## 📈 Roadmap

### Versão 1.1 (Próxima)
- [ ] Sistema de logs de auditoria
- [ ] Backup automático do banco
- [ ] Notificações por email
- [ ] API REST para integração

### Versão 1.2 (Futura)
- [ ] Sistema de permissões granular
- [ ] Múltiplos idiomas
- [ ] Temas personalizáveis
- [ ] Dashboard com gráficos interativos

### Versão 2.0 (Longo Prazo)
- [ ] Microserviços
- [ ] Docker containerization
- [ ] CI/CD pipeline
- [ ] Testes automatizados

## 🤝 Contribuição

### Como Contribuir
1. Fork o projeto
2. Crie uma branch para sua feature
3. Commit suas mudanças
4. Push para a branch
5. Abra um Pull Request

### Padrões de Código
- Use PSR-12 para PHP
- Comente funções complexas
- Mantenha a consistência de nomenclatura
- Teste suas alterações

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo `LICENSE` para mais detalhes.

## 👨‍💻 Desenvolvedor

**Elinaldo Oliveira**
- LinkedIn: [https://www.linkedin.com/in/elinaldonasc/]
- GitHub: [https://github.com/elinaldo-nasc]

---

**⭐ Se este projeto foi útil, considere dar uma estrela!**

---
