<?php

if ($result && is_array($result)) {
    // Vérification du mot de passe
    if (password_verify($password, $result['mot_de_passe'])) {
        session_start();

        $_SESSION['id_utilisateur'] = $result['id_utilisateur'];
        $_SESSION['nom'] = $result['nom'];
        $_SESSION['prenom'] = $result['prenom'];
        $_SESSION['email'] = $result['email'];
        $_SESSION['type'] = $result['type'];

        header("Location: ../IHM/Admin/index.php");
        // include(ROOT.'IHM\Admin\index.php');
        exit();
    } else {
        $error = "Identifiants incorrects.";
    }
} else {
    $error = "Aucun compte associé à cet email.";
}

