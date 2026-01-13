<?php
// Evita erro de sessão duplicada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../sql/conexao.php";

// Somente administradores podem acessar
if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../login.php");
    exit;
}

$idLogado = $_SESSION['idUsuario'];

// Consulta todos os usuários
$sql = "
SELECT 
    u.idUsuario,
    u.nome,
    u.email,

    c.cpf,
    c.cartao,

    a.cod_funcionario,
    a.permissao
FROM Usuario u
LEFT JOIN Cliente c ON c.idUsuario = u.idUsuario
LEFT JOIN Administrador a ON a.idUsuario = u.idUsuario
ORDER BY u.idUsuario ASC
";

resultado:
$resultado = $conexao->query($sql);

// Alterar permissão do administrador (toggle 0 ↔ 1)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['idUsuario'])) {

    $idUsuario = intval($_POST['idUsuario']);

    // Verifica se já é administrador
    $check = $conexao->query("SELECT * FROM Administrador WHERE idUsuario = $idUsuario");

    if ($check->num_rows > 0) {
        $dados = $check->fetch_assoc();
        $atual = $dados['permissao'];

        // Alterna 0 ↔ 1
        $novaPermissao = ($atual == 1) ? 0 : 1;

        $conexao->query("UPDATE Administrador SET permissao = $novaPermissao WHERE idUsuario = $idUsuario");
    } else {
        // Se não for admin, vira admin com permissão 1
        $conexao->query("INSERT INTO Administrador (permissao, idUsuario) VALUES (1, $idUsuario)");
    }

    header("Location: usuario.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Controle de Usuários</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eee;
        }
        .container {
            background: red;
            padding: 25px;
            width: 85%;
            margin: auto;
            margin-top: 75px;
            border: 4px solid black;
            border-radius: 15px;

        }

        h1 {
            text-align: center;
            background: red;
            border: 3px solid #3c0aff;
            padding: 12px;
            width: 40%;
            margin: auto;
            border-radius: 10px;
            color: white;
            margin-top: 25px
        }

        table {
            width: 100%;
            margin-top: 25px;
            background: white;
            border-radius: 10px;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ccc;
            text-align: center;
        }

        th {
            background: rgb(255, 80, 80);
            color: white;
            font-size: 18px;
        }

        .btn {
            padding: 6px 12px;
            background: rgb(50, 50, 255);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .btn:hover {
            opacity: .8;
        }
        .voltar {
            display: block;
            width: 85%;          /* igual ao container */
            margin: 20px auto;   /* distante do container */
            text-align: center;
            background: red;
            padding: 15px;
            border-radius: 10px;
            color: white;
            font-size: 20px;
            font-weight: bold;
            text-decoration: none;
        }
        .voltar:hover {
            color: white;
            background: red;
        }
    </style>
</head>

<body>

<?php include "include/topo_usuario.php"; ?>

<h1>CONTROLE DE USUÁRIOS</h1>

<div class="container">
<table>
    <tr>
        <th>Identificação</th>
        <th>Código</th>
        <th>Tipo</th>
        <th>CPF / Funcionário</th>
        <th>Situação / Permissão</th>
        <th>Ação</th>
    </tr>

    <?php while ($linha = $resultado->fetch_assoc()): ?>

    <?php
        // Identificação
        $id = $linha['idUsuario'];
        $nome = $linha['nome'];

        // Se é cliente
        $cpf = $linha['cpf'];

        // Se é administrador
        $codFuncionario = $linha['cod_funcionario'];
        $permissao = $linha['permissao'];

        // Descobre o tipo
        if ($cpf && $codFuncionario) {
            $tipo = "Cliente + ADM";
        } elseif ($cpf) {
            $tipo = "Cliente";
        } elseif ($codFuncionario) {
            $tipo = "Administrador";
        } else {
            $tipo = "Usuário comum";
        }
    ?>

    <tr>
        <td><?= $nome ?></td>
        <td><?= $id ?></td>
        <td><?= $tipo ?></td>

        <td>
            <?php 
                if ($cpf) echo "CPF: $cpf";
                else if ($codFuncionario) echo "Cód: $codFuncionario";
                else echo "-";
            ?>
        </td>

        <td>
            <?php if ($codFuncionario): ?>
                Permissão atual: <strong><?= $permissao ?></strong>
            <?php else: ?>
                -
            <?php endif; ?>
        </td>

        <td>
            <?php if ($id != $idLogado): ?>
                <?php if ($codFuncionario): ?>
                    <form method="POST">
                        <input type="hidden" name="idUsuario" value="<?= $id ?>">

                        <button class="btn">
                            Alternar para <?= ($permissao == 1 ? "0" : "1") ?>
                        </button>
                    </form>
                <?php else: ?>
                    <small>---</small>
                <?php endif; ?>
            <?php else: ?>
                <small>(você)</small>
            <?php endif; ?>
        </td>
    </tr>

    <?php endwhile; ?>
</table>
</div>

<a href="administrador.php" class="voltar">VOLTAR</a>

</body>
</html>
