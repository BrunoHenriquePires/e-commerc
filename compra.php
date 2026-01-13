<?php
include 'sql/conexao.php';

// Recebe id e quantidade
$idProduto = isset($_GET['idProduto']) ? intval($_GET['idProduto']) : 0;
$qtd = isset($_GET['qtd']) ? intval($_GET['qtd']) : 1;

// Busca o produto
$sql = "SELECT valor, nome FROM Produto WHERE idProduto = $idProduto";
$result = $conexao->query($sql);

if ($result->num_rows > 0) {
    $dados = $result->fetch_assoc();
    $valorProduto = $dados['valor'];
    $nomeProduto = $dados['nome'];
} else {
    $valorProduto = 0.00;
    $nomeProduto = "Produto não encontrado";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/compra.css">
    <title>Comprar Produto</title>
</head>
<body>

<div class="overlay">
    <form class="card" action="core/processa_compra.php" method="POST">

        <!-- Envia o id do produto -->
        <input type="hidden" name="idProduto" value="<?php echo $idProduto; ?>">

        <!-- Envia a quantidade -->
        <input type="hidden" name="qtd" value="<?php echo $qtd; ?>">

        <!-- Nome do produto -->
        <input type="text" value="<?php echo $nomeProduto; ?>" readonly>

        <!-- Mostra a quantidade na tela -->
        <input type="text" value="Quantidade: <?php echo $qtd; ?>" readonly>

        <!-- Valor formatado -->
        <input type="text" name="valor" 
               value="R$ <?php echo number_format($valorProduto, 2, ',', '.'); ?>" 
               readonly>

        <!-- Pagamento -->
        <select name="pagamento" required>
            <option value="">Escolha</option>
            <option value="debito">Cartão de débito</option>
            <option value="credito">Cartão de crédito</option>
            <option value="pix">Pix</option>
        </select>

        <button type="submit" class="confirmar">CONFIRMAR</button>
        <a href="index.php" class="cancelar">CANCELAR</a>
    </form>
</div>

</body>
</html>
