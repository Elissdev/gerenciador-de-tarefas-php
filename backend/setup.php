<?php
$host = 'db';
$db   = 'gerenciador_tarefas';
$user = 'root';
$pass = 'root';

try {
    // 1. Conecta na "despensa" (MySQL)
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    
    // 2. O comando para criar a prateleira de tarefas
    $sql = "CREATE TABLE IF NOT EXISTS tarefas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        titulo VARCHAR(255) NOT NULL,
        descricao TEXT,
        status ENUM('pendente', 'concluida') DEFAULT 'pendente',
        data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    // 3. Executa o comando
    $pdo->exec($sql);
    
    echo "Sucesso! A tabela 'tarefas' foi criada com o campo de data e tudo!";

} catch (PDOException $e) {
    echo "Erro ao criar a tabela: " . $e->getMessage();
}