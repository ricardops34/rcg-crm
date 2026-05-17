# Comunicação — Casos de Borda (Edge Cases)

## 1. Notificação para Usuário Deletado
- **Cenário:** O sistema dispara uma notificação automática para um supervisor, mas o registro deste supervisor foi deletado ou inativado instantes antes.
- **Comportamento Legado:** O registro é criado em `system_notification` com o ID do usuário, mas nunca será lido ou exibido, tornando-se dado órfão no banco `communication`. 🟢
- **Risco:** Inchaço desnecessário da tabela de notificações.

## 2. Documento com Múltiplas Restrições Conflitantes
- **Cenário:** Um documento está liberado para o "Grupo Vendedores", mas restrito explicitamente para o "Usuário João" (que é um vendedor).
- **Comportamento Legado:** O sistema utiliza lógica aditiva. Se o usuário pertence ao Grupo OU está na lista de Usuários, ele ganha acesso. Não há lógica de "Deny" (Negativa explícita) sobreposta ao "Allow". 🟢
- **Impacto:** Impossibilidade de criar exceções restritivas dentro de um grupo permitido.

## 3. Caractere Especial em Nome de Arquivo (Upload)
- **Cenário:** Usuário faz upload de um PDF com nome "Relatório #123 (Final).pdf".
- **Comportamento Legado:** O sistema Adianti geralmente sanitiza o nome do arquivo no disco, mas mantém a referência original no banco. 🟡
- **Risco no Rebuild:** Quebra de links de download se a codificação de URL não tratar caracteres como `#`, `&` ou espaços.

## 4. Estouro de Notificações (Spam de Sistema)
- **Cenário:** Um processo em loop ou erro em lote gera 10.000 notificações para o mesmo usuário.
- **Comportamento Legado:** O banco aceita todos os registros. A interface pode travar ou ficar extremamente lenta ao tentar renderizar a lista de notificações no cabeçalho. 🟡
- **Recomendação:** Implementar throttling ou agrupamento de notificações similares.
