<?php
session_start();
include('conexao.php');

if(empty($_POST['email']) || empty($_POST['senha'])) {
    header('Location: index.php');
    exit();
}

$email = $mysqli->real_escape_string($_POST['email']);
$senha = $_POST['senha'];

$query = "SELECT id, senha, nome FROM users WHERE email = '{$email}'";
$result = $mysqli->query($query);

$row = $result->fetch_assoc();

// Verifica se encontrou o email e se a senha bate com o hash
if($result->num_rows == 1 && password_verify($senha, $row['senha'])) {
    // CRIA AS VARIÁVEIS DE SESSÃO
    $_SESSION['id'] = $row['id'];
    $_SESSION['nome'] = $row['nome']; // Pode ser null no começo
    $_SESSION['email'] = $email;
    $_SESSION['tipo'] = $row['TIPO'];
    
    header('Location: dashboard.php'); // Redireciona para a página restrita
    exit();
} else {
    header('Location: index.php?erro=login_falha');
    exit();
}
?>