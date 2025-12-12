<?php
session_start();
include('conexao.php');

if(!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_SESSION['id'];

// Recebendo dados do formulário e limpando strings para segurança básica
$nome = $mysqli->real_escape_string($_POST['nome']);
$idcpf = $mysqli->real_escape_string($_POST['idcpf']);
$dataNascimento = $_POST['dataNascimento']; // Date não precisa de escape string se for validado, mas ok
$telefone = $mysqli->real_escape_string($_POST['telefone']);
$sexo = $mysqli->real_escape_string($_POST['SEXO']);
$endereco = $mysqli->real_escape_string($_POST['endereco']);
$numero = $mysqli->real_escape_string($_POST['numero']);
$cidade = $mysqli->real_escape_string($_POST['cidade']);
$uf = $mysqli->real_escape_string($_POST['UF']);
$cep = $mysqli->real_escape_string($_POST['CEP']);

// Lógica de Upload de Imagem
$sql_foto = ""; 
$uploadOk = 1;

if(isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $target_dir = "uploads/";
    // Gera nome único para evitar sobrescrita: ID_TIMESTAMP.ext
    $ext = pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION);
    $new_name = $id . "_" . time() . "." . $ext;
    $target_file = $target_dir . $new_name;

    // Checa se é imagem real
    $check = getimagesize($_FILES["foto"]["tmp_name"]);
    if($check !== false) {
        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
            // Se upload deu certo, prepara o trecho SQL
            $sql_foto = ", urlfoto = '$target_file'";
        } else {
            $uploadOk = 0;
        }
    } else {
        $uploadOk = 0;
    }
}

// Monta a query de UPDATE
$sql = "UPDATE users SET 
        nome = '$nome',
        idcpf = '$idcpf',
        dataNascimento = " . ($dataNascimento ? "'$dataNascimento'" : "NULL") . ",
        telefone = '$telefone',
        SEXO = '$sexo',
        endereco = '$endereco',
        numero = '$numero',
        cidade = '$cidade',
        UF = '$uf',
        CEP = '$cep'
        $sql_foto
        WHERE id = '$id'";

if($mysqli->query($sql)) {
    if(isset($_FILES['foto']) && $_FILES['foto']['error'] == 0 && $uploadOk == 0){
        // Salvou dados, mas upload falhou
        header("Location: perfil.php?status=erro_upload");
    } else {
        // Tudo certo
        $_SESSION['nome'] = $nome; // Atualiza nome na sessão
        header("Location: perfil.php?status=sucesso");
    }
} else {
    header("Location: perfil.php?status=erro");
    // Para debug: echo $mysqli->error;
}
?>