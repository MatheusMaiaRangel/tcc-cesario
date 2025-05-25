<?php
// Conectar ao banco
$conn = new mysqli("localhost", "root", "", "tccteste");

// Checa conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$cpf = preg_replace('/\D/', '', $_POST['cpf']); // remove máscara

$usuario = null;
$tipo = null;

// Checar nas 3 tabelas
// Tabela de aluno
$sql_aluno = "SELECT Id_Aluno FROM alunos WHERE Cpf_Aluno = ?";
$stmt = $conn->prepare($sql_aluno);
$stmt->bind_param("s", $cpf);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();
    $tipo = "aluno";
}

// Tabela de professor
if (!$usuario) {
    $sql_prof = "SELECT Id_Prof FROM professores WHERE Cpf_Prof = ?";
    $stmt = $conn->prepare($sql_prof);
    $stmt->bind_param("s", $cpf);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        $tipo = "professor";
    }
}

// Tabela de coordenador
if (!$usuario) {
    $sql_coord = "SELECT Id_Coord FROM coordenadores WHERE Cpf_Coord = ?";
    $stmt = $conn->prepare($sql_coord);
    $stmt->bind_param("s", $cpf);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        $tipo = "coordenador";
    }
}

if ($usuario && $tipo) {
    // Redireciona para a página de alterar senha com id e tipo
    header("Location: alterar_senha.html?tipo=$tipo&id=" . reset($usuario));
    exit();
} else {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Erro!',
                text: 'CPF não encontrado.',
                icon: 'error'
            }).then(function() {
                window.location.href = 'localizacpf.html';
            });
        });
    </script>";
}

$conn->close();
?>
