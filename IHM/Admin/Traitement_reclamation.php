<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer-master/src/Exception.php';
require 'PHPMailer/PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer/PHPMailer-master/src/SMTP.php';

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
    $message = "Réclamation traitée et email envoyé !";
} catch (Exception $e) {
    $errors = "Erreur lors de l'envoi de l'email !";
}



    
?>
