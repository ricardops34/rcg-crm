# Relatórios — Casos de Borda (Edge Cases)

## 1. Notas Fiscais de Devolução (Diferenciação)
- **Cenário:** O usuário solicita o relatório de faturamento global, mas a base contém notas fiscais de entrada (devolução de cliente) e notas de saída (venda).
- **Comportamento Legado:** O sistema utiliza filtros explícitos na view `view_base_venda` onde `tipo = 'N'` para garantir que apenas vendas efetivas sejam contadas como faturamento positivo. 🟢
- **Risco:** No rebuild, esquecer este filtro resultará em faturamento inflado por notas de devolução ou remessas.

## 2. XML Corrompido ou Incompleto no Banco
- **Cenário:** Por erro de integração do ERP legado, a coluna `xml_sig` contém um texto que não é um XML válido.
- **Comportamento Legado:** A biblioteca `NFePHP` lança uma exceção interna ao tentar parsear o XML. O controller `DanfeErp` capturaria o erro (via `try-catch`) e exibiria a mensagem técnica para o usuário. 🟡
- **Melhoria Sugerida:** Validar o schema XML antes de tentar a renderização PDF para fornecer erro amigável ("XML Inválido").

## 3. Logos de Unidade Diferentes
- **Cenário:** A empresa possui unidades com diferentes logotipos (ex: RCG Filial 2).
- **Comportamento Legado:** O código aponta para um caminho fixo `danfe/rcg_danfe.png`. 🟢
- **Impacto:** Todas as DANFEs saem com o mesmo logotipo, independente da filial emissora.

## 4. Notas Fiscais muito antigas (Dialetos SEFAZ)
- **Cenário:** Consulta de uma nota de 2015 que usava o schema NFe 2.0.
- **Comportamento Legado:** Depende da compatibilidade retroativa da versão instalada da `NFePHP`. 🟡
- **Risco:** Falha de renderização para documentos históricos migrados.
