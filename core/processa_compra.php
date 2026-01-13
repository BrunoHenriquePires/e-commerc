<?php
session_start();
include '../sql/conexao.php';

// VERIFICA LOGIN
if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../login.php");
    exit;
}

$idUsuario = $_SESSION['idUsuario'];
$pagamento = $_POST['pagamento'];

// Compra individual ou carrinho?
$idProduto = isset($_POST['idProduto']) ? intval($_POST['idProduto']) : 0;
$idCarrinho = isset($_POST['idCarrinho']) ? intval($_POST['idCarrinho']) : 0;

if ($idProduto <= 0 && $idCarrinho <= 0) {
    die("Nenhum produto ou carrinho recebido.");
}

/* -----------------------------------------------------
   1. BUSCAR CLIENTE
------------------------------------------------------ */
$sqlCliente = "SELECT * FROM Cliente WHERE idUsuario = $idUsuario LIMIT 1";
$result = $conexao->query($sqlCliente);

if ($result->num_rows == 1) {
    $cliente = $result->fetch_assoc();
    $cpf = $cliente['cpf'];
} else {
    $cpf = "000.000.000-00";
    $conexao->query("INSERT INTO Cliente (cpf, idUsuario) VALUES ('$cpf', $idUsuario)");
}

/* =====================================================
   🔵 MODO 1 — COMPRA DIRETA DE UM PRODUTO
===================================================== */
if ($idProduto > 0) {

    $sqlProd = "
        SELECT idProduto, nome, valor, quantidade AS estoque
        FROM Produto
        WHERE idProduto = $idProduto
    ";
    $res = $conexao->query($sqlProd);

    if ($res->num_rows == 0) {
        die("Produto não encontrado.");
    }

    $prod = $res->fetch_assoc();

    if ($prod['estoque'] < 1) {
        die("Estoque insuficiente para comprar este produto.");
    }

    // Dados
    $total = $prod['valor'];
    $listaItens = [];

    $listaItens[] = [
        "idProduto" => $prod["idProduto"],
        "nome" => $prod["nome"],
        "valor" => $prod["valor"],
        "quantidade" => 1,
        "estoque" => $prod["estoque"]
    ];
}

/* =====================================================
   🔵 MODO 2 — COMPRA DO CARRINHO COMPLETO
===================================================== */
if ($idCarrinho > 0) {

    $sqlItens = "
        SELECT 
            Produto.idProduto,
            Produto.nome,
            Produto.valor,
            Produto.quantidade AS estoque,
            ItemCarrinho.quantidade
        FROM ItemCarrinho
        INNER JOIN Produto 
            ON Produto.idProduto = ItemCarrinho.idProduto
        WHERE ItemCarrinho.idCarrinho = $idCarrinho
    ";

    $itens = $conexao->query($sqlItens);

    if ($itens->num_rows == 0) {
        die("Carrinho vazio.");
    }

    $total = 0;
    $listaItens = [];

    while ($item = $itens->fetch_assoc()) {

        if ($item['estoque'] < $item['quantidade']) {
            die("Estoque insuficiente para o produto: " . $item['nome']);
        }

        $subtotal = $item['valor'] * $item['quantidade'];
        $total += $subtotal;

        $listaItens[] = $item;
    }
}

/* -----------------------------------------------------
   3. REGISTRAR A VENDA
------------------------------------------------------ */
$dataHoje = date("Y-m-d");

$sqlVenda = "
    INSERT INTO Venda (idUsuario, total, data_venda) 
    VALUES ($idUsuario, '$total', '$dataHoje')
";
$conexao->query($sqlVenda);
$idVenda = $conexao->insert_id;

/* -----------------------------------------------------
   4. REGISTRAR OS ITENS DA VENDA + ATUALIZAR ESTOQUE
------------------------------------------------------ */
foreach ($listaItens as $item) {

    $idP = intval($item['idProduto']);
    $nome = $item['nome'];
    $valor = floatval($item['valor']);
    $qtd = intval($item['quantidade']);
    $estoqueAtual = intval($item['estoque']);

    // Registrar item da venda
    $sqlItem = "
        INSERT INTO ItemVenda (idVenda, idProduto, nome, quantidade, valor)
        VALUES ($idVenda, $idP, '$nome', $qtd, '$valor')
    ";
    $conexao->query($sqlItem);

    // Atualizar estoque
    $novoEstoque = $estoqueAtual - $qtd;

    $sqlUpdateEstoque = "
        UPDATE Produto 
        SET quantidade = $novoEstoque
        WHERE idProduto = $idP
    ";
    $conexao->query($sqlUpdateEstoque);
}

/* -----------------------------------------------------
   5. SE FOI DO CARRINHO → FECHA O CARRINHO
------------------------------------------------------ */
if ($idCarrinho > 0) {
    $conexao->query("UPDATE Carrinho SET status='finalizado' WHERE idCarrinho = $idCarrinho");
    $conexao->query("DELETE FROM ItemCarrinho WHERE idCarrinho = $idCarrinho");
}

/* -----------------------------------------------------
   6. GERAR DADOS DO COMPROVANTE
------------------------------------------------------ */
$valorFormatado = number_format($total, 2, ',', '.');
$nomeUsuario = $_SESSION['nome'];
$dataHora = date('d/m/Y \à\s H:i:s');

$cpfMascarado =
    substr($cpf, 0, 3) . '.***.' .
    substr($cpf, 6, 3) . '-**';

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Pagamento Concluído</title>
<link rel="stylesheet" href="../css/comprovante.css">
</head>
<body>
<div class="comprovante">
    <div class="icone">
        <div class="circulo">
            <div class="check"></div>
        </div>
    </div>

    <h2>Pagamento via <?= ucfirst($pagamento); ?> concluído!</h2>
    <p class="data"><?= $dataHora; ?></p>

    <hr>

    <p class="valor-texto">Valor total pago</p>
    <p class="valor">R$ <?= $valorFormatado; ?></p>

    <p class="para">Itens comprados</p>
    <?php foreach ($listaItens as $item): ?>
        <p class="nome"><?= $item['quantidade'] . "x " . $item['nome']; ?></p>
    <?php endforeach; ?>

    <p class="para">Comprador</p>
    <p class="nome"><?= $nomeUsuario; ?></p>
    <p class="cpf">CPF: <?= $cpfMascarado; ?></p>

    <a href="../index.php" class="botao">Voltar ao início</a>
</div>
</body>
</html>
