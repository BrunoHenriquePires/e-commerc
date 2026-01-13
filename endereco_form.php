<?php
session_start();
require_once 'sql/conexao.php';

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

    header("Location: perfil.php");
    exit;
}
?>



<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/endereco.css">
    <title>Cadastrar Endereço</title>
</head>
<body>

<div class="container">
    <form action="" method="POST">

        <input type="text" name="rua" placeholder="Rua" required>

        <input type="text" name="bairro" placeholder="Bairro" required>

        <input type="number" name="numero" placeholder="Número" required>

        <input type="text" name="cidade" placeholder="Cidade" required>

        <select name="estado" required>
            <option value="">Estado</option>
            <option value="SP">SP</option>
            <option value="RJ">RJ</option>
            <option value="MG">MG</option>
            <option value="RS">RS</option>
            <option value="PR">PR</option>
            <option value="SC">SC</option>
            <option value="BA">BA</option>
            <option value="ES">ES</option>
            <option value="GO">GO</option>
            <option value="MT">MT</option>
        </select>

        <button class="btn" type="submit">ENVIAR</button>
        <a href="perfil.php">
            <button class="btn" type="button">CANCELAR</button>
        </a>

    </form>
</div>

</body>
</html>
