# Admin — Contratos de Interface (API/RPC)

Embora o módulo Admin seja consumido primariamente por telas do Adianti (Server-Side), ele expõe lógica de autenticação que pode ser mapeada como contratos de API.

## 1. Serviço de Autenticação (Pseudo-API)

**Endpoint de Login** (Mapeado de `LoginForm::onLogin`)

- **Método:** `POST`
- **Caminho (Inferido):** `/api/auth/login`
- **Entrada (JSON):**
    ```json
    {
      "login": "string",
      "password": "string",
      "reCAPTCHA": "token_string (opcional)",
      "unit_id": "integer (opcional)"
    }
    ```
- **Saída Sucesso (200 OK):**
    ```json
    {
      "status": "success",
      "session_id": "string",
      "user": {
        "id": 1,
        "name": "Ricardo",
        "email": "..."
      },
      "requires_2fa": false,
      "requires_unit_selection": false
    }
    ```
- **Saída Erro (401 Unauthorized):**
    ```json
    {
      "status": "error",
      "message": "Usuário ou senha incorretos"
    }
    ```

## 2. Desafio 2FA

- **Método:** `POST`
- **Caminho (Inferido):** `/api/auth/2fa/verify`
- **Entrada:**
    ```json
    {
      "code": "123456"
    }
    ```
- **Resposta Sucesso (200 OK):** Liberação da sessão completa.

## 3. RBAC (Contrato de Permissões)

Mapeia como o frontend consome a lista de programas autorizados.

- **Símbolo:** `SystemUsers::getPrograms()`
- **Estrutura de Dados Autorizada:**
    ```json
    [
      { "id": 1, "name": "Clientes", "controller": "ClienteList" },
      { "id": 2, "name": "Dashboard", "controller": "DashboardVendedor" }
    ]
    ```

## 4. Integração reCAPTCHA

- **Provedor:** Google reCAPTCHA v2/v3.
- **Protocolo:** HTTPS POST para `https://www.google.com/recaptcha/api/siteverify`.
- **Campos de Retorno Utilizados:** `success` (boolean), `error-codes` (array).
