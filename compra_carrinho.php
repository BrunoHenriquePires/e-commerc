<?php
require_once 'sql/conexao.php';
session_start();

if (!isset($_SESSION['idUsuario'])) {
    header("Location: login.php");
    exit();
}

$idUsuario = $_SESSION['idUsuario'];

// Aceita tanto idCarrinho quanto id
$idCarrinho = 0;

if (isset($_GET['idCarrinho'])) {
    $idCarrinho = intval($_GET['idCarrinho']);
} elseif (isset($_GET['id'])) {
    $idCarrinho = intval($_GET['id']);
}

if ($idCarrinho <= 0) {
    die("Carrinho inválido.");
}

// Buscar itens do carrinho
$sql = "
SELECT 
    Produto.nome,
    Produto.valor,
    ItemCarrinho.quantidade
FROM ItemCarrinho
INNER JOIN Produto 
    ON Produto.idProduto = ItemCarrinho.idProduto
WHERE ItemCarrinho.idCarrinho = $idCarrinho
";

$result = $conexao->query($sql);

$valorTotal = 0;

if ($result->num_rows > 0) {
    while ($item = $result->fetch_assoc()) {
        $valorTotal += $item['valor'] * $item['quantidade'];
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Comprar Carrinho</title>
    <link rel="stylesheet" href="css/compra.css">
</head>
<body>

<div class="overlay">
    <form class="card" action="core/processa_compra.php" method="POST">

        <input type="hidden" name="idCarrinho" value="<?= $idCarrinho ?>">

        <input type="text" value="Compra do Carrinho" readonly>

        <input type="text" value="R$ <?= number_format($valorTotal, 2, ',', '.') ?>" readonly>

        <select name="pagamento" required>
            <option value="">Escolha</option>
            <option value="debito">Cartão de débito</option>
            <option value="credito">Cartão de crédito</option>
            <option value="pix">Pix</option>
        </select>

        <button type="submit" class="confirmar">CONFIRMAR</button>
        <a href="carrinho.php" class="cancelar">CANCELAR</a>
    </form>
</div>

</body>
</html>
