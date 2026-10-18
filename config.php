<?php

// Carrega variáveis do arquivo .env (na raiz do projeto), sem sobrescrever
// variáveis já definidas no ambiente do servidor.
// Formato: CHAVE=valor, uma por linha. Linhas com # são comentários.
function carregar_env(string $arquivo): void {
  if (!is_file($arquivo)) {
    return;
  }

  foreach (file($arquivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $linha) {
    $linha = trim($linha);
    if ($linha === "" || $linha[0] === "#" || strpos($linha, "=") === false) {
      continue;
    }

    [$chave, $valor] = explode("=", $linha, 2);
    $chave = trim($chave);
    $valor = trim($valor, " \t\"'");

    if (getenv($chave) === false) {
      putenv("$chave=$valor");
    }
  }
}

carregar_env(__DIR__ . "/.env");

// Lê uma variável de ambiente, com valor padrão quando não existir
function env(string $chave, string $padrao = ""): string {
  $valor = getenv($chave);
  return $valor === false ? $padrao : $valor;
}

// Escapa um valor vindo do formulário, removendo tags e quebras de linha no cabeçalho
function limpar_campo($valor): string {
  $texto = is_string($valor) ? $valor : "";
  return htmlspecialchars(strip_tags(trim($texto)), ENT_QUOTES, "UTF-8");
}
