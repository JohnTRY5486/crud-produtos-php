<?php
// UPDATE: carrega o produto pelo id e atualiza os dados
require "conexao.php";

$id = (int)($_GET["id"] ?? $_POST["id"] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = :id");
$stmt->execute([":id" => $id]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    header("Location: index.php");
    exit;
}

$erros = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome  = trim($_POST["nome"] ?? "");
    $marca = trim($_POST["marca"] ?? "");
    $senha = $_POST["senha"] ?? "";   // se vier vazia, mantem a senha atual
    $cor   = trim($_POST["cor"] ?? "");
    $valor = str_replace(",", ".", trim($_POST["valor"] ?? ""));
    $ativo = isset($_POST["ativo"]) ? 1 : 0;

    if ($nome === "" || strlen($nome) > 100) {
        $erros[] = "Informe o nome (ate 100 caracteres).";
    }
    if ($marca === "" || strlen($marca) > 100) {
        $erros[] = "Informe a marca (ate 100 caracteres).";
    }
    if ($senha !== "" && strlen($senha) < 6) {
        $erros[] = "A nova senha deve ter pelo menos 6 caracteres.";
    }
    if ($valor === "" || !is_numeric($valor) || $valor < 0) {
        $erros[] = "Informe um valor valido.";
    }

    if (count($erros) === 0) {
        try {
            if ($senha !== "") {
                $sql = "UPDATE produtos SET nome=:nome, marca=:marca, senha=:senha,
                        cor=:cor, valor=:valor, ativo=:ativo WHERE id=:id";
                $params = [":senha" => password_hash($senha, PASSWORD_DEFAULT)];
            } else {
                $sql = "UPDATE produtos SET nome=:nome, marca=:marca,
                        cor=:cor, valor=:valor, ativo=:ativo WHERE id=:id";
                $params = [];
            }
            $params += [
                ":nome" => $nome,
                ":marca" => $marca,
                ":cor" => $cor,
                ":valor" => $valor,
                ":ativo" => $ativo,
                ":id" => $id
            ];
            $pdo->prepare($sql)->execute($params);
            header("Location: index.php?msg=editado");
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $erros[] = "Ja existe um produto com essa marca.";
            } else {
                $erros[] = "Erro ao atualizar o produto.";
            }
        }
    }

    // se deu erro, mostra de novo o que a pessoa digitou
    $produto["nome"] = $nome;
    $produto["marca"] = $marca;
    $produto["cor"] = $cor;
    $produto["valor"] = $valor;
    $produto["ativo"] = $ativo;
}

include "cabecalho.php";
?>

<h1 class="h3 mb-3">Editar produto #<?= (int)$produto["id"] ?></h1>

<?php if (count($erros) > 0): ?>
  <div class="alert alert-danger">
    <ul class="mb-0">
      <?php foreach ($erros as $e): ?>
        <li><?= h($e) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<form method="post" id="formProduto" data-edicao="1" novalidate>
  <input type="hidden" name="id" value="<?= (int)$produto["id"] ?>">
  <div class="row g-3">
    <div class="col-md-6">
      <label for="nome" class="form-label">Nome</label>
      <input type="text" class="form-control" id="nome" name="nome" maxlength="100" value="<?= h($produto["nome"]) ?>" required>
    </div>
    <div class="col-md-6">
      <label for="marca" class="form-label">Marca</label>
      <input type="text" class="form-control" id="marca" name="marca" maxlength="100" value="<?= h($produto["marca"]) ?>" required>
    </div>
    <div class="col-md-6">
      <label for="senha" class="form-label">Nova senha (deixe vazio para manter a atual)</label>
      <input type="password" class="form-control" id="senha" name="senha">
    </div>
    <div class="col-md-3">
      <label for="cor" class="form-label">Cor</label>
      <input type="text" class="form-control" id="cor" name="cor" maxlength="50" value="<?= h($produto["cor"]) ?>">
    </div>
    <div class="col-md-3">
      <label for="valor" class="form-label">Valor (R$)</label>
      <input type="text" class="form-control" id="valor" name="valor" value="<?= h($produto["valor"]) ?>" required>
    </div>
    <div class="col-12">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" id="ativo" name="ativo" <?= $produto["ativo"] ? "checked" : "" ?>>
        <label class="form-check-label" for="ativo">Produto ativo</label>
      </div>
    </div>
  </div>
  <div class="mt-4">
    <button type="submit" class="btn btn-primary">Atualizar</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
  </div>
</form>

<?php include "rodape.php"; ?>
