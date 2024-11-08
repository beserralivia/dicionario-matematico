<?php
$servername = "localhost";
$username = "root"; // Seu nome de usuário
$password = ""; // Sua senha
$dbname = "dicionario"; // Nome do banco de dados

// Criação da conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Checando a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
