<?php 
header('Content-Type: application/json');
define('ROOT', str_replace('Traitement\Admin\upload.php', '', $_SERVER['SCRIPT_FILENAME']));
require_once ROOT . 'Traitement\Utilisateurs.php';

// Vérifier si un fichier est envoyé
if (!isset($_FILES['fileUpload']) || $_FILES['fileUpload']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'Aucun fichier envoyé ou erreur détectée.']);
    exit;
}

$file = $_FILES['fileUpload'];
$fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if ($fileExtension !== 'txt') {
    echo json_encode(['success' => false, 'message' => 'Seuls les fichiers .txt sont acceptés.']);
    exit;
}

$targetDirectory = ROOT . 'uploads/';
if (!is_dir($targetDirectory)) {
    mkdir($targetDirectory);
}

$targetFile = $targetDirectory . basename($file['name']);
if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
    echo json_encode(['success' => false, 'message' => 'Erreur lors du téléversement.']);
    exit;
}

// Lire le fichier texte
$data = [];
$fileContent = file($targetFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($fileContent as $line) {
    $columns = explode(";", $line); // Supposons que les colonnes sont séparées par ";"
    if (count($columns) < 4) continue; // Vérifier que la ligne contient assez de données

    $client_id = intval($columns[0]);
    $mois = intval($columns[1]);
    $annee = intval($columns[2]);
    $valeur_compteur = floatval($columns[3]);

    $data[] = [$client_id, $mois, $annee, $valeur_compteur];
}

// Insérer dans la base de données
$sql = "INSERT INTO consommation (client_id, mois, annee, valeur_compteur) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$successCount = 0;

foreach ($data as $row) {
    $stmt->bind_param("iiid", $row[0], $row[1], $row[2], $row[3]);
    if ($stmt->execute()) {
        $successCount++;
    }
}

$stmt->close();
$conn->close();

// Répondre en JSON
echo json_encode([
    'success' => true,
    'message' => "$successCount enregistrements ajoutés avec succès."
]);
?>
