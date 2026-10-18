<?php

require("./sendgrid-php/sendgrid-php.php");
require("./config.php");

// Configurações vindas do .env (ver .env.example)
$email_site = env("SENDGRID_FROM");
$nome_site = env("SITE_NAME", "Bikcraft");
$api_key = env("SENDGRID_API_KEY");

$email_user = filter_var($_POST["email"] ?? "", FILTER_VALIDATE_EMAIL) ?: "";
$nome_user = limpar_campo($_POST["nome"] ?? "");

// Verifica se não é bot (honeypot)
$notbot = ($_POST["leaveblank"] ?? "") === "" && ($_POST["dontchange"] ?? "http://") === "http://";

if (!$notbot || $email_user === "" || $email_site === "" || $api_key === "") {
  http_response_code(400);
  exit("Não foi possível enviar o formulário.");
}

// Monta o corpo do e-mail com os campos sanitizados
$body_content = "";
foreach ($_POST as $field => $value) {
  if (in_array($field, ["leaveblank", "dontchange", "enviar"], true)) {
    continue;
  }
  $body_content .= limpar_campo($field) . ": " . limpar_campo($value) . "\n";
}

$email = new \SendGrid\Mail\Mail();
$email->setFrom($email_site, $nome_site);
$email->addTo($email_site, $nome_site);

$email->setReplyTo($email_user, $nome_user);

$email->setSubject("Formulário " . $nome_site);
$email->addContent("text/plain", $body_content);

$sucesso = false;

$sendgrid = new \SendGrid($api_key);
try {
  $response = $sendgrid->send($email);
  $sucesso = $response->statusCode() >= 200 && $response->statusCode() < 300;
  if (!$sucesso) {
    error_log("SendGrid respondeu " . $response->statusCode() . ": " . $response->body());
  }
} catch (Exception $e) {
  error_log("Erro SendGrid: " . $e->getMessage());
}

?>
<!doctype html>
<html lang="pt-BR">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php if ($sucesso) { ?>
    <title>Formulário enviado</title>
    <meta http-equiv="refresh" content="10;url=./">
  </head>
  <body>
    <!-- Mensagem de sucesso -->
    <div class="form-content" id="form-send">
      <h2>Formulário enviado!</h2>
      <p>Em breve eu entro em contato com você.</p>
    </div>
  </body>
    <?php } else { ?>
    <title>Erro no envio</title>
    <meta http-equiv="refresh" content="10;url=./">
  </head>
  <body>
    <!-- Mensagem de erro -->
    <div class="form-content" id="form-erro">
      <h2>Um erro ocorreu!</h2>
      <p>Você pode tentar novamente ou enviar direto para <?php echo htmlspecialchars($email_site, ENT_QUOTES, "UTF-8"); ?></p>
    </div>
  </body>
    <?php } ?>
</html>
