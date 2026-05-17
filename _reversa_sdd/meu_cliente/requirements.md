# Meu Cliente (B2B) — Requisitos

## Visão Geral
Esta unit provê um portal de autoatendimento (B2B) para os clientes finais da RCG. Ela permite a consulta descentralizada de Notas Fiscais, boletos e situação financeira, reduzindo o volume de chamados ao suporte comercial e financeiro.

## Responsabilidades
- Autenticar clientes finais via CPF/CNPJ de forma segura e isolada. 🟢
- Permitir a visualização e edição (limitada) de dados cadastrais mestre. 🟢
- Fornecer consulta de títulos a receber, saldos e segundas vias. 🟢
- Listar Notas Fiscais eletrônicas faturadas para o cliente. 🟢
- Garantir que um cliente acesse exclusivamente seus próprios dados. 🟢

## Regras de Negócio
- **Login Isolado:** A autenticação de clientes utiliza a tabela `cliente_acesso`, separada dos usuários administrativos. 🟢
- **Sessão Exclusiva:** Ao logar, o sistema deve definir a flag `cliente_logado = true` e travar todos os filtros de banco de dados para o `cliente_id` da sessão. 🟢
- **Hash de Senha:** As senhas do portal utilizam hash MD5 (conforme padrão legado). 🟡
- **Visibilidade de Notas:** Apenas notas fiscais ativas (`reg_ativo = 'S'`) e do tipo 'Normal' são exibidas. 🟢
- **Acesso Financeiro:** O cliente pode visualizar apenas títulos com saldo > 0 ou liquidados recentemente (limitado pela view SQL). 🟡

## Requisitos Funcionais

| ID | Requisito | Prioridade | Critério de Aceite |
|----|-----------|-----------|-------------------|
| RF-01 | Login B2B | Must | Autenticação via CNPJ e senha com isolamento de sessão. |
| RF-02 | Painel do Cliente | Must | Dashboard simplificado com atalhos para Meus Dados, Financeiro e Notas. |
| RF-03 | Consulta Financeira | Must | Listagem de títulos com indicação de status (A Receber, Em Atraso, Recebido). |
| RF-04 | Consulta de NFes | Must | Listagem histórica de notas fiscais com link para visualização/DANFE. |
| RF-05 | Alteração de Senha | Should | Funcionalidade para o cliente atualizar sua credencial de acesso ao portal. |

## Requisitos Não Funcionais

| Tipo | Requisito inferido | Evidência no código | Confiança |
|------|--------------------|---------------------|-----------|
| Segurança | Filtro global de `cliente_id` em todos os repositórios | `app/control/meu_cliente/PainelClienteForm.php` | 🟢 |
| Usabilidade | Interface responsiva/mobile-first (implícito) | `app/control/meu_cliente/FormLoginCliente.php` | 🟡 |
| Disponibilidade| Acesso 24/7 independente do horário comercial | (Propósito do portal) | 🟢 |

## Critérios de Aceitação

```gherkin
Cenário: Login de Cliente PJ
Dado que a empresa "Farma X" possui login cadastrado em cliente_acesso
Quando o usuário informar o CNPJ e senha corretos no LoginClienteForm
Então o sistema deve carregar o PainelClienteForm filtrado para "Farma X"

Cenário: Tentativa de Acesso a Dados de Terceiros
Dado que o Cliente A está logado no portal
Quando ele tentar acessar a URL de uma nota fiscal pertencente ao Cliente B
Então o sistema deve retornar erro de permissão ou "Nota não localizada"
```

## Prioridade (MoSCoW)

| Requisito | MoSCoW | Justificativa |
|-----------|--------|---------------|
| Autenticação Segura | Must | Segurança e privacidade de dados fiscais/financeiros. |
| Consulta de Financeiro | Must | Reduz sobrecarga do departamento de cobrança. |
| Listagem de Notas | Must | Facilita a gestão contábil do cliente final. |
| Edição Cadastral | Could | Conveniência, mas o ERP mestre é a fonte da verdade. |

## Rastreabilidade de Código

| Arquivo | Função / Classe | Cobertura |
|---------|-----------------|-----------|
| `app/control/meu_cliente/LoginClienteForm.php` | `LoginClienteForm` | 🟢 |
| `app/model/ClienteAcesso.php` | `ClienteAcesso` | 🟢 |
| `app/control/meu_cliente/PainelClienteForm.php`| `PainelClienteForm` | 🟢 |
| `app/control/meu_cliente/ClientePublicaFormView.php`| Visualização de dados | 🟢 |
