<?php
/**
 * ARQUIVO: conexao.php
 * RESPONSABILIDADE: Estabelecer a conexão com o banco de dados 'cadexpansao'.
 */

// 1. Definição das Variáveis de Conexão
$servidor = "localhost"; 
$usuario = "root";       
$senha = "";             
$banco_de_dados = "cadexpansao"; // O banco que criamos no início

// 2. Criação da Conexão usando a extensão MySQLi
// O "new mysqli" tenta abrir a comunicação com o banco de dados
$conexao = new mysqli($servidor, $usuario, $senha, $banco_de_dados);

// 3. Checagem de Erros na Conexão
if ($conexao->connect_error) {
    // Se falhar, interrompe o script e exibe a mensagem de erro.
    die("Falha na conexão com o banco de dados: " . $conexao->connect_error);
}

// 4. Define o charset para garantir que caracteres especiais (acentos) sejam exibidos corretamente
$conexao->set_charset("utf8");

// A partir daqui, a variável $conexao está pronta para uso em qualquer arquivo que inclua este.
?>