<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $venda_id = $_POST["venda_id"];
    $cliente_id = $_POST["cliente_id"];
    $total_venda = $_POST["total_venda"];

    // 1. Altera o status de 'carrinho' para 'venda' e associa o cliente
    $sql = "UPDATE vendas 
            SET cliente_id = '$cliente_id', total = '$total_venda', status = 'venda' 
            WHERE id = '$venda_id'";
    
    if ($conn->query($sql) === TRUE) {
        
        $itens = $conn->query("SELECT produto_id, quantidade FROM itens_venda WHERE venda_id = '$venda_id'");
        while ($row = $itens->fetch_assoc()) {
            $id_p = $row['produto_id'];
            $qtd = $row['quantidade'];
            $conn->query("UPDATE produtos SET quantidade = quantidade - $qtd WHERE id = '$id_p'");
        }

        echo "Venda Feita e finalizada com sucesso!";
        echo "<br><a href='cadastrar_venda.php'>Nova Venda</a>";
    } else {
        echo "Erro ao finalizar venda: " . $conn->error;
    }
    $conn->close();
}
?>