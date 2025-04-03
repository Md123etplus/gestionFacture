<?php
require_once 'Connexion.php';

function add_client($nom, $prenom, $email, $mot_de_passe, $type, $numero_compteur, $adresse_installation) {
    $conn = connexion();
    
    try {
        $conn->beginTransaction();
        
        // Insérer l'utilisateur
        $sql_utilisateur = "INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, type) VALUES (:nom, :prenom, :email, :mot_de_passe, :type)";
        $stmt = $conn->prepare($sql_utilisateur);
        $stmt->execute(['nom' => $nom, 'prenom' => $prenom, 'email' => $email, 'mot_de_passe' => $mot_de_passe, 'type' => $type]);
        
        // Récupérer l'ID du nouvel utilisateur
        $id_utilisateur = $conn->lastInsertId();
        
        // Insérer dans `client`
        $sql_client = "INSERT INTO client (id_client, numero_compteur, adresse_installation) VALUES (:id_client, :numero_compteur, :adresse_installation)";
        $stmt = $conn->prepare($sql_client);
        $stmt->execute(['id_client' => $id_utilisateur, 'numero_compteur' => $numero_compteur, 'adresse_installation' => $adresse_installation]);
        
        $conn->commit();
        return true;
    } catch (PDOException $e) {
        $conn->rollBack();
        return false;
    }
}

function get_all_clients() {
    $conn = connexion();
    $sql = "SELECT id_client, numero_compteur, adresse_installation FROM client";
    return $conn->query($sql)->fetchAll();
}

function get_client_by_id($id_client) {
    $conn = connexion();
    $sql = "SELECT u.id_utilisateur, u.nom, u.prenom, u.email, c.numero_compteur, c.adresse_installation 
            FROM utilisateur u
            JOIN client c ON u.id_utilisateur = c.id_client
            WHERE u.id_utilisateur = :id_client";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id_client' => $id_client]);
    return $stmt->fetch();
}

function update_client($nom, $prenom, $email, $numero_compteur, $adresse_installation, $id_client) {
    $conn = connexion();
    
    try {
        $conn->beginTransaction();
        
        $sql_utilisateur = "UPDATE utilisateur SET nom = :nom, prenom = :prenom, email = :email WHERE id_utilisateur = :id_client";
        $stmt = $conn->prepare($sql_utilisateur);
        $stmt->execute(['nom' => $nom, 'prenom' => $prenom, 'email' => $email, 'id_client' => $id_client]);
        
        $sql_client = "UPDATE client SET numero_compteur = :numero_compteur, adresse_installation = :adresse_installation WHERE id_client = :id_client";
        $stmt = $conn->prepare($sql_client);
        $stmt->execute(['numero_compteur' => $numero_compteur, 'adresse_installation' => $adresse_installation, 'id_client' => $id_client]);
        
        $conn->commit();
        return true;
    } catch (PDOException $e) {
        $conn->rollBack();
        return false;
    }
}

function get_consommations() {
    $conn = connexion();
    $sql = "SELECT id_consommation, client_id, mois, annee, valeur_compteur, photo_compteur, validee FROM consommation";
    return $conn->query($sql)->fetchAll();
}

function update_consommation($id, $valeur) {
    $conn = connexion();
    $stmt = $conn->prepare("UPDATE consommation SET valeur_compteur = :valeur, validee = 1 WHERE id_consommation = :id");
    return $stmt->execute(['valeur' => $valeur, 'id' => $id]);
}

function get_facture_info($id_client) {
    $conn = connexion();
    $sql = "SELECT u.nom, u.prenom, u.email, c.numero_compteur, c.adresse_installation, co.mois, co.annee, co.valeur_compteur 
            FROM client c
            JOIN utilisateur u ON c.id_client = u.id_utilisateur
            JOIN consommation co ON c.id_client = co.client_id
            WHERE c.id_client = :id_client";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id_client' => $id_client]);
    return $stmt->fetch();
}

function get_all_reclamation() {
    $conn = connexion();
    $sql = "SELECT id_reclamation, client_id, date_soumission, type_reclamation, statut FROM reclamation";
    return $conn->query($sql)->fetchAll();
}

function update_reclamation($id_reclamation) {
    $conn = connexion();
    
    try {
        $conn->beginTransaction();
        
        $sql_update = "UPDATE reclamation SET statut = 'résolue' WHERE id_reclamation = :id_reclamation";
        $stmt = $conn->prepare($sql_update);
        $stmt->execute(['id_reclamation' => $id_reclamation]);
        
        $sql_client = "SELECT u.email, u.nom, u.prenom, r.type_reclamation, r.description 
                        FROM utilisateur u
                        JOIN reclamation r ON u.id_utilisateur = r.client_id
                        WHERE r.id_reclamation = :id_reclamation";
        $stmt = $conn->prepare($sql_client);
        $stmt->execute(['id_reclamation' => $id_reclamation]);
        
        $conn->commit();
        return $stmt->fetch();
    } catch (PDOException $e) {
        $conn->rollBack();
        return false;
    }
}

function handle_login($email) {
    $conn = connexion();
    $stmt = $conn->prepare("SELECT id_utilisateur, nom, prenom, email, mot_de_passe, type FROM utilisateur WHERE email = :email");
    $stmt->execute(['email' => $email]);
    return $stmt->fetch();
}
function getAllUsers(){
    $conn = connexion();
    $sql = "SELECT id_utilisateur, nom, prenom, email, type FROM utilisateur";
    return $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}
