# Relatórios — Requisitos

## Visão Geral
Esta unit gerencia a visualização analítica, faturamento detalhado e a conformidade fiscal do sistema. Ela é responsável por transformar dados brutos em documentos oficiais (DANFE) e relatórios operacionais que suportam o controle de faturamento e a gestão de ativos em regime de comodato.

## Responsabilidades
- Gerar o Documento Auxiliar da Nota Fiscal Eletrônica (DANFE) a partir de XMLs autorizados. 🟢
- Fornecer visão detalhada de itens faturados com regras de impostos e descontos. 🟢
- Monitorar e listar ativos da empresa sob custódia de terceiros (Comodatos). 🟢
- Centralizar o acesso a documentos fiscais para usuários administrativos e clientes. 🟢
- Rastrear o faturamento por cliente e período. 🟢

## Regras de Negócio
- **Conformidade Fiscal:** A geração de DANFE deve seguir rigorosamente o padrão SEFAZ, utilizando o XML original armazenado em banco de dados. 🟢
- **Integridade de Faturamento:** Apenas notas fiscais ativas (`reg_ativo = 'S'`) e com espécie fiscal 'SPED' são consideradas para fins de relatórios analíticos de venda. 🟢
- **Visualização de Itens:** O detalhamento de uma nota deve exibir valores de tabela, percentuais de desconto e valor líquido individual por item, garantindo rastreabilidade comercial. 🟢
- **Regime de Comodato:** Itens marcados como comodato em notas de saída devem ser identificados para controle de patrimônio externo. 🟢

## Requisitos Funcionais

| ID | Requisito | Prioridade | Critério de Aceite |
|----|-----------|-----------|-------------------|
| RF-01 | Emissão de DANFE | Must | Recuperar XML do banco e renderizar PDF conforme regras da NFePHP. |
| RF-02 | Detalhe de Nota Fiscal| Must | Exibir cabeçalho e itens de faturamento com cálculo de descontos. |
| RF-03 | Listagem de Comodatos | Should | Filtrar notas fiscais que contenham movimentação de ativos em comodato. |
| RF-04 | Busca por Chave NFe | Must | Permitir localizar e visualizar documentos fiscais via chave de 44 dígitos. |
| RF-05 | Histórico de Emissão | Should | Visualizar datas de autorização e protocolos SEFAZ vinculados ao documento. |

## Requisitos Não Funcionais

| Tipo | Requisito inferido | Evidência no código | Confiança |
|------|--------------------|---------------------|-----------|
| Segurança | Download de documentos condicionado a login/permissão | `app/control/relatorios/Danfe.php` | 🟢 |
| Performance | Armazenamento de XML em banco para evitar I/O excessivo | `app/database/erp_online-pgsql.sql` (`notasaida_xml`) | 🟢 |
| Interoperabilidade| Uso de biblioteca padrão de mercado (NFePHP) | `composer.json` | 🟢 |

## Critérios de Aceitação

```gherkin
Cenário: Geração de PDF da DANFE
Dado que existe um registro de nota fiscal com XML autorizado em `notasaida_xml`
Quando o usuário administrativo solicitar a visualização da DANFE
Então o sistema deve gerar um arquivo PDF temporário
E abrir o documento no navegador do usuário via `TPage::openFile`

Cenário: Nota Fiscal sem XML
Dado uma nota fiscal faturada mas que não possui o XML correspondente no banco
Quando o usuário tentar gerar a DANFE
Então o sistema deve exibir alerta "Nota Fiscal não Localizada" e impedir o fluxo
```

## Prioridade (MoSCoW)

| Requisito | MoSCoW | Justificativa |
|-----------|--------|---------------|
| Visualização de DANFE | Must | Requisito legal para circulação de mercadorias e contabilidade. |
| Detalhamento de Itens | Must | Necessário para conferência comercial de preços e descontos. |
| Relatórios de Comodato | Should | Importante para controle de ativos, mas não bloqueia o faturamento. |
| Histórico de Protocolos | Could | Informação técnica útil para auditoria fiscal. |

## Rastreabilidade de Código

| Arquivo | Função / Classe | Cobertura |
|---------|-----------------|-----------|
| `app/control/relatorios/Danfe.php` | `DanfeErp` | 🟢 |
| `app/control/relatorios/NotaSaidaFormView.php` | `NotaSaidaFormView` | 🟢 |
| `app/control/relatorios/ComodatoNotafiscalSimpleList.php` | `ComodatoNotafiscal` | 🟢 |
| `app/model/NotasaidaXml.php` | `NotasaidaXml` | 🟢 |
