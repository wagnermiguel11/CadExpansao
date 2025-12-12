<?php
// --- INÍCIO DA PROTEÇÃO ---
session_start();
if(!isset($_SESSION['id'])) {
    // Se não tiver sessão, manda pro login
    header("Location: index.php");
    exit;
}
// --- FIM DA PROTEÇÃO ---
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Área do Aluno</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="jumbotron">
            <h1>Bem-vindo, <?php echo $_SESSION['email']; ?>!</h1>
            <p class="lead">Esta é uma página protegida. Só usuários logados podem ver.</p>
            <hr class="my-4">
            <p>Seu ID de usuário é: <?php echo $_SESSION['id']; ?></p>
            
            <?php if(empty($_SESSION['nome'])): ?>
                <div class="alert alert-warning">
                    Você ainda não completou seu cadastro (Nome, CPF, etc). 
                    <a href="perfil.php">Clique aqui para completar</a>.
                </div>
            <?php endif; ?>

            <a class="btn btn-danger btn-lg" href="logout.php" role="button">Sair (Logout)</a>
        </div>
    </div>
</body>
</html>