<?php
// Conexão com o banco de dados
$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'auxiliofamilia';

$conexao = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conexao->connect_errno) {
    die("Erro: " . $conexao->connect_error);
}

// Função para limpar os dados de entrada
function limparEntrada($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action == 'signup') {
        $name = limparEntrada($_POST['name']);
        $email = limparEntrada($_POST['email']);
        $password = limparEntrada($_POST['password']);
        $confirmPassword = limparEntrada($_POST['confirmPassword']);

        if ($password !== $confirmPassword) {
            $response['status'] = 'error';
            $response['message'] = 'As senhas não coincidem!';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $query = "INSERT INTO usuarios (nome, email, senha) VALUES ('$name', '$email', '$hashedPassword')";

            if ($conexao->query($query) === TRUE) {
                $response['status'] = 'success';
                $response['message'] = 'Usuário registrado com sucesso!';
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Erro: ' . $conexao->error;
            }
        }
    }

    if ($action == 'signin') {
        $email = limparEntrada($_POST['email']);
        $password = limparEntrada($_POST['password']);

        $query = "SELECT * FROM usuarios WHERE email='$email'";
        $result = $conexao->query($query);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['senha'])) {
                $response['status'] = 'success';
                $response['message'] = 'Login bem-sucedido!';
                $response['redirect'] = '/auxiliofamilia/inicial.html'; // URL de redirecionamento
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Email ou senha inválidos!';
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Email ou senha inválidos!';
        }
    }
}

$conexao->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
