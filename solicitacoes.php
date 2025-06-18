<?php
session_start();
// Verifique se é diretor
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'diretor') {
    header("Location: login.html");
    exit();
}
$conn = new mysqli("localhost", "root", "", "tccteste");
if ($conn->connect_error) die("Conexão falhou: " . $conn->connect_error);

if (isset($_GET['aprovar'])) {
    $id = intval($_GET['aprovar']);
    $res = $conn->query("SELECT tipo, id_usuario FROM solicitacoes WHERE id=$id");
    if ($row = $res->fetch_assoc()) {
        if ($row['tipo'] === 'professor') {
            $conn->query("UPDATE professores SET aprovado=1 WHERE Id_Prof={$row['id_usuario']}");
        } else {
            $conn->query("UPDATE coordenadores SET aprovado=1 WHERE Id_Coord={$row['id_usuario']}");
        }
        $conn->query("UPDATE solicitacoes SET status='aprovado' WHERE id=$id");
    }
    header("Location: solicitacoes.php");
    exit();
}
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

// Busca todas as aprovações pendentes com todos os dados do usuário
$res = $conn->query("
    SELECT a.id, a.tipo, a.id_usuario, a.status,
        p.Nome_Prof, p.NomeSocial_Prof, p.Cpf_Prof, p.Cel_Prof, p.Email_Prof,
        c.Nome_Coord, c.NomeSocial_Coord, c.Cpf_Coord, c.Cel_Coord, c.Email_Coord
    FROM solicitacoes a
    LEFT JOIN professores p ON a.tipo='professor' AND a.id_usuario=p.Id_Prof
    LEFT JOIN coordenadores c ON a.tipo='coordenador' AND a.id_usuario=c.Id_Coord
    WHERE a.status='pendente'
");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Aprovações pendentes</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Aprovações pendentes</h1>
    <table border="1">
        <tr>
            <th>Tipo</th>
            <th>Nome</th>
            <th>Nome Social</th>
            <th>CPF</th>
            <th>Celular</th>
            <th>Email</th>
            <th>Ações</th>
        </tr>
        <?php while($row = $res->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['tipo']) ?></td>
                <td>
                    <?= $row['tipo']=='professor' ? htmlspecialchars($row['Nome_Prof']) : htmlspecialchars($row['Nome_Coord']) ?>
                </td>
                <td>
                    <?= $row['tipo']=='professor' ? htmlspecialchars($row['NomeSocial_Prof']) : htmlspecialchars($row['NomeSocial_Coord']) ?>
                </td>
                <td>
                    <?= $row['tipo']=='professor' ? htmlspecialchars($row['Cpf_Prof']) : htmlspecialchars($row['Cpf_Coord']) ?>
                </td>
                <td>
                    <?= $row['tipo']=='professor' ? htmlspecialchars($row['Cel_Prof']) : htmlspecialchars($row['Cel_Coord']) ?>
                </td>
                <td>
                    <?= $row['tipo']=='professor' ? htmlspecialchars($row['Email_Prof']) : htmlspecialchars($row['Email_Coord']) ?>
                </td>
                <td>
                    <a href="?aprovar=<?= $row['id'] ?>">Aprovar</a> | 
                    <a href="?negar=<?= $row['id'] ?>">Negar</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>