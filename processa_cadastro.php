<?php
session_start();
include('conexao.php');

$email = $mysqli->real_escape_string($_POST['email']);
$senha = $_POST['senha'];
$confirma_senha = $_POST['confirma_senha'];

// Validações básicas
if($senha !== $confirma_senha) {
    header("Location: index.php?erro=senha");
    exit;
}

// Verifica se usuário já existe
$sql_check = "SELECT id FROM users WHERE email = '$email'";
$result_check = $mysqli->query($sql_check);

if($result_check->num_rows > 0) {
    header("Location: index.php?erro=existe");
    exit;
}

// Criptografa a senha (Segurança Técnica Obrigatória)
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

// Insere no banco (campos nome, cpf, etc ficam NULL por enquanto)
$sql_insert = "INSERT INTO users (EMAIL, senha, TIPO) VALUES ('$email', '$senhaHash', 'NORMAL')";

if($mysqli->query($sql_insert)) {
    header("Location: index.php?sucesso=ok");
} else {
    header("Location: index.php?erro=erro");
}
?>