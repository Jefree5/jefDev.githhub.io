<?php
// Configuration de la base de données
$host = 'localhost'; 
$dbname = 'blogue'; 
$username = 'root';
$password = ''; 

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérification si le formulaire a été soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupération des données du formulaire
        $firstName = htmlspecialchars(trim($_POST['first-name']));
        $lastName = htmlspecialchars(trim($_POST['last-name']));
        $email = htmlspecialchars(trim($_POST['email']));
        $subject = htmlspecialchars(trim($_POST['subject']));
        $message = htmlspecialchars(trim($_POST['message']));

        // Validation des données
        if (empty($firstName) || empty($lastName) || empty($email) || empty($message)) {
            echo "Tous les champs sont requis.";
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Adresse e-mail invalide.";
            exit;
        }

        // Préparation de la requête SQL
        $stmt = $pdo->prepare("INSERT INTO contacts (nom, email, message, date_creation) VALUES (:nom, :email, :message, NOW())");

        // Liaison des paramètres avec bindValue
        $stmt->bindValue(':nom', $firstName . ' ' . $lastName); // Combine le prénom et le nom
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':message', $message);

        // Exécution de la requête
        if ($stmt->execute()) {
            echo "Message envoyé avec succès !";
            // Afficher la date et l'heure d'envoi
            echo "Date et heure d'envoi : " . date('Y-m-d H:i:s');
        } else {
            echo "Erreur lors de l'envoi du message.";
        }
    }
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>
