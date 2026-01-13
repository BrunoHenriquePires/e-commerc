<?php
require_once 'conexao.php';
session_start();

$idItem = $_GET['idItem'];
$idUsuario = $_SESSION['idUsuario'];

/* Segurança: só apaga itens do carrinho do usuário */
$sql = "
    DELETE IC 
    FROM ItemCarrinho IC
    INNER JOIN Carrinho C ON C.idCarrinho = IC.idCarrinho
    WHERE IC.idItemCarrinho = $idItem 
    AND C.idUsuario = $idUsuario
";

$conexao->query($sql);

header("Location: ../carrinho.php");
exit;
