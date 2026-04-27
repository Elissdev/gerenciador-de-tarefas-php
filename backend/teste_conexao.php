<?php
$host = 'db';
$db   = 'gerenciador_tarefas';
$user = 'root';
$pass = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    echo "Sucesso! A cozinha (PHP) conseguiu conversar com a despensa (MySQL).";
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
}