<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer-master/src/Exception.php';
require 'PHPMailer/PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer/PHPMailer-master/src/SMTP.php';

// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "electricity");

if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Vérifier si l'ID de réclamation est bien passé
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de réclamation invalide.");
}

$id_reclamation = intval($_GET['id']); // Sécuriser l'ID

// Mettre à jour la réclamation comme "résolue"
$sql_update = "UPDATE reclamation SET statut = 'résolue' WHERE id_reclamation = $id_reclamation";

if ($conn->query($sql_update) === TRUE) {
    // Récupérer les informations du client et de la réclamation
    $sql_client = "SELECT u.email, u.nom, u.prenom, r.type_reclamation, r.description
                   FROM utilisateur u
                   JOIN reclamation r ON u.id_utilisateur = r.client_id
                   WHERE r.id_reclamation = $id_reclamation";
    
    $result = $conn->query($sql_client);
    
    if ($result->num_rows > 0) {
        $client = $result->fetch_assoc();
        $email = $client['email'];
        $nom = $client['nom'];
        $prenom = $client['prenom'];
        $type_reclamation = $client['type_reclamation'];
        $description = $client['description'];

        // Création d'un nouvel email avec PHPMailer
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Serveur SMTP (à adapter selon ton fournisseur)
            $mail->SMTPAuth = true;
            $mail->Username = 'rr9444037@gmail.com'; // Ton email
            $mail->Password = 'gtjz bjqi hbkn mddz'; // Ton mot de passe ou mot de passe d'application
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('ton-email@gmail.com', 'VoltForce - Support');
            $mail->addAddress($email, "$nom $prenom");

            $mail->isHTML(true);
            $mail->Subject = "Votre réclamation a été traitée";
            $mail->Body = "
                <h3>Bonjour $nom $prenom,</h3>
                <p>Nous vous informons que votre réclamation concernant <strong>$type_reclamation</strong> a été traitée.</p>
                <p><strong>Description :</strong> $description</p>
                <p>Merci de votre patience et de votre confiance.</p>
                <br>
                <p>Cordialement, <br><strong>VoltForce - Service Client</strong></p>
            ";

            $mail->send();
            echo "<script>alert('Réclamation traitée et email envoyé !'); window.location.href='reclamation.php';</script>";
        } catch (Exception $e) {
            echo "Erreur lors de l'envoi de l'email : {$mail->ErrorInfo}";
        }
    } else {
        echo "Erreur : Client introuvable.";
    }
} else {
    echo "Erreur de mise à jour : " . $conn->error;
}

$conn->close();
?>
