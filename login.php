<?php
session_start();
$conn = new mysqli("localhost", "root", "", "tccteste");
if ($conn->connect_error) die("Conexão falhou: " . $conn->connect_error);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cpf = preg_replace('/\D/', '', trim($_POST['cpf']));
    $senha = trim($_POST['senha']);

    if (!empty($cpf) && !empty($senha)) {
        $queries = [
            "SELECT 'aluno' AS tipo, Nome_Aluno AS nome, Id_Aluno as id, fk_Turma_Id_Turma as turma, Senha_Aluno AS senha FROM alunos WHERE Cpf_Aluno = ?",
            "SELECT 'professor' AS tipo, Nome_Prof AS nome, Id_Prof as id, Senha_Prof AS senha FROM professores WHERE Cpf_Prof = ?",
            "SELECT 'coordenador' AS tipo, Nome_Coord AS nome, Id_Coord as id, Senha_Coord AS senha FROM coordenadores WHERE Cpf_Coord = ?"
        ];

        foreach ($queries as $query) {
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $cpf);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado->num_rows > 0) {
                $usuario = $resultado->fetch_assoc();

                // Comparação com senha hash
                if (password_verify($senha, $usuario['senha'])) {   
                    $_SESSION['usuario'] = $usuario['nome'];
                    $_SESSION['tipo'] = $usuario['tipo'];

                    // Redirecionamento conforme o tipo
                    if ($usuario['tipo'] == 'aluno') {
                        session_start();
                        $_SESSION['id'] = $usuario['id'];
                        $_SESSION['turma'] = $usuario['turma'];
                        header("Location: calendario_aluno.php");

                    } elseif ($usuario['tipo'] == 'professor') {
                        session_start();
                        $_SESSION['id'] = $usuario['id'];
                        header("Location: calendario_admin.php");

                    } elseif ($usuario['tipo'] == 'coordenador') {
                        session_start();
                        $_SESSION['id'] = $usuario['id'];
                        header("Location: calendario_admin.php");
                    }
                    exit();
                } else {
                    // Senha incorreta
                    header("Location: login.html?erro=senha");
                    exit();
                }
            }
        }

        // CPF não encontrado
        header("Location: login.html?erro=cpf");
        exit();
    } else {
        header("Location: login.html?erro=campos");
        exit();
    }
}
?>
