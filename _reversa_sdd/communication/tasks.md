# Comunicação, Tarefas de Implementação

## Pré-requisitos
- [ ] Banco de dados `communication` criado e acessível.
- [ ] Banco `permission` (Admin) funcional para resolução de usuários/remetentes.
- [ ] Mecanismo de roteamento configurado para aceitar URLs serializadas.

## Tarefas

- [ ] T-01, Implementar Motor de Notificações Global
  - Origem no legado: `app/model/communication/SystemNotification.php`
  - Critério de pronto: Método estático capaz de criar alertas vinculados a uma classe/método (Action) e um usuário.
  - Confiança: 🟢

- [ ] T-02, Criar Serviço de Mensageria Interna
  - Origem no legado: `app/model/communication/SystemMessage.php` e `SystemMessageForm.php`
  - Critério de pronto: Possibilitar a troca de mensagens de texto entre IDs de `system_users` com status de leitura.
  - Confiança: 🟢

- [ ] T-03, Central de Documentos com Controle de Acesso
  - Origem no legado: `app/model/communication/SystemDocument.php` e `SystemDocumentGroup.php`
  - Critério de pronto: Implementar lógica de permissão multinível (Público, por Grupo, por Usuário) para visualização de arquivos.
  - Confiança: 🟢

- [ ] T-04, Implementar Badge de Alertas na Master Page
  - Origem no legado: Lógica de carregamento global no cabeçalho do sistema.
  - Critério de pronto: Exibir contagem de itens `checked='N'` no menu superior com atualização periódica.
  - Confiança: 🟡

- [ ] T-05, Componente de Upload e Download Seguro
  - Origem no legado: `app/control/communication/SystemDocumentUploadForm.php`
  - Critério de pronto: Validar tipo de arquivo e persistir no storage, protegendo a URL de download contra acesso direto sem sessão.
  - Confiança: 🟢

## Tarefas de Teste

- [ ] TT-01, Enviar notificação para o Usuário A e validar se o Usuário B não a visualiza.
- [ ] TT-02, Validar se o clique na notificação executa o redirecionamento correto (Action).
- [ ] TT-03, Testar restrição de documento: usuário fora do grupo autorizado não deve conseguir o link de download.
- [ ] TT-04, Validar se a deleção de um usuário não quebra a visualização de mensagens antigas enviadas por ele.

## Tarefas de Migração de Dados (se aplicável)

- [ ] TM-01, Migrar tabelas do banco `communication` legado, preservando os vínculos de ID com o banco `permission`.

## Ordem Sugerida
1. T-01: Notificações são o componente mais utilizado transversalmente no sistema.
2. T-03 e T-05: Documentação de suporte.
3. T-02: Mensageria opcional.

## Lacunas Pendentes (🔴)
- Avaliar a evolução do sistema de notificações para usar **Push Web (VAPID)** ou **WebSockets** (Socket.io/SignalR) para eliminar o custo de polling no banco de dados.
