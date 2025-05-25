<?php
session_start();

if (!isset($_SESSION['cadastro_aluno'])) {
    echo "<script>alert('Dados do aluno não encontrados.'); window.location.href='cadastro.html';</script>";
    exit();
}

$dados = $_SESSION['cadastro_aluno'];

$conn = new mysqli("localhost", "root", "", "tccteste");
if ($conn->connect_error) die("Conexão falhou: " . $conn->connect_error);

$stmt = $conn->prepare("INSERT INTO alunos (Nome_Aluno, NomeSocial_Aluno, Cpf_Aluno, Cel_Aluno, Email_Aluno, Senha_Aluno, fk_Turma_Id_Turma) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssi", $dados['nome'], $dados['nomeSocial'], $dados['cpf'], $dados['celular'], $dados['email'], $dados['senha'], $dados['turma_id']);

if ($stmt->execute()) {
    unset($_SESSION['cadastro_aluno']); // limpa a sessão após cadastro
    echo "<script>alert('Cadastro realizado com sucesso!'); window.location.href='index.html';</script>";
} else {
    echo "<script>alert('Erro ao cadastrar aluno: " . $stmt->error . "'); window.history.back();</script>";
}
?>
