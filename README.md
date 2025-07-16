# 💰 Finanças que Salvam

> **Sistema completo de controle financeiro pessoal com foco em sustentabilidade e conscientização**

[![PHP](https://img.shields.io/badge/PHP-8.0+-blue.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange.svg)](https://mysql.com)
[![Chart.js](https://img.shields.io/badge/Chart.js-4.0+-yellow.svg)](https://chartjs.org)
[![FontAwesome](https://img.shields.io/badge/FontAwesome-6.0+-lightgrey.svg)](https://fontawesome.com)

## 📋 Índice

- [Sobre o Projeto](#-sobre-o-projeto)
- [Funcionalidades](#-funcionalidades)
- [Tecnologias Utilizadas](#-tecnologias-utilizadas)
- [Estrutura do Projeto](#-estrutura-do-projeto)
- [Instalação](#-instalação)
- [Configuração](#-configuração)
- [Como Usar](#-como-usar)
- [API e Backend](#-api-e-backend)
- [Contribuição](#-contribuição)
- [Licença](#-licença)

## 🌱 Sobre o Projeto

**Finanças que Salvam** é um sistema web completo para controle financeiro pessoal que vai além do simples registro de despesas. O projeto combina gestão financeira tradicional com conscientização ambiental, ajudando usuários a entenderem o impacto de seus gastos tanto no bolso quanto no planeta.

### 🎯 Objetivos

- ✅ **Controle Financeiro**: Registro e categorização de despesas
- ✅ **Análise Visual**: Gráficos interativos de gastos
- ✅ **Conscientização**: Relacionamento entre gastos e impacto ambiental
- ✅ **Simplicidade**: Interface intuitiva e responsiva
- ✅ **Segurança**: Sistema de autenticação robusto

## ✨ Funcionalidades

### 🔐 **Sistema de Autenticação**
- **Cadastro de usuários** com validação completa
- **Login seguro** com sessões PHP
- **Logout** com destruição segura de sessão
- **Validação de força de senha** em tempo real
- **Verificação de email único** no cadastro

### 📊 **Dashboard Interativo**
- **Visão geral financeira** com estatísticas rápidas
- **Gráficos dinâmicos** usando Chart.js
- **Comparação gastos vs salário** em tempo real
- **Distribuição por categorias** com cores intuitivas
- **Alertas visuais** para gastos excessivos

### 💸 **Gestão de Despesas**
- **Cadastro de despesas** com categorização
- **Edição e exclusão** de registros
- **Categorias personalizáveis** pelo usuário
- **Validação de dados** em tempo real
- **Histórico completo** de transações

### 📈 **Análise Financeira**
- **Percentual gasto** do salário mensal
- **Saldo restante** calculado automaticamente
- **Tendências de gastos** por categoria
- **Alertas de orçamento** quando necessário
- **Relatórios visuais** interativos

### 🎨 **Interface Moderna**
- **Design responsivo** para todos os dispositivos
- **Paleta de cores** consistente e acessível
- **Animações suaves** e feedback visual
- **Ícones intuitivos** (FontAwesome)
- **Tipografia legível** (Inter)

## 🛠️ Tecnologias Utilizadas

### **Backend**
- **PHP 8.0+** - Linguagem principal
- **MySQL 8.0+** - Banco de dados
- **PDO** - Conexão segura com banco
- **Sessions** - Gerenciamento de sessões

### **Frontend**
- **HTML5** - Estrutura semântica
- **CSS3** - Estilização moderna
- **JavaScript ES6+** - Interatividade
- **Chart.js** - Gráficos interativos
- **FontAwesome** - Ícones

### **Ferramentas**
- **XAMPP** - Ambiente de desenvolvimento
- **Git** - Controle de versão
- **VS Code** - Editor de código

## 📁 Estrutura do Projeto

```
financas-que-salvam/
├── 📁 assets/
│   ├── 📁 js/
│   │   └── graficos.js          # Scripts de gráficos
│   └── 📁 css/
│       └── cadastrar_despesas.css
├── 📁 css/
│   └── index.css                # Estilos principais
├── 📁 includes/
│   └── header.php               # Header reutilizável
├── 📄 index.php                 # Página inicial
├── 📄 dashboard.php             # Dashboard principal
├── 📄 login.php                 # Página de login
├── 📄 cadastrar.php             # Página de cadastro
├── 📄 logout.php                # Sistema de logout
├── 📄 configurar_salario.php    # Configuração de salário
├── 📄 cadastrar_despesas.php    # Cadastro de despesas
├── 📄 graficos.php              # Página de gráficos
├── 📄 Connection.php            # Conexão com banco
├── 📄 Login.php                 # Backend de login
├── 📄 Signin.php                # Backend de cadastro
├── 📄 NovaDespesa.php           # Backend de nova despesa
├── 📄 EditarDespesa.php         # Backend de edição
├── 📄 DeletarDespesa.php        # Backend de exclusão
├── 📄 NovaCategoria.php         # Backend de categorias
├── 📄 UsuarioDashboard.php      # Dashboard do usuário
├── 📄 financas.sql              # Estrutura do banco
├── 📄 adicionar_salario.sql     # Script para coluna salário
├── 📄 README_DASHBOARD.md       # Documentação da dashboard
├── 📄 README_LOGIN.md           # Documentação do login
└── 📄 README.md                 # Este arquivo
```

## 🚀 Instalação

### **Pré-requisitos**
- XAMPP (Apache + MySQL + PHP)
- PHP 8.0 ou superior
- MySQL 8.0 ou superior
- Navegador web moderno

### **Passos de Instalação**

1. **Clone o repositório**
   ```bash
   git clone https://github.com/seu-usuario/financas-que-salvam.git
   cd financas-que-salvam
   ```

2. **Configure o XAMPP**
   - Inicie o Apache e MySQL
   - Coloque o projeto na pasta `htdocs`

3. **Configure o banco de dados**
   ```sql
   -- Execute o arquivo financas.sql
   mysql -u root -p < financas.sql
   
   -- Execute o script para adicionar coluna salário
   mysql -u root -p financas < adicionar_salario.sql
   ```

4. **Configure a conexão**
   - Edite `Connection.php` com suas credenciais
   ```php
   $host = 'localhost';
   $dbname = 'financas';
   $username = 'seu_usuario';
   $password = 'sua_senha';
   ```

5. **Acesse o projeto**
   ```
   http://localhost/financas-que-salvam
   ```

## ⚙️ Configuração

### **Banco de Dados**

O sistema utiliza as seguintes tabelas:

#### **usuarios**
```sql
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    salario DECIMAL(10,2) DEFAULT 0.00
);
```

#### **categorias**
```sql
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL
);
```

#### **despesas**
```sql
CREATE TABLE despesas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    categoria INT,
    valor DECIMAL(10,2) NOT NULL,
    data DATE NOT NULL,
    descricao TEXT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (categoria) REFERENCES categorias(id)
);
```

### **Configurações de Segurança**

- **Sessões PHP** configuradas para segurança
- **Prepared statements** para prevenir SQL injection
- **Hash de senhas** com `password_hash()`
- **Validação de entrada** em todos os formulários

## 📖 Como Usar

### **Primeiro Acesso**

1. **Acesse a página inicial**
   - Vá para `http://localhost/financas-que-salvam`

2. **Crie sua conta**
   - Clique em "Comece Agora"
   - Preencha seus dados
   - Crie uma senha forte

3. **Configure seu salário**
   - Na dashboard, clique em "Configurar Salário"
   - Insira seu salário mensal

4. **Comece a usar**
   - Cadastre suas primeiras despesas
   - Explore os gráficos
   - Monitore seus gastos

### **Uso Diário**

#### **Cadastrar Despesa**
1. Acesse a dashboard
2. Clique em "Nova Despesa"
3. Preencha: valor, categoria, data, descrição
4. Clique em "Cadastrar Despesa"

#### **Ver Gráficos**
1. Na dashboard, visualize os gráficos automáticos
2. Para gráficos detalhados, clique em "Gráficos Detalhados"
3. Escolha entre diferentes tipos de visualização

#### **Gerenciar Categorias**
1. Clique em "Nova Categoria"
2. Digite o nome da categoria
3. Salve para usar em despesas

#### **Editar/Excluir**
1. Na tabela de despesas, use os botões de ação
2. Editar: modifica os dados da despesa
3. Excluir: remove permanentemente

### **Análise Financeira**

#### **Dashboard Principal**
- **Total Gasto**: Soma de todas as despesas
- **Salário Mensal**: Valor configurado pelo usuário
- **Percentual Gasto**: Relação gastos/salário
- **Saldo Restante**: Salário - gastos

#### **Gráficos Disponíveis**
- **Despesas por Categoria**: Distribuição percentual
- **Gastos vs Salário**: Comparação visual
- **Tendências Temporais**: Evolução dos gastos

## 🔧 API e Backend

### **Endpoints Principais**

#### **Autenticação**
- `POST /Login.php` - Autenticação de usuário
- `POST /Signin.php` - Cadastro de novo usuário
- `GET /logout.php` - Encerramento de sessão

#### **Despesas**
- `POST /NovaDespesa.php` - Criar nova despesa
- `POST /EditarDespesa.php` - Editar despesa existente
- `POST /DeletarDespesa.php` - Excluir despesa

#### **Categorias**
- `POST /NovaCategoria.php` - Criar nova categoria

#### **Configurações**
- `POST /configurar_salario.php` - Atualizar salário

### **Estrutura de Resposta**

#### **Sucesso**
```php
header("Location: ./dashboard.php?sucesso=1");
```

#### **Erro**
```php
header("Location: ./login.php?sucesso=0&erro=tipo_erro");
```

### **Tipos de Erro**
- `campos_vazios` - Campos obrigatórios não preenchidos
- `credenciais_invalidas` - Email ou senha incorretos
- `email_em_uso` - Email já cadastrado
- `email_invalido` - Formato de email inválido
- `dominio_invalido` - Domínio de email inválido
- `erro_interno` - Erro interno do servidor

## 🤝 Contribuição

### **Como Contribuir**

1. **Fork o projeto**
2. **Crie uma branch** para sua feature
   ```bash
   git checkout -b feature/NovaFuncionalidade
   ```
3. **Commit suas mudanças**
   ```bash
   git commit -m 'Adiciona nova funcionalidade'
   ```
4. **Push para a branch**
   ```bash
   git push origin feature/NovaFuncionalidade
   ```
5. **Abra um Pull Request**

### **Padrões de Código**

- **PHP**: PSR-12
- **JavaScript**: ES6+
- **CSS**: BEM methodology
- **HTML**: Semantic markup

### **Funcionalidades Sugeridas**

- [ ] **Metas de economia** mensais
- [ ] **Alertas de gastos** excessivos
- [ ] **Exportação de relatórios** em PDF
- [ ] **Integração com bancos** via API
- [ ] **Modo escuro** na interface
- [ ] **Notificações push** para lembretes
- [ ] **Backup automático** dos dados
- [ ] **Multi-idioma** (português/inglês)

## 📄 Licença

Este projeto está sob a licença **MIT**. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

## 👥 Autores

- **Seu Nome** - *Desenvolvimento inicial* - [SeuGitHub](https://github.com/seu-usuario)

## 🙏 Agradecimentos

- **Chart.js** - Gráficos interativos
- **FontAwesome** - Ícones
- **Google Fonts** - Tipografia Inter
- **XAMPP** - Ambiente de desenvolvimento

## 📞 Suporte

- **Email**: seu-email@exemplo.com
- **Issues**: [GitHub Issues](https://github.com/seu-usuario/financas-que-salvam/issues)
- **Documentação**: [Wiki do Projeto](https://github.com/seu-usuario/financas-que-salvam/wiki)

---

**⭐ Se este projeto te ajudou, considere dar uma estrela no repositório!**

**🌱 Finanças que Salvam - Controle financeiro com consciência ambiental**