<?php
session_start();

$nome = trim($_POST['nome']);
$nomeSocial = trim($_POST['nomeSocial']);
$cpf = preg_replace('/\D/', '', $_POST['cpf']);
$celular = preg_replace('/\D/', '', $_POST['celular']);
$email = trim($_POST['email']);
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

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

$conn = new mysqli("localhost", "root", "", "tccteste");
if ($conn->connect_error) die("Conexão falhou: " . $conn->connect_error);

// Verificação de CPF em todas as tabelas
$cpfExistente = false;
$tabelas = [
    ['alunos', 'Cpf_Aluno'],
    ['professores', 'Cpf_Prof'],
    ['coordenadores', 'Cpf_Coord']
];

foreach ($tabelas as [$tabela, $coluna]) {
    $stmt = $conn->prepare("SELECT 1 FROM $tabela WHERE $coluna = ?");
    $stmt->bind_param("s", $cpf);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) $cpfExistente = true;
    $stmt->close();
}

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

// Salva os dados na sessão
$_SESSION['cadastro_aluno'] = [
    'nome' => $nome,
    'nomeSocial' => $nomeSocial,
    'cpf' => $cpf,
    'celular' => $celular,
    'email' => $email,
    'senha' => $senha
];

header("Location: verifica_serie_.php");
exit();
?>
