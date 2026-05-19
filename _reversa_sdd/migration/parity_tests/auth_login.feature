# language: pt
Funcionalidade: Login com Rehash de Senha
  Como um usuário do sistema legado
  Eu quero me autenticar no novo sistema Reversa
  Para que meu acesso seja preservado e minha segurança seja modernizada (MD5 para Bcrypt)

  Contexto:
    Dado que o usuário "joao.silva" existe no banco de dados com senha "123456" em MD5
    E o sistema Reversa está configurado para o modo Strangler Fig com Identity Bridge

  Cenário: Login bem-sucedido com conversão de Hash
    Quando eu acesso a tela de login "/auth/login"
    E preencho o campo "Usuário" com "joao.silva"
    E preencho o campo "Senha" com "123456"
    E clico no botão "Entrar"
    Então o sistema deve validar a senha contra o hash MD5 legado
    E deve gerar um novo hash Bcrypt para a senha "123456"
    E deve atualizar o campo "password" no banco de dados
    E deve retornar um JWT válido com as permissões do usuário
    E deve redirecionar para o dashboard inicial "/v2/dashboard"

  Cenário: Login bloqueado por usuário inativo
    Dado que o usuário "pedro.inativo" possui status "N" (Inativo)
    Quando eu tento fazer login com "pedro.inativo" e senha "654321"
    Então o sistema deve retornar a mensagem "Usuário inativo. Contate o administrador."
    E não deve permitir o acesso ao sistema
