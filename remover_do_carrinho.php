<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_id = $_POST["item_id"];

    $sql = "DELETE FROM itens_venda WHERE id = '$item_id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: cadastrar_venda.php");
        exit;
    } else {
        echo "Erro ao retirar item do carrinho: " . $conn->error;
    }

    $conn->close();
}
?>