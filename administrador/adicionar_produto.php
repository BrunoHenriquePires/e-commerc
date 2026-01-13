<?php
require_once '../sql/conexao.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST['nome'];
    $valor = $_POST['valor'];
    $descricao = $_POST['descricao'];
    $quantidade = $_POST['quantidade'];

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $imagemTmp = file_get_contents($_FILES['imagem']['tmp_name']);
        $stmt = $conexao->prepare("INSERT INTO Produto (nome, quantidade, valor, descricao, imagem) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sidsb", $nome, $quantidade, $valor, $descricao, $imagemTmp);
        $stmt->send_long_data(4, $imagemTmp);
        $stmt->execute();
        echo "<script>alert('Produto cadastrado com sucesso!'); window.location.href='administrador.php';</script>";
    } else {
        echo "<script>alert('Erro ao enviar a imagem.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Produto</title>
    <link rel="stylesheet" href="css/adicionar.css">
</head>
<body>
<div class="container">
    <form method="post" enctype="multipart/form-data">
        <div class="produto-box">
            <div class="imagem-preview">
                <img id="preview" src="https://via.placeholder.com/200x200?text=Imagem+do+Produto" alt="Prévia da imagem">
                <input type="file" name="imagem" accept="image/*" onchange="mostrarImagem(event)">
            </div>

            <div class="info">
                <h2><input type="text" name="nome" placeholder="Nome do produto" required></h2>
                <p><strong>R$</strong><input type="number" step="0.01" name="valor" placeholder="0,00" required></p>

                <p><strong>Sobre:</strong></p>
                <textarea name="descricao" placeholder="Descrição do produto..." required></textarea>

                <div class="quantidade">
                    <p><strong>Quantidade</strong></p>
                    <div class="controle-qtd">
                        <button type="button" onclick="mudarQtd(-1)">−</button>
                        <input type="text" id="quantidade" name="quantidade" value="1" readonly>
                        <button type="button" onclick="mudarQtd(1)">+</button>
                    </div>
                </div>
                <div class= "ladinho">
                    <a href="administrador.php">VOLTAR</a>
                <button type="submit" class="btn">Criar produto</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function mostrarImagem(event) {
    const img = document.getElementById('preview')
    img.src = URL.createObjectURL(event.target.files[0])
}
function mudarQtd(valor) {
    let qtd = document.getElementById("quantidade")
    let atual = parseInt(qtd.value)
    if (atual + valor >= 1) qtd.value = atual + valor
}
</script>
</body>
</html>
