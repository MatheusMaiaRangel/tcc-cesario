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

// Consulta para buscar os eventos
$sql = "SELECT dia, mes, ano, nome, time_from, time_to, tipo, descricao FROM evento";
$result = $conn->query($sql);

// Verifica se há resultados
$eventos = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $eventos[] = $row;
    }
}

// Retorna os dados como JSON
header('Content-Type: application/json');
echo json_encode($eventos);

// Fecha a conexão
$conn->close();
?>
