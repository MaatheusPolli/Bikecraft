<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "./PHPMailer/src/Exception.php";
require "./PHPMailer/src/PHPMailer.php";
require "./PHPMailer/src/SMTP.php";
require "./config.php";

// Configurações vindas do .env (ver .env.example)
$email_envio = env("SMTP_USER");
$email_pass = env("SMTP_PASS");
$host_smtp = env("SMTP_HOST");
$host_port = (int) env("SMTP_PORT", "587");
$smtp_secure = env("SMTP_SECURE", "tls") === "ssl" ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;

$site_name = env("SITE_NAME", "Bikcraft");

// Não mudar abaixo:
$email = filter_var($_POST["email"] ?? "", FILTER_VALIDATE_EMAIL) ?: "";
$nome = limpar_campo($_POST["nome"] ?? "");

// Verifica se não é bot (honeypot)
$notbot = ($_POST["leaveblank"] ?? "") === "" && ($_POST["dontchange"] ?? "http://") === "http://";

// Monta o corpo do e-mail com os campos sanitizados
$body_content = "";
foreach ($_POST as $field => $value) {
  if (in_array($field, ["leaveblank", "dontchange", "enviar"], true)) {
    continue;
  }
  $body_content .= limpar_campo($field) . ": " . limpar_campo($value) . "\n";
}

$sucesso = false;

if ($notbot && $email !== "") {
  // Inicia o objeto PHPMailer
  $mail = new PHPMailer(true);

  try {
    $mail->CharSet = "UTF-8";

    //$mail->SMTPDebug = 3; // Tire do comentário para debugar
    $mail->isSMTP();
    $mail->Host = $host_smtp;
    $mail->SMTPAuth = true;
    $mail->Username = $email_envio;
    $mail->Password = $email_pass;
    $mail->Port = $host_port;
    $mail->SMTPSecure = $smtp_secure;

    $mail->setFrom($email_envio, "Formulário - " . $site_name);
    $mail->addAddress($email_envio, $site_name);
    $mail->addReplyTo($email, $nome);

    $mail->WordWrap = 70;
    $mail->Subject = "Formulário - " . $site_name . " - " . $nome;
    $mail->Body = $body_content;

    $mail->send();
    $sucesso = true;
  } catch (Exception $e) {
    $sucesso = false;
  }
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
      <p>Você pode tentar novamente ou enviar direto para <?php echo htmlspecialchars($email_envio, ENT_QUOTES, "UTF-8"); ?></p>
    </div>
  </body>
    <?php } ?>
</html>
