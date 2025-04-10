<?php
require_once 'Connexion.php';

function add_client($nom, $prenom, $email, $mot_de_passe, $type, $numero_compteur, $adresse_installation) {
    $conn = connexion();

    try {
        $conn->beginTransaction();

        // Insérer l'utilisateur
        $sql_utilisateur = "INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, type) 
                            VALUES (:nom, :prenom, :email, :mot_de_passe, :type)";
        $stmt = $conn->prepare($sql_utilisateur);
        $stmt->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'mot_de_passe' => $mot_de_passe,
            'type' => $type
        ]);

        // Récupérer l'ID du nouvel utilisateur
        $id_utilisateur = $conn->lastInsertId();

        // Si c’est un client, on insère les infos dans la table client
        if ($type === 'client') {
            $sql_client = "INSERT INTO client (id_client, numero_compteur, adresse_installation) 
                           VALUES (:id_client, :numero_compteur, :adresse_installation)";
            $stmt = $conn->prepare($sql_client);
            $stmt->execute([
                'id_client' => $id_utilisateur,
                'numero_compteur' => $numero_compteur,
                'adresse_installation' => $adresse_installation
            ]);
        }

        $conn->commit();
        return true;
    } catch (PDOException $e) {
        $conn->rollBack();
        return false;
    }
}


