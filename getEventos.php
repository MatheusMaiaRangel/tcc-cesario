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

session_start();
$tipo = $_SESSION['tipo'] ?? null;
$turma = $_SESSION['turma'] ?? null;

if ($tipo === 'aluno') {
    if (!$turma) {
        echo json_encode([]);
        exit;
    }
    $sql = "SELECT e.id, e.dia, e.mes, e.ano, e.nome, e.time_from, e.time_to, e.tipo, e.descricao, m.cor_materia, e.fk_Turma_Id_Turma, t.Nome_Turma FROM evento e LEFT JOIN materias m ON e.tipo = m.Nome_Materia LEFT JOIN turma t ON e.fk_Turma_Id_Turma = t.Id_Turma WHERE e.fk_Turma_Id_Turma = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $turma);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Professores e coordenadores veem todos os eventos
    $sql = "SELECT e.id, e.dia, e.mes, e.ano, e.nome, e.time_from, e.time_to, e.tipo, e.descricao, m.cor_materia, e.fk_Turma_Id_Turma, t.Nome_Turma FROM evento e LEFT JOIN materias m ON e.tipo = m.Nome_Materia LEFT JOIN turma t ON e.fk_Turma_Id_Turma = t.Id_Turma";
    $result = $conn->query($sql);
}

// Verifica se há resultados
$eventos = [];
if (isset($result) && $result->num_rows > 0) {
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
