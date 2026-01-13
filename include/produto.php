<?php
require_once 'sql/conexao.php';

$sql = "SELECT * FROM Produto";
$resultado = $conexao->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Produtos</title>
    <link rel="stylesheet" href="css/produto.css">
</head>
<body>

<div class="Tudo">
    <?php
    if ($resultado->num_rows > 0) {
        while ($linha = $resultado->fetch_assoc()) {
            $imagem = base64_encode($linha['imagem']);

            echo "<a href='ver_produto.php?idProduto={$linha['idProduto']}' class='card'>";

            echo "<img src='data:image/jpeg;base64,{$imagem}' alt='Produto'>";

            echo "<p class='nome'>{$linha['nome']}</p>";

            echo "<p class='valor'><strong>R$" . number_format($linha['valor'], 2, ',', '.') . "</strong></p>";

            if (mb_strlen($linha['descricao'], 'UTF-8') > 30) {
                $texto_limitado = mb_substr($linha['descricao'], 0, 30, 'UTF-8') . "...";
            } else {
                $texto_limitado = $linha['descricao'];
            }

            echo "<p class='descricao'>{$texto_limitado}</p>";

            echo "</a>";
        }
    } else {
        echo "<p>Nenhum produto encontrado.</p>";
    }
    ?>
</div>

</body>
</html>
