# Meu Cliente, Design Técnico

## Interface

### Models e Controllers Principais

| Símbolo | Assinatura | Retorno | Observação |
|---------|-----------|---------|------------|
| `LoginClienteForm.onLogin` | `($param)` | `void` | Autentica contra `cliente_acesso`. 🟢 |
| `PainelClienteForm.onShow` | `($param)` | `void` | Renderiza dashboard e valida permissão. 🟢 |
| `ClienteAcesso` | Active Record | - | Credenciais B2B vinculadas a `cliente_id`. 🟢 |

## Fluxo Principal (Autenticação B2B)

1. **Início:** O cliente acessa a URL pública do portal.
2. **Entrada:** Informa CPF/CNPJ (Login) e Senha.
3. **Validação (`onLogin`):**
    - O sistema limpa a sessão atual (`TSession::freeSession()`).
    - Consulta `ClienteAcesso` onde `login == $login` AND `senha == md5($password)`. 🟢
4. **Estabelecimento de Sessão:**
    - Se encontrar o registro, define:
        - `cliente_logado = true`
        - `cliente_id = [id_do_cliente]`
        - `userid = 0` (Diferencia de usuário administrativo). 🟢
5. **Redirecionamento:** Encaminha o usuário para o `PainelClienteForm`.

## Fluxo de Consulta de Dados (Isolamento)

Para garantir que um cliente não veja dados de outro, o sistema aplica um filtro compulsório:

1. **Verificação:** No construtor de qualquer classe do portal (ex: `PainelClienteForm`), o sistema checa `TSession::getValue('cliente_logado')`.
2. **Filtro SQL:** Todas as listagens (Grids) utilizam um repositório filtrado:
    ```php
    $criteria = new TCriteria;
    $criteria->add(new TFilter('cliente_id', '=', TSession::getValue('cliente_id')));
    ```
3. **Segurança:** Se a variável de sessão estiver vazia ou for inválida, o sistema dispara um redirecionamento forçado para a tela de login. 🟢

## Dependências

- **Adianti TRecord / TCriteria:** Mecanismos de persistência e filtragem. 🟢
- **MD5:** Algoritmo de hash de senha utilizado para o portal (conforme identificado no `onLogin`). 🟢
- **Database `erp_online`:** O portal consome os dados do banco principal, mas via repositórios restritos. 🟢

## Decisões de Design Identificadas

| Decisão | Evidência no código | Confiança |
|---------|---------------------|-----------|
| Credenciais em tabela dedicada | `app/model/ClienteAcesso.php` | 🟢 |
| Uso de flag de sessão `cliente_logado`| `app/control/meu_cliente/LoginClienteForm.php` | 🟢 |
| Layout simplificado para B2B | `app/control/meu_cliente/FormLoginCliente.php` | 🟢 |

## Estado Interno

- **Sessão:** Mantém o vínculo persistente com o `cliente_id` durante toda a navegação.
- **Auditoria:** O campo `dt_alteracao` em `ClienteAcesso` registra a última mudança de credenciais. 🟢

## Observabilidade

- Não foram encontrados logs de auditoria de acesso (`system_access_log`) específicos para o portal B2B no código analisado (parecem restritos ao portal Admin). 🟡

## Riscos e Lacunas

- 🔴 **Hashing de Senha:** O uso de MD5 no portal do cliente é uma vulnerabilidade de segurança. No rebuild, deve-se migrar obrigatoriamente para Bcrypt/Argon2.
- 🟡 **Reset de Senha:** Não foi encontrado fluxo automatizado de "Esqueci minha senha" (self-service) no código; a criação e alteração parecem depender de ação administrativa ou contato prévio.
