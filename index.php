<?php
// READ: lista todos os produtos cadastrados, com busca por nome ou marca
require "conexao.php";

$busca = trim($_GET["busca"] ?? "");

if ($busca !== "") {
    $sql = "SELECT id, nome, marca, cor, valor, ativo, data_criacao
            FROM produtos
            WHERE nome LIKE :busca OR marca LIKE :busca
            ORDER BY id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":busca" => "%" . $busca . "%"]);
} else {
    $stmt = $pdo->query("SELECT id, nome, marca, cor, valor, ativo, data_criacao
                         FROM produtos ORDER BY id DESC");
}
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$msg = $_GET["msg"] ?? "";

include "cabecalho.php";
?>

<h1 class="h3 mb-3">Produtos cadastrados</h1>

<?php if ($msg === "criado"): ?>
  <div class="alert alert-success">Produto cadastrado com sucesso!</div>
<?php elseif ($msg === "editado"): ?>
  <div class="alert alert-success">Produto atualizado com sucesso!</div>
<?php elseif ($msg === "excluido"): ?>
  <div class="alert alert-warning">Produto excluido.</div>
<?php endif; ?>

<form class="row g-2 mb-3" method="get" action="index.php">
  <div class="col-12 col-md-8">
    <input type="text" name="busca" class="form-control" placeholder="Buscar por nome ou marca"
           value="<?= h($busca) ?>">
  </div>
  <div class="col-6 col-md-2 d-grid">
    <button class="btn btn-primary" type="submit">Buscar</button>
  </div>
  <div class="col-6 col-md-2 d-grid">
    <a class="btn btn-success" href="cadastrar.php">Novo produto</a>
  </div>
</form>

<div class="table-responsive">
  <table class="table table-striped table-hover align-middle">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Marca</th>
        <th>Cor</th>
        <th>Valor</th>
        <th>Status</th>
        <th>Criado em</th>
        <th>Acoes</th>
      </tr>
    </thead>
    <tbody>
      <?php if (count($produtos) === 0): ?>
        <tr><td colspan="8" class="text-center">Nenhum produto encontrado.</td></tr>
      <?php endif; ?>

      <?php foreach ($produtos as $p): ?>
        <tr>
          <td><?= (int)$p["id"] ?></td>
          <td><?= h($p["nome"]) ?></td>
          <td><?= h($p["marca"]) ?></td>
          <td><?= h($p["cor"]) ?></td>
          <td>R$ <?= number_format($p["valor"], 2, ",", ".") ?></td>
          <td>
            <?php if ($p["ativo"]): ?>
              <span class="badge bg-success">Ativo</span>
            <?php else: ?>
              <span class="badge bg-secondary">Inativo</span>
            <?php endif; ?>
          </td>
          <td><?= date("d/m/Y H:i", strtotime($p["data_criacao"])) ?></td>
          <td class="text-nowrap">
            <a class="btn btn-sm btn-warning" href="editar.php?id=<?= (int)$p["id"] ?>">Editar</a>
            <form class="d-inline" method="post" action="excluir.php"
                  onsubmit="return confirm('Deseja realmente excluir este produto?');">
              <input type="hidden" name="id" value="<?= (int)$p["id"] ?>">
              <button class="btn btn-sm btn-danger" type="submit">Excluir</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include "rodape.php"; ?>
