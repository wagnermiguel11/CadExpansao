<?php
session_start();
include('conexao.php');

// Segurança
if(!isset($_SESSION['id'])) { header("Location: index.php"); exit; }

$user_id = $_SESSION['id'];
$tipo_usuario = isset($_SESSION['tipo']) ? $_SESSION['tipo'] : 'NORMAL';

// Redireciona aluno
if($tipo_usuario == 'NORMAL') { header("Location: dashboard.php"); exit; }

$msg = "";
$erro = "";

// --- LÓGICA DE SALVAR/EDITAR PRESENÇA ---
if(isset($_POST['salvar_presenca'])) {
    $data_aula = $_POST['data_aula'];
    $turma_id = $_POST['turma_id'];

    // 1. Verificação de Segurança (Dono da Turma)
    if($tipo_usuario == 'PROFESSOR') {
        $check_dono = $mysqli->query("SELECT id FROM turmas WHERE id = '$turma_id' AND professor_id = '$user_id'");
        if($check_dono->num_rows == 0) {
            die("ERRO DE SEGURANÇA: Esta turma não pertence a você.");
        }
    }

    // 2. Processamento
    // Recebemos um array com todos os IDs de matrícula listados na tela
    if(isset($_POST['matriculas_ids'])) {
        foreach($_POST['matriculas_ids'] as $matricula_id) {
            
            // Verifica se o checkbox daquele ID estava marcado
            // Se estiver no array $_POST['presenca'], é 1 (Presente), senão 0 (Falta)
            $presente = (isset($_POST['presenca'][$matricula_id])) ? 1 : 0;
            
            // Pega a justificativa correspondente
            $justificativa = $mysqli->real_escape_string($_POST['justificativa'][$matricula_id]);

            // Verifica se já existe registro para Atualizar ou Inserir
            $check = $mysqli->query("SELECT id FROM presencas WHERE matricula_id='$matricula_id' AND data_aula='$data_aula'");
            
            if($check->num_rows > 0) {
                // UPDATE
                $mysqli->query("UPDATE presencas SET presente='$presente', justificativa='$justificativa' WHERE matricula_id='$matricula_id' AND data_aula='$data_aula'");
            } else {
                // INSERT
                $mysqli->query("INSERT INTO presencas (matricula_id, data_aula, presente, justificativa) VALUES ('$matricula_id', '$data_aula', '$presente', '$justificativa')");
            }
        }
        $msg = "Chamada salva com sucesso para o dia " . date('d/m/Y', strtotime($data_aula));
    }
}

