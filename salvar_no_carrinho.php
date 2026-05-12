<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $produto_id = $_POST["produto_id"];
    $quantidade = $_POST["quantidade"];

    $res = $conn->query("SELECT id FROM vendas WHERE status = 'carrinho' LIMIT 1");
    $venda = $res->fetch_assoc();

    if (!$venda) {
        $conn->query("INSERT INTO vendas (status, total) VALUES ('carrinho', 0)");
        $venda_id = $conn->insert_id;
    } else {
        $venda_id = $venda['id'];
    }

    $res_p = $conn->query("SELECT preco_unitario FROM produtos WHERE id = '$produto_id'");
    $prod = $res_p->fetch_assoc();
    $preco = $prod['preco_unitario'];

    $sql = "INSERT INTO itens_venda (venda_id, produto_id, quantidade, preco_unitario) 
            VALUES ('$venda_id', '$produto_id', '$quantidade', '$preco')";

    if ($conn->query($sql) === TRUE) {
        header("Location: cadastrar_venda.php");
    } else {
        echo "Erro ao salvar no carrinho: " . $conn->error;
    }
    $conn->close();
}
?>