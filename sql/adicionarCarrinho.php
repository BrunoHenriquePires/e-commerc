<?php
require_once 'conexao.php';
session_start();

// -------------------------
// VERIFICAR LOGIN
// -------------------------
if (!isset($_SESSION['idUsuario'])) {
     header("Location: ../login.html");
    exit();
}

$idUsuario = $_SESSION['idUsuario'];

// -------------------------
// PEGAR DADOS DA URL
// -------------------------
if (!isset($_GET['idProduto'])) {
    die("Produto não informado.");
}

$idProduto = intval($_GET['idProduto']);
$qtd = isset($_GET['qtd']) ? intval($_GET['qtd']) : 1;
if ($qtd < 1) $qtd = 1;

// -------------------------
// VERIFICAR SE EXISTE UM CARRINHO ATIVO
// -------------------------
$sql = "SELECT idCarrinho FROM Carrinho 
        WHERE idUsuario = $idUsuario AND status = 'ativo'";

$res = $conexao->query($sql);

if ($res->num_rows > 0) {
    $idCarrinho = $res->fetch_assoc()['idCarrinho'];
} else {
    // Criar novo carrinho
    $conexao->query("INSERT INTO Carrinho (idUsuario, status) VALUES ($idUsuario, 'ativo')");
    $idCarrinho = $conexao->insert_id;
}

// -------------------------
// VERIFICAR SE O PRODUTO JÁ ESTÁ NO CARRINHO
// -------------------------
$sqlItem = "SELECT * FROM ItemCarrinho 
            WHERE idCarrinho = $idCarrinho AND idProduto = $idProduto";

$resItem = $conexao->query($sqlItem);

if ($resItem->num_rows > 0) {
    // Atualizar quantidade
    $conexao->query("
        UPDATE ItemCarrinho 
        SET quantidade = quantidade + $qtd
        WHERE idCarrinho = $idCarrinho AND idProduto = $idProduto
    ");
} else {
    // Inserir novo item
    $conexao->query("
        INSERT INTO ItemCarrinho (idCarrinho, idProduto, quantidade)
        VALUES ($idCarrinho, $idProduto, $qtd)
    ");
}

// -------------------------
// REDIRECIONAR DE VOLTA
// -------------------------
header("Location: ../carrinho.php");
exit;
