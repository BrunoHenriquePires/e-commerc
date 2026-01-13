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
    <link rel="stylesheet" href="css/administrador.css">
    <style>
        .produto {
            text-align: center;
            cursor: pointer;
        }
        .produto img {
            width: 150px;
            height: 150px;
            border: 3px solid transparent;
            border-radius: 10px;
            transition: 0.2s;
        }
        .produto img.selecionado {
            border-color: #4010ddff;
            box-shadow: 0 0 10px #e2b9deff;
        }
    </style>
</head>
<body>
    <?php include 'include/topo.php'; ?>
<div class="container">
    <div class="produtos-box">
        <div class="topo">SELECIONAR PRODUTO</div>
        <div class="grid-produtos">
            <?php
            if ($resultado->num_rows > 0) {
                while ($linha = $resultado->fetch_assoc()) {
                    $imagem = base64_encode($linha['imagem']);
                    $id = $linha['idProduto'];
                    echo "<div class='produto' data-id='$id'>";
                    echo "<img src='data:image/jpeg;base64,{$imagem}' alt='Imagem do Produto'>";
                    echo "<p>{$linha['nome']}</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>Nenhum produto encontrado.</p>";
            }
            ?>
        </div>
    </div>
    <div class="botoes">
        <input type="hidden" id="idSelecionado">
        <a href="adicionar_produto.php" class="btn">ADICIONAR PRODUTO</a>
        <button id="btnAlterar" class="btn">ALTERAR PRODUTO</button>
    </div>
</div>

<script>
    let produtos = document.querySelectorAll('.produto');
    let idSelecionado = document.getElementById('idSelecionado');
    let botaoAlterar = document.getElementById('btnAlterar');

    produtos.forEach(produto => {
        produto.addEventListener('click', () => {
            // Remove seleção anterior
            produtos.forEach(p => p.querySelector('img').classList.remove('selecionado'));

            // Marca apenas a imagem clicada
            let img = produto.querySelector('img');
            img.classList.add('selecionado');

            // Salva o ID do produto
            idSelecionado.value = produto.getAttribute('data-id');
        });
    });

    botaoAlterar.addEventListener('click', () => {
        if (idSelecionado.value) {
            window.location.href = 'alterar_produto.php?idProduto=' + idSelecionado.value;
        } else {
            alert('Selecione um produto antes de alterar.');
        }
    });
</script>

</body>
</html>
