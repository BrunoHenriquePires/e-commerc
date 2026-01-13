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
        <div class="esquerda">
            <div class="usuario">
                <a href="usuario.php">
                <img src="../img/usuario.png" alt="">
                </a>
            </div>
            <div class="venda">
                <a href="venda.php">
                <img src="../img/venda.png" alt="">
                </a>
            </div>
        </div>

        <div class="caixa-pesquisa">
            <form action="pesquisa.php" method="post">
                <input type="text" placeholder="Pesquisar..." name="busca">
                <button type="submit"><img src="../img/lupa.png" alt="Pesquisar"></button>
            </form>
        </div>

        <div class="direita">
            <?php if(isset($_SESSION['idUsuario'])): ?>
                <!-- Usuário logado -->
                <div class="login">
                    <a href="perfil.php"><img src="../img/Login.png" alt=""></a>
                    <a href="perfil.php"><p><?php echo htmlspecialchars($_SESSION['nome']); ?></p></a>
                </div>
            <?php else: ?>
                <!-- Usuário NÃO logado -->
                <div class="login">
                    <a href="login.html"><img src="../img/Login.png" alt="Logar"></a>
                    <a href="login.html"><p>Logar</p></a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
