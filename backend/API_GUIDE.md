# 🚀 Guia de Uso da API — RCG CRM (v2)

Bem-vindo à documentação técnica da API do RCG CRM. Este guia descreve como autenticar, consumir recursos e integrar dados com o sistema.

---

## 📍 Base URL
Todas as requisições devem ser feitas para:
`http://localhost:3000` (Desenvolvimento)
`https://api-crm.bjsoft.com.br` (Produção)

---

## 🔐 1. Autenticação e Segurança

O sistema utiliza **JWT (JSON Web Token)** e implementa o padrão **Single Session** (apenas um login ativo por usuário).

### 1.1 Login Básico
**POST** `/auth/login`
```json
{
  "login": "seu_usuario",
  "password": "sua_senha"
}
```

**Retornos possíveis:**
- **200 OK**: Se o login for direto (usuários sem 2FA/Termos pendentes).
- **200 OK (nextStep: 2FA)**: Usuário precisa validar código enviado por e-mail.
- **200 OK (nextStep: TERMS)**: Usuário precisa aceitar os termos de uso.

### 1.2 Fluxo de Desafio (2FA / TERMS)
Se o login retornar um `accessToken` com um `nextStep`, esse token possui **escopo limitado**. Você deve usá-lo para concluir o desafio:

- **Validar 2FA**: `POST /auth/verify-2fa` (Body: `{ "code": "123456" }`)
- **Aceitar Termos**: `POST /auth/accept-terms` (Sem body)

Após concluir o desafio, você receberá o **Token Final** com acesso total.

### 1.3 Usando o Token
Em todas as rotas protegidas, envie o cabeçalho:
`Authorization: Bearer <seu_token_final>`

---

## 🔄 2. Integração e Sincronização (ERP Legado)

O sistema foi desenhado para receber dados do ERP legado de forma incremental.

### 2.1 Padrão de Sincronização (`SyncManager`)
Todos os endpoints em `/sync/*` aceitam o padrão de lote:
**POST** `/sync/master-data/clientes`
```json
{
  "conteudo": [
    {
      "cod_erp": "C001",
      "razao": "Cliente Teste",
      "cnpj_cpf": "00.000.000/0001-00",
      "vendedor": "V010" // O sistema resolve o ID interno pelo cod_erp do vendedor
    }
  ]
}
```

**Endpoints disponíveis:**
- `/sync/master-data/produtos`
- `/sync/master-data/vendedores`
- `/sync/sales/pedidos`
- `/sync/finance/titulos`

---

## 📊 3. BI e Analytics

Endpoints otimizados para dashboards reativos.

### 3.1 Dashboard de Vendas
**GET** `/analytics/dashboard?year=2024&month=05&vendedorId=10`
Retorna KPIs de atingimento, metas e vendas por categoria.

### 3.2 Média de Venda do Cliente (MVC)
**GET** `/analytics/mvc?year=2024`
Retorna a situação de toda a carteira de clientes, calculando automaticamente a média dos últimos 3 meses e a tendência de queda ou alta.

---

## 📱 4. Integração WhatsApp (Bot)

Serviço especializado para consulta externa rápida.

### 4.1 Posição Financeira por Telefone
**GET** `/whatsapp/telefone/5567999999999`
Retorna um resumo financeiro formatado do cliente vinculado ao número:
```json
{
  "cliente": { "razao": "...", "status": "A" },
  "financeiro": {
    "vencido": 1500.50,
    "a_vencer": 300.00,
    "situacao": "INADIMPLENTE"
  }
}
```

---

## ⚠️ 5. Tratamento de Erros

| Código | Descrição | Motivo |
|--------|-----------|--------|
| **401** | Unauthorized | Token expirado ou **sessão derrubada** (outro login realizado). |
| **403** | Forbidden | Usuário logado mas sem permissão (**RBAC**) para este recurso. |
| **400** | Bad Request | Dados de entrada inválidos ou falha em regra de negócio. |

---

## 📖 6. Documentação Completa (Swagger)
Para visualizar todos os modelos de dados (Schemas) e testar endpoints em tempo real, acesse:
👉 `/docs` no seu navegador.
