<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cadastro</title>
    <link rel="stylesheet" href="css/cadastro.css">
</head>
<body>
    <div class="tela">
            <div class="cima"></div>
            <img src="img/fundo.png" class="foto">
            <h1 class="texto">COMECE<br>HOJE!</h1>
            <div class="baixo"></div>
        <div class="direita">
            <div class="cadastro">
                <form action="core/cadastro.php" method="post">
                    <h1 class="h1">Cadastro</h1>
                    <input type="text" name="nome" placeholder="Nome:" required>
                    <input type="email" name="email" placeholder="Email:" required>
                    <input type="password" name="senha" placeholder="Senha:" required>
                    <input type="text" name="cpf" placeholder="Cpf: XXX.XXX.XXX-YY" required>
                    <button type="submit">cadastrar</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>