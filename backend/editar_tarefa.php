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

    $sql = "UPDATE tarefas SET titulo = :titulo, descricao = :descricao WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':titulo' => $dados['titulo'],
        ':descricao' => $dados['descricao'],
        ':id' => $dados['id']
    ]);

    echo json_encode(["mensagem" => "Tarefa atualizada!"]);
} catch (PDOException $e) {
    echo json_encode(["erro" => $e->getMessage()]);
}