// Prepara data para exibição (Pega do GET se houver filtro, senão hoje)
$data_filtro = isset($_GET['data_aula']) ? $_GET['data_aula'] : date('Y-m-d');
$turma_selecionada = isset($_GET['turma_selecionada']) ? $_GET['turma_selecionada'] : '';

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Lançar Presença</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        .switch { position: relative; display: inline-block; width: 50px; height: 24px; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; -webkit-transition: .4s; transition: .4s; border-radius: 34px; }
        .slider:before { position: absolute; content: ""; height: 16px; width: 16px; left: 4px; bottom: 4px; background-color: white; -webkit-transition: .4s; transition: .4s; border-radius: 50%; }
        input:checked + .slider { background-color: #28a745; }
        input:focus + .slider { box-shadow: 0 0 1px #28a745; }
        input:checked + .slider:before { -webkit-transform: translateX(26px); -ms-transform: translateX(26px); transform: translateX(26px); }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5 mb-5">
        <div class="d-flex justify-content-between mb-4">
            <h2>Registro de Chamada</h2>
            <a href="dashboard.php" class="btn btn-secondary">Voltar</a>
        </div>

        <?php if($msg) echo "<div class='alert alert-success'>$msg</div>"; ?>

        <div class="card p-4 shadow-sm">
            <form method="get" id="formFiltro">
                <div class="row">
                    <div class="col-md-5">
                        <label>Selecione a Turma</label>
                        <select name="turma_selecionada" class="form-control" onchange="document.getElementById('formFiltro').submit()">
                            <option value="">Selecione...</option>
                            <?php
                            $sql_turmas = ($_SESSION['tipo'] == 'ADMIN') 
                                ? "SELECT t.id, t.codigo_turma, c.nome FROM turmas t JOIN cursos c ON t.curso_id = c.id"
                                : "SELECT t.id, t.codigo_turma, c.nome FROM turmas t JOIN cursos c ON t.curso_id = c.id WHERE t.professor_id = $user_id";
                            
                            $res_t = $mysqli->query($sql_turmas);
                            while($t = $res_t->fetch_assoc()) {
                                $selected = ($turma_selecionada == $t['id']) ? 'selected' : '';
                                echo "<option value='{$t['id']}' $selected>{$t['codigo_turma']} - {$t['nome']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    
                    <?php if($turma_selecionada): ?>
                    <div class="col-md-4">
                        <label>Data da Aula (Para visualizar ou editar)</label>
                        <input type="date" name="data_aula" class="form-control" value="<?php echo $data_filtro; ?>" onchange="document.getElementById('formFiltro').submit()">
                    </div>
                    <?php endif; ?>
                </div>
            </form>

            <?php if($turma_selecionada): ?>
                <hr>
                <form method="post">
                    <input type="hidden" name="turma_id" value="<?php echo $turma_selecionada; ?>">
                    <input type="hidden" name="data_aula" value="<?php echo $data_filtro; ?>">

                    <h5 class="text-primary mb-3">
                        Lista de Alunos - Data: <?php echo date('d/m/Y', strtotime($data_filtro)); ?>
                    </h5>

                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 30%;">Aluno</th>
                                <th style="width: 20%;">CPF</th>
                                <th style="width: 15%; text-align: center;">Presença</th>
                                <th style="width: 35%;">Justificativa / Observação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Query Inteligente: Traz o aluno e faz LEFT JOIN com a tabela de presenças
                            // Assim sabemos se ele já tem presença marcada nessa data específica
                            $sql_alunos = "SELECT m.id as matricula_id, u.nome, u.idcpf, p.presente, p.justificativa
                                           FROM matriculas m 
                                           JOIN users u ON m.aluno_id = u.id 
                                           LEFT JOIN presencas p ON m.id = p.matricula_id AND p.data_aula = '$data_filtro'
                                           WHERE m.turma_id = $turma_selecionada
                                           ORDER BY u.nome ASC";
                            
                            $res_a = $mysqli->query($sql_alunos);
                            
                            if($res_a->num_rows > 0):
                                while($aluno = $res_a->fetch_assoc()):
                                    // Se 'presente' for NULL (ainda não lançou), assume 1 (Presente por padrão)
                                    // Se já lançou, pega o valor do banco (0 ou 1)
                                    $status_check = ($aluno['presente'] === null || $aluno['presente'] == 1) ? 'checked' : '';
                                    
                                    // Se já foi lançado e é 0, não marca. Se for null (novo), marca.
                                    // Lógica ajustada: Se for novo dia, vem checked. Se for edição, respeita o banco.
                                    if($aluno['presente'] !== null && $aluno['presente'] == 0) {
                                        $status_check = '';
                                    }
                            ?>
                            <tr>
                                <td class="align-middle">
                                    <?php echo $aluno['nome']; ?>
                                    <input type="hidden" name="matriculas_ids[]" value="<?php echo $aluno['matricula_id']; ?>">
                                </td>
                                <td class="align-middle"><?php echo $aluno['idcpf']; ?></td>
                                <td class="text-center align-middle">
                                    <label class="switch">
                                        <input type="checkbox" name="presenca[<?php echo $aluno['matricula_id']; ?>]" <?php echo $status_check; ?>>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <input type="text" 
                                           name="justificativa[<?php echo $aluno['matricula_id']; ?>]" 
                                           class="form-control form-control-sm" 
                                           placeholder="Ex: Atestado médico..." 
                                           value="<?php echo htmlspecialchars($aluno['justificativa']); ?>">
                                </td>
                            </tr>
                            <?php 
                                endwhile; 
                            else:
                                echo "<tr><td colspan='4' class='text-center'>Nenhum aluno matriculado nesta turma.</td></tr>";
                            endif;
                            ?>
                        </tbody>
                    </table>

                    <div class="text-right">
                        <button type="submit" name="salvar_presenca" class="btn btn-success btn-lg px-5">Salvar Chamada</button>
                    </div>
                </form>
            <?php endif; ?>

        </div>
    </div>
</body>
</html>