<?php

require_once '../sql/conexao.php';

$nome  = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha'];
$cpf   = $_POST['cpf'];

// Verifica se o e-mail já está cadastrado
$verificaEmail = "SELECT idUsuario FROM Cliente WHERE cpf = '$cpf'";
$resultado = $conexao->query($verificaEmail);

if ($resultado->num_rows > 0) {
    // Se já existir, redireciona de volta para a página de cadastro
    header("Location: ../cadastro.php?erro=cpf");
    exit();
}

// Insere o usuário
$sqlUsuario = "INSERT INTO Usuario (nome, email, senha) VALUES ('$nome', '$email', '$senha')";
if ($conexao->query($sqlUsuario) === TRUE) {

    $idUsuario = $conexao->insert_id;

    $sqlCliente = "INSERT INTO Cliente (cpf, idUsuario) VALUES ('$cpf', $idUsuario)";
    if ($conexao->query($sqlCliente) === TRUE) {
        header("Location: ../index.php");
        exit();
    } else {
        echo "Erro ao cadastrar Cliente: " . $conexao->error;
    }

} else {
    echo "Erro ao cadastrar Usuario: " . $conexao->error;
}

$conexao->close();
?>
