# Relatórios, Design Técnico

## Interface

### Endpoints e Classes de Apoio

| Símbolo | Assinatura | Retorno | Observação |
|---------|-----------|---------|------------|
| `DanfeErp.__construct` | `($param: array)` | `void` | Inicia a recuperação e renderização da DANFE. 🟢 |
| `NotaSaidaFormView.onShow` | `($param: array)` | `void` | Carrega os detalhes do faturamento. 🟢 |
| `NotasaidaXml` | Active Record | - | Abstração da tabela de armazenamento de XMLs. 🟢 |
| `NFePHP\DA\NFe\Danfe` | Library Class | `PDF` | Motor externo de renderização fiscal. 🟢 |

## Fluxo Principal (Emissão de DANFE)

1. **Entrada:** Recebe `id` da nota ou `chave` via `$param`.
2. **Conexão:** Abre transação no banco `portal_erp` (conforme código legado). 🟢
3. **Busca:** 
    - Realiza query: `SELECT xml_sig FROM notasaida_xml WHERE nota_saida_id = :id`.
4. **Validação:** 
    - Se o retorno for vazio, dispara `TMessage` informativa.
5. **Preparação:**
    - O conteúdo XML (`xml_sig`) é salvo em um arquivo temporário físico no diretório `app/output/`. 🟡
6. **Processamento:**
    - Instancia `NFePHP\DA\NFe\Danfe` passando o caminho do XML e as configurações da logomarca (`danfe/rcg_danfe.png`). 🟢
7. **Saída:**
    - O PDF gerado é disponibilizado para o browser através de `TPage::openFile()`. 🟢

## Fluxo de Visualização Analítica (Faturamento)

1. **Acesso:** Invocado via `NotaSaidaFormView`.
2. **Dados de Cabeçalho:**
    - Carrega `Cliente`, `Vendedor` e `Transportadora` (relacionamentos herdados de `TRecord`). 🟢
3. **Dados de Itens:**
    - Aplica um filtro de `criteria` na tabela `nota_saida_item` baseado no `nota_saida_id`.
    - Ordena os itens pela sequência original (`item asc`). 🟢
4. **Cálculos na Grade:**
    - Exibe `vlr_unitario`, `vlr_tabela` e `vlr_desconto` para conferência de margens. 🟢
5. **Navegação:**
    - Botão de ação que salta para o fluxo de "Emissão de DANFE" descrito acima.

## Dependências

- **NFePHP Library:** Essencial para a conformidade do layout da DANFE. 🟢
- **Adianti TQuickGrid:** Utilizado para a listagem rápida e performática dos itens de nota. 🟢
- **portal_erp DB:** Banco de dados específico que armazena os metadados fiscais (XML). 🟢

## Decisões de Design Identificadas

| Decisão | Evidência no código | Confiança |
|---------|---------------------|-----------|
| Armazenamento de XML como Texto/Blob | `app/database/erp_online-pgsql.sql` | 🟢 |
| PDF sob demanda (não persistente) | `app/control/relatorios/Danfe.php` | 🟢 |
| Separação de Bancos (Transaction) | `Danfe.php:TTransaction::open('portal_erp')` | 🟢 |

## Estado Interno

O módulo de relatórios é majoritariamente **Stateless**:
- Não altera dados, apenas lê e formata.
- A persistência temporária do PDF e XML em `app/output/` é transitória e limpa pelo sistema operacional ou scripts de cleanup do framework. 🟡

## Observabilidade

- Nenhuma métrica ou log customizado identificado para geração de relatórios, além dos logs de SQL globais.

## Riscos e Lacunas

- 🔴 **Versão do XML:** O código não demonstra tratamento para diferentes versões de NFe (ex: 3.10 vs 4.0), delegando isso totalmente à biblioteca `NFePHP`. No rebuild, garantir que o motor de renderização suporte as versões históricas presentes no banco.
- 🟡 **Consumo de Memória:** A geração de PDFs de notas muito grandes (centenas de itens) pode exigir aumento no `memory_limit` do PHP, o que é um ponto de atenção na infraestrutura alvo.
