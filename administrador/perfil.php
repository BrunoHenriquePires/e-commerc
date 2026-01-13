<?php
session_start();
include 'sql/conexao.php';

if (!isset($_SESSION['idUsuario'])) {
    header("Location: login.php");
    exit;
}

$idUsuario = $_SESSION['idUsuario'];

// Busca dados do usuário e cliente (se existir)
$sql = "SELECT 
            Usuario.nome, Usuario.email,
            Cliente.cpf, Cliente.cartao
        FROM Usuario
        LEFT JOIN Cliente ON Cliente.idUsuario = Usuario.idUsuario
        WHERE Usuario.idUsuario = $idUsuario";

$result = $conexao->query($sql);
$dados = $result->fetch_assoc();

// Verifica se é funcionário (Administrador, no seu caso)
$sqlFuncionario = "SELECT * FROM Administrador WHERE idUsuario = $idUsuario";
$resFuncionario = $conexao->query($sqlFuncionario);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Perfil do Usuário</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #121212;
            color: #e0e0e0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .perfil-container {
            background: #1e1e1e;
            width: 420px;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.6);
            text-align: center;
        }

        h1 {
            color: #ffffff;
            margin-bottom: 10px;
        }

        h2 {
            color: #bbbbbb;
            font-size: 18px;
            margin-top: 20px;
            border-bottom: 1px solid #333;
            padding-bottom: 5px;
        }

        p {
            margin: 8px 0;
            color: #cccccc;
        }

        strong {
            color: #ffffff;
        }

        a.logout-btn {
            display: inline-block;
            margin-top: 20px;
            background: #d9534f;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }

        a.logout-btn:hover {
            background: #c9302c;
        }
    </style>
</head>
<body>

<div class="perfil-container">
    <h1>Bem-vindo, <?php echo $dados['nome']; ?>!</h1>

    <h2>Informações da Conta</h2>
    <p><strong>Email:</strong> <?php echo $dados['email']; ?></p>

    <h2>Informações de Cliente</h2>
    <p><strong>CPF:</strong> <?php echo $dados['cpf'] ?? 'Não cadastrado'; ?></p>
    <p><strong>Cartão:</strong> <?php echo $dados['cartao'] ?? 'Não cadastrado'; ?></p>

    <?php if ($resFuncionario->num_rows > 0): ?>
        <!-- Usuário Funcionário -->
        <a href="administrador.php" class="logout-btn">INÍCIO</a>
    <?php else: ?>
        <!-- Usuário Cliente -->
        <a href="index.php" class="logout-btn">INÍCIO</a>
    <?php endif; ?>

    <a href="../sql/logout.php" class="logout-btn">SAIR</a>
</div>

</body>
</html>
