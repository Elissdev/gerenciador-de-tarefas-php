<?php
// Cabeçalhos de segurança e tipo de arquivo
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

// Responde ao "segurança" do navegador (CORS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

$host = 'db';
$db   = 'gerenciador_tarefas';
$user = 'root';
$pass = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    
    // Recebe o ID da tarefa que quero carimbar
    $dados = json_decode(file_get_contents("php://input"), true);
    $id = $dados['id'];

    if (!$id) {
        http_response_code(400);
        echo json_encode(["erro" => "ID da tarefa não informado."]);
        exit;
    }

    // Atualiza apenas onde o ID for igual ao que recebedo front-end, para evitar atualizar a tarefa errada
    $sql = "UPDATE tarefas SET status = 'concluída' WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    echo json_encode(["mensagem" => "Tarefa concluída com sucesso!"]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["erro" => "Erro ao atualizar: " . $e->getMessage()]);
}