function get_all_clients() {
    $conn = connexion(); // Assuming `connexion` is your PDO connection function
    $sql = "SELECT id_client, numero_compteur, adresse_installation FROM client";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch the results as an associative array
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
            IFNULL(SUM(co.consommation_totale), 0) AS total_consommation_annuelle,  -- Total consommation (basé sur la consommation annuelle)
            IFNULL(SUM(cons.valeur_compteur), 0) AS total_consommation_mensuelle,  -- Total consommation (basé sur la consommation mensuelle)
            COUNT(DISTINCT CASE WHEN f.statut = 'impayée' THEN f.id_facture END) AS factures_impayees,  -- Nombre de factures impayées
            IFNULL(SUM(CASE WHEN f.statut = 'impayée' THEN f.montant_ttc END), 0) AS montant_impaye,  -- Montant total impayé
            COUNT(DISTINCT CASE WHEN r.statut in ('soumise','en cours') THEN r.id_reclamation END) AS reclamations_non_traitees  -- Nombre de réclamations non résolues
        FROM 
            client c
        LEFT JOIN consommationannuelle co ON c.id_client = co.client_id  -- Jointure sur la consommation annuelle
        LEFT JOIN consommation cons ON c.id_client = cons.client_id  -- Jointure sur la consommation mensuelle
        LEFT JOIN facture f ON c.id_client = f.client_id  -- Jointure sur les factures
        LEFT JOIN reclamation r ON c.id_client = r.client_id  -- Jointure sur les réclamations
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
        ORDER BY r.date_soumission DESC LIMIT 5;
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
function getAllAnomaliesHTML(){
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
    $conn = connexion(); // Connexion à la base de données

    $sql = "SELECT 
            r.id_reclamation,
            u.nom, 
            u.prenom, 
            r.date_soumission,
            r.type_reclamation,
            r.statut,
            f.id_facture
        FROM reclamation r
        JOIN utilisateur u ON r.client_id = u.id_utilisateur
        LEFT JOIN (
            SELECT f1.*
            FROM facture f1
            INNER JOIN (
                SELECT client_id, MAX(date_emission) AS max_date
                FROM facture
                GROUP BY client_id
            ) f2 ON f1.client_id = f2.client_id AND f1.date_emission = f2.max_date
        ) f ON f.client_id = r.client_id
        WHERE r.statut IN ('soumise', 'en cours')
        ORDER BY r.date_soumission DESC
        LIMIT 5;
        ";

    $stmt = $conn->query($sql);
    $reclamations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $html = "";
    foreach ($reclamations as $reclamation) {
        $buttons = "<button class='btn btn-sm btn-primary btn-voir' data-id='{$reclamation['id_reclamation']}'>Voir</button>";

        if ($reclamation['statut'] == 'soumise') {
            $buttons .= " <a href='/Traitement/Utilisateurs.php?action=traiter_reclamation&id={$reclamation['id_reclamation']}' class='btn btn-sm btn-warning'>Traiter</a>";
        } elseif ($reclamation['statut'] == 'en cours') {
            $buttons .= " <button class='btn btn-sm btn-warning btn-finaliser' data-id='{$reclamation['id_reclamation']}'>Finaliser</button>";
        }


        $html .= "
            <tr>
                <td>{$reclamation['id_reclamation']}</td>
                <td>{$reclamation['nom']} {$reclamation['prenom']}</td>
                <td>{$reclamation['type_reclamation']}</td>
                <td>" . date("d/m/Y", strtotime($reclamation['date_soumission'])) . "</td>
                <td>" . ($reclamation['id_facture'] ?? 'N/A') . "</td>
                <td><span class='badge bg-" . ($reclamation['statut'] == 'soumise' ? "danger" : "warning") . "'>{$reclamation['statut']}</span></td>
                <td>{$buttons}</td>
            </tr>
        ";
    }

    if (empty($reclamations)) {
        $html .= "
            <tr>
                <td colspan='7' class='text-center'>Aucune réclamation non traitée trouvée.</td>
            </tr>
        ";
    }

    return $html;
}


function getAllReclamationsHTML() {
    $conn = connexion(); // Connexion à la base de données

    $sql = "SELECT 
            r.id_reclamation,
            u.nom, 
            u.prenom, 
            r.date_soumission,
            r.type_reclamation,
            r.statut,
            f.id_facture
        FROM reclamation r
        JOIN utilisateur u ON r.client_id = u.id_utilisateur
        LEFT JOIN (
            SELECT f1.*
            FROM facture f1
            INNER JOIN (
                SELECT client_id, MAX(date_emission) AS max_date
                FROM facture
                GROUP BY client_id
            ) f2 ON f1.client_id = f2.client_id AND f1.date_emission = f2.max_date
        ) f ON f.client_id = r.client_id
        WHERE r.statut IN ('soumise', 'en cours')
        ORDER BY r.date_soumission DESC
        ";

    $stmt = $conn->query($sql);
    $reclamations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $html = "";
    foreach ($reclamations as $reclamation) {
        $buttons = "<button class='btn btn-sm btn-primary btn-voir' data-id='{$reclamation['id_reclamation']}'>Voir</button>";

        if ($reclamation['statut'] == 'soumise') {
            $buttons .= " <a href='/Traitement/Utilisateurs.php?action=traiter_reclamation&id={$reclamation['id_reclamation']}' class='btn btn-sm btn-warning'>Traiter</a>";
        } elseif ($reclamation['statut'] == 'en cours') {
            $buttons .= " <button class='btn btn-sm btn-warning btn-finaliser' data-id='{$reclamation['id_reclamation']}'>Finaliser</button>";
        }


        $html .= "
            <tr>
                <td>{$reclamation['id_reclamation']}</td>
                <td>{$reclamation['nom']} {$reclamation['prenom']}</td>
                <td>{$reclamation['type_reclamation']}</td>
                <td>" . date("d/m/Y", strtotime($reclamation['date_soumission'])) . "</td>
                <td>" . ($reclamation['id_facture'] ?? 'N/A') . "</td>
                <td><span class='badge bg-" . ($reclamation['statut'] == 'soumise' ? "danger" : "warning") . "'>{$reclamation['statut']}</span></td>
                <td>{$buttons}</td>
            </tr>
        ";
    }

    if (empty($reclamations)) {
        $html .= "
            <tr>
                <td colspan='7' class='text-center'>Aucune réclamation non traitée trouvée.</td>
            </tr>
        ";
    }

    return $html;
}
function getReclamationById($id_reclamation) {
    $conn = connexion();
    $sql = "SELECT r.id_reclamation, u.nom, u.prenom, r.date_soumission, r.type_reclamation, r.description, r.statut 
            FROM reclamation r 
            JOIN utilisateur u ON r.client_id = u.id_utilisateur 
            WHERE r.id_reclamation = :id_reclamation";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id_reclamation' => $id_reclamation]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
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
function updateReclamationStatut($id, $nouveauStatut) {
    $conn = connexion();
    $stmt = $conn->prepare("UPDATE reclamation SET statut = ? WHERE id_reclamation = ?");
    return $stmt->execute([$nouveauStatut, $id]);
}
function insererConsommationAnnuelle($client_id, $annee, $consommation_totale, $date_generation, $id_agent) {
    // Connexion à la base de données
    $conn = connexion();

    // Préparer la requête SQL avec des paramètres
    $sql = "INSERT INTO consommationannuelle (client_id, annee, consommation_totale, date_generation, id_agent) 
            VALUES (:client_id, :annee, :consommation_totale, :date_generation, :id_agent)";
    $stmt = $conn->prepare($sql);

    // Lier les paramètres aux valeurs
    $stmt->bindParam(':client_id', $client_id, PDO::PARAM_INT);
    $stmt->bindParam(':annee', $annee, PDO::PARAM_INT);
    $stmt->bindParam(':consommation_totale', $consommation_totale, PDO::PARAM_STR);
    $stmt->bindParam(':date_generation', $date_generation, PDO::PARAM_STR);
    $stmt->bindParam(':id_agent', $id_agent, PDO::PARAM_INT);

    // Exécuter la requête
    try {
        $stmt->execute();
    } catch (PDOException $e) {
        // Si une erreur se produit lors de l'exécution de la requête
        echo "Erreur lors de l'insertion dans la base de données: " . $e->getMessage();
        error_log('stop');
        exit();
    }
}
?>
