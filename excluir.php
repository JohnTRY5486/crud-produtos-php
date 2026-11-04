<?php
// DELETE: exclui o produto. Usa POST para nao apagar por engano via link.
require "conexao.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = (int)($_POST["id"] ?? 0);
    if ($id > 0) {
        $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = :id");
        $stmt->execute([":id" => $id]);
    }
    header("Location: index.php?msg=excluido");
    exit;
}

header("Location: index.php");
exit;
