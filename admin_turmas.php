<?php
session_start();
include('conexao.php');

// Segurança: Apenas ADMIN
if(!isset($_SESSION['tipo']) || $_SESSION['tipo'] != 'ADMIN') {
    header("Location: dashboard.php"); exit;
}

// 1. CRIAR TURMA
if(isset($_POST['criar_turma'])) {
    $codigo = $_POST['codigo'];
    $curso_id = $_POST['curso_id'];
    $professor_id = $_POST['professor_id'];
    $mysqli->query("INSERT INTO turmas (codigo_turma, curso_id, professor_id) VALUES ('$codigo', '$curso_id', '$professor_id')");
    header("Location: admin_turmas.php");
}

// 2. ATUALIZAR TURMA (Trocar professor, código, etc)
if(isset($_POST['atualizar_turma'])) {
    $id = $_POST['turma_id'];
    $codigo = $_POST['codigo'];
    $curso_id = $_POST['curso_id'];
    $professor_id = $_POST['professor_id'];
    
    $mysqli->query("UPDATE turmas SET codigo_turma='$codigo', curso_id='$curso_id', professor_id='$professor_id' WHERE id='$id'");
    header("Location: admin_turmas.php?editar=$id&msg=atualizado");
}

// 3. MATRICULAR ALUNO
if(isset($_POST['matricular'])) {
    $turma_id = $_POST['turma_id'];
    $aluno_id = $_POST['aluno_id'];
    
    // Verifica duplicidade
    $check = $mysqli->query("SELECT id FROM matriculas WHERE turma_id='$turma_id' AND aluno_id='$aluno_id'");
    if($check->num_rows == 0){
        $mysqli->query("INSERT INTO matriculas (turma_id, aluno_id) VALUES ('$turma_id', '$aluno_id')");
    }
    // Volta para a mesma tela de edição
    header("Location: admin_turmas.php?editar=$turma_id");
}

// 4. REMOVER ALUNO DA TURMA
if(isset($_GET['remover_aluno'])) {
    $matricula_id = intval($_GET['remover_aluno']);
    $turma_atual = intval($_GET['turma']);
    $mysqli->query("DELETE FROM matriculas WHERE id='$matricula_id'");
    header("Location: admin_turmas.php?editar=$turma_atual");
}

