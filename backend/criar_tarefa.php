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
// 1. Avisa que a nossa API vai se comunicar usando o formato JSON (o padrão do mercado)
header('Content-Type: application/json');

$host = 'db';
$db   = 'gerenciador_tarefas';
$user = 'root';
$pass = 'root';

try {
    // 2. Conecta na despensa (MySQL)
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 3. Lê o "papel do pedido" que o garçom trouxe
    $dados = json_decode(file_get_contents("php://input"), true);

    // 4. O FISCAL EM AÇÃO (Validação)
    // Verifica se a palavra 'titulo' não veio ou se veio apenas com espaços em branco
    if (!isset($dados['titulo']) || trim($dados['titulo']) === '') {
        
        // Devolve um código de erro 400 (Bad Request - Pedido Ruim)
        http_response_code(400);
        echo json_encode(["erro" => "O campo 'titulo' é obrigatório e não pode estar vazio."]);
        exit; // Para a execução aqui mesmo, o código abaixo não roda!
    }

    // 5. Prepara os dados limpos
    $titulo = trim($dados['titulo']);
    // Se a descrição vier vazia, o sistema aceita, pois não é obrigatória
    $descricao = isset($dados['descricao']) ? trim($dados['descricao']) : '';
    
    // (O status e a data_criacao o nosso banco de dados já sabe preencher sozinho!)

    // 6. Prepara o comando de forma segura (previne ataques de injeção de código)
    $sql = "INSERT INTO tarefas (titulo, descricao) VALUES (:titulo, :descricao)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':titulo', $titulo);
    $stmt->bindParam(':descricao', $descricao);
    
    // 7. Executa e salva no banco de verdade
    $stmt->execute();

    // 8. Devolve um código 201 (Created - Criado com sucesso) e uma mensagem feliz
    http_response_code(201);
    echo json_encode(["mensagem" => "Tarefa criada com sucesso!"]);

} catch (PDOException $e) {
    // Se der qualquer erro no servidor ou banco de dados, devolve um erro 500
    http_response_code(500);
    echo json_encode(["erro" => "Erro interno no servidor: " . $e->getMessage()]);
}