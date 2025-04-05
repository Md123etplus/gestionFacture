<?php
session_start();
require_once '../../BD/Connexion.php';
require_once '../../BD/utilisateurs.php';
require_once '../../BD/client.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    try {
        $user = getUserByEmail($email);
        $usermdp = $user['mot_de_passe'];
        $usertype = $user['type'];

        if ($user && $password === $usermdp) {
            $_SESSION['user_id'] = $user['id_utilisateur'];
            $_SESSION['user_type'] = $usertype;
            $_SESSION['user_name'] = $user['prenom'] . ' ' . $user['nom'];

            // Redirection selon le type
            if ($usertype === 'client') {
                header('Location: ../../IHM/Client/dashboard_finalUser.php');
            } else {
                header('Location: ../IHM/dashboard_fournisseur.php');
            }
            exit();
        } else {
            $_SESSION['login_error'] = "Email ou mot de passe incorrect";
            header('Location: ../../IHM/Client/login.php');
            exit();
        }
    } catch (Exception $e) {
        $_SESSION['login_error'] = "Erreur de connexion: " . $e->getMessage();
        header('Location: ../../IHM/Client/login.php');
        exit();
    }
} else {
    header('Location: ../../IHM/Client/login.php');
    exit();
}
