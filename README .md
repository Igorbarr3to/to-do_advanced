# 🧩 Sistema de Controle de Tarefas (Desafio Técnico)

Este é um **sistema web completo de gestão de tarefas** (to-do list avançada) desenvolvido como parte de um **desafio técnico**.  
O projeto foi construído inteiramente com **PHP 8+ puro**, **MySQL**, **Bootstrap 5** e **jQuery**.

A arquitetura central é baseada numa **mini-API** que responde a todas as interações com **JSON**, enquanto o frontend é uma **Single Page Application (SPA)** totalmente funcional — nenhuma ação (criar, editar, excluir, concluir, filtrar ou paginar) recarrega a página, graças ao uso intensivo de **AJAX**.

---

## 🚀 Funcionalidades Principais

### 🔐 Autenticação de Usuário
- Sistema completo de **Registo, Login e Logout**
- Sessões PHP seguras
- Armazenamento de palavras-passe com **hash**

### ✅ CRUD de Tarefas 100% AJAX
- **Criar:** Adiciona tarefas instantaneamente  
- **Listar:** Carrega as tarefas dinamicamente  
- **Editar:** Atualiza tarefas através de um modal responsivo  
- **Excluir:** Exclui tarefas com **alerta de confirmação (SweetAlert2)**  
- **Concluir:** Marca tarefas como concluídas com atualização imediata  

### 🔍 Filtros Dinâmicos (Live)
- **Pesquisa por Título:** Filtragem em tempo real (com debounce)
- **Filtro por Status:** Exibe tarefas “Pendente” ou “Concluída”
- **Paginação AJAX:** Controle de páginas dinâmico e sem reload

### Outros Destaques
- **Notificações Profissionais:** Feedback via Bootstrap Toasts
- **Design Responsivo:** Interface adaptada para desktop, tablet e mobile

---

## 🖼️ Screenshots

Aqui está uma prévia do das páginas da aplicação:

### Login
![Tela de login](./docs/tela_login.png)

### Registro
![Tela de registro](./docs/tela_registro.png)

### Dashboard
![Dashboard](./docs/dashboard.png)

---

## 🛠️ Stack Tecnológica

| Camada | Tecnologia |
|--------|-------------|
| **Backend** | PHP 8+ (puro, sem frameworks) |
| **Frontend** | HTML5, CSS3, Bootstrap 5, jQuery 3.7+ |
| **Base de Dados** | MySQL |
| **Bibliotecas JS** | jQuery, Bootstrap, SweetAlert2 |

---

## 📚 Documentação Completa

A pasta `/docs` contém toda a documentação do projeto:

### 📄 Manual Funcional
Guia do usuário final com:
- Instruções de instalação
- Passo a passo de uso do sistema

### 🛠️ Documentação Técnica
Guia para desenvolvedores, contendo:
- Arquitetura e estrutura do código
- Modelo da base de dados
- Funcionamento do AJAX
- Descrição dos scripts PHP

---
