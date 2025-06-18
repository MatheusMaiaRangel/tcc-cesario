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
$cpf = preg_replace('/\D/', '', $_POST['cpf']);
$celular = '+' . preg_replace('/[^\d]/', '', ltrim($_POST['celular'], '+'));
$email = trim($_POST['email']);
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

// Verifica se todos os campos obrigatórios foram preenchidos
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
$tabelas = [
    ['alunos', 'Cpf_Aluno'],
    ['professores', 'Cpf_Prof'],
    ['coordenadores', 'Cpf_Coord'],
    ['diretores', 'Cpf_Diretor']
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
                text: 'CPF já cadastrado.',
                icon: 'error'
            }).then(function() {
                window.history.back();
            });
        });
    </script>";
    exit();
}

// Insere o diretor com aprovado=0
$stmt = $conn->prepare("INSERT INTO diretores (Nome_Diretor, NomeSocial_Diretor, Cpf_Diretor, Cel_Diretor, Email_Diretor, Senha_Diretor, aprovado) VALUES (?, ?, ?, ?, ?, ?, 0)");
$stmt->bind_param("ssssss", $nome, $nomeSocial, $cpf, $celular, $email, $senha);

if ($stmt->execute()) {
    $id_diretor = $conn->insert_id;
    // Cria solicitação de aprovação
    $solicitacao = $conn->prepare("INSERT INTO solicitacoes (tipo, id_usuario) VALUES ('diretor', ?)");
    $solicitacao->bind_param("i", $id_diretor);
    $solicitacao->execute();
    $solicitacao->close();

    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Aguardando aprovação!',
                text: 'Seu cadastro será analisado por um diretor.',
                icon: 'info'
            }).then(function() {
                window.location.href = 'login.html';
            });
        });
    </script>";
    exit();
} else {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Erro!',
                text: 'Erro ao cadastrar diretor: " . addslashes($stmt->error) . "',
                icon: 'error'
            }).then(function() {
                window.history.back();
            });
        });
    </script>";
    exit();
}
$stmt->close();
$conn->close();
?>
