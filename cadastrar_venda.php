<?php
include 'db.php';

$produtos = $conn->query("SELECT id, nome, preco_unitario FROM produtos WHERE quantidade > 0")->fetch_all(MYSQLI_ASSOC);
$clientes = $conn->query("SELECT id, nome FROM clientes")->fetch_all(MYSQLI_ASSOC);

$itens = $conn->query("
    SELECT i.id, p.nome, i.quantidade, i.preco_unitario, (i.quantidade * i.preco_unitario) AS subtotal, i.venda_id
    FROM itens_venda i
    JOIN produtos p ON i.produto_id = p.id
    JOIN vendas v ON i.venda_id = v.id
    WHERE v.status = 'carrinho'
")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Venda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Cadastrar Intenção de Venda (Carrinho)</h1>
        
        <form action="salvar_no_carrinho.php" method="POST">
            <div class="mb-3">
                <label class="form-label">Produto</label>
                <select class="form-control" name="produto_id" required>
                    <option value="">Selecione o Produto...</option>
                    <?php foreach ($produtos as $p): ?>
                        <option value="<?= $p['id'] ?>"><?= $p['nome'] ?> - R$ <?= $p['preco_unitario'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Quantidade</label>
                <input type="number" class="form-control" name="quantidade" value="1" min="1" required>
            </div>
            <button type="submit" class="btn btn-primary">Cadastrar no Carrinho</button>
            <a href="index.html"><button type="button" class="btn btn-success">Menu</button></a>
        </form>

        <hr class="my-5">

        <?php if (!empty($itens)): ?>
            <h3>Itens Atuais no Carrinho</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Qtd</th>
                        <th>Subtotal</th>
                        <th>Ação</th> </tr>
                </thead>
                <tbody>
                    <?php 
                    $total = 0;
                    $venda_id_atual = $itens[0]['venda_id'];
                    foreach ($itens as $item): 
                        $total += $item['subtotal'];
                    ?>
                    <tr>
                        <td><?= $item['nome'] ?></td>
                        <td><?= $item['quantidade'] ?></td>
                        <td>R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></td>
                        <td>
                            <form action="remover_do_carrinho.php" method="POST" style="display:inline-block;">
                                <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Retirar</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <h4>Total: R$ <?= number_format($total, 2, ',', '.') ?></h4>

            <form action="finalizar_venda_feita.php" method="POST" class="mt-4">
                <input type="hidden" name="venda_id" value="<?= $venda_id_atual ?>">
                <input type="hidden" name="total_venda" value="<?= $total ?>">
                
                <div class="mb-3">
                    <label class="form-label">Associar Cliente para Venda Feita</label>
                    <select class="form-control" name="cliente_id" required>
                        <option value="">Selecione o Cliente...</option>
                        <?php foreach ($clientes as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= $c['nome'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Finalizar Venda Feita</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>