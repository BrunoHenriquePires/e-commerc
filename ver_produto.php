<?php
require_once 'sql/conexao.php';
include 'include/topo.php';

if (!isset($_GET['idProduto'])) {
    echo "Produto não especificado.";
    exit;
}

$idProduto = $_GET['idProduto'];
$sql = "SELECT * FROM Produto WHERE idProduto = $idProduto";
$resultado = $conexao->query($sql);

if ($resultado->num_rows == 0) {
    echo "Produto não encontrado.";
    exit;
}

$produto = $resultado->fetch_assoc();
$imagemBase64 = 'data:image/jpeg;base64,' . base64_encode($produto['imagem']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title><?php echo $produto['nome']; ?></title>
<link rel="stylesheet" href="css/ver_produto.css">
</head>
<body>

<div class="container">
    <div class="produto-img">
        <img src="<?php echo $imagemBase64; ?>">
    </div>

    <div class="produto-info">
        <h2><?php echo $produto['nome']; ?></h2>
        <div class="valor">R$<?php echo number_format($produto['valor'], 2, ',', '.'); ?></div>

        <div class="sobre">Sobre:</div>
        <p><?php echo $produto['descricao']; ?></p>

        <!-- QUANTIDADE -->
        <div class="quantidade">
            <label>Quantidade</label>
            <button onclick="diminuir()">-</button>
            <input type="text" id="qtd" value="1" readonly>
            <button onclick="aumentar()">+</button>
        </div>

        <!-- BOTÕES -->
        <div class="botoes">
            <a id="btnComprar" href="compra.php">Comprar</a>
            <a id="btnCarrinho" href="sql/adicionarCarrinho.php">Carrinho</a>
        </div>

    </div>
</div>

<!-- SCRIPTS -->
<script>
let idProduto = <?php echo $produto['idProduto']; ?>;

function atualizarLinks() {
    let qtd = document.getElementById('qtd').value;

    document.getElementById('btnComprar').href =
        "compra.php?idProduto=" + idProduto + "&qtd=" + qtd;

    document.getElementById('btnCarrinho').href =
        "sql/adicionarCarrinho.php?idProduto=" + idProduto + "&qtd=" + qtd;
}

function diminuir() {
    let qtd = document.getElementById('qtd');
    if (qtd.value > 1) qtd.value--;
    atualizarLinks();
}

function aumentar() {
    let qtd = document.getElementById('qtd');
    qtd.value++;
    atualizarLinks();
}

atualizarLinks();
</script>

</body>
</html>
