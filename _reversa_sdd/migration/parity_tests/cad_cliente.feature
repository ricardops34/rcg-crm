# language: pt
Funcionalidade: Cadastro de Cliente (Master Data)
  Como um operador de cadastros
  Eu quero gerenciar dados de clientes no novo sistema Reversa
  Para garantir paridade com as regras de validação do legado RCG

  Contexto:
    Dado que estou autenticado no sistema Reversa
    E possuo permissão para editar clientes

  Cenário: Validação de CPF/CNPJ e Máscaras
    Quando eu acesso "/v2/cadastros/clientes/novo"
    E insiro um CNPJ inválido "00.000.000/0000-00"
    Então o sistema deve exibir o erro de validação PO-UI "CNPJ inválido"
    E o botão "Salvar" deve permanecer desabilitado

  Cenário: Paridade de Persistência (Preservado)
    Quando eu preencho um novo cliente com os dados:
      | Campo         | Valor              |
      | Nome          | Cliente Teste Ltda |
      | CNPJ          | 12.345.678/0001-90 |
      | Vendedor      | 001 - Vendedor A   |
      | Cond. Pagto   | 01 - 30 dias       |
    E clico em "Salvar"
    Então o sistema deve persistir os dados na tabela "pessoa" e "cliente"
    E deve gerar um registro de auditoria na tabela "system_sql_log" idêntico ao legado
    E deve exibir a mensagem de sucesso "Operação realizada com sucesso"

  Cenário: Bloqueio de Cliente
    Dado que o cliente "Cliente Bloqueado" possui status "B"
    Quando eu tento visualizar os detalhes deste cliente
    Então o sistema deve exibir um alerta visual "CLIENTE BLOQUEADO" em vermelho
    E deve impedir a criação de novos orçamentos para este cliente
