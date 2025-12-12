<?php
session_start();
include('conexao.php');

// Apenas ADMIN acessa
if(!isset($_SESSION['tipo']) || $_SESSION['tipo'] != 'ADMIN') {
    header("Location: dashboard.php"); exit;
}

// Cadastrar Novo Professor
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cadastrar_prof'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    
    // Verifica se email já existe
    $check = $mysqli->query("SELECT id FROM users WHERE email='$email'");
    if($check->num_rows == 0) {
        $sql = "INSERT INTO users (nome, EMAIL, senha, TIPO) VALUES ('$nome', '$email', '$senha', 'PROFESSOR')";
        $mysqli->query($sql);
        $msg = "Professor cadastrado com sucesso!";
    } else {
        $erro = "Email já cadastrado!";
    }
}

// Remover/Rebaixar (Opcional - muda para NORMAL ao invés de deletar para não perder histórico)
if(isset($_GET['rebaixar'])) {
    $id = intval($_GET['rebaixar']);
    $mysqli->query("UPDATE users SET TIPO='NORMAL' WHERE id=$id");
    header("Location: admin_professores.php");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Gerenciar Professores</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="d-flex justify-content-between mb-4">
            <h2>Gestão de Professores</h2>
            <a href="dashboard.php" class="btn btn-secondary">Voltar</a>
        </div>

        <?php if(isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>
        <?php if(isset($erro)) echo "<div class='alert alert-danger'>$erro</div>"; ?>

        <div class="row">
            <div class="col-md-4">
                <div class="card p-3">
                    <h4>Novo Professor</h4>
                    <form method="post">
                        <div class="form-group">
                            <label>Nome</label>
                            <input type="text" name="nome" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Senha Provisória</label>
                            <input type="password" name="senha" class="form-control" required>
                        </div>
                        <button type="submit" name="cadastrar_prof" class="btn btn-primary btn-block">Cadastrar</button>
                    </form>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card p-3">
                    <h4>Professores Ativos</h4>
                    <table class="table table-striped">
                        <thead><tr><th>ID</th><th>Nome</th><th>Email</th><th>Ação</th></tr></thead>
                        <tbody>
                            <?php
                            $sql = "SELECT id, nome, EMAIL FROM users WHERE TIPO = 'PROFESSOR'";
                            $res = $mysqli->query($sql);
                            while($row = $res->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['nome']; ?></td>
                                <td><?php echo $row['EMAIL']; ?></td>
                                <td>
                                    <a href="?rebaixar=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Remover acesso de professor deste usuário?')">Remover Acesso</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>