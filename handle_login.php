<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        // Connexion à la base de données
        $conn = new mysqli("localhost", "root", "Hf_MySQl_root+2684", "electricity");

        // Vérifier la connexion
        if ($conn->connect_error) {
            die("Échec de la connexion : " . $conn->connect_error);
        }

        // Préparer et exécuter la requête SQL
        $stmt = $conn->prepare("SELECT id_utilisateur, nom, prenom, email, mot_de_passe, type FROM utilisateur WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

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
                header("Location: dashboard_Admin.php");
                exit();
            } else {
                $error = "Identifiants incorrects.";
            }
        } else {
            $error = "Aucun compte associé à cet email.";
        }
        
        // Fermer la connexion
        $stmt->close();
        $conn->close();
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}

header("Location: login.php?error=" . urlencode($error));
exit();
