<?php
session_start();
require_once '../sql/conexao.php';

// PEGAR ID DO USUÁRIO LOGADO
$idUsuario = $_SESSION['idUsuario']; // já deve estar salvo no login

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $rua = $_POST['rua'];
    $bairro = $_POST['bairro'];
    $numero = $_POST['numero'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];

    // Inserir endereço
    $sql = "INSERT INTO Endereco (rua, cidade, bairro, numero, Estado)
            VALUES ('$rua', '$cidade', '$bairro', $numero, '$estado')";
    $conexao->query($sql);

    // Recuperar ID do endereço criado
    $idEndereco = $conexao->insert_id;

    // Vincular endereço ao cliente pelo idUsuario
    $sql2 = "UPDATE Cliente SET idEndereco = $idEndereco WHERE idUsuario = $idUsuario";
    $conexao->query($sql2);

    header("Location: ../perfil.php");
    exit;
}
?>