function getStatistics(){
    $conn = connexion();
    $sql = "SELECT 
            COUNT(DISTINCT c.id_client) AS total_clients,  -- Total des clients
            IFNULL(SUM(co.consommation_totale), 0) AS total_consommation,  -- Total consommation (basé sur la consommation annuelle)
            COUNT(DISTINCT f.id_facture) AS factures_impayees,  -- Nombre de factures impayées
            IFNULL(SUM(f.montant_ttc), 0) AS montant_impaye,  -- Montant total impayé
            COUNT(DISTINCT r.id_reclamation) AS reclamations_non_traitees  -- Nombre de réclamations non traitées
        FROM 
            client c
        LEFT JOIN consommationannuelle co ON c.id_client = co.client_id  -- Jointure sur la consommation annuelle
        LEFT JOIN facture f ON c.id_client = f.client_id AND f.statut = 'impayée'  -- Jointure sur les factures impayées
        LEFT JOIN reclamation r ON c.id_client = r.client_id AND r.statut != 'résolue'  -- Jointure sur les réclamations non résolues
        ";
    return $conn->query($sql)->fetch(PDO::FETCH_ASSOC);
}
function getAnomaliesHTML(){
    $conn = connexion();
    $sql = "SELECT 
            c.id_client, 
            u.nom, 
            u.prenom, 
            r.date_soumission AS date_releve, 
            c.numero_compteur, 
            r.description, 
            (co.valeur_compteur - ca.consommation_totale) / ca.consommation_totale * 100 AS ecart
        FROM reclamation r
        JOIN client c ON r.client_id = c.id_client
        JOIN utilisateur u ON c.id_client = u.id_utilisateur
        JOIN consommation co ON c.id_client = co.client_id
        JOIN consommationannuelle ca ON c.id_client = ca.client_id
        WHERE ABS((co.valeur_compteur - ca.consommation_totale) / ca.consommation_totale) > 0.5
        ORDER BY r.date_soumission DESC;
        ";

    $stmt = $conn->query($sql);
    $anomalies = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $html = "";
    foreach ($anomalies as $anomalie) {
        $ecart = round($anomalie['ecart']);
        $badgeClass = $ecart > 0 ? "bg-danger" : "bg-warning";
        $sign = $ecart > 0 ? "+" : "";

        // S'assurer que les valeurs nulles ou 0 soient bien gérées
        $description = !empty($anomalie['description']) ? $anomalie['description'] : 'Aucune description fournie';
        $consommation = !empty($anomalie['consommation']) ? $anomalie['consommation'] : 0;
        $consommationMoyenne = !empty($anomalie['consommation_moyenne']) ? $anomalie['consommation_moyenne'] : 0;

        $html .= "
            <tr>
                <td>CL-{$anomalie['id_client']}</td>
                <td>{$anomalie['nom']} {$anomalie['prenom']}</td>
                <td>" . date("d/m/Y", strtotime($anomalie['date_releve'])) . "</td>
                <td>{$consommation} kWh</td>
                <td><span class='badge $badgeClass'>{$sign}{$ecart}%</span></td>
                <td>
                    <button class='btn btn-sm btn-primary'>Vérifier</button>
                    <button class='btn btn-sm btn-outline-success'>Valider</button>
                </td>
            </tr>
        ";
    }
    
    return $html;
}
function getRecentReclamationsHTML() {
    $conn = connexion(); // Assuming you have a function for DB connection.
    
    // Corrected SQL query to join reclamation with utilisateur instead of client for names.
    $sql = "SELECT r.id_reclamation, u.nom, u.prenom, r.date_soumission, r.type_reclamation, r.statut 
            FROM reclamation r 
            JOIN utilisateur u ON r.client_id = u.id_utilisateur 
            WHERE r.statut = 'non traité' 
            ORDER BY r.date_soumission DESC 
            LIMIT 5";
    
    $stmt = $conn->query($sql);
    $reclamations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $html = "";
    foreach ($reclamations as $reclamation) {
        // Generate HTML content for each reclamation
        $html .= "
            <tr>
                <td>{$reclamation['id_reclamation']}</td>
                <td>{$reclamation['nom']} {$reclamation['prenom']}</td>
                <td>" . date("d/m/Y", strtotime($reclamation['date_soumission'])) . "</td>
                <td>{$reclamation['type_reclamation']}</td>
                <td>{$reclamation['statut']}</td>
            </tr>
        ";
    }
    
    // If there are no reclamations, add a message indicating that.
    if (empty($reclamations)) {
        $html .= "
            <tr>
                <td colspan='5' class='text-center'>Aucune réclamation non traitée trouvée.</td>
            </tr>
        ";
    }

    return $html;
}


function getGlobalConsumptionData(){
    $conn = connexion();
    // Corrected SQL query to use 'valeur_compteur' instead of 'consommation'
    $sql = "SELECT SUM(valeur_compteur) as total_consumption FROM consommation";
    $stmt = $conn->query($sql);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getClaimsDistributionData(){
    $conn = connexion();
    $sql = "SELECT type_reclamation, COUNT(*) as count FROM reclamation GROUP BY type_reclamation";
    $stmt = $conn->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);  
}
?>
