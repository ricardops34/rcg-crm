# ADR 001: Migração Transparente de Hashing de Senhas

## Status
Aceito (Retroativo) 🟢

## Contexto
O sistema possuía uma base legada de senhas armazenadas em MD5 (considerado inseguro para padrões modernos). Havia a necessidade de migrar para `PASSWORD_DEFAULT` (Bcrypt) sem forçar o usuário a trocar de senha ou invalidar os hashes atuais.

## Decisão
Implementar um mecanismo de "rehash on login" dentro do método `SystemUsers::authenticate()`. 
Ao validar uma senha:
1. Tenta validar via Bcrypt (`password_verify`).
2. Se falhar, tenta validar via MD5 (`hash_equals` + `md5`).
3. Se o MD5 for bem-sucedido, gera o novo hash Bcrypt e atualiza o banco de dados imediatamente.

## Alternativas consideradas
- **Troca de Senha Obrigatória:** Descartada por impactar a experiência do usuário e aumentar o suporte técnico.
- **Manter MD5:** Descartada por questões de segurança e conformidade (LGPD).

## Consequências
- **Positivas:** Migração orgânica e indolor; aumento imediato da segurança para usuários ativos.
- **Negativas:** Pequena sobrecarga de CPU no primeiro login após a mudança; o código de autenticação carrega a lógica legada por tempo indeterminado.
