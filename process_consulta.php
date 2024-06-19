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
        case 'consult-family':
            // Consultar uma família
            if (!empty($_POST['family-name'])) {
                $familyName = limparEntrada($_POST['family-name']);
                $query = "SELECT * FROM familias WHERE nome='$familyName'";
                $result = $conexao->query($query);

                if ($result->num_rows > 0) {
                    echo "<table><tr><th>ID</th><th>Nome</th></tr>";
                    while($row = $result->fetch_assoc()) {
                        echo "<tr><td>" . $row['id'] . "</td><td>" . $row['nome'] . "</td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "Família não encontrada.";
                }
            }
            break;

        case 'consult-member':
            // Consultar um membro
            if (!empty($_POST['member-name'])) {
                $memberName = limparEntrada($_POST['member-name']);
                $query = "SELECT * FROM membros WHERE nome='$memberName'";
                $result = $conexao->query($query);

                if ($result->num_rows > 0) {
                    echo "<table><tr><th>ID</th><th>Nome</th><th>Idade</th><th>Sexo</th><th>CPF</th><th>Família ID</th></tr>";
                    while($row = $result->fetch_assoc()) {
                        echo "<tr><td>" . $row['id'] . "</td><td>" . $row['nome'] . "</td><td>" . $row['idade'] . "</td><td>" . $row['sexo'] . "</td><td>" . $row['cpf'] . "</td><td>" . $row['familia_id'] . "</td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "Membro não encontrado.";
                }
            }
            break;

        case 'edit-family':
            // Editar uma família
            if (!empty($_POST['edit-family-name']) && !empty($_POST['new-family-name'])) {
                $familyName = limparEntrada($_POST['edit-family-name']);
                $newFamilyName = limparEntrada($_POST['new-family-name']);

                $query = "UPDATE familias SET nome='$newFamilyName' WHERE nome='$familyName'";

                if ($conexao->query($query) === TRUE) {
                    echo "Família editada com sucesso!";
                } else {
                    echo "Erro ao editar família: " . $conexao->error;
                }
            }
            break;

        case 'edit-member':
            // Editar um membro
            if (!empty($_POST['edit-member-name']) && !empty($_POST['new-member-name']) && !empty($_POST['new-member-age']) && !empty($_POST['new-member-sex']) && !empty($_POST['new-member-cpf'])) {
                $memberName = limparEntrada($_POST['edit-member-name']);
                $newMemberName = limparEntrada($_POST['new-member-name']);
                $newMemberAge = limparEntrada($_POST['new-member-age']);
                $newMemberSex = limparEntrada($_POST['new-member-sex']);
                $newMemberCpf = limparEntrada($_POST['new-member-cpf']);

                $query = "UPDATE membros SET nome='$newMemberName', idade=$newMemberAge, sexo='$newMemberSex', cpf='$newMemberCpf' WHERE nome='$memberName'";

                if ($conexao->query($query) === TRUE) {
                    echo "Membro editado com sucesso!";
                } else {
                    echo "Erro ao editar membro: " . $conexao->error;
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
