<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/topo.css">
</head>
<body>
    <div class="principal">
        <div class="plano"></div>

        <div class="caixa-pesquisa">
            <form action="pesquisa.php" method="post">
                <input type="text" placeholder="Pesquisar..." name="busca">
                <button type="submit"><img src="img/lupa.png" alt="Pesquisar"></button>
            </form>
        </div>

        <div class="direita">
            <?php if(isset($_SESSION['idUsuario'])): ?>
                <div class="login"><a href="perfil.php"><img src="img/Login.png" alt="Perfil"></a>
                <a href="perfil.php"><p><?php echo $_SESSION['nome']; ?></p></a></div>
            <?php else: ?>
                <div class="login"><a href="login.html"><img src="img/Login.png" alt="Logar"></a>
                <a href="login.html"><p>Logar</p></a></div>
            <?php endif; ?>

            <div class="carrinho"><a href="carrinho.php"><img src="img/Compra.png" alt="Compra"></a>
            <a href="carrinho.php"><p>carrinho</p></a></div>
        </div>
    </div>
</body>
</html>
