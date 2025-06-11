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
        <option value="terceiro">4° Ano</option>
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
      <div class="btn-container">
      <button type="button" class="btn-cinza" onclick="window.location.href='cadastro.html'">Voltar</button>
      <button type="submit">Enviar</button>
      </div>
    </form>
  </div>

</body>
</html>
