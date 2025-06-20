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
// Celular: mantém o + e remove todos os outros caracteres não numéricos
$celular = '+' . preg_replace('/[^\d]/', '', ltrim($_POST['celular'], '+'));
$email = trim($_POST['email']);
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

// Verifica se todos os campos com "required" foram preenchidos
if (empty($nome) || empty($cpf) || empty($celular) || empty($email) || empty($_POST['senha'])) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Erro!',
                text: 'Por favor, preencha todos os campos obrigatórios.',
                icon: 'error'
            }).then(function() {
                window.history.back();
            });
        });
    </script>";
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
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Erro!',
                text: 'CPF já cadastrado para outro usuário!',
                icon: 'error'
            }).then(function() {
                window.history.back();
            });
        });
    </script>";
    exit();
}

// Insere no banco
$stmt = $conn->prepare("INSERT INTO coordenadores (Nome_Coord, NomeSocial_Coord, Cpf_Coord, Cel_Coord, Email_Coord, Senha_Coord, aprovado) VALUES (?, ?, ?, ?, ?, ?, 0)");
$stmt->bind_param("ssssss", $nome, $nomeSocial, $cpf, $celular, $email, $senha);

if ($stmt->execute()) {
    $id_coord = $conn->insert_id;
    // Cria solicitação de aprovação
    $conn->query("INSERT INTO solicitacoes (tipo, id_usuario) VALUES ('coordenador', $id_coord)");
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Aguardando aprovação!',
                text: 'Seu cadastro será analisado pelo diretor.',
                icon: 'info'
            }).then(function() {
                window.location.href = 'index.html';
            });
        });
    </script>";
    exit();
}

// So pra ter certeza
/*var_dump([
    'nome' => $nome,
    'nomeSocial' => $nomeSocial,
    'cpf' => $cpf,
    'celular' => $celular,
    'email' => $email,
    'senha_hash' => $senha
]);*/

$conn->close();
?>
