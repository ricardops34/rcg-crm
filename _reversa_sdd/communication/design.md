# Comunicação, Design Técnico

## Interface

### Endpoints / Classes Principais (Service Layer)

| Símbolo | Assinatura | Retorno | Observação |
|---------|-----------|---------|------------|
| `SystemNotification.register` | `($user_to, $msg, $action, $label, $icon)` | `void` | Método estático global para disparar alertas. 🟢 |
| `SystemMessage.get_user_mixed` | `()` | `SystemUsers` | Resolve o remetente/destinatário conforme o contexto da sessão. 🟢 |
| `SystemDocument.get_users` | `()` | `array` | Retorna lista de usuários com acesso explícito ao documento. 🟢 |

## Fluxo Principal (Sistema de Notificações)

1. **Gatilho:** Qualquer unit (ex: `cobranca`, `vendedor`) invoca `SystemNotification::register()`.
2. **Registro:** 
    - Inicia transação no banco `communication`.
    - Instancia `SystemNotification`.
    - Popula `action_url` com a serialização do objeto `TAction` (ex: `class=PedidoList&method=onShow&id=123`). 🟢
    - Define `checked = 'N'`.
    - Salva o registro.
3. **Consumo (UI):**
    - O cabeçalho da aplicação (em cada request ou via polling/ajax) consulta `SystemNotification` filtrando por `system_user_id` do logado e `checked = 'N'`.
    - Exibe o contador (Badge) e a lista resumida de notificações. 🟢
4. **Ação:**
    - O usuário clica na notificação.
    - O sistema invoca a URL em `action_url`.
    - Marca `checked = 'Y'` no registro correspondente. 🟢

## Fluxo de Mensageria Interna

1. **Composição:** O usuário abre `SystemMessageForm`.
2. **Envio:** Seleciona destinatário(s) e escreve o assunto/mensagem.
3. **Persistência:** Grava em `system_message` vinculando `system_user_id` (remetente) e `system_user_to_id` (destinatário). 🟢
4. **Visualização:** O destinatário recebe o alerta e visualiza a mensagem via `SystemMessageFormView`.

## Dependências

- **Adianti Framework:** Core da aplicação (Persistência, Sessão, UI). 🟢
- **Database `communication`:** Banco de dados isolado para dados de interação. 🟢
- **FontAwesome:** Provedor de ícones para o sistema de alertas. 🟢

## Decisões de Design Identificadas

| Decisão | Evidência no código | Confiança |
|---------|---------------------|-----------|
| Banco de Dados de Comunicação Isolado | `app/config/communication.php` | 🟢 |
| Ações Serializadas (TAction) em Notificações | `app/model/communication/SystemNotification.php:register` | 🟢 |
| Controle N:N para acesso a documentos | `app/model/communication/SystemDocumentGroup.php` | 🟢 |

## Estado Interno

- **Sessão:** A contagem de notificações não lidas é frequentemente recalculada e exibida globalmente.
- **Flags de Leitura:** O sistema depende do campo `checked` para manter o histórico de consumo.

## Observabilidade

- **Logs de Notificação:** O sistema registra quem recebeu e quando visualizou o alerta. 🟢

## Riscos e Lacunas

- 🔴 **Push Real-time:** Não foi detectado uso de WebSockets ou Firebase Cloud Messaging. O sistema parece depender de refresh de página ou polling AJAX simples para atualizar os badges.
- 🟡 **Anexos em Mensagens:** O modelo `SystemMessage` não possui campo de anexo; o compartilhamento de arquivos deve ser feito via unit de Documentos.
