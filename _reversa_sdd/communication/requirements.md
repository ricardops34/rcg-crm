# Comunicação — Requisitos

## Visão Geral
Esta unit gerencia a colaboração interna entre os usuários do sistema, fornecendo ferramentas de mensageria direta, notificações automáticas baseadas em eventos e uma central de documentos compartilhados. Ela garante que a equipe administrativa e de vendas esteja alinhada e informada sobre as movimentações do ERP.

## Responsabilidades
- Facilitar a troca de mensagens diretas entre usuários administrativos. 🟢
- Alertar usuários sobre eventos críticos (ex: novos orçamentos, títulos vencidos) via notificações. 🟢
- Armazenar e organizar documentos e arquivos por categoria. 🟢
- Controlar o acesso a documentos por perfil de usuário ou grupo. 🟢
- Rastrear a leitura de mensagens e notificações. 🟢

## Regras de Negócio
- **Notificações Contextuais:** Toda notificação deve permitir a vinculação de uma ação (`action_url`), permitindo que o usuário navegue diretamente para o registro que originou o alerta. 🟢
- **Privacidade de Documentos:** Documentos podem ser marcados como "Públicos" (todos os usuários logados) ou restritos a Grupos/Usuários específicos. 🟢
- **Controle de Leitura:** O sistema deve diferenciar visualmente mensagens/notificações "Não Lidas" de "Lidas" via flag `checked`. 🟢
- **Integridade de Remetente:** Mensagens internas são imutáveis e devem manter o vínculo com o `system_user_id` original mesmo após deleção lógica (se aplicada). 🟢

## Requisitos Funcionais

| ID | Requisito | Prioridade | Critério de Aceite |
|----|-----------|-----------|-------------------|
| RF-01 | Envio de Mensagens | Must | Permitir compor e enviar texto para um ou mais destinatários internos. |
| RF-02 | Alertas do Sistema | Must | Registrar notificações automáticas via código (backend) para usuários alvo. |
| RF-03 | Gestão de Documentos | Must | Upload de arquivos físicos com metadados e categorias. |
| RF-04 | Controle de Acesso | Should | Bloquear download de documentos para usuários não autorizados na unit. |
| RF-05 | Badge de Contagem | Should | Exibir no cabeçalho do sistema a quantidade de mensagens/alertas novos. |

## Requisitos Não Funcionais

| Tipo | Requisito inferido | Evidência no código | Confiança |
|------|--------------------|---------------------|-----------|
| Segurança | Controle de acesso a nível de registro (Documentos) | `app/model/communication/SystemDocument.php` | 🟢 |
| Usabilidade | Uso de ícones FontAwesome para identificação de alertas | `app/model/communication/SystemNotification.php` | 🟢 |
| Integridade | Armazenamento de XML/PDFs em diretórios protegidos | `app/control/communication/SystemDocumentForm.php` | 🟡 |

## Critérios de Aceitação

```gherkin
Cenário: Notificação de Novo Orçamento
Dado que um vendedor salvou um novo orçamento
Quando a regra de negócio disparar o alerta para o supervisor
Então um registro deve ser criado em system_notification
E o supervisor deve ver um badge incremental no menu superior

Cenário: Restrição de Documento
Dado um documento na categoria "RH" restrito ao grupo "Gerência"
Quando um usuário do grupo "Vendedor" tentar acessar a lista de documentos
Então o documento da categoria "RH" não deve ser listado para ele
```

## Prioridade (MoSCoW)

| Requisito | MoSCoW | Justificativa |
|-----------|--------|---------------|
| Notificações de Eventos | Must | Essencial para o fluxo de trabalho reativo (aprovações/alertas). |
| Upload de Documentos | Must | Centralização de contratos e anexos do ERP. |
| Mensagens Diretas | Should | Importante, mas pode ser substituído por ferramentas externas (Slack/WhatsApp). |
| Categorização de Docs | Could | Organização melhora o uso, mas não impede a função básica. |

## Rastreabilidade de Código

| Arquivo | Função / Classe | Cobertura |
|---------|-----------------|-----------|
| `app/model/communication/SystemMessage.php` | `SystemMessage` | 🟢 |
| `app/model/communication/SystemNotification.php` | `SystemNotification` | 🟢 |
| `app/control/communication/SystemDocumentForm.php`| `SystemDocumentForm` | 🟢 |
| `app/model/communication/SystemDocumentUser.php` | `SystemDocumentUser` | 🟢 |
