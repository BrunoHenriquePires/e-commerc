<?php
require_once 'conexao.php';

$idItem = $_POST['idItem'];
$mudanca = $_POST['mudanca'];

/* BUSCAR quantidade atual e idCarrinho */
$sql = "SELECT quantidade, idCarrinho, idProduto 
        FROM ItemCarrinho 
        WHERE idItemCarrinho = $idItem";

$dados = $conexao->query($sql)->fetch_assoc();

$qtdAtual = $dados['quantidade'];
$idCarrinho = $dados['idCarrinho'];
$idProduto = $dados['idProduto'];

/* BUSCAR quantidade disponível no estoque */
$sqlEstoque = "SELECT quantidade FROM Produto WHERE idProduto = $idProduto";
$estoque = $conexao->query($sqlEstoque)->fetch_assoc()['quantidade'];

/* CALCULAR nova quantidade */
$qtdNova = $qtdAtual + $mudanca;

/* IMPEDIR quantidade < 1 */
if ($qtdNova < 1) {
    $qtdNova = 1;
}

/* IMPEDIR quantidade > estoque */
if ($qtdNova > $estoque) {
    $qtdNova = $estoque; 
}

/* ATUALIZAR quantidade no banco */
$conexao->query("UPDATE ItemCarrinho 
                 SET quantidade = $qtdNova 
                 WHERE idItemCarrinho = $idItem");

/* RECALCULAR TOTAL do carrinho */
$sqlT = "SELECT Produto.valor, ItemCarrinho.quantidade
         FROM ItemCarrinho
         INNER JOIN Produto 
         ON Produto.idProduto = ItemCarrinho.idProduto
         WHERE ItemCarrinho.idCarrinho = $idCarrinho";

$res = $conexao->query($sqlT);

$total = 0;
while ($i = $res->fetch_assoc()) {
    $total += $i['valor'] * $i['quantidade'];
}

$total = number_format($total, 2, ',', '.');

/* RETORNO PARA O JAVASCRIPT */
echo json_encode([
    "sucesso" => true,
    "qtd" => $qtdNova,
    "total" => $total,
    "estoque" => $estoque
]);
