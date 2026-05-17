# Visão Geral Arquitetural — rcg

Este documento descreve a arquitetura do sistema `rcg`, um ERP baseado no Adianti Framework para gestão comercial, financeira e de força de vendas.

## 1. Estilo Arquitetural

O sistema segue o padrão **MVC (Model-View-Controller)** clássico do Adianti Framework, operando como uma aplicação web monolítica com renderização no lado do servidor (Server-Side Rendering).

- **Monolito Modular:** Embora seja um único deploy, o código é organizado em pastas que representam domínios de negócio (`admin`, `cadastros`, `cobranca`, `vendedor`, etc.).
- **Data-Driven:** A arquitetura é fortemente orientada ao banco de dados, utilizando o padrão **Active Record** (`TRecord`) para persistência e **Database Views** para agregação de indicadores de BI.

## 2. Containers e Tecnologias

| Container | Descrição | Tecnologia |
|-----------|-----------|------------|
| **Web App** | Aplicação PHP que serve as telas e processa a lógica. | PHP 7+, Adianti 4.2 |
| **Frontend**| Interface rica renderizada no browser. | Bootstrap, jQuery, plugins Adianti |
| **API REST** | Interface para integrações e possivelmente o portal B2B. | Mad\Rest\Router |
| **Database** | Bancos de dados relacionais para negócio e auditoria. | MySQL, SQLite, MSSQL (suportados) |
| **File Storage** | Armazenamento de XMLs de NFes e documentos. | Sistema de arquivos local (`app/output`, `files/`) |

## 3. Integrações Externas

- **SEFAZ:** Consulta e transmissão de Notas Fiscais Eletrônicas via biblioteca `NFePHP`.
- **Bancos (Bradesco):** Geração de boletos via `OpenBoleto`.
- **MadBuilder:** Integração com serviços de infraestrutura e desenvolvimento da MadBuilder.
- **ChatWoot:** Integração para atendimento via chat (detectado via código).

## 4. Dívidas Técnicas Identificadas

- **Segurança:** Uso de hashing MD5 em partes do sistema (Portal do Cliente), embora o sistema administrativo já utilize rehash para Bcrypt.
- **Testes:** Ausência quase total de testes automatizados (unitários ou de integração), elevando o risco de regressão em mudanças estruturais.
- **Portabilidade:** Uso intenso de Database Views pode dificultar a migração entre diferentes motores de banco de dados se dialetos específicos forem utilizados.
- **Sessão:** Lógica de permissões espalhada entre variáveis de sessão e Traits, o que pode causar inconsistências se não for centralizada.
