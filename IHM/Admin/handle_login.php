<?php

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    
    // Vérification du mot de passe
    // if (password_verify($password, $user['mot_de_passe'])) {
    if ($password == $user['mot_de_passe']){
        // Stocker les informations de l'utilisateur en session
        $_SESSION['id_utilisateur'] = $user['id_utilisateur'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['prenom'] =  $user['prenom'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['type'] = $user['type'];
        
        // Redirection vers la page d'accueil
        header("Location: index.php");
        exit();
    } else {
        $error = "Identifiants incorrects.";
    }
} else {
    $error = "Aucun compte associé à cet email.";
}
