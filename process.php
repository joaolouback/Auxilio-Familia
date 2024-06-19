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

    switch ($action) {
        case 'add-family':
            // Adicionar uma nova família
            if (!empty($_POST['family-name'])) {
                $familyName = limparEntrada($_POST['family-name']);
                $query = "INSERT INTO familias (nome) VALUES ('$familyName')";

                if ($conexao->query($query) === TRUE) {
                    echo "Família adicionada com sucesso!";
                } else {
                    echo "Erro ao adicionar família: " . $conexao->error;
                }
            }
            break;

        case 'delete-family':
            // Excluir uma família
            if (!empty($_POST['delete-family-name'])) {
                $familyName = limparEntrada($_POST['delete-family-name']);
                $query = "DELETE FROM familias WHERE nome='$familyName'";

                if ($conexao->query($query) === TRUE) {
                    echo "Família excluída com sucesso!";
                } else {
                    echo "Erro ao excluir família: " . $conexao->error;
                }
            }
            break;

        case 'add-member':
            // Adicionar um novo membro à família
            if (!empty($_POST['consult-family-name']) && !empty($_POST['member-name']) && !empty($_POST['member-age']) && !empty($_POST['member-sex']) && !empty($_POST['member-cpf'])) {
                $familyName = limparEntrada($_POST['consult-family-name']);
                $memberName = limparEntrada($_POST['member-name']);
                $memberAge = limparEntrada($_POST['member-age']);
                $memberSex = limparEntrada($_POST['member-sex']);
                $memberCpf = limparEntrada($_POST['member-cpf']);

                // Obter o ID da família
                $familyQuery = "SELECT id FROM familias WHERE nome='$familyName'";
                $familyResult = $conexao->query($familyQuery);
                
                if ($familyResult->num_rows > 0) {
                    $familyRow = $familyResult->fetch_assoc();
                    $familyId = $familyRow['id'];

                    // Inserir o novo membro
                    $query = "INSERT INTO membros (nome, idade, sexo, cpf, familia_id) VALUES ('$memberName', $memberAge, '$memberSex', '$memberCpf', $familyId)";

                    if ($conexao->query($query) === TRUE) {
                        echo "Membro adicionado com sucesso!";
                    } else {
                        echo "Erro ao adicionar membro: " . $conexao->error;
                    }
                } else {
                    echo "Família não encontrada.";
                }
            }
            break;

        case 'delete-member':
            // Excluir um membro
            if (!empty($_POST['delete-member-name'])) {
                $memberName = limparEntrada($_POST['delete-member-name']);
                $query = "DELETE FROM membros WHERE nome='$memberName'";

                if ($conexao->query($query) === TRUE) {
                    echo "Membro excluído com sucesso!";
                } else {
                    echo "Erro ao excluir membro: " . $conexao->error;
                }
            }
            break;

        default:
            echo "Ação inválida.";
            break;
    }
}

// Fechar a conexão com o banco de dados
$conexao->close();
?>
