<?php
require_once 'sql/conexao.php';
session_start();
if (!isset($_SESSION['idUsuario'])) {
    header("Location: login.html");
    exit();
}

$idUsuario = $_SESSION['idUsuario'];

/* Buscar carrinho ativo */
$sql = "SELECT * FROM Carrinho 
        WHERE idUsuario = $idUsuario AND status = 'ativo'
        LIMIT 1";

$result = $conexao->query($sql);

if ($result->num_rows > 0) {
    $carrinho = $result->fetch_assoc();
    $idCarrinho = $carrinho['idCarrinho'];
} else {
    $sqlCriar = "INSERT INTO Carrinho (idUsuario) VALUES ($idUsuario)";
    $conexao->query($sqlCriar);
    $idCarrinho = $conexao->insert_id;
}

/* Buscar itens */
$sqlItens = "SELECT 
                ItemCarrinho.idItemCarrinho,
                ItemCarrinho.quantidade,
                Produto.idProduto,
                Produto.nome,
                Produto.valor,
                Produto.imagem
            FROM ItemCarrinho
            INNER JOIN Produto 
                ON Produto.idProduto = ItemCarrinho.idProduto
            WHERE ItemCarrinho.idCarrinho = $idCarrinho";

$itens = $conexao->query($sqlItens);

/* Calcular total */
$total = 0;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Carrinho</title>
<link rel="stylesheet" href="css/carrinho.css">
</head>

<body>

<?php include 'include/topo.php'; ?>

<div class="carrinho-container">

    <div class="cabecalho-carrinho">
        <h2 class="titulo">🛒 Carrinho</h2>
        <h3 class="total-topo">Total: R$0,00</h3>
    </div>

    <?php if ($itens->num_rows === 0): ?>
        <p class="vazio">Seu carrinho está vazio.</p>

    <?php else: ?>

        <?php while ($item = $itens->fetch_assoc()): 
            $total += $item['valor'] * $item['quantidade'];
        ?>

        <div class="item-card" id="item-<?= $item['idItemCarrinho'] ?>">

            <div class="imagem">
                <img src="data:image/jpeg;base64,<?= base64_encode($item['imagem']) ?>">
            </div>

            <div class="info">
                <h3><?= $item['nome'] ?></h3>

                <div class="linha-qtd">
                    <button class="btn-qtd" onclick="alterarQtd(<?= $item['idItemCarrinho'] ?>, -1)">−</button>

                    <p id="qtd-<?= $item['idItemCarrinho'] ?>">
                        <?= $item['quantidade'] ?>
                    </p>

                    <button class="btn-qtd" onclick="alterarQtd(<?= $item['idItemCarrinho'] ?>, 1)">+</button>
                </div>

                <a class="excluir" href="sql/excluir_item.php?idItem=<?= $item['idItemCarrinho'] ?>">Excluir</a>
            </div>

            <div class="preco">
                R$<?= number_format($item['valor'], 2, ',', '.') ?>
            </div>

        </div>

        <?php endwhile; ?>

        <script>
            document.querySelector(".total-topo").innerHTML =
                "Total: R$<?= number_format($total, 2, ',', '.') ?>";
        </script>

        <a class="botao-pagar" href="compra_carrinho.php?id=<?= $idCarrinho ?>">
            Comprar
        </a>

    <?php endif; ?>
            <a class="botao-pagar" href="index.php">VOLTAR</a>
</div>

<!-- SCRIPT PARA + e - -->
<script>
function alterarQtd(idItem, mudanca) {
    fetch("sql/atualizar_qtd.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "idItem=" + idItem + "&mudanca=" + mudanca
    })
    .then(r => r.json())
    .then(d => {
        if (d.sucesso) {

            document.getElementById("qtd-" + idItem).innerText = d.qtd;

            document.querySelector(".total-topo").innerHTML =
                "Total: R$" + d.total;
        }
    });
}
</script>

</body>
</html>
