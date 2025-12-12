<?php
session_start();
include('conexao.php');

if(!isset($_SESSION['tipo']) || $_SESSION['tipo'] != 'ADMIN') {
    header("Location: dashboard.php"); exit;
}

// Lógica de Exclusão
if(isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $mysqli->query("DELETE FROM cursos WHERE id = $id");
    header("Location: admin_cursos.php");
}

// Lógica de Cadastro/Edição
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $horario = $_POST['horario'];
    $descricao = $_POST['descricao'];
    $id = $_POST['id'];

    if($id) {
        // Update
        $sql = "UPDATE cursos SET nome='$nome', horario='$horario', descricao='$descricao' WHERE id=$id";
    } else {
        // Insert
        $sql = "INSERT INTO cursos (nome, horario, descricao) VALUES ('$nome', '$horario', '$descricao')";
    }
    $mysqli->query($sql);
    header("Location: admin_cursos.php");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Gerenciar Cursos</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css"> </head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Gerenciamento de Cursos</h2>
            <a href="dashboard.php" class="btn btn-secondary">Voltar ao Dashboard</a>
        </div>

        <div class="card p-4 mb-4">
            <h4>Novo / Editar Curso</h4>
            <form method="post">
                <input type="hidden" name="id" id="id">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Nome do Curso</label>
                        <input type="text" name="nome" id="nome" class="form-control" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Horário (Ex: Seg/Qua 19h)</label>
                        <input type="text" name="horario" id="horario" class="form-control" required>
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Descrição</label>
                        <textarea name="descricao" id="descricao" class="form-control"></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Salvar Curso</button>
                <button type="button" onclick="limparForm()" class="btn btn-outline-secondary">Limpar</button>
            </form>
        </div>

        <div class="card p-4">
            <table class="table table-striped">
                <thead><tr><th>ID</th><th>Nome</th><th>Horário</th><th>Ações</th></tr></thead>
                <tbody>
                    <?php
                    $result = $mysqli->query("SELECT * FROM cursos");
                    while($row = $result->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['nome']; ?></td>
                        <td><?php echo $row['horario']; ?></td>
                        <td>
                            <button onclick="editar(<?php echo htmlspecialchars(json_encode($row)); ?>)" class="btn btn-sm btn-info">Editar</button>
                            <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?');">Excluir</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function editar(dados) {
            document.getElementById('id').value = dados.id;
            document.getElementById('nome').value = dados.nome;
            document.getElementById('horario').value = dados.horario;
            document.getElementById('descricao').value = dados.descricao;
        }
        function limparForm() {
            document.getElementById('id').value = '';
            document.getElementById('nome').value = '';
            document.getElementById('horario').value = '';
            document.getElementById('descricao').value = '';
        }
    </script>
</body>
</html>