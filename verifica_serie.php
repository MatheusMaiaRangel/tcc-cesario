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


$mapaSerie = ['primeiro' => 1, 'segundo' => 2, 'terceiro' => 3, 'quarto' => 4];
$idSerie = $mapaSerie[$serie] ?? null;
$idCurso = is_numeric($curso) ? intval($curso) : null;

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

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Selecione a sua turma</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  
  <div class="container-login">
    <img src="img/Vector.png" alt="Logo" class="logo">
    
    <h1>Selecione sua série e curso</h1>
    
    <form id="turma" action="verifica_serie.php" method="POST">
    <label for="serie">Selecione sua série:</label>
      <select id="serie" name="serie" required>
        <option value="">Selecione</option>
        <option value="primeiro">1° Ano</option>
        <option value="segundo">2° Ano</option>
        <option value="terceiro">3° Ano</option>
        <option value="quarto">4° Ano</option>
      </select>
      
      <label for="curso">Seu curso:</label>
      <?php
      $conn = new mysqli("localhost", "root", "", "tccteste");
      if ($conn->connect_error) {
        echo '<select id="curso" name="curso" required><option value="">Erro ao carregar cursos</option></select>';
      } else {
        $resCursos = $conn->query("SELECT Id_Curso, Nome_Curso FROM cursos");
        echo '<select id="curso" name="curso" required>';
        echo '<option value="">Selecione</option>';
        while ($curso = $resCursos->fetch_assoc()) {
          echo '<option value="' . htmlspecialchars($curso['Id_Curso']) . '">' . htmlspecialchars($curso['Nome_Curso']) . '</option>';
        }
        echo '</select>';
        $conn->close();
      }
      ?>
      <button type="submit">Enviar</button>
      <a href ="cadastro.html">
      <img src="img/left-arrow (1).png" alt="Voltar" class="back-button"></a>
    </form>
  </div>

</body>
</html>
