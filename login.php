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
            "SELECT 'professor' AS tipo, Nome_Prof AS nome, Id_Prof as id, Senha_Prof AS senha, aprovado FROM professores WHERE Cpf_Prof = ?",
            "SELECT 'coordenador' AS tipo, Nome_Coord AS nome, Id_Coord as id, Senha_Coord AS senha, aprovado FROM coordenadores WHERE Cpf_Coord = ?",
            "SELECT 'diretor' AS tipo, Nome_Diretor AS nome, Id_Diretor as id, Senha_Diretor AS senha, aprovado FROM diretores WHERE Cpf_Diretor = ?"
        ];

        foreach ($queries as $query) {
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $cpf);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado->num_rows > 0) {
                $usuario = $resultado->fetch_assoc();

                // Se for professor, coordenador ou diretor, checa aprovação
                if (
                    (isset($usuario['tipo']) && $usuario['tipo'] === 'professor' && isset($usuario['aprovado']) && !$usuario['aprovado']) ||
                    (isset($usuario['tipo']) && $usuario['tipo'] === 'coordenador' && isset($usuario['aprovado']) && !$usuario['aprovado']) ||
                    (isset($usuario['tipo']) && $usuario['tipo'] === 'diretor' && isset($usuario['aprovado']) && !$usuario['aprovado'])
                ) {
                    header("Location: login.html?erro=aprovacao");
                    exit();
                }

                // Comparação com senha hash
                if (password_verify($senha, $usuario['senha'])) {   
                    $_SESSION['usuario'] = $usuario['nome'];
                    $_SESSION['tipo'] = $usuario['tipo'];

                    // Redirecionamento conforme o tipo
                    if ($usuario['tipo'] == 'aluno') {
                        $_SESSION['id'] = $usuario['id'];
                        $_SESSION['turma'] = $usuario['turma'];
                        header("Location: calendario_aluno.php");
                    } elseif ($usuario['tipo'] == 'professor') {
                        $_SESSION['id'] = $usuario['id'];
                        header("Location: calendario_admin.php");
                    } elseif ($usuario['tipo'] == 'coordenador') {
                        $_SESSION['id'] = $usuario['id'];
                        header("Location: calendario_admin.php");
                    } elseif ($usuario['tipo'] == 'diretor') {
                        $_SESSION['id'] = $usuario['id'];
                        header("Location: calendario_admin.php");
                    }
                    exit();
                } else {
                    header("Location: login.html?erro=senha");
                    exit();
                }
            }
        }
        header("Location: login.html?erro=naoencontrado");
        exit();
    }
}
?>