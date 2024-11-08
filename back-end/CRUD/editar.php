<?php
// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dicionario";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verifica se o ID foi passado e tenta buscar o termo no banco
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM termos WHERE id=$id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "<p class='alert alert-danger'>Termo não encontrado.</p>";
        exit;
    }
} else {
    echo "<p class='alert alert-danger'>ID do termo não fornecido.</p>";
    exit;
}

// Lógica para atualizar o termo no banco de dados
if (isset($_POST['editar'])) {
    $id = $_POST['id'];
    $termo = $_POST['termo'];
    $definicao = $_POST['definicao'];
    $imagem = $row['imagem']; // Manter a imagem existente por padrão

    // Verificar se uma nova imagem foi enviada
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $imagem_nome = $_FILES['imagem']['name'];
        $imagem_tmp = $_FILES['imagem']['tmp_name'];
        $imagem_destino = '../uploads/' . basename($imagem_nome);

        // Tenta mover a nova imagem para o diretório de uploads
        if (move_uploaded_file($imagem_tmp, $imagem_destino)) {
            $imagem = $imagem_destino; // Atualiza o caminho da imagem
        } else {
            echo "<p class='alert alert-danger mt-3'>Erro ao fazer upload da imagem.</p>";
        }
    }

    if (!empty($termo) && !empty($definicao)) {
        // Consulta SQL para atualizar o termo
        $sql = "UPDATE termos SET termo=?, definicao=?, imagem=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $termo, $definicao, $imagem, $id);

        if ($stmt->execute()) {
            echo "<p class='alert alert-success'>Termo atualizado com sucesso!</p>";
            header("Location: ../index.php");
            exit;
        } else {
            echo "<p class='alert alert-danger'>Erro ao atualizar: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p class='alert alert-warning'>Por favor, preencha todos os campos.</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Termo</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1><a href="../index.php" class="btn-voltar"><i class="bi bi-arrow-left"></i></a>Editar Termo</h1>

    <form method="POST" action="editar.php?id=<?php echo $id; ?>" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo isset($row['id']) ? $row['id'] : ''; ?>">
        <div class="mb-3">
            <label for="termo" class="form-label">Termo</label>
            <input type="text" name="termo" class="form-control" value="<?php echo isset($row['termo']) ? $row['termo'] : ''; ?>" required>
        </div>
        <div class="mb-3">
            <label for="definicao" class="form-label">Definição</label>
            <textarea name="definicao" class="form-control" required><?php echo isset($row['definicao']) ? $row['definicao'] : ''; ?></textarea>
        </div>
        <div class="mb-3">
            <label for="imagem" class="form-label">Imagem</label>
            <input type="file" name="imagem" class="form-control">
            <?php if (!empty($row['imagem'])): ?>
                <p>Imagem atual: <img src="<?php echo $row['imagem']; ?>" width="100" alt="Imagem atual"></p>
            <?php endif; ?>
        </div>
        <button type="submit" name="editar" class="btn btn-primary">Salvar</button>
    </form>
</div>
</body>
</html>
