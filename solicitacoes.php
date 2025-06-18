<?php
session_start();
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'diretor') {
    header("Location: login.html");
    exit();
}
$conn = new mysqli("localhost", "root", "", "tccteste");
if ($conn->connect_error) die("Conexão falhou: " . $conn->connect_error);

// Aprovar
if (isset($_GET['aprovar'])) {
    $id = intval($_GET['aprovar']);
    $res = $conn->query("SELECT tipo, id_usuario FROM solicitacoes WHERE id=$id");
    if ($row = $res->fetch_assoc()) {
        if ($row['tipo'] === 'professor') {
            $conn->query("UPDATE professores SET aprovado=1 WHERE Id_Prof={$row['id_usuario']}");
        } elseif ($row['tipo'] === 'coordenador') {
            $conn->query("UPDATE coordenadores SET aprovado=1 WHERE Id_Coord={$row['id_usuario']}");
        } elseif ($row['tipo'] === 'diretor') {
            $conn->query("UPDATE diretores SET aprovado=1 WHERE Id_Diretor={$row['id_usuario']}");
        }
        $conn->query("UPDATE solicitacoes SET status='aprovado' WHERE id=$id");
    }
    header("Location: solicitacoes.php");
    exit();
}

// Negar
if (isset($_GET['negar'])) {
    $id = intval($_GET['negar']);
    $res = $conn->query("SELECT tipo, id_usuario FROM solicitacoes WHERE id=$id");
    if ($row = $res->fetch_assoc()) {
        if ($row['tipo'] === 'professor') {
            $conn->query("DELETE FROM professores WHERE Id_Prof={$row['id_usuario']}");
        } else {
            $conn->query("DELETE FROM coordenadores WHERE Id_Coord={$row['id_usuario']}");
        }
        $conn->query("UPDATE solicitacoes SET status='negado' WHERE id=$id");
    }
    header("Location: solicitacoes.php");
    exit();
}

// Buscar pendentes
$res = $conn->query("
    SELECT a.id, a.tipo, a.id_usuario, a.status,
        p.Nome_Prof, p.NomeSocial_Prof, p.Cpf_Prof, p.Cel_Prof, p.Email_Prof,
        c.Nome_Coord, c.NomeSocial_Coord, c.Cpf_Coord, c.Cel_Coord, c.Email_Coord,
        d.Nome_Diretor, d.NomeSocial_Diretor, d.Cpf_Diretor, d.Cel_Diretor, d.Email_Diretor
    FROM solicitacoes a
    LEFT JOIN professores p ON a.tipo='professor' AND a.id_usuario=p.Id_Prof
    LEFT JOIN coordenadores c ON a.tipo='coordenador' AND a.id_usuario=c.Id_Coord
    LEFT JOIN diretores d ON a.tipo='diretor' AND a.id_usuario=d.Id_Diretor
    WHERE a.status='pendente'
");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Solicitações Pendentes</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>
  <!-- Navbar -->
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

  <div class="container bg-white shadow-lg rounded p-4 mb-5" style="margin-top: 80px;">
    <h2 class="text-center titulo-azul mb-4">Solicitações Pendentes</h2>
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark text-center">
          <tr>
            <th>Tipo</th>
            <th>Nome</th>
            <th>Nome Social</th>
            <th>CPF</th>
            <th>Celular</th>
            <th>Email</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
        <?php if ($res->num_rows > 0): ?>
            <?php while ($row = $res->fetch_assoc()): ?>
              <tr>
                <td class="text-center"><?= htmlspecialchars($row['tipo']) ?></td>
                <td><?= $row['tipo'] == 'professor' ? htmlspecialchars($row['Nome_Prof']) : ($row['tipo'] == 'coordenador' ? htmlspecialchars($row['Nome_Coord']) : htmlspecialchars($row['Nome_Diretor'])) ?></td>
                <td><?= $row['tipo'] == 'professor' ? htmlspecialchars($row['NomeSocial_Prof']) : ($row['tipo'] == 'coordenador' ? htmlspecialchars($row['NomeSocial_Coord']) : htmlspecialchars($row['NomeSocial_Diretor'])) ?></td>
                <td><?= $row['tipo'] == 'professor' ? htmlspecialchars($row['Cpf_Prof']) : ($row['tipo'] == 'coordenador' ? htmlspecialchars($row['Cpf_Coord']) : htmlspecialchars($row['Cpf_Diretor'])) ?></td>
                <td><?= $row['tipo'] == 'professor' ? htmlspecialchars($row['Cel_Prof']) : ($row['tipo'] == 'coordenador' ? htmlspecialchars($row['Cel_Coord']) : htmlspecialchars($row['Cel_Diretor'])) ?></td>
                <td><?= $row['tipo'] == 'professor' ? htmlspecialchars($row['Email_Prof']) : ($row['tipo'] == 'coordenador' ? htmlspecialchars($row['Email_Coord']) : htmlspecialchars($row['Email_Diretor'])) ?></td>
                <td class="text-center">
                  <a href="?aprovar=<?= $row['id'] ?>" class="btn btn-success btn-sm"><i class="fas fa-check"></i> Aprovar</a>
                  <a href="?negar=<?= $row['id'] ?>" class="btn btn-danger btn-sm"><i class="fas fa-times"></i> Negar</a>
                </td>
              </tr>
            <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="7" class="text-center text-muted">Nenhuma solicitação pendente.</td>
          </tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
