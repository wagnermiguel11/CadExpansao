<?php
// Inclui o arquivo de conexão para usar a variável $conexao
include('conexao.php');

// ----------------------------------------------------
// 1. QUERY SQL: Seleciona TODAS as colunas do seu banco
// ----------------------------------------------------

$sql = "SELECT idcpf, nome, email, dataNascimento, telefone, endereco, numero, cidade, CEP, SEXO, TIPO 
        FROM users";

// Executa a consulta no banco de dados
$resultado = $conexao->query($sql);

// Verifica se a consulta foi bem-sucedida
if ($resultado === FALSE) {
    die("Erro na consulta: " . $conexao->error);
}

// Verifica se retornou alguma linha
$total_usuarios = $resultado->num_rows;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista Completa de Usuários</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { margin-bottom: 5px; }
        a { text-decoration: none; color: #007bff; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 14px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>📋 Usuários Cadastrados (Total: <?php echo $total_usuarios; ?>)</h1>
    <a href="index.php">Voltar para a Página Inicial</a>
    
    <?php if ($total_usuarios > 0): ?>
    
        <table>
            <thead>
                <tr>
                    <th>CPF</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Data Nasc.</th>
                    <th>Telefone</th>
                    <th>Endereço</th>
                    <th>Nº</th>
                    <th>Cidade</th>
                    <th>CEP</th>
                    <th>Gênero</th>
                    <th>Tipo</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Loop: percorre cada linha e extrai os dados
                while ($linha = $resultado->fetch_assoc()): 
                ?>
                <tr>
                    <td><?php echo $linha['idcpf']; ?></td>
                    <td><?php echo $linha['nome']; ?></td>
                    <td><?php echo $linha['email']; ?></td>
                    <td><?php echo $linha['dataNascimento']; ?></td>
                    <td><?php echo $linha['telefone']; ?></td>
                    <td><?php echo $linha['endereco']; ?></td>
                    <td><?php echo $linha['numero']; ?></td>
                    <td><?php echo $linha['cidade']; ?></td>
                    <td><?php echo $linha['CEP']; ?></td>
                    <td><?php echo $linha['SEXO']; ?></td>
                    <td><?php echo $linha['TIPO']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
    <?php else: ?>
        <p>Ainda não há usuários cadastrados no banco de dados.</p>
    <?php endif; ?>

    <?php 
    // Fecha a conexão com o banco de dados
    $conexao->close(); 
    ?>
</body>
</html>