<?php

function connexion(){
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=gestionFacture", "root", "", [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        return $pdo;
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }
        
    
}
