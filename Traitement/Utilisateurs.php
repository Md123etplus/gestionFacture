<?php
define('ROOT', str_replace('Traitement\Utilisateurs.php', '', $_SERVER['SCRIPT_FILENAME']));
require_once ROOT . 'BD\Utilisateur.php';
// if(isset($_FILES['fileUpload'])){
//     var_dump($_FILES);
// }

if (empty($_POST)&& empty($_GET)) {
    // $users = getAllUsers();
    // $_SESSION['users'] = $users;
    // header('Location: ');
    // include('..\IHM\utilisateur\index.php');
    include(ROOT.'IHM\Client\index.php');
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
        case "getAllAnomalies":
            $html = getAllAnomaliesHTML();
            // error_log("HTML Anomalies: " . $html); // Pour vérifier la valeur générée
            echo json_encode(["success" => true, "html" => $html]);
            exit();
        case "loadRecentReclamations":
            $html = getRecentReclamationsHTML();
            // error_log("HTML Anomalies: " . $html); // Pour vérifier la valeur générée

            echo json_encode(["success" => true, "html" => $html]);
            exit();

        case "loadAllReclamations":
            $html = getAllReclamationsHTML();
            // error_log("HTML Anomalies: " . $html); // Pour vérifier la valeur générée

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
            if (!isset($_GET['id']) || empty($_GET['id'])) {
                $errors = "ID client non valide.";
                include(ROOT.'IHM\Admin\clients.php');
            }
            else{
                if (!isset($_GET['id']) || empty($_GET['id'])) {
                    $errors = "ID client non valide.";
                    include(ROOT . 'IHM\Admin\clients.php');
                } else {
                    $id_client = intval($_GET['id']); // Sécuriser l'ID
                    $client = get_client_by_id($id_client);
                
                    if (!$client) {
                        $errors = "Client non trouvé";  
                        $clients = get_all_clients();
                        include(ROOT . 'IHM\Admin\clients.php');             
                    } else {
                        // Le client a été trouvé
                        // Tu peux faire une redirection ou afficher ses infos
                        // header('Location: ...'); (à adapter si nécessaire)
                        include(ROOT . 'IHM\Admin\edit_client.php');
                    }
                }
            }         
            break;

        case "reclamation":
            $reclamations = get_all_reclamation();

            // header('Location: ');
            include(ROOT.'IHM\Admin\reclamation.php');
            break;
        
        case "traiter_reclamation":
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
        
        case "login":
            include(ROOT.'IHM\Client\login.php');
            break;

        case "homeAdmin":
            include(ROOT.'IHM\Admin\index.php');
            exit();

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
            header("Location: ../IHM/Client/login.php");
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
    
        $type = $_POST['type']; 
    
        // Par défaut, vide si fournisseur
        $numero_compteur = ($type === 'client' && isset($_POST['numero_compteur'])) ? $_POST['numero_compteur'] : null;
        $adresse_installation = ($type === 'client' && isset($_POST['adresse_installation'])) ? $_POST['adresse_installation'] : null;
    
        $result = add_client($nom, $prenom, $email, $mot_de_passe, $type, $numero_compteur, $adresse_installation);
    
        if ($result) {
            $clients = get_all_clients();
            include(ROOT.'IHM\Admin\clients.php');
        } else {
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
else if(isset($_POST['handle_login'])){
    $email = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        $result = handle_login($email, $password);
        include(ROOT.'IHM\Admin\handle_login.php');
    }
    else {
        $error = "Veuillez remplir tous les champs.";
    }
    
    // header("Location: /IHM/Admin/login.php");
    include(ROOT.'IHM\Client\login.php');
    // exit();
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

}else if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case "voirReclamation":
            $id = $_POST['id_reclamation'];
            $rec = getReclamationById($id);

            if ($rec) {
                ob_start();
                ?>
                <p><strong>Type :</strong> <?= htmlspecialchars($rec['type_reclamation']) ?></p>
                <p><strong>Description :</strong> <?= nl2br(htmlspecialchars($rec['description'])) ?></p>
                <p><strong>Date de soumission :</strong> <?= date("d/m/Y", strtotime($rec['date_soumission'])) ?></p>
                <?php
                $html = ob_get_clean();
                echo json_encode(["success" => true, "html" => $html]);
            } else {
                echo json_encode(["success" => false, "message" => "Réclamation introuvable."]);
            }
            exit();

        case "traiterReclamation":
            $id = $_POST['id_reclamation'];
            $success = updateReclamationStatut($id, 'en cours');
            echo json_encode(["success" => $success]);
            exit();

        case "finaliserReclamation":
            $id = $_POST['id_reclamation'];
            $success = updateReclamationStatut($id, 'résolue');
            echo json_encode(["success" => $success]);
            exit();
    }

}else // Vérification du fichier et insertion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['fileUpload'])) {
    // var_dump($_FILES); 
    echo "File received";
    $file = $_FILES['fileUpload']['tmp_name'];
    
    if (($handle = fopen($file, 'r')) !== false) {
        // Lire la première ligne (en-têtes)
        fgetcsv($handle, 1000, ';');
        
        // Traiter les lignes du fichier
        while (($data = fgetcsv($handle, 1000, ';')) !== false) {
            // Récupérer les données
            $client_id = $data[0];
            $annee = $data[1];
            $consommation_totale = $data[2];
            $date_generation = $data[3];
            $id_agent = $data[4];
            
            // Insérer dans la base de données
            insererConsommationAnnuelle($client_id, $annee, $consommation_totale, $date_generation, $id_agent);
        }
        
        fclose($handle);
        
        // Retourner une réponse JSON
        echo json_encode(["success" => true, "message" => "Les données ont été insérées avec succès."]);
    } else {
        echo json_encode(["success" => false, "message" => "Erreur lors de l'ouverture du fichier."]);
    }
}
else{
    echo "Action non reconnue";
}
