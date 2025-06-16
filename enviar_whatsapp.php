<?php

require_once __DIR__ . '/config.php';
// Recebe: turma_id, mensagem, apikey (opcional)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Método não permitido']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$turma_id = intval($data['turma_id'] ?? 0);
$mensagem = $data['mensagem'] ?? '';
$apikey = $data['apikey'] ?? '';

if ($turma_id <= 0 || empty($mensagem)) {
    echo json_encode(['status' => 'error', 'message' => 'Dados obrigatórios ausentes.']);
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'tccteste');
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Erro de conexão.']);
    exit;
}

// Busca todos os alunos da turma, incluindo nome social
$sql = "SELECT Nome_Aluno, NomeSocial_Aluno, Cel_Aluno FROM alunos WHERE fk_Turma_Id_Turma = ? AND Cel_Aluno IS NOT NULL AND Cel_Aluno != ''";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $turma_id);
$stmt->execute();
$res = $stmt->get_result();
$alunos = [];
while ($row = $res->fetch_assoc()) {
    $alunos[] = $row;
}
$stmt->close();
$conn->close();

if (empty($alunos)) {
    echo json_encode(['status' => 'error', 'message' => 'Nenhum aluno com celular encontrado.']);
    exit;
}

$results = [];
foreach ($alunos as $aluno) {
    $telefone = preg_replace('/\D/', '', $aluno['Cel_Aluno']);
    if (strlen($telefone) < 11) continue;
    // Prioriza nome social se não for vazio ou só espaços
    $nome = isset($aluno['NomeSocial_Aluno']) && trim($aluno['NomeSocial_Aluno']) !== '' ? trim($aluno['NomeSocial_Aluno']) : $aluno['Nome_Aluno'];
    // Personaliza a mensagem para cada aluno
    $mensagemPersonalizada = str_replace('{nome}', $nome, $mensagem);
    $url = "https://api.callmebot.com/whatsapp.php?phone={$telefone}&text=" . urlencode($mensagemPersonalizada);
    if ($apikey) $url .= "&apikey=" . urlencode($apikey);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $results[] = [
        'aluno' => $nome,
        'telefone' => $telefone,
        'status' => $httpcode,
        'resposta' => $response
    ];
    usleep(1500000); // 1.5s
}
// Debug: logar o conteúdo recebido para análise
file_put_contents(__DIR__ . '/whatsapp_debug.log', date('Y-m-d H:i:s') . ' | DATA: ' . json_encode($data) . "\n", FILE_APPEND);
echo json_encode(['status' => 'ok', 'results' => $results]);