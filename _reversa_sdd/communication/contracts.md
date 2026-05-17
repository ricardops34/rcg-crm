# Comunicação — Contratos de Interface (API/RPC)

## 1. WebService de Notificações (Polling)

Utilizado pelo cabeçalho da aplicação para manter os badges atualizados.

- **Método:** `GET`
- **Caminho (Inferido):** `/api/communication/notifications/unread`
- **Filtros:** `system_user_id` (via Token/Sessão)
- **Resposta (JSON):**
    ```json
    {
      "total": 3,
      "items": [
        {
          "id": 101,
          "message": "Novo orçamento gerado: #450",
          "icon": "fa-file-invoice",
          "action_url": "class=OrcamentoForm&method=onEdit&id=450"
        }
      ]
    }
    ```

## 2. Endpoint de Upload de Documentos

- **Método:** `POST`
- **Caminho:** `/api/communication/document/upload`
- **Content-Type:** `multipart/form-data`
- **Payload:**
    ```json
    {
      "category_id": 1,
      "description": "Contrato Social",
      "file": "binary_data"
    }
    ```
- **Resposta:** `201 Created` com o ID do documento.

## 3. Serialização de Ações (TAction Pattern)

O sistema utiliza um padrão proprietário do Adianti para representar callbacks de UI em formato string.

**Formato:** `class=[NomeDaClasse]&method=[NomeDoMetodo]&[param1]=[valor1]...`

**Exemplos mapeados:**
- Cobrança: `class=NegociacaoList&method=onShow&cliente_id=5`
- Dashboard: `class=DashboardVendedor&method=onShow`
- Cadastro: `class=ClienteForm&method=onEdit&id=1000`

> **Nota para Rebuild:** No rebuild em tecnologias modernas (React/Vue/Flutter), este padrão deve ser mapeado para **Deep Links** ou **Roteamento Nomeado** (ex: `/negociacoes/cliente/5`).
