<?php
session_start();
include '../sql/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Verifica se o usuário existe
    $sql = "SELECT * FROM Usuario WHERE email = '$email' AND senha = '$senha'";
    $result = $conexao->query($sql);

    if ($result->num_rows == 1) {
        $usuario = $result->fetch_assoc();
        $idUsuario = $usuario['idUsuario'];

        $_SESSION['idUsuario'] = $idUsuario;
        $_SESSION['nome'] = $usuario['nome'];

        // Verifica se é cliente
        $sqlCliente = "SELECT * FROM Cliente WHERE idUsuario = $idUsuario";
        $resCliente = $conexao->query($sqlCliente);

        // Verifica se é funcionário
        $sqlFuncionario = "SELECT * FROM Administrador WHERE idUsuario = $idUsuario";
        $resFuncionario = $conexao->query($sqlFuncionario);

        if ($resCliente->num_rows > 0) {
            header("Location: ../index.php");
            exit;
        } elseif ($resFuncionario->num_rows > 0) {
            header("Location: ../administrador/administrador.php");
            exit;
        } else {
            echo "Usuário sem categoria definida!";
        }
    } else {
    }
}
?>
