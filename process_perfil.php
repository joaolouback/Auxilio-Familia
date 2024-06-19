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

// Verificar a ação solicitada pelo formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'update-profile') {
        // Atualizar perfil do usuário
        $userName = limparEntrada($_POST['user-name']);
        $userEmail = limparEntrada($_POST['user-email']);
        $userPhone = limparEntrada($_POST['user-phone']);

        // Carregar a foto do perfil
        if (!empty($_FILES['profile-pic']['name'])) {
            $targetDir = "uploads/";
            $fileName = basename($_FILES['profile-pic']['name']);
            $targetFilePath = $targetDir . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

            // Permitir certos formatos de arquivo
            $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
            if (in_array($fileType, $allowTypes)) {
                if (move_uploaded_file($_FILES['profile-pic']['tmp_name'], $targetFilePath)) {
                    $profilePic = $targetFilePath;
                } else {
                    $profilePic = null;
                }
            } else {
                $profilePic = null;
            }
        } else {
            $profilePic = null;
        }

        // Atualizar a base de dados
        $query = "UPDATE usuarios SET nome='$userName', email='$userEmail', telefone='$userPhone'";
        if ($profilePic) {
            $query .= ", foto_perfil='$profilePic'";
        }
        $query .= " WHERE id='1'"; // Substituir '1' pelo ID real do usuário

        if ($conexao->query($query) === TRUE) {
            echo "Perfil atualizado com sucesso!";
        } else {
            echo "Erro ao atualizar perfil: " . $conexao->error;
        }
    }
}

// Fechar a conexão com o banco de dados
$conexao->close();
?>
