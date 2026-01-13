<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../sql/conexao.php";

// Somente administradores
if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../login.php");
    exit;
}

// Termo enviado pela pesquisa
$busca = isset($_POST['busca']) ? $conexao->real_escape_string($_POST['busca']) : "";

// Consulta filtrando pelo nome do cliente
$sql = "
SELECT 
    v.idVenda,
    v.data_venda,
    v.total,
    u.nome AS nomeCliente
FROM Venda v
INNER JOIN Usuario u ON u.idUsuario = v.idUsuario
WHERE u.nome LIKE '%$busca%'
ORDER BY v.idVenda DESC
";

$resultado = $conexao->query($sql);

// Somar total geral da pesquisa
$sqlTotal = "
SELECT SUM(v.total) AS totalGeral
FROM Venda v
INNER JOIN Usuario u ON u.idUsuario = v.idUsuario
WHERE u.nome LIKE '%$busca%'
";

$resTotal = $conexao->query($sqlTotal);
$totalGeral = $resTotal->fetch_assoc()["totalGeral"];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Pesquisa de Vendas</title>

    <style>
        body {
            background: #eee;
            font-family: Arial;
        }
        .container {
            background: red;
            padding: 25px;
            width: 85%;
            margin: auto;
            margin-top: 75px;
            border: 4px solid black;
            border-radius: 15px;
            position: relative;
        }

        h1 {
            text-align: center;
            background: red;
            border: 3px solid #3c0aff;
            padding: 12px;
            width: 50%;
            margin: auto;
            border-radius: 10px;
            color: white;
        }

        /* TOTAL NO CANTO SUPERIOR DIREITO */
        .totalGeral {
            position: absolute;
            top: 20px;
            right: 20px;
            background: white;
            padding: 12px 20px;
            border-radius: 10px;
            border: 2px solid black;
            font-size: 18px;
            font-weight: bold;
        }

        table {
            width: 100%;
            margin-top: 60px;
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
        .detalhes {
            background: #fafafa;
            border-left: 4px solid black;
            display: none;
        }

        .voltar {
            display: block;
            width: 85%;
            margin: 20px auto;
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

    <script>
        function mostrar(id) {
            let box = document.getElementById("detalhes_" + id);
            box.style.display = (box.style.display === "none") ? "table-row" : "none";
        }
    </script>
</head>

<body>

<?php include "include/topo_venda.php"; ?>

<h1>RESULTADO DA PESQUISA</h1>

<div class="container">

    <!-- TOTAL GERAL DA PESQUISA -->
    <div class="totalGeral">
        Total: R$ <?= number_format($totalGeral, 2, ",", ".") ?>
    </div>

<table>
    <tr>
        <th>ID Venda</th>
        <th>Cliente</th>
        <th>Total (R$)</th>
        <th>Data</th>
        <th>Ação</th>
    </tr>

<?php while ($v = $resultado->fetch_assoc()): ?>
    <tr>
        <td><?= $v["idVenda"] ?></td>
        <td><?= $v["nomeCliente"] ?></td>
        <td><?= number_format($v["total"], 2, ",", ".") ?></td>
        <td><?= $v["data_venda"] ?></td>
        <td>
            <button class="btn" onclick="mostrar(<?= $v['idVenda'] ?>)">Ver Itens</button>
        </td>
    </tr>

    <!-- DETALHES -->
    <tr id="detalhes_<?= $v['idVenda'] ?>" class="detalhes">
        <td colspan="5">

            <?php
                $idVenda = $v["idVenda"];
                $sqlItens = "
                    SELECT 
                        iv.nome,
                        iv.quantidade,
                        iv.valor
                    FROM ItemVenda iv
                    WHERE iv.idVenda = $idVenda
                ";
                $items = $conexao->query($sqlItens);
            ?>

            <table width="100%">
                <tr>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Valor Unitário</th>
                </tr>

                <?php while($item = $items->fetch_assoc()): ?>
                <tr>
                    <td><?= $item["nome"] ?></td>
                    <td><?= $item["quantidade"] ?></td>
                    <td>R$ <?= number_format($item["valor"], 2, ",", ".") ?></td>
                </tr>
                <?php endwhile; ?>
            </table>

        </td>
    </tr>

<?php endwhile; ?>

</table>
</div>

<a href="venda.php" class="voltar">VOLTAR</a>

</body>
</html>
