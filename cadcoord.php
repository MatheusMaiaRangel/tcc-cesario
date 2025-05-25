<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tccteste";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Conexão falhou: " . $conn->connect_error);

// Recebe e trata os dados
$nome = trim($_POST['nome']);
$nomeSocial = trim($_POST['nomeSocial']);
$cpf = preg_replace('/\D/', '', $_POST['cpf']); // remove máscara
$celular = preg_replace('/\D/', '', $_POST['celular']); // remove máscara
$email = trim($_POST['email']);
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

// Verifica se todos os campos com "required" foram preenchidos
if (empty($nome) || empty($cpf) || empty($celular) || empty($email) || empty($_POST['senha'])) {
    echo "<script>alert('Por favor, preencha todos os campos obrigatórios.'); window.history.back();</script>";
    exit();
}

// Verifica se o CPF já existe em qualquer tabela
$cpfExistente = false;

// Verifica em alunos
$verificaAluno = $conn->prepare("SELECT 1 FROM alunos WHERE Cpf_Aluno = ?");
$verificaAluno->bind_param("s", $cpf);
$verificaAluno->execute();
$verificaAluno->store_result();
if ($verificaAluno->num_rows > 0) $cpfExistente = true;

// Verifica em professores
$verificaProf = $conn->prepare("SELECT 1 FROM professores WHERE Cpf_Prof = ?");
$verificaProf->bind_param("s", $cpf);
$verificaProf->execute();
$verificaProf->store_result();
if ($verificaProf->num_rows > 0) $cpfExistente = true;

// Verifica em coordenadores
$verificaCoord = $conn->prepare("SELECT 1 FROM coordenadores WHERE Cpf_Coord = ?");
$verificaCoord->bind_param("s", $cpf);
$verificaCoord->execute();
$verificaCoord->store_result();
if ($verificaCoord->num_rows > 0) $cpfExistente = true;

if ($cpfExistente) {
    echo "<script>alert('CPF já cadastrado para outro usuário!'); window.history.back();</script>";
    exit();
}

// Insere no banco
$stmt = $conn->prepare("INSERT INTO coordenadores (Nome_Coord, NomeSocial_Coord, Cpf_Coord, Cel_Coord, Email_Coord, Senha_Coord) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $nome, $nomeSocial, $cpf, $celular, $email, $senha);

if ($stmt->execute()) {
    echo "<script>alert('Coordenador cadastrado com sucesso!'); window.location.href='index.html';</script>";
} else {
    echo "<script>alert('Erro ao cadastrar coordenador: " . $stmt->error . "'); window.history.back();</script>";
}

// So pra ter certeza
var_dump([
    'nome' => $nome,
    'nomeSocial' => $nomeSocial,
    'cpf' => $cpf,
    'celular' => $celular,
    'email' => $email,
    'senha_hash' => $senha
]);

$conn->close();
?>
