<?php 
include '../db/conexao.php'; 

// Verifique se a conexão foi bem-sucedida
if (!$conn) {
    die("Conexão falhou: " . mysqli_connect_error());
}

if (isset($_POST['adicionar'])) {
    $termo = $_POST['termo'];
    $definicao = $_POST['definicao'];
    $imagem = NULL;

    // Verificar se foi enviado um arquivo de imagem
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $imagem_nome = $_FILES['imagem']['name'];
        $imagem_tmp = $_FILES['imagem']['tmp_name'];
        $imagem_destino = '../uploads/' . basename($imagem_nome); // Caminho corrigido

        // Tenta mover a imagem para o diretório de uploads
        if (move_uploaded_file($imagem_tmp, $imagem_destino)) {
            $imagem = $imagem_destino;
        } else {
            echo "<p class='alert alert-danger mt-3'>Erro ao fazer upload da imagem.</p>";
        }
    }

    // Inserir o termo e a imagem no banco de dados
    $sql = "INSERT INTO termos (termo, definicao, imagem) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $termo, $definicao, $imagem);

    if ($stmt->execute()) {
        echo "<p class='alert alert-success mt-3'>Termo adicionado com sucesso!</p>";
    } else {
        echo "<p class='alert alert-danger mt-3'>Erro: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Termo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container mt-4">
        <h1><a href="../index.php" class="btn-voltar"><i class="bi bi-arrow-left"></i></a>Adicionar Novo Termo</h1>
        <form method="POST" action="adicionar.php" enctype="multipart/form-data">
        <div class="mb-3">
    <label for="termo" class="form-label">Termo</label>
    <input type="text" id="termo" name="termo" class="form-control" required>
</div>
<div class="mb-3">
    <label for="definicao" class="form-label">Definição</label>
    <textarea id="definicao" name="definicao" class="form-control" required></textarea>
</div>
<div class="mb-3">
    <label for="imagem" class="form-label">Imagem</label>
    <input type="file" id="imagem" name="imagem" class="form-control">
</div>
            <button type="submit" name="adicionar" class="btn btn-primary">Adicionar</button>
        </form>
    </div>
</body>
</html>
