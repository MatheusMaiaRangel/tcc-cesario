<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] !== 'coordenador') {
  header("Location: login.html");
  exit();
}

$conn = new mysqli("localhost", "root", "", "tccteste");
if ($conn->connect_error)
  die("Conexão falhou: " . $conn->connect_error);

$coordenador_id = $_SESSION['id'];

// Busca apenas matérias do coordenador logado
$materias = $conn->query("SELECT * FROM materias WHERE fk_Coordenadores_Id_Coord = $coordenador_id");

// Adicionar matéria
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nova_materia'])) {
  $nome = trim($_POST['nome_materia']);
  $cor = isset($_POST['cor_materia']) ? $_POST['cor_materia'] : '#FFFFFF';
  if ($nome) {
    $stmt = $conn->prepare("INSERT INTO materias (Nome_Materia, fk_Coordenadores_Id_Coord, cor_materia) VALUES (?, ?, ?)");
    if (!$stmt) {
      die("Erro ao preparar statement: " . $conn->error);
    }
    $stmt->bind_param("sis", $nome, $coordenador_id, $cor);
    if (!$stmt->execute()) {
      die("Erro ao inserir matéria: " . $stmt->error);
    }
    header("Location: gerenciar_materias.php");
    exit();
  }
}

// Remover matéria
if (isset($_GET['remover']) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
  $id = intval($_GET['remover']);
  $result = $conn->query("DELETE FROM materias WHERE Id_Materia = $id AND fk_Coordenadores_Id_Coord = $coordenador_id");
  if ($result) {
    echo json_encode(['success' => true]);
  } else {
    echo json_encode(['success' => false]);
  }
  exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <title>Gerenciar matérias</title>

  <!--links de estilos-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="css/style_calendar.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
    integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
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
        </ul>
        <form class="d-flex" method="post" action="logout.php">
          <button class="btn-logout" type="submit">Sair</button>
        </form>
      </div>
    </div>
  </nav>

  <div class="container bg-white shadow-lg rounded p-4 mb-5 d-flex flex-column align-items-center"
    style="margin-top:80px; margin-left:auto; margin-right:auto;">
    <h2 class="text-center mb-4 titulo-azul w-100">Lista de matérias</h2>

    <!-- Botão para abrir o modal de nova matéria -->
    <div class="text-end mb-3 w-100">
      <img src="img/notebook.png" style="cursor:pointer; width:50px; height:auto;" data-bs-toggle="modal"
        data-bs-target="#modalNovaMateria" alt="Nova Matéria">
    </div>

    <div class="table-responsive w-100">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark text-center">
          <tr>
            <th>Cor</th>
            <th>Nome da matéria</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($materias->num_rows > 0): ?>
            <?php while ($m = $materias->fetch_assoc()): ?>
              <tr>
                <td class="text-center"><span style="display:inline-block;width:24px;height:24px;border-radius:50%;background:<?= htmlspecialchars($m['cor_materia']) ?>;border:1px solid #ccc;"></span></td>
                <td><?= htmlspecialchars($m['Nome_Materia']) ?></td>
                <td class="text-center">
                  <a href="?remover=<?= $m['Id_Materia'] ?>" class="btn btn-sm btn-danger btn-remover-materia" data-nome="<?= htmlspecialchars($m['Nome_Materia']) ?>">Remover</a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="3" class="text-center text-muted">Nenhuma matéria cadastrada.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>


  <!-- Modal Nova Matéria -->
  <div class="modal fade" id="modalNovaMateria" tabindex="-1" aria-labelledby="modalNovaMateriaLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <form method="post" class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalNovaMateriaLabel">Nova matéria</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="nome_materia" class="form-label">Nome da matéria</label>
            <input type="text" class="form-control" id="nome_materia" name="nome_materia" required>
          </div>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="cor_materia">Cor da matéria:</label>
            <input type="color" class="form-control form-control-color cor_materia" name="cor_materia" id="event_color"
              value="#FFFFFF" title="Escolha a cor da bolinha">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-cinza" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" name="nova_materia" class="btn btn-success">Adicionar</button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('.btn-remover-materia').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
          e.preventDefault();
          const url = this.getAttribute('href');
          const nome = this.getAttribute('data-nome');
          const row = btn.closest('tr');
          Swal.fire({
            title: `Remover matéria?`,
            text: `Deseja realmente remover a matéria "${nome}"? Essa ação não poderá ser desfeita!`,
            icon: 'warning',
            showDenyButton: true,   
            confirmButtonText: 'Remover',
            denyButtonText: 'Cancelar',
            confirmButtonColor: '#d33',
            denyButtonColor: '#666',
            reverseButtons: true // <-- faz o botão cancelar ficar à esquerda
          }).then((result) => {
            if (result.isConfirmed) {
              fetch(url, { method: 'GET', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(response => response.json())
                .then(data => {
                  if (data.success) {
                    row.remove();
                    Swal.fire('Removida!', 'A matéria foi removida com sucesso.', 'success');
                  } else {
                    Swal.fire('Erro!', 'Não foi possível remover a matéria.', 'error');
                  }
                });
            } else if (result.isDenied) {
              Swal.fire('A matéria não foi removida.', '', 'info');
            }
          });
        });
      });
    });
  </script>
</body>

</html>