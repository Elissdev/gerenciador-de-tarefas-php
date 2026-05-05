<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { exit; }

$host = 'db'; $db = 'gerenciador_tarefas'; $user = 'root'; $pass = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $dados = json_decode(file_get_contents("php://input"), true);
    $id = $dados['id'];

    if (!$id) {
        http_response_code(400);
        echo json_encode(["erro" => "ID não informado."]);
        exit;
    }

    // Deleta APENAS a tarefa com este ID específico
    $sql = "DELETE FROM tarefas WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    echo json_encode(["mensagem" => "Tarefa excluída com sucesso!"]);

} catch (PDOException $e) {
    echo json_encode(["erro" => "Erro ao excluir: " . $e->getMessage()]);
}