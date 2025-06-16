<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tccteste";

// Conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}


if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $sql = "DELETE FROM evento WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        echo "Evento deletado com sucesso!";
    } else {
        echo "Erro ao deletar evento.";
    }
    $stmt->close();
    $conn->close();
} else {
    echo "ID do evento não informado.";
}
?>