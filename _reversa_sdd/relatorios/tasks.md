# Relatórios, Tarefas de Implementação

## Pré-requisitos
- [ ] Bibliotecas de manipulação de XML e geração de PDF (equivalentes à NFePHP) disponíveis na linguagem alvo.
- [ ] Banco de dados `portal_erp` (com a tabela `notasaida_xml`) acessível.
- [ ] Pasta de output temporário com permissões de escrita.
- [ ] Logotipo da empresa (`danfe/rcg_danfe.png`) migrado para o novo storage.

## Tarefas

- [ ] T-01, Implementar Motor de Recuperação de XML
  - Origem no legado: `app/model/NotasaidaXml.php`
  - Critério de pronto: Função capaz de buscar o XML assinado por `nota_saida_id` ou `chave_nfe` no banco `portal_erp`.
  - Confiança: 🟢

- [ ] T-02, Serviço de Geração de DANFE (PDF)
  - Origem no legado: `app/control/relatorios/Danfe.php`
  - Critério de pronto: Transformar o XML bruto em um documento PDF formatado conforme o padrão nacional da NFe.
  - Confiança: 🟢

- [ ] T-03, Criar Visualização Detalhada de Nota Fiscal
  - Origem no legado: `app/control/relatorios/NotaSaidaFormView.php`
  - Critério de pronto: Tela que exibe cabeçalho resumido e grade de itens, com foco na conferência comercial (Preço vs Desconto).
  - Confiança: 🟢

- [ ] T-04, Relatório Analítico de Comodatos
  - Origem no legado: `app/control/relatorios/ComodatoNotafiscalSimpleList.php`
  - Critério de pronto: Listagem que filtra notas fiscais baseada em itens de regime de comodato ( Ativos da empresa em terceiros).
  - Confiança: 🟢

- [ ] T-05, Componente de Download e Impressão
  - Origem no legado: `TPage::openFile()`
  - Critério de pronto: Fornecer stream de download seguro para o usuário administrativo e para o portal B2B.
  - Confiança: 🟢

## Tarefas de Teste

- [ ] TT-01, Gerar DANFE de uma nota antiga e comparar o layout com o original (Fidelidade Visual).
- [ ] TT-02, Tentar gerar DANFE de nota sem XML e validar se o sistema retorna o erro tratado corretamente.
- [ ] TT-03, Validar se notas fiscais marcadas como `reg_ativo = 'N'` (Canceladas) são omitidas dos relatórios operacionais.
- [ ] TT-04, Verificar o consumo de memória ao processar notas com mais de 500 itens.

## Tarefas de Migração de Dados (se aplicável)

- [ ] TM-01, Migrar os blobs/textos da tabela `notasaida_xml`. **Atenção:** Volume de dados pode ser alto; planejar migração em lotes.

## Ordem Sugerida
1. T-01 e T-02: Funcionalidade crítica (obrigação fiscal).
2. T-03: Visualização operacional.
3. T-04: Relatórios secundários.

## Lacunas Pendentes (🔴)
- Validar se o novo sistema fará a transmissão direta para a SEFAZ ou apenas a visualização de notas já emitidas pelo ERP legado. Se houver transmissão, o escopo da unit aumenta significativamente para incluir Assinatura Digital (Certificados A1/A3).
