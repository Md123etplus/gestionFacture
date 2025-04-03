<?php

function connexion(){
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=electricity", "root", "", [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        return $pdo;
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }
}

// function connexion(){
//     $conn = new mysqli("localhost", "root", "", "electricity");
//     $conn->set_charset("utf8");
//     if ($conn->connect_error) {
//         die("Échec de la connexion : " . $conn->connect_error);
//     }
//     return $conn;
// }
