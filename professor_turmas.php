<?php
session_start();
include('conexao.php');

if(!isset($_SESSION['tipo']) || $_SESSION['tipo'] != 'PROFESSOR') {
    header("Location: dashboard.php"); exit;
}

$id_prof = $_SESSION['id'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Minhas Turmas</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="d-flex justify-content-between mb-4">
            <h2>Minhas Turmas e Aulas</h2>
            <a href="dashboard.php" class="btn btn-secondary">Voltar</a>
        </div>

        <div class="row">
            <?php
            // Seleciona APENAS as turmas deste professor
            $sql = "SELECT t.id, t.codigo_turma, c.nome as nome_curso, c.horario 
                    FROM turmas t 
                    JOIN cursos c ON t.curso_id = c.id 
                    WHERE t.professor_id = '$id_prof'";
            
            $res = $mysqli->query($sql);

            if($res->num_rows > 0):
                while($turma = $res->fetch_assoc()):
            ?>
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title text-primary"><?php echo $turma['nome_curso']; ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted">Turma: <?php echo $turma['codigo_turma']; ?></h6>
                        <p class="card-text">
                            <strong>Horário:</strong> <?php echo $turma['horario']; ?>
                        </p>
                        <hr>
                        <a href="professor_presenca.php?turma_selecionada=<?php echo $turma['id']; ?>" class="btn btn-success btn-block">
                            Realizar Chamada
                        </a>
                    </div>
                </div>
            </div>
            <?php 
                endwhile; 
            else:
            ?>
                <div class="col-12"><div class="alert alert-info">Você ainda não está vinculado a nenhuma turma.</div></div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>