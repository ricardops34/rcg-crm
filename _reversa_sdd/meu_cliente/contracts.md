# Meu Cliente — Contratos de Interface (API/RPC)

## 1. WebService de Autenticação Cliente (B2B)

- **Método:** `POST`
- **Caminho (Inferido):** `/api/b2b/login`
- **Entrada (JSON):**
    ```json
    {
      "login": "string (CPF/CNPJ)",
      "password": "string"
    }
    ```
- **Saída Sucesso (200 OK):**
    ```json
    {
      "status": "success",
      "session_token": "string",
      "cliente_id": 500,
      "razao_social": "Farma Exemplo LTDA"
    }
    ```

## 2. Consulta de Posição Financeira

- **Método:** `GET`
- **Caminho:** `/api/b2b/financeiro/aberto`
- **Filtro Automático:** `cliente_id` da sessão.
- **Resposta:**
    ```json
    {
      "resumo": {
        "vencido": 1500.50,
        "a_vencer": 4000.00,
        "total": 5500.50
      },
      "titulos": [
        { "numero": "12345/01", "vencimento": "2026-06-01", "valor": 500.00 }
      ]
    }
    ```

## 3. Download de XML/PDF de Nota Fiscal

- **Método:** `GET`
- **Caminho:** `/api/b2b/notas/{id}/danfe`
- **Segurança:** O backend deve validar se o `id` da nota pertence ao `cliente_id` logado antes de liberar o stream do arquivo.
- **Saída:** Binary stream (PDF).
