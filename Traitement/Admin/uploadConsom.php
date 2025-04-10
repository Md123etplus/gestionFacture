<?php
define('ROOT', str_replace('Traitement\Admin\uploadConsom.php', '', $_SERVER['SCRIPT_FILENAME']));
require_once ROOT . 'BD\Utilisateur.php';// ini_set('display_errors', 1);
// error_reporting(E_ALL);

header('Content-Type: application/json');

// Vérification de la méthode et du fichier
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['fileUpload'])) {
    
    // Récupérer le fichier téléchargé
    $fileTmpPath = $_FILES['fileUpload']['tmp_name'];
    $fileName = $_FILES['fileUpload']['name'];
    $fileSize = $_FILES['fileUpload']['size'];
    $fileType = $_FILES['fileUpload']['type'];
    
    // Vérification de l'extension du fichier (seulement .txt)
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));
    
    if ($fileExtension !== 'txt') {
        echo json_encode(["success" => false, "message" => "Type de fichier non autorisé. Seuls les fichiers .txt sont acceptés."]);
        exit;
    }
    
    // Déplacer le fichier téléchargé vers le dossier d'uploads
    $uploadFileDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadFileDir)) {
        mkdir($uploadFileDir, 0755, true);  // Créer le dossier si nécessaire
    }

    $dest_path = $uploadFileDir . $fileName;
    
    if (!move_uploaded_file($fileTmpPath, $dest_path)) {
        echo json_encode(["success" => false, "message" => "Erreur lors du déplacement du fichier."]);
        exit;
    }
    // var_dump($dest_path);
    

    // Ouverture du fichier et lecture ligne par ligne
    if (file_exists($dest_path)) {
        $fileContent = file($dest_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if ($fileContent === false) {
            echo json_encode(["success" => false, "message" => "Erreur lors de la lecture du fichier."]);
            exit;
        }

        // Traiter chaque ligne du fichier
        $lineNumber = 0;
        foreach ($fileContent as $line) {
            $lineNumber++;
            // Traiter la ligne (exemple de séparation par point-virgule)
            $data = explode(";", $line); // Séparer par ';'

            // Vérification du nombre d'éléments dans la ligne
            if (count($data) < 5) {
                echo json_encode(["success" => false, "message" => "Erreur dans la ligne $lineNumber. Le nombre d'éléments est incorrect."]);
                exit;
            }

            // Récupérer les données
            $client_id = $data[0];
            $annee = $data[1];
            $consommation_totale = $data[2];
            $date_generation = $data[3];
            $id_agent = $data[4];

            // Insérer dans la base de données
            insererConsommationAnnuelle2($client_id, $annee, $consommation_totale, $date_generation, $id_agent);
        }

        // Retourner une réponse JSON de succès
        echo json_encode(["success" => true, "message" => "Les données ont été insérées avec succès."]);
    } else {
        // Si le fichier n'existe pas après le déplacement
        echo json_encode(["success" => false, "message" => "Fichier non trouvé après le téléchargement."]);
    }
} else {
    // Si la méthode n'est pas POST ou si aucun fichier n'est fourni
    echo json_encode(["success" => false, "message" => "Requête invalide ou fichier non fourni."]);
}
function insererConsommationAnnuelle2($client_id, $annee, $consommation_totale, $date_generation, $id_agent) {
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