// --- PREPARAÇÃO DE DADOS PARA EDIÇÃO ---
$turma_edit = null;
$alunos_turma = [];
if(isset($_GET['editar'])) {
    $id_edit = intval($_GET['editar']);
    $res = $mysqli->query("SELECT * FROM turmas WHERE id='$id_edit'");
    $turma_edit = $res->fetch_assoc();
    
    // Busca alunos desta turma
    $sql_alunos = "SELECT m.id as matricula_id, u.nome, u.idcpf, u.EMAIL 
                   FROM matriculas m 
                   JOIN users u ON m.aluno_id = u.id 
                   WHERE m.turma_id = '$id_edit'";
    $alunos_turma = $mysqli->query($sql_alunos);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Gerenciar Turmas</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .box-edit { border: 2px solid #7971ea; background-color: #f9f9ff; }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5 mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Gestão de Turmas</h2>
            <a href="dashboard.php" class="btn btn-secondary">Voltar ao Dashboard</a>
        </div>

        <?php if(isset($_GET['msg']) && $_GET['msg']=='atualizado'): ?>
            <div class="alert alert-success">Turma atualizada com sucesso!</div>
        <?php endif; ?>

        <div class="row">
            
            <div class="col-md-5">
                <div class="card p-3 shadow-sm">
                    <h5 class="text-primary">Todas as Turmas</h5>
                    <div class="table-responsive">
                        <table class="table table-hover table-sm" style="font-size: 0.9em;">
                            <thead><tr><th>Cód</th><th>Curso</th><th>Ação</th></tr></thead>
                            <tbody>
                                <?php
                                $sql = "SELECT t.id, t.codigo_turma, c.nome as nome_curso 
                                        FROM turmas t 
                                        JOIN cursos c ON t.curso_id = c.id ORDER BY t.id DESC";
                                $res = $mysqli->query($sql);
                                while($row = $res->fetch_assoc()):
                                    $active = (isset($_GET['editar']) && $_GET['editar'] == $row['id']) ? 'table-active' : '';
                                ?>
                                <tr class="<?php echo $active; ?>">
                                    <td><?php echo $row['codigo_turma']; ?></td>
                                    <td><?php echo $row['nome_curso']; ?></td>
                                    <td>
                                        <a href="?editar=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary">Gerenciar</a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <a href="admin_turmas.php" class="btn btn-sm btn-outline-secondary btn-block">Nova Turma (Limpar Seleção)</a>
                </div>
            </div>

            <div class="col-md-7">
                
                <div class="card p-4 shadow-sm mb-4 <?php echo $turma_edit ? 'box-edit' : ''; ?>">
                    <h4><?php echo $turma_edit ? 'Editar Turma: ' . $turma_edit['codigo_turma'] : 'Criar Nova Turma'; ?></h4>
                    
                    <form method="post">
                        <?php if($turma_edit): ?>
                            <input type="hidden" name="turma_id" value="<?php echo $turma_edit['id']; ?>">
                            <input type="hidden" name="atualizar_turma" value="1">
                        <?php else: ?>
                            <input type="hidden" name="criar_turma" value="1">
                        <?php endif; ?>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Código</label>
                                <input type="text" name="codigo" class="form-control" required 
                                       value="<?php echo $turma_edit ? $turma_edit['codigo_turma'] : ''; ?>" 
                                       placeholder="Ex: 2024-A">
                            </div>
                            <div class="form-group col-md-8">
                                <label>Curso</label>
                                <select name="curso_id" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <?php 
                                    $cursos = $mysqli->query("SELECT * FROM cursos");
                                    while($c = $cursos->fetch_assoc()){
                                        $sel = ($turma_edit && $turma_edit['curso_id'] == $c['id']) ? 'selected' : '';
                                        echo "<option value='{$c['id']}' $sel>{$c['nome']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Professor Responsável</label>
                            <select name="professor_id" class="form-control" required>
                                <option value="">Selecione o Professor...</option>
                                <?php 
                                $profs = $mysqli->query("SELECT * FROM users WHERE TIPO='PROFESSOR'"); 
                                while($p = $profs->fetch_assoc()){
                                    $sel = ($turma_edit && $turma_edit['professor_id'] == $p['id']) ? 'selected' : '';
                                    echo "<option value='{$p['id']}' $sel>{$p['nome']} ({$p['EMAIL']})</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <button type="submit" class="btn <?php echo $turma_edit ? 'btn-primary' : 'btn-success'; ?> btn-block">
                            <?php echo $turma_edit ? 'Salvar Alterações na Turma' : 'Criar Turma'; ?>
                        </button>
                    </form>
                </div>

                <?php if($turma_edit): ?>
                <div class="card p-4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="m-0 text-success">Alunos Matriculados</h5>
                        <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modalAddAluno">
                            + Adicionar Aluno
                        </button>
                    </div>

                    <table class="table table-bordered table-sm">
                        <thead class="thead-light"><tr><th>Nome</th><th>CPF</th><th>Ação</th></tr></thead>
                        <tbody>
                            <?php if($alunos_turma && $alunos_turma->num_rows > 0): ?>
                                <?php while($aluno = $alunos_turma->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $aluno['nome']; ?></td>
                                    <td><?php echo $aluno['idcpf']; ?></td>
                                    <td class="text-center">
                                        <a href="?remover_aluno=<?php echo $aluno['matricula_id']; ?>&turma=<?php echo $turma_edit['id']; ?>" 
                                           class="text-danger small" 
                                           onclick="return confirm('Remover este aluno da turma?');">[Remover]</a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="3" class="text-center text-muted">Nenhum aluno nesta turma.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <?php if($turma_edit): ?>
    <div class="modal fade" id="modalAddAluno" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post">
                    <div class="modal-header">
                        <h5 class="modal-title">Matricular em <?php echo $turma_edit['codigo_turma']; ?></h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="turma_id" value="<?php echo $turma_edit['id']; ?>">
                        <div class="form-group">
                            <label>Selecione o Aluno</label>
                            <select name="aluno_id" class="form-control" required>
                                <option value="">Pesquise na lista...</option>
                                <?php
                                // Lista apenas alunos (NORMAL)
                                $all_alunos = $mysqli->query("SELECT * FROM users WHERE TIPO='NORMAL' ORDER BY nome ASC");
                                while($a = $all_alunos->fetch_assoc()):
                                ?>
                                <option value="<?php echo $a['id']; ?>"><?php echo $a['nome']; ?> (CPF: <?php echo $a['idcpf']; ?>)</option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="matricular" class="btn btn-primary">Confirmar Matrícula</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>