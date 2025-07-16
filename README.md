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
│   ├── css/
│   │   ├── alerts.css
│   │   ├── buttons.css
│   │   ├── cadastrar.css
│   │   ├── cadastrar_categoria.css
│   │   ├── cadastrar_despesas.css
│   │   ├── configurar_salario.css
│   │   ├── dashboard.css
│   │   ├── editar_despesas.css
│   │   ├── editar_usuario.css
│   │   ├── forms.css
│   │   ├── header.css
│   │   ├── index.css
│   │   ├── login.css
│   │   ├── tables.css
│   │   ├── utilities.css
│   │   └── charts.css
│   └── js/
│       ├── cadastrar.js
│       ├── cadastrar_despesas.js
│       ├── dashboard.js
│       ├── editar_despesas.js
│       ├── graficoPesquisa.js
│       ├── graficos.js
│       └── login.js
├── cadastrar.php
├── cadastrar_categoria.php
├── cadastrar_despesas.php
├── configurar_salario.php
├── dashboard.php
├── DeletarDespesa.php
├── editar.php
├── editar_despesa.php
├── editar_usuario.php
├── excluir.php
├── financas.sql
├── graficos.php
├── includes/
│   ├── Connection.php
│   ├── footer.php
│   ├── header.php
│   └── usuarioDashboard.php
├── index.php
├── login.php
├── logout.php
├── NovaCategoria.php
├── NovaDespesa.php
├── README.md
├── Signin.php
└── UsuarioDashboard.php
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

- **Thainá Tavares de Souza** - *Scrum Master* - https://github.com/souzathaina
- **Priscila Schlotenfeldt da Silva** - *Product Owner* - https://github.com/PriscilaSchloten
- **Leonardo Souza**  - Q/A, controle de branchs, organização do git e dashboard dinâmico* - https://github.com/leoverardo
- **Vitor Machado** - *Banco de dados e teste de telas* - https://github.com/VitorMachado07
- **João Pedro** - *Footer (css), aba sobre (css) e implementação de carrossel de imagens na index* - https://github.com/JaoPedros
- **Pedro Gabriel Algayer Silveira** - *alerts(css), cadastro(css), login(css) e dashboard(css)* - https://github.com/PedroSenac123
- **Lucas Boesel Hendler** - *Daschboard (css), index(css), despesas(css) e logo* - https://github.com/LucasBoesel
- **Jair de Souza Ribeiro** - *teste de responsividade, gráficos barras, pizza e rosquinha e teste de telas* - https://github.com/jairsrib
- **Gustavo Farias** - *Conexão* - https://github.com/Gustavofariass15
- **Jeremias Fagundes Ribeiro** - *Banco de dados e alerts para tomadas de desisões* - https://github.com/jerezrib1
- **Fabricio Lacerda Moraes** - *CadUsuário, Alerts, crud de categorias, login* - https://github.com/Hanso667
- **Gustavo Kruger** - *Dashboard dinamica, logout, filtro mensal e por categoria e cadastro de despesas* - https://github.com/Gustavokvs

## 🙏 Agradecimentos

- Chart.js
- FontAwesome
- Google Fonts (Inter)
- XAMPP


---

**⭐ Se este projeto te ajudou, considere dar uma estrela no repositório!**

## 💡 Quer contribuir?
Se você se interessou pelo projeto Finanças que Salvam e deseja colaborar com melhorias, sugestões ou novas funcionalidades, será muito bem-vindo(a)!

Formas de contribuir:
👨‍💻 Corrigir bugs ou melhorar funcionalidades

🧠 Sugerir melhorias de usabilidade ou novas ideias

🎨 Ajudar na parte visual e responsiva

🛡️ Revisar segurança e performance

📖 Melhorar a documentação

Como começar:
Faça um fork do repositório

Crie uma branch com sua contribuição (ex: feature/nova-funcionalidade)

Envie um Pull Request com uma descrição clara das alterações

Se tiver dúvidas ou quiser conversar sobre o projeto, sinta-se à vontade para abrir uma Issue. Toda ajuda é bem-vinda e reconhecida!

✨ Juntos, podemos construir um sistema financeiro mais acessível, moderno e sustentável!

**🌱 Finanças que Salvam - Controle financeiro com consciência ambiental e visual moderno**
