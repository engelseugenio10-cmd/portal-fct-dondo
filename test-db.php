<?php

$host = 'ep-shiny-sky-b93g4p6a.c-7.eu-central-1.aws.neon.tech';
$db   = 'neondb';
$user = 'neondb_owner';
// O trim() remove automaticamente qualquer espaço ou enter invisível copiado do browser
$pass = trim('npg_1ygmN5RvIEwA');
$port = '5432';

$dsn = "pgsql:host=$host;port=$port;dbname=$db;sslmode=require";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    echo "SUCESSO ABSOLUTO: LIGACAO BEM-SUCEDIDA AO NEON!\n";
} catch (\PDOException $e) {
    echo "ERRO DE LIGACAO: " . $e->getMessage() . "\n";
}