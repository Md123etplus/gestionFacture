<?php
define('ROOT', str_replace('Traitement\Utilisateurs.php', '', $_SERVER['SCRIPT_FILENAME']));
require_once ROOT . 'BD\Utilisateur.php';
if (empty($_POST)&& empty($_GET)) {
    // $users = getAllUsers();
    // $_SESSION['users'] = $users;
    // header('Location: ');
    // include('..\IHM\utilisateur\index.php');
    include(ROOT.'IHM\Admin\index.php');
    exit();
}
else if(isset($_GET['action'])){
    $action=$_GET['action'];
    switch($action){
        case "loadData":
            $users = getAllUsers();
            // print_r($users);
            echo json_encode(value: ["success" => true, "users" => $users]);
            exit();

        case "loadStatistics":
            // echo "Loading statistics...";
            $stats = getStatistics();
            // print_r($stats);
            echo json_encode(["success" => true] + $stats);
            exit();

        case "loadAnomalies":
            $html = getAnomaliesHTML();
            // error_log("HTML Anomalies: " . $html); // Pour vérifier la valeur générée
            echo json_encode(["success" => true, "html" => $html]);
            exit();

        case "loadRecentReclamations":
            $html = getRecentReclamationsHTML();
            error_log("HTML Anomalies: " . $html); // Pour vérifier la valeur générée

            echo json_encode(["success" => true, "html" => $html]);
            exit();

        case "loadGlobalConsumption":
            $data = getGlobalConsumptionData();
            echo json_encode(["success" => true] + $data);
            exit();

        case "loadClaimsDistribution":
            $data = getClaimsDistributionData();
            echo json_encode(["success" => true] + $data);
            exit();

        case "getAllClients":
            $clients = get_all_clients();

            header('Location: ');
            include(ROOT.'IHM\Admin\clients.php');
            break;

        case "editClient":
            // // Vérifier si un ID client est fourni
            // if (!isset($_GET['id']) || empty($_GET['id'])) {
            //     $errors = "ID client non valide.";
            //     include(ROOT.'IHM\Admin\clients.php');
            // }
            // else{
            //     $id_client = intval($_GET['id']); // Sécuriser l'ID
            //     $result = get_client_by_id($id_client);
    
            //     if($result->num_rows == 0){
            //         $errors = "Client non trouvé";  
            //         $clients = get_all_clients();
            //         include(ROOT.'IHM\Admin\clients.php');             
            //     }
            //     else{
            //         $client = $result->fetch_assoc();
            //         header('Location: ');
            //         include(ROOT.'IHM\Admin\clients.php');
            //     }
            // }         
            // break;

        case "reclamation":
            $reclamations = get_all_reclamation();

            header('Location: ');
            include(ROOT.'IHM\Admin\reclamation.php');
            break;

        case "consommation":
            $consommations = get_consommations();

            header('Location: ');
            include(ROOT.'IHM\Admin\consommation.php');
            break;

        case "generer_facture":

            if (!isset($_GET['id']) || empty($_GET['id']))
                $errors = "ID client manquant ou invalide.";
            else{
                $id_client = intval($_GET['id']);
                
                $facture_data = get_facture_info($id_client);
                
                if (!$facture_data) 
                    $errors = "Données introuvables pour ce client.";
                else{
                    $message = "Facture générée avec succès !";
                    include(ROOT.'IHM\Admin\facture.php');
                }

            }

            $consommations = get_consommations();
            include(ROOT.'IHM\Admin\consommation.php');
            break;

        case "logout":
            session_start();

            // Détruire toutes les variables de session
            $_SESSION = array();

            // Supprimer le cookie de session si nécessaire
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }

            // Détruire la session
            session_destroy();

            // Rediriger vers la page de connexion
            header("Location: login.php");
            exit();
            break;

        default:
            echo "Action non reconnue";
    }
}
else if(isset($_POST['add_client'])){

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupération des données du formulaire
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT); // Hash du mot de passe
        $type = 'client'; // On force le type à 'client'
        $numero_compteur = $_POST['numero_compteur'];
        $adresse_installation = $_POST['adresse_installation'];


        $result = add_client($nom, $prenom, $email, $mot_de_passe, $type, $numero_compteur, $adresse_installation);

        if($result){
            $clients = get_all_clients();
            include(ROOT.'IHM\Admin\clients.php');
        }
        else{
            $errors = "Erreur lors de l'ajout du client. Réessayer!";
            include(ROOT.'IHM\Admin\add_client.php');
        }
    }

}
else if(isset($_POST['submit_editClient'])){
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $numero_compteur = $_POST['numero_compteur'];
        $adresse_installation = $_POST['adresse_installation'];
        $id_client = $_POST['id_client'];
    
        $result = update_client($nom, $prenom, $email, $numero_compteur, $adresse_installation, $id_client);
    
        if ($result) {
            $clients = get_all_clients();
            $message = "Client modifié avec succès !";
            include(ROOT.'IHM\Admin\clients.php');
        } else {
            $clients = get_all_clients();
            $errors = "Erreur lors de la mise à jour : " ;
            include(ROOT.'IHM\Admin\clients.php');
        }
    }
}
else if(isset($_POST['traiter_reclamation'])){
    // Vérifier si l'ID de réclamation est bien passé
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        $errors = "ID de réclamation invalide.";
    }
    else{
        $id_reclamation = intval($_GET['id']); // Sécuriser l'ID
        $client = update_reclamation($id_reclamation);

        if(!$client)
            $errors = "Erreur lors du traitement!";
        else{
            include(ROOT.'IHM\Admin\Traitement_reclamation.php');
        }
    }

    $reclamations = get_all_reclamation();

    header('Location: ');
    include(ROOT.'IHM\Admin\reclamation.php');

}
else if(isset($_POST['handle_login'])){
    session_start();

    $email = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        $result = handle_login($email, $password);

        include(ROOT.'IHM\Admin\handle_login.php');
    }
    else {
        $error = "Veuillez remplir tous les champs.";
    }
    
    header("Location: login.php?error=" . urlencode($error));
    exit();
}
else if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $id = $_POST['id_consommation'];
    $valeur = $_POST['valeur_compteur'];

    $result = update_consommation($id, $valeur);

    if ($result) {
        $message = "Valeur corrigée avec succès !";
        $consommations = get_consommations();

        include(ROOT.'IHM\Admin\consommation.php');
    } else {
        $errors = "Erreur lors de la modification de la consommation !";
        $consommations = get_consommations();

        include(ROOT.'IHM\Admin\consommation.php');
    }

}
else{
    echo "Action non reconnue";
}
