# 💰 Finanças que Salvam

> **Sistema completo de controle financeiro pessoal com foco em sustentabilidade, usabilidade e visual moderno**

[![PHP](https://img.shields.io/badge/PHP-8.0+-blue.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange.svg)](https://mysql.com)
[![Chart.js](https://img.shields.io/badge/Chart.js-4.0+-yellow.svg)](https://chartjs.org)
[![FontAwesome](https://img.shields.io/badge/FontAwesome-6.0+-lightgrey.svg)](https://fontawesome.com)
[![Inter](https://img.shields.io/badge/Font-Inter-lightgrey.svg)](https://fonts.google.com/specimen/Inter)

## 📋 Índice

- [Sobre o Projeto](#-sobre-o-projeto)
- [Novidades e Melhorias](#-novidades-e-melhorias)
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

**Finanças que Salvam** é um sistema web completo para controle financeiro pessoal, combinando gestão financeira tradicional com consciência ambiental e uma experiência visual moderna e responsiva.

## 🚀 Novidades e Melhorias

- **Filtros dinâmicos no dashboard**: Filtre despesas por mês, ano e categoria, com atualização instantânea dos dados e gráficos.
- **Novo gráfico "Gastos x Salário"**: Visualização independente, dinâmica e responsiva, atualizada via AJAX sem recarregar a página.
- **Interface profissional e responsiva**: Todos os formulários e páginas seguem um padrão visual moderno, com a fonte 'Inter' e layout adaptável.
- **Estrutura de arquivos aprimorada**: Arquivo de conexão movido para `includes/Connection.php` e organização otimizada.
- **Experiência de usuário refinada**: Navegação intuitiva, feedback visual consistente e formulários com validação aprimorada.

## ✨ Funcionalidades

### 🔐 **Sistema de Autenticação**
- Cadastro e login de usuários com validação e segurança
- Logout seguro
- Validação de senha forte e email único

### 📊 **Dashboard Interativo**
- Visão geral financeira com estatísticas rápidas
- **Filtros dinâmicos** (mês, ano, categoria)
- **Gráficos dinâmicos** (incluindo "Gastos x Salário" e distribuição por categoria)
- Alertas visuais para gastos excessivos

### 💸 **Gestão de Despesas**
- Cadastro, edição e exclusão de despesas
- Categorias personalizáveis
- Histórico completo de transações

### 📈 **Análise Financeira**
- Percentual gasto do salário mensal
- Saldo restante automático
- Tendências de gastos por categoria
- Relatórios visuais interativos

### 🎨 **Interface Moderna**
- **Design responsivo** para todos os dispositivos
- **Fonte 'Inter'** para máxima legibilidade
- Paleta de cores consistente
- Ícones intuitivos (FontAwesome)

## 🛠️ Tecnologias Utilizadas

### **Backend**
- PHP 8.0+
- MySQL 8.0+
- PDO
- Sessions

### **Frontend**
- HTML5
- CSS3 (com organização em `assets/css/`)
- JavaScript ES6+ (scripts em `assets/js/`)
- Chart.js
- FontAwesome
- Google Fonts (Inter)

### **Ferramentas**
- XAMPP
- Git
- VS Code

## 📁 Estrutura do Projeto

```
financas-que-salvam/
├── assets/
│   ├── js/
│   │   ├── graficos.js           # Scripts de gráficos gerais
│   │   └── graficoPesquisa.js    # Script do gráfico "Gastos x Salário" (dinâmico)
│   └── css/
│       ├── alerts.css
│       ├── buttons.css
│       ├── cadastrar_despesas.css
│       ├── charts.css
│       ├── dashboard.css
│       ├── forms.css
│       ├── header.css
│       ├── index.css
│       ├── tables.css
│       └── utilities.css
├── includes/
│   ├── Connection.php            # Conexão com banco (novo caminho)
│   ├── conexao.php
│   ├── footer.php
│   ├── header.php
│   └── usuarioDashboard.php
├── index.php
├── dashboard.php
├── login.php
├── cadastrar.php
├── logout.php
├── configurar_salario.php
├── cadastrar_despesas.php
├── cadastrar_categoria.php
├── editar_despesa.php
├── editar_usuario.php
├── editar.php
├── graficos.php
├── NovaCategoria.php
├── NovaDespesa.php
├── EditarDespesa.php
├── DeletarDespesa.php
├── UsuarioDashboard.php
├── financas.sql
└── README.md
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
   ```

4. **Configure a conexão**
   - Edite `includes/Connection.php` com suas credenciais
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

- Sessões PHP configuradas para segurança
- Prepared statements para prevenir SQL injection
- Hash de senhas com `password_hash()`
- Validação de entrada em todos os formulários

## 📖 Como Usar

### **Primeiro Acesso**

1. Acesse a página inicial: `http://localhost/financas-que-salvam`
2. Crie sua conta e configure seu salário
3. Cadastre suas despesas e categorias
4. Use os filtros do dashboard para analisar seus gastos
5. Visualize os gráficos dinâmicos e monitore seu orçamento

### **Uso Diário**

- Cadastre, edite e exclua despesas facilmente
- Use filtros para visualizar gastos por período e categoria
- Analise gráficos dinâmicos e relatórios visuais
- Gerencie categorias conforme sua necessidade

### **Destaques Visuais**

- Todos os formulários e páginas seguem um padrão visual moderno, com a fonte 'Inter' e layout responsivo
- Filtros e botões estilizados para melhor experiência
- Gráficos atualizados em tempo real conforme os filtros

## 🔧 API e Backend

### **Endpoints Principais**

- `POST /NovaDespesa.php` - Criar nova despesa
- `POST /EditarDespesa.php` - Editar despesa existente
- `POST /DeletarDespesa.php` - Excluir despesa
- `POST /NovaCategoria.php` - Criar nova categoria
- `POST /configurar_salario.php` - Atualizar salário
- `POST /Signin.php` - Cadastro de novo usuário
- `POST /Login.php` - Autenticação de usuário
- `GET /logout.php` - Encerramento de sessão

## 🤝 Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature
3. Commit e push das mudanças
4. Abra um Pull Request

### **Padrões de Código**
- PHP: PSR-12
- JavaScript: ES6+
- CSS: BEM methodology
- HTML: Semantic markup

## 📄 Licença

Este projeto está sob a licença **MIT**. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

## 👥 Autores

- **Seu Nome** - *Desenvolvimento inicial* - [SeuGitHub](https://github.com/seu-usuario)

## 🙏 Agradecimentos

- Chart.js
- FontAwesome
- Google Fonts (Inter)
- XAMPP

## 📞 Suporte

- Email: seu-email@exemplo.com
- Issues: [GitHub Issues](https://github.com/seu-usuario/financas-que-salvam/issues)
- Documentação: [Wiki do Projeto](https://github.com/seu-usuario/financas-que-salvam/wiki)

---

**⭐ Se este projeto te ajudou, considere dar uma estrela no repositório!**

**🌱 Finanças que Salvam - Controle financeiro com consciência ambiental e visual moderno**