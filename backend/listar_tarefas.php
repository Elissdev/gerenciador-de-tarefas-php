<?php
// Libera o acesso para o Live Server (porta 5500)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
// ... seus cabeçalhos anteriores ...
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// NOVO: Se o navegador estiver apenas "perguntando" (OPTIONS), responda OK e pare aqui.
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}
header('Content-Type: application/json');

$host = 'db';
$db   = 'gerenciador_tarefas';
$user = 'root';
$pass = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    
    // Busca todas as tarefas 
    $stmt = $pdo->query("SELECT * FROM tarefas");
    $tarefas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Mostra o resultado como JSON
    echo json_encode($tarefas);

} catch (PDOException $e) {
    echo json_encode(["erro" => $e->getMessage()]);
}