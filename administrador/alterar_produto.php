<?php
require_once '../sql/conexao.php';

// ---------------------------
//  RECEBE ID PELA URL
// ---------------------------
$idProduto = isset($_GET['idProduto']) ? intval($_GET['idProduto']) : 0;

if ($idProduto <= 0) {
    die("Produto inválido.");
}

// ---------------------------
//  SE CLICAR EM DELETAR
// ---------------------------
if (isset($_POST['deletar'])) {

    // Remove itens do carrinho
    $sql1 = "DELETE FROM ItemCarrinho WHERE idProduto = ?";
    $stmt1 = $conexao->prepare($sql1);
    $stmt1->bind_param("i", $idProduto);
    $stmt1->execute();

    // Remove itens da venda
    $sql2 = "DELETE FROM ItemVenda WHERE idProduto = ?";
    $stmt2 = $conexao->prepare($sql2);
    $stmt2->bind_param("i", $idProduto);
    $stmt2->execute();

    // Remove vendas que envolvem o produto
    $sql3 = "DELETE FROM Venda WHERE idProduto = ?";
    $stmt3 = $conexao->prepare($sql3);
    $stmt3->bind_param("i", $idProduto);
    $stmt3->execute();

    // Agora remove o produto
    $sql4 = "DELETE FROM Produto WHERE idProduto = ?";
    $stmt4 = $conexao->prepare($sql4);
    $stmt4->bind_param("i", $idProduto);
    $stmt4->execute();

    echo "<script>alert('Produto deletado com sucesso!'); window.location.href='administrador.php';</script>";
    exit;
}

// ---------------------------
//  BUSCA DADOS DO PRODUTO
// ---------------------------
$sql = "SELECT * FROM Produto WHERE idProduto = $idProduto";
$result = $conexao->query($sql);

if ($result->num_rows == 0) {
    die("Produto não encontrado.");
}

$produto = $result->fetch_assoc();

// ---------------------------
//  ATUALIZAR PRODUTO
// ---------------------------
if ($_SERVER["REQUEST_METHOD"] === "POST" && !isset($_POST['deletar'])) {

    $nome = $_POST['nome'];
    $valor = $_POST['valor'];
    $descricao = $_POST['descricao'];
    $quantidade = $_POST['quantidade'];

    if ($quantidade < 1) $quantidade = 1;

    // ATUALIZAR COM IMAGEM NOVA
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {

        $imagemTmp = file_get_contents($_FILES['imagem']['tmp_name']);

        $stmt = $conexao->prepare("
            UPDATE Produto 
            SET nome=?, quantidade=?, valor=?, descricao=?, imagem=? 
            WHERE idProduto=?
        ");

        $stmt->bind_param("sidsbi", $nome, $quantidade, $valor, $descricao, $imagemTmp, $idProduto);
        $stmt->send_long_data(4, $imagemTmp);
        $stmt->execute();

    } else {

        // ATUALIZAR SEM IMAGEM
        $stmt = $conexao->prepare("
            UPDATE Produto 
            SET nome=?, quantidade=?, valor=?, descricao=? 
            WHERE idProduto=?
        ");

        $stmt->bind_param("sidsi", $nome, $quantidade, $valor, $descricao, $idProduto);
        $stmt->execute();
    }

    echo "<script>alert('Produto atualizado com sucesso!'); window.location.href='administrador.php';</script>";
    exit;
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Produto</title>
    <link rel="stylesheet" href="css/adicionar.css">
</head>
<body>

<div class="container">
    <form method="post" enctype="multipart/form-data">
        <div class="produto-box">

            <div class="imagem-preview">
                <img id="preview" 
                     src="data:image/jpeg;base64,<?= base64_encode($produto['imagem']) ?>" 
                     alt="Prévia da imagem">

                <input type="file" name="imagem" accept="image/*" onchange="mostrarImagem(event)">
            </div>

            <div class="info">
                <h2>
                    <input type="text" name="nome" value="<?= $produto['nome'] ?>" required>
                </h2>

                <p><strong>R$</strong>
                    <input type="number" step="0.01" name="valor"
                           value="<?= $produto['valor'] ?>" required>
                </p>

                <p><strong>Sobre:</strong></p>
                <textarea name="descricao" required><?= $produto['descricao'] ?></textarea>

                <!-- QUANTIDADE EDITÁVEL -->
                <div class="quantidade">
                    <p><strong>Quantidade</strong></p>
                    <div class="controle-qtd">
                        <button type="button" onclick="mudarQtd(-1)">−</button>

                        <input type="number" id="quantidade" name="quantidade"
                               value="<?= $produto['quantidade'] ?>" min="1">

                        <button type="button" onclick="mudarQtd(1)">+</button>
                    </div>
                </div>

                <div class="ladinho">
                    <a href="administrador.php">VOLTAR</a>
                    <button type="submit" class="btn">Salvar alterações</button>
                </div>

                <!-- Botão Deletar -->
                <button type="submit" name="deletar"
                        onclick="return confirm('Tem certeza que deseja excluir este produto?')"
                        style="margin-top: 15px; background-color: red; color: white; padding: 10px; border: none; cursor: pointer;">
                    Excluir Produto
                </button>

            </div>

        </div>
    </form>
</div>

<script>
function mostrarImagem(event) {
    const img = document.getElementById('preview');
    img.src = URL.createObjectURL(event.target.files[0]);
}

function mudarQtd(valor) {
    let qtd = document.getElementById("quantidade");
    let atual = parseInt(qtd.value) || 1;

    atual += valor;
    if (atual < 1) atual = 1;

    qtd.value = atual;
}
</script>

</body>
</html>
