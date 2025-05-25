<?php
$conn = new mysqli("localhost", "root", "", "tccteste");

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

$tipo = $_POST['tipo'];
$id = $_POST['id'];
$senha = $_POST['senha'];
$confirma = $_POST['confirma'];

if ($senha !== $confirma) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Erro!',
                text: 'As senhas não são iguais.',
                icon: 'error'
            }).then(function() {
                window.history.back();
            });
        });
    </script>";
    exit();
}

$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

switch ($tipo) {
    case "aluno":
        $sql = "UPDATE alunos SET Senha_Aluno = ? WHERE Id_Aluno = ?";
        break;
    case "professor":
        $sql = "UPDATE professores SET Senha_Prof = ? WHERE Id_Prof = ?";
        break;
    case "coordenador":
        $sql = "UPDATE coordenadores SET Senha_Coord = ? WHERE Id_Coord = ?";
        break;
    default:
        echo "<script>alert('Tipo de usuário inválido.'); window.location.href='localizacpf.html';</script>";
        exit();
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $senhaHash, $id);

if ($stmt->execute()) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Sucesso!',
                text: 'Senha alterada com sucesso!',
                icon: 'success'
            }).then(function() {
                window.location.href = 'index.html';
            });
        });
    </script>";
} else {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Erro!',
                text: 'Erro ao alterar a senha.',
                icon: 'error'
            }).then(function() {
                window.history.back();
            });
        });
    </script>";
}

$conn->close();
?>
