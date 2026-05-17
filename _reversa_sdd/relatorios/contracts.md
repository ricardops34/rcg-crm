# Relatórios — Contratos de Interface (API/RPC)

## 1. Serviço de Obtenção de DANFE

Endpoint consumido tanto pelo painel Administrativo quanto pelo Portal do Cliente.

- **Método:** `GET`
- **Caminho (Inferido):** `/api/fiscal/notas/{id}/danfe`
- **Parâmetros:** `id` (nota_saida_id) ou `chave` (44 dígitos).
- **Cabeçalho de Resposta:** `Content-Type: application/pdf`.
- **Comportamento:** Stream binário direto para o browser.

## 2. WebService de Busca de Nota por Chave

- **Método:** `POST`
- **Caminho:** `/api/fiscal/notas/search`
- **Payload:**
    ```json
    {
      "chave": "35230501234567890123550010000000011234567890"
    }
    ```
- **Resposta:** Redirecionamento para a tela de visualização ou metadados básicos da nota.

## 3. Contrato de Integração NFePHP (Layout)

- **Entrada:** String XML (UTF-8) assinada.
- **Configurações Adicionais:**
    - `logo`: `danfe/rcglogo.png`
    - `especie`: `SPED` (conforme filtros de banco). 🟢

> **Nota para Rebuild:** Na migração para Node.js, Python ou Go, deve-se buscar bibliotecas que implementem o padrão **PL_009** (NFe 4.00) para garantir compatibilidade com as regras atuais da SEFAZ brasileira.
