<?php
$servidor = "yamabiko.proxy.rlwy.net";
$usuario  = "root";
$senha    = "YbPgdlGOJyOsGYYLUZZNmrnvOyRAdexN";
$banco    = "e_commerce"; 
$porta    = 16828;     

$conexao = new mysqli($servidor, $usuario, $senha, $banco, $porta);

if ($conexao->connect_error) {
    die("Erro na conexão com o banco: " . $conexao->connect_error);
}

?>
