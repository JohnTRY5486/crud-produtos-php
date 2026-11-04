<?php
// Conexao com o banco de dados usando PDO.
// No XAMPP o usuario padrao e "root" e a senha fica vazia.
$host = "localhost";
$banco = "sistema_db";
$usuario = "root";
$senha_banco = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$banco;charset=utf8mb4", $usuario, $senha_banco);
    // faz o PDO lancar excecoes quando der erro no SQL
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar no banco de dados: " . $e->getMessage());
}

// funcao para escapar texto antes de mostrar na tela (protege contra XSS)
function h($texto) {
    return htmlspecialchars($texto ?? "", ENT_QUOTES, "UTF-8");
}
?>
