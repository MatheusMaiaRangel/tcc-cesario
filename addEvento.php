<?php
require_once __DIR__ . '/config.php';
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
$event_turma = $data['event_turma'] ?? 0;

// Permite múltiplas turmas (array ou valor único)
$turmas = is_array($event_turma) ? $event_turma : [$event_turma];

if (
    empty($event_name) ||
    empty($event_time_from) ||
    empty($event_time_to) ||
    empty($event_description) ||
    empty($event_type) ||
    $event_day <= 0 ||
    $event_month <= 0 ||
    $event_year <= 0 ||
    empty($turmas) || (count($turmas) === 1 && ($turmas[0] === 0 || $turmas[0] === ''))
) {
    echo json_encode(["status" => "error", "message" => "Preencha todos os campos obrigatórios."]);
    exit;
}

$sucesso = 0;
$falha = 0;
foreach ($turmas as $turmaId) {
    $turmaId = intval($turmaId);
    if ($turmaId <= 0) continue;
    // Verifica se um evento exatamente igual já existe
    $checkStmt = $conn->prepare("SELECT id FROM evento WHERE nome = ? AND time_from = ? AND time_to = ? AND descricao = ? AND tipo = ? AND dia = ? AND mes = ? AND ano = ? AND fk_Turma_Id_Turma = ?");
    $checkStmt->bind_param("ssssssiii", $event_name, $event_time_from, $event_time_to, $event_description, $event_type, $event_day, $event_month, $event_year, $turmaId);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    if ($result->num_rows > 0) {
        $falha++;
        $checkStmt->close();
        continue;
    }
    $checkStmt->close();
    // Insere o evento
    $stmt = $conn->prepare("INSERT INTO evento (nome, time_from, time_to, descricao, tipo, dia, mes, ano, fk_Turma_Id_Turma) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssiii", $event_name, $event_time_from, $event_time_to, $event_description, $event_type, $event_day, $event_month, $event_year, $turmaId);
    if ($stmt->execute()) {
        $sucesso++;
        // Envio automático de WhatsApp para eventos URGENTES
        if (strtolower($event_type) === 'urgente') {
            // Buscar todos os alunos da turma
            $conn2 = new mysqli($servername, $username, $password, $dbname);
            $sqlAlunos = "SELECT Cel_Aluno FROM alunos WHERE fk_Turma_Id_Turma = ? AND Cel_Aluno IS NOT NULL AND Cel_Aluno != ''";
            $stmtAlunos = $conn2->prepare($sqlAlunos);
            $stmtAlunos->bind_param('i', $turmaId);
            $stmtAlunos->execute();
            $resAlunos = $stmtAlunos->get_result();
            while ($rowAluno = $resAlunos->fetch_assoc()) {
                $telefone = preg_replace('/\D/', '', $rowAluno['Cel_Aluno']);
                if (strlen($telefone) < 11) continue;
                $mensagem = "ATENÇÃO: Novo evento urgente cadastrado para sua turma. Acesse o sistema para mais detalhes.
https://github.com/MatheusMaiaRangel/tcc-cesario";
                $url = "https://api.callmebot.com/whatsapp.php?phone={$telefone}&text=" . urlencode($mensagem) . "&apikey=" . urlencode($CALLMEBOT_APIKEY);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
                $response = curl_exec($ch);
                curl_close($ch);
                // Opcional: salvar log
            }
            $stmtAlunos->close();
            $conn2->close();
        }
    } else {
        $falha++;
    }
    $stmt->close();
}
$conn->close();
if ($sucesso > 0 && $falha === 0) {
    echo json_encode(["status" => "success", "message" => "Evento salvo com sucesso para $sucesso turma(s)."]);
} elseif ($sucesso > 0) {
    echo json_encode(["status" => "success", "message" => "Evento salvo para $sucesso turma(s), mas $falha já existiam ou falharam."]);
} else {
    echo json_encode(["status" => "error", "message" => "Nenhum evento salvo. Todos já existiam ou falharam."]);
}
?>


