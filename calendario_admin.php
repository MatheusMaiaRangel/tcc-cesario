<?php
// Inicia a sessão
session_start();
// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
  // Redireciona para a página de login se não estiver logado
  header("Location: login.html");
  exit();
}
?>
<!doctype html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1.0">
  <meta name="keywords" content="calendar, events, reminders, javascript, html, css, open source coding">

  <title>Bem vindo professor</title>

  <!--links de estilos-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="css/style_calendar.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
    integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="main">
  <!--navbar-->
  <nav class="navbar fixed-top navbar-expand-lg background_blue">
    <div class="container-fluid">
      <a class="navbar-brand text-white" href="calendario_admin.php">Oblivion</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item"><a class="nav-link text-white" href="gerenciar_materias.php">Matérias</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="editar_perfil.php">Meu perfil</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="solicitacoes.php">Solicitações</a></li>
        </ul>
        <form class="d-flex" method="post" action="logout.php">
          <button class="btn-logout" type="submit">Sair</button>
        </form>
      </div>
    </div>
  </nav>

  <!--container-->
  <div class="container">
    <div class="left">
      <div class="calendar">
        <div class="month">
          <i class="fas fa-angle-left prev"></i>
          <div class="date"></div>
          <i class="fas fa-angle-right next"></i>
        </div>
        <div class="weekdays">
          <div>Dom</div>
          <div>Seg</div>
          <div>Ter</div>
          <div>Qua</div>
          <div>Qui</div>
          <div>Sex</div>
          <div>Sáb</div>
        </div>
        <div class="days"></div>


        <div class="goto-today">
          <div class="goto">
            <input type="text" placeholder="mm/aaaa" class="date-input" />
            <button class="goto-btn">Ir</button>
          </div>
          <button class="today-btn">Hoje</button>
        </div>
      </div>
    </div>

    <!--consulta do calendario-->
    <div class="right">
      <div class="today-date">
        <div class="event-day"></div>
        <div class="event-date"></div>
      </div>
      <div class="events"></div>
    </div>

    <!--adiciona eventos-->
    <button type="button" class="add-event" data-bs-toggle="modal" data-bs-target="#exampleModal">
      <i class="fas fa-plus"></i>
    </button>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <div class="add-event-header">
              <div class="title">Adicionar evento</div>
              <i type="button" class="fas fa-times close" data-bs-dismiss="modal"></i>
            </div>
          </div>
          <form id="add-event-form">
            <div class="add-event-body">
              <div class="add-event-input">
                <input type="text" placeholder="Nome do evento:" class="event-name" name="event_nome" required>
              </div>
              <div class="add-event-input">
                <input type="text" placeholder="Hora do evento de:" class="event-time-from" name="event_time_from"
                  required>
              </div>
              <div class="add-event-input">
                <input type="text" placeholder="Hora do evento até:" class="event-time-to" name="event_time_to"
                  required>
              </div>
              <div class="add-event-input">
                <input type="text" placeholder="Descrição do evento:" class="event-description" name="event_description"
                  required>
              </div>
              <?php
              // Busca matérias do banco
              $conn = new mysqli("localhost", "root", "", "tccteste");
              $materias = [];
              $turmas = [];
              $predios = [];
              if (!$conn->connect_error) {
                $res = $conn->query("SELECT Nome_Materia, cor_materia FROM materias");
                while ($row = $res->fetch_assoc()) {
                  $materias[] = $row;
                }
                // Busca prédios
                $resPredio = $conn->query("SELECT id, nome FROM predio");
                while ($rowPredio = $resPredio->fetch_assoc()) {
                  $predios[] = $rowPredio;
                }
                // Busca turmas
                $res2 = $conn->query("SELECT Id_Turma, Nome_Turma, id_predio FROM turma");
                while ($row2 = $res2->fetch_assoc()) {
                  $turmas[] = $row2;
                }
                $conn->close();
              }
              ?>
              <!-- Seleção de matéria -->
              <select class="form-select event-type" aria-label="Default select example" name="event_type" required>
                <option value="">Selecione uma matéria</option>
                <?php foreach ($materias as $mat): ?>
                  <option value="<?= htmlspecialchars($mat['Nome_Materia']) ?>"
                    data-color="<?= htmlspecialchars($mat['cor_materia']) ?>">
                    <span style="color:<?= htmlspecialchars($mat['cor_materia']) ?>;">&#9679;</span>
                    <?= htmlspecialchars($mat['Nome_Materia']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <!-- Seleção de prédio -->
              <select class="form-select event-predio" aria-label="Selecione o prédio" name="event_predio">
                <option value="">Selecione o prédio (opcional)</option>
                <?php foreach ($predios as $predio): ?>
                  <option value="<?= $predio['id'] ?>"><?= htmlspecialchars($predio['nome']) ?></option>
                <?php endforeach; ?>
              </select>
              <!-- Seleção de turma -->
              <select class="form-select event-turma" aria-label="Selecione a turma" name="event_turma" required>
                <option value="">Selecione a turma</option>
                <option value="ALL_BY_BUILDING" style="font-weight:bold;">Todas as turmas do prédio selecionado</option>
                <?php foreach ($turmas as $turma): ?>
                  <option value="<?= $turma['Id_Turma'] ?>" data-predio="<?= $turma['id_predio'] ?>">
                    <?= htmlspecialchars($turma['Nome_Turma']) ?></option>
                <?php endforeach; ?>
              </select>
              <input type="hidden" name="event_day" id="event-day-input">
              <input type="hidden" name="event_month" id="event-month-input">
              <input type="hidden" name="event_year" id="event-year-input">
              <!--botão de adicionar eventos-->
              <div class="add-event-footer">
                <button type="submit" class="add-event-btn">Adicionar evento</button>
              </div>
            </div>
          </form>

        </div><!--modal end-->

      </div><!--close container-->





      <script src="javascript/scriptv2.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>