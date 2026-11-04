<?php
// CREATE: formulario e gravacao de um novo produto
require "conexao.php";

$erros = [];
$nome = $marca = $cor = "";
$valor = "";
$ativo = 1;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome  = trim($_POST["nome"] ?? "");
    $marca = trim($_POST["marca"] ?? "");
    $senha = $_POST["senha"] ?? "";
    $cor   = trim($_POST["cor"] ?? "");
    $valor = str_replace(",", ".", trim($_POST["valor"] ?? ""));
    $ativo = isset($_POST["ativo"]) ? 1 : 0;

    // validacao no servidor (a do JavaScript pode ser burlada)
    if ($nome === "" || strlen($nome) > 100) {
        $erros[] = "Informe o nome (ate 100 caracteres).";
    }
    if ($marca === "" || strlen($marca) > 100) {
        $erros[] = "Informe a marca (ate 100 caracteres).";
    }
    if (strlen($senha) < 6) {
        $erros[] = "A senha deve ter pelo menos 6 caracteres.";
    }
    if ($valor === "" || !is_numeric($valor) || $valor < 0) {
        $erros[] = "Informe um valor valido.";
    }

    if (count($erros) === 0) {
        // a senha nunca e guardada em texto puro
        $hash = password_hash($senha, PASSWORD_DEFAULT);

        try {
            $sql = "INSERT INTO produtos (nome, marca, senha, cor, valor, ativo)
                    VALUES (:nome, :marca, :senha, :cor, :valor, :ativo)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ":nome" => $nome,
                ":marca" => $marca,
                ":senha" => $hash,
                ":cor" => $cor,
                ":valor" => $valor,
                ":ativo" => $ativo
            ]);
            header("Location: index.php?msg=criado");
            exit;
        } catch (PDOException $e) {
            // codigo 23000 = violacao de chave unica (marca repetida)
            if ($e->getCode() == 23000) {
                $erros[] = "Ja existe um produto com essa marca.";
            } else {
                $erros[] = "Erro ao salvar o produto.";
            }
        }
    }
}

include "cabecalho.php";
?>

<h1 class="h3 mb-3">Novo produto</h1>

<?php if (count($erros) > 0): ?>
  <div class="alert alert-danger">
    <ul class="mb-0">
      <?php foreach ($erros as $e): ?>
        <li><?= h($e) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<form method="post" id="formProduto" novalidate>
  <div class="row g-3">
    <div class="col-md-6">
      <label for="nome" class="form-label">Nome</label>
      <input type="text" class="form-control" id="nome" name="nome" maxlength="100" value="<?= h($nome) ?>" required>
    </div>
    <div class="col-md-6">
      <label for="marca" class="form-label">Marca</label>
      <input type="text" class="form-control" id="marca" name="marca" maxlength="100" value="<?= h($marca) ?>" required>
    </div>
    <div class="col-md-6">
      <label for="senha" class="form-label">Senha</label>
      <input type="password" class="form-control" id="senha" name="senha" required>
    </div>
    <div class="col-md-3">
      <label for="cor" class="form-label">Cor</label>
      <input type="text" class="form-control" id="cor" name="cor" maxlength="50" value="<?= h($cor) ?>">
    </div>
    <div class="col-md-3">
      <label for="valor" class="form-label">Valor (R$)</label>
      <input type="text" class="form-control" id="valor" name="valor" value="<?= h($valor) ?>" required>
    </div>
    <div class="col-12">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" id="ativo" name="ativo" <?= $ativo ? "checked" : "" ?>>
        <label class="form-check-label" for="ativo">Produto ativo</label>
      </div>
    </div>
  </div>
  <div class="mt-4">
    <button type="submit" class="btn btn-success">Salvar</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
  </div>
</form>

<?php include "rodape.php"; ?>
