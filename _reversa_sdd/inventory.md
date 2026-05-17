# Inventário do Projeto — rcg

## 1. Estrutura de Pastas Principal

- `app/`: Núcleo da aplicação (MVC, Configurações, Banco de Dados)
- `lib/`: Bibliotecas customizadas e frameworks externos (Adianti, Bootstrap, jQuery)
- `vendor/`: Dependências gerenciadas via Composer
- `files/`: Armazenamento de documentos
- `imagens/`: Ativos visuais
- `telas/`: Possíveis templates ou recursos de UI (a confirmar)
- `tmp/`: Arquivos temporários

## 2. Stack Tecnológica

- **Linguagem Principal:** PHP (1984 arquivos)
- **Frontend:** JavaScript (1114 arquivos), CSS, HTML (baseado em Bootstrap/jQuery via Adianti)
- **Framework Backend:** Adianti Framework (v4.2 identificado em `application.ini`)
- **Gerenciador de Dependências:** Composer
- **Banco de Dados:** Suporte multi-banco (SQLite, MySQL, PostgreSQL, MSSQL, Oracle) identificado via scripts SQL e arquivos `.db`. O banco principal configurado é `erp_online`.

## 3. Pontos de Entrada e Configuração

- **Entrada Web:** `index.php`, `engine.php`, `rest.php`
- **Entrada CLI:** `cmd.php`
- **API:** `app/routes/api.php` (prefixos `/api`, `/api/internal`)
- **Configurações:** `app/config/application.ini`, `app/config/erp_online.php`

## 4. Módulos Identificados (Baseado em `app/control/`)

- `admin`: Administração do sistema
- `cadastros`: Gestão de entidades básicas (Clientes, Fornecedores, Produtos, etc.)
- `cobranca`: Processos financeiros e faturamento
- `comercial`: (Inferido via modelos como Pedido, Orcamento)
- `gerencia`: Relatórios e visões gerenciais
- `supervisor`: Gestão de equipe de vendas
- `vendedor`: Interface para força de vendas
- `meu_cliente`: Portal ou módulo de autoatendimento
- `communication`: Sistema de mensagens e notificações internal

## 5. Banco de Dados e Modelos

- **Modelos (ORM):** 110+ classes em `app/model/`, incluindo entidades complexas como `Cliente`, `Pedido`, `Orcamento`, `NotaSaida`, `Produto`.
- **Database Manager:** Interface interna detectada via rotas da API para gerenciamento de tabelas e queries.

## 6. Testes e Qualidade

- **Testes:** Quase inexistentes (apenas 1 arquivo `.feature` detectado em templates).
- **Cobertura Automatizada:** < 1% (Estimado).

## 7. Complexidade de UI

- **Telas Estimadas:** ~260 classes estendendo componentes de página do Adianti.
