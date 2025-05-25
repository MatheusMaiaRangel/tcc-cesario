<?php
session_start();

$conn = new mysqli("localhost", "root", "", "tccteste");
if ($conn->connect_error) die("Conexão falhou: " . $conn->connect_error);

$serie = $_POST['serie'];
$curso = $_POST['curso'];

if (empty($serie) || empty($curso)) {
    echo "<script>alert('Por favor, selecione a série e o curso.'); window.history.back();</script>";
    exit();
}

// Mapeamento do nome para ID real (substituir por IDs corretos se necessário)
$mapaSerie = ['primeiro' => 1, 'segundo' => 2, 'terceiro' => 3];
$mapaCurso = ['infonet' => 1, 'adm' => 2];

$idSerie = $mapaSerie[$serie] ?? null;
$idCurso = $mapaCurso[$curso] ?? null;

if (!$idSerie || !$idCurso) {
    echo "<script>alert('Série ou curso inválido.'); window.history.back();</script>";
    exit();
}

$sql = "SELECT Id_Turma FROM turma WHERE fk_Serie_Id_Serie = ? AND fk_Cursos_Id_Curso = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $idSerie, $idCurso);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $_SESSION['cadastro_aluno']['turma_id'] = $row['Id_Turma'];
        header("Location: finaliza_cadastro_aluno.php");
        exit();
    } else {
        echo "<script>alert('Turma não encontrada.'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Erro na preparação da consulta.'); window.history.back();</script>";
}
?>
    