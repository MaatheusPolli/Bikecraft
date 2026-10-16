# Bikcraft — Site institucional de bicicletas personalizadas

Site multipágina de uma marca fictícia de bicicletas, feito em 2021 como estudo no curso da **Origamid**. Usa só HTML, CSS e JavaScript, com um formulário de contato que envia e-mail por PHP.

> Projeto de estudo.

## Páginas

| Página | Arquivo | Conteúdo |
|--------|---------|----------|
| Home | `index.html` | Apresentação, linhas de produto, depoimentos em slide |
| Sobre | `sobre.html` | História e equipe |
| Produtos | `produtos.html` | Linhas Passeio, Retrô e Esporte |
| Portfólio | `portfolio.html` | Clientes, com slide navegável |
| Contato | `contato.html` | Formulário, endereço e telefone |

## O que foi praticado

- HTML semântico e layout em grid próprio (`css/grid.css`) com versão responsiva (`css/responsivo.css`).
- Plugins JS escritos do zero:
  - `simple-slide.js`: slides com tempo e navegação (`data-slide`).
  - `simple-anime.js`: animação de elementos ao rolar a página.
  - `simple-form.js`: envio do formulário com feedback.
- Formulário com honeypot anti-bot (campos `leaveblank` e `dontchange`).
- Envio de e-mail por **PHPMailer** (`enviar.php`) ou **SendGrid** (`enviar-sendgrid.php`).

## Estrutura

```
Bikecraft/
├── index.html, sobre.html, produtos.html, portfolio.html, contato.html
├── css/            reset, normalize, grid, estilos por página, responsivo
├── js/             script.js + plugins simple-*
├── img/
├── enviar.php            envio via SMTP (PHPMailer)
├── enviar-sendgrid.php   envio via SendGrid
└── PHPMailer/ , sendgrid-php/   bibliotecas incluídas no repositório
```

## Como rodar

As páginas abrem direto no navegador (`index.html`).

O formulário precisa de PHP 8:

```bash
php -S localhost:8000
```

Antes de usar, preencha em `enviar.php` o e-mail, a senha, o host e a porta SMTP. Nunca versione credenciais reais.

## Pendências conhecidas

- O `enviar.php` precisa de ajustes para PHP 8.1 ou mais novo: `FILTER_SANITIZE_STRING` foi descontinuado e há um erro de digitação em `SMTPSecure` (está `"tsl"` em vez de `"tls"`).
- O CI ainda é um placeholder.

## Licença

Este projeto usa **licença dupla**. O texto completo está em [LICENSE](LICENSE).

- **Uso pessoal, educacional e não comercial:** livre, nos termos da licença MIT (mantenha o aviso de copyright).
- **Uso comercial:** exige licença comercial. Isso inclui usar em produto ou serviço comercial, vender acesso ou derivados, usar em atividades com fins lucrativos e implantar em empresas.

Contato para licença comercial: ma.polli@hotmail.com
