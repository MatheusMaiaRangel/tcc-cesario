<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "tccteste";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

// Verifica se houve erro na conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Recebe os dados do JSON
$data = json_decode(file_get_contents("php://input"), true);

$event_name = $data['event_nome'] ?? '';
$event_time_from = $data['event_time_from'] ?? '';
$event_time_to = $data['event_time_to'] ?? '';
$event_description = $data['event_description'] ?? '';
$event_type = $data['event_type'] ?? '';
$event_day = intval($data['event_day'] ?? 0);
$event_month = intval($data['event_month'] ?? 0);
$event_year = intval($data['event_year'] ?? 0);

if (
    empty($event_name) ||
    empty($event_time_from) ||
    empty($event_time_to) ||
    empty($event_description) ||
    empty($event_type) ||
    $event_day <= 0 ||
    $event_month <= 0 ||
    $event_year <= 0
) {
    echo json_encode(["status" => "error", "message" => "Preencha todos os campos obrigatórios."]);
    exit;
}

// Verifica se um evento exatamente igual já existe
$checkStmt = $conn->prepare("SELECT id FROM evento WHERE nome = ? AND time_from = ? AND time_to = ? AND descricao = ? AND tipo = ? AND dia = ? AND mes = ? AND ano = ?");
$checkStmt->bind_param("sssssiii", $event_name, $event_time_from, $event_time_to, $event_description, $event_type, $event_day, $event_month, $event_year);
$checkStmt->execute();
$result = $checkStmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "O evento já foi salvo."]);
} else {
    // Insere o evento
    $stmt = $conn->prepare("INSERT INTO evento (nome, time_from, time_to, descricao, tipo, dia, mes, ano) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssiii", $event_name, $event_time_from, $event_time_to, $event_description, $event_type, $event_day, $event_month, $event_year);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Evento salvo com sucesso."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Erro ao salvar evento: " . $stmt->error]);
    }

    $stmt->close();
}

$checkStmt->close();
$conn->close();
?>


