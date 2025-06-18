<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.html');
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'tccteste');
if ($conn->connect_error)
    die('Conexão falhou: ' . $conn->connect_error);

$tipo = $_SESSION['tipo'];
$id = $_SESSION['id'];

// Atualiza dados se enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $nomeSocial = trim($_POST['nomeSocial']);
    // Mantém o "+" e remove todos os outros caracteres não numéricos
    $celular = '+' . preg_replace('/[^\d]/', '', ltrim($_POST['celular'], '+'));
    $email = trim($_POST['email']);
    $turma = isset($_POST['turma']) ? intval($_POST['turma']) : null;
    if ($tipo === 'aluno') {
        $stmt = $conn->prepare('UPDATE alunos SET Nome_Aluno=?, NomeSocial_Aluno=?, Cel_Aluno=?, Email_Aluno=?, fk_Turma_Id_Turma=? WHERE Id_Aluno=?');
        $stmt->bind_param('ssssii', $nome, $nomeSocial, $celular, $email, $turma, $id);
        if ($stmt->execute()) {
            $_SESSION['turma'] = $turma;
            $msg = 'Dados atualizados com sucesso!';
        } else {
            $msg = 'Erro ao atualizar dados.';
        }
    } elseif ($tipo === 'professor') {
        $stmt = $conn->prepare('UPDATE professores SET Nome_Prof=?, NomeSocial_Prof=?, Cel_Prof=?, Email_Prof=? WHERE Id_Prof=?');
        $stmt->bind_param('ssssi', $nome, $nomeSocial, $celular, $email, $id);
        if ($stmt->execute()) {
            $msg = 'Dados atualizados com sucesso!';
        } else {
            $msg = 'Erro ao atualizar dados.';
        }
    } elseif ($tipo === 'coordenador') {
        $stmt = $conn->prepare('UPDATE coordenadores SET Nome_Coord=?, NomeSocial_Coord=?, Cel_Coord=?, Email_Coord=? WHERE Id_Coord=?');
        $stmt->bind_param('ssssi', $nome, $nomeSocial, $celular, $email, $id);
        if ($stmt->execute()) {
            $msg = 'Dados atualizados com sucesso!';
        } else {
            $msg = 'Erro ao atualizar dados.';
        }
    }
} elseif ($tipo === 'diretor') {
    $stmt = $conn->prepare('UPDATE diretores SET Nome_Diretor=?, NomeSocial_Diretor=?, Cel_Diretor=?, Email_Diretor=? WHERE Id_Diretor=?');
    $stmt->bind_param('ssssi', $nome, $nomeSocial, $celular, $email, $id);
    if ($stmt->execute()) {
        $msg = 'Dados atualizados com sucesso!';
    } else {
        $msg = 'Erro ao atualizar dados.';
    }
}

// Busca dados atuais do usuário
if ($tipo === 'aluno') {
    $stmt = $conn->prepare('SELECT Nome_Aluno, NomeSocial_Aluno, Cel_Aluno, Email_Aluno, fk_Turma_Id_Turma FROM alunos WHERE Id_Aluno=?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->bind_result($nome, $nomeSocial, $celular, $email, $turmaAtual);
    $stmt->fetch();
    $stmt->close();
} elseif ($tipo === 'professor') {
    $stmt = $conn->prepare('SELECT Nome_Prof, NomeSocial_Prof, Cel_Prof, Email_Prof FROM professores WHERE Id_Prof=?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->bind_result($nome, $nomeSocial, $celular, $email);
    $stmt->fetch();
    $stmt->close();
    $turmaAtual = null;
} elseif ($tipo === 'coordenador') {
    $stmt = $conn->prepare('SELECT Nome_Coord, NomeSocial_Coord, Cel_Coord, Email_Coord FROM coordenadores WHERE Id_Coord=?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->bind_result($nome, $nomeSocial, $celular, $email);
    $stmt->fetch();
    $stmt->close();
    $turmaAtual = null;
} elseif ($tipo === 'diretor') {
    $stmt = $conn->prepare('SELECT Nome_Diretor, NomeSocial_Diretor, Cel_Diretor, Email_Diretor FROM diretores WHERE Id_Diretor=?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->bind_result($nome, $nomeSocial, $celular, $email);
    $stmt->fetch();
    $stmt->close();
    $turmaAtual = null;
}

// Busca turmas (só mostra select se for aluno)
$turmas = [];
if ($tipo === 'aluno') {
    $res = $conn->query('SELECT Id_Turma, Nome_Turma FROM turma');
    while ($row = $res->fetch_assoc()) {
        $turmas[] = $row;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container-login">
        <img src="img/Vector.png" alt="Logo" class="logo" />
        <h1>Editar Perfil</h1>
        <?php if (isset($msg) && $_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: '<?= strpos($msg, "sucesso") !== false ? "success" : "error" ?>',
                        title: '<?= strpos($msg, "sucesso") !== false ? "Sucesso!" : "Erro!" ?>',
                        text: '<?= htmlspecialchars($msg) ?>',
                        confirmButtonColor: '#00aaff'
                    });
                });
            </script>
        <?php endif; ?>
        <form method="post">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($nome) ?>" required>
            <label for="nomeSocial">Nome social:</label>
            <input type="text" id="nomeSocial" name="nomeSocial" value="<?= htmlspecialchars($nomeSocial) ?>">
            <label for="celular">Celular:</label>
            <input type="tel" id="celular" name="celular" value="<?= htmlspecialchars($celular) ?>" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
            <?php if ($tipo === 'aluno'): ?>
                <label for="turma">Turma:</label>
                <select id="turma" name="turma" required>
                    <?php foreach ($turmas as $t): ?>
                        <option value="<?= $t['Id_Turma'] ?>" <?= $t['Id_Turma'] == $turmaAtual ? 'selected' : '' ?>>
                            <?= htmlspecialchars($t['Nome_Turma']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
            <div class="btn-container">
                <button type="button" class="btn-cinza" onclick="window.history.back()">Voltar</button>
                <button type="submit">Salvar alterações</button>
            </div>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const celularInput = document.getElementById('celular');
            if (celularInput) {
                celularInput.addEventListener('input', function (e) {
                    let value = e.target.value;
                    // Garante que começa com +
                    if (!value.startsWith('+')) value = '+' + value.replace(/[^\d]/g, '');
                    else value = '+' + value.substring(1).replace(/\D/g, '');
                    value = value.substring(0, 13);
                    if (value.length > 7)
                        value = value.replace(/^(\+\d{2})(\d{2})(\d{5})(\d{0,4})/, '$1 ($2) $3-$4');
                    else if (value.length > 4)
                        value = value.replace(/^(\+\d{2})(\d{2})(\d{0,5})/, '$1 ($2) $3');
                    else if (value.length > 3)
                        value = value.replace(/^(\+\d{2})(\d{0,2})/, '$1 ($2');
                    e.target.value = value;
                });
            }
        });
    </script>
</body>

</html>
