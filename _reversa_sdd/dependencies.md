# Dependências do Projeto — rcg

## Composer (composer.json)

| Pacote | Versão | Descrição |
|--------|--------|-----------|
| `phpmailer/phpmailer` | `^6.9.1` | Envio de e-mails |
| `picqer/php-barcode-generator` | `^2.4.0` | Geração de códigos de barras |
| `dompdf/dompdf` | `^2.0.4` | Geração de PDFs |
| `bacon/bacon-qr-code` | `^2.0.7` | Geração de QR Codes |
| `firebase/php-jwt` | `^6.10.0` | Autenticação via JSON Web Tokens |
| `linfo/linfo` | `^4.0` | Informações de sistema e hardware |
| `nfephp-org/sped-nfe` | `^5.2` | Emissão de Nota Fiscal Eletrônica |
| `nnnick/chartjs` | `^4.5` | Gráficos (Chart.js) |
| `openboleto/openboleto` | `dev-master` | Geração de boletos bancários |
| `quilhasoft/jasperphp` | `dev-master` | Integração com JasperReports |
| `adianti/plugins` | `dev-master` | Plugins do Adianti Framework |

## Bibliotecas Locais (lib/)

- **Adianti Framework:** Localizado em `lib/adianti`.
- **Bootstrap / jQuery:** Versões embutidas na pasta `lib/`.
- **Math:** Biblioteca local para cálculos (a investigar).

## Integrações Externas Identificadas

- **MadBuilder:** Integração com serviços da MadBuilder (URLs configuradas em `application.ini`).
- **ChatWoot:** Referência em `public_classes` (`pedidoChatWoot`).
