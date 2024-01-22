<?php
session_start();

//protection de la route, si on est pas connecté on ne peut pas accéder à la page ajouter un avatar
if(!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}
$title = "Ajout d'un avatar";
require_once 'includes/connect.php';
require_once 'includes/header.php';
require_once 'includes/navBar.php';

// on vérifie si un fichier a bien été envoyé
if(isset($_FILES["image"]) && $_FILES['image']['error'] === 0) {
    //on a bien reçu l'image
    // on fait toutes les vérifications
    // on vérifie l'extension et le type Mime
    $ExtensionsAllowed = [
        "jpg" => "image/jpeg",
        "jpeg" => "image/jpeg",
        "png" => "image/png"
    ];

    $fileName = $_FILES['image']['name'];
    $fileType = $_FILES['image']['type'];
    $fileSize = $_FILES['image']['size'];

    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // on vérifie l'absence de l'extension dans les clés ou l'absence du type Mime dans les valeurs
    if(!array_key_exists($fileExtension, $ExtensionsAllowed) || !in_array($fileType, $ExtensionsAllowed)) {
        //soit l'extension, soit le type sont incorrect, ou les deux
        die("Erreur: format de fichier incorect"); 
    }

    //ici le type est correct
    //on limite à 1 Mo
    if($fileSize > 1024 * 1024) {
        die("fichier trop volumineux");
    }
    
    //On génère un nom unique
    $newName = md5(uniqid());
    // on génère le chemin complet
    $newFileName = __DIR__ . "/uploads/avatar/$newName.$fileExtension";

    // on déplace le fichier de tmp à uploads en le renommant
    if(!move_uploaded_file($_FILES['image']['tmp_name'], $newFileName)) {
        die("l'upload a échoué");
    }

    // on supprime le nom deja existant dans la base de données avant la mise à jour
    $userId = $_SESSION['user']['id'];
    $query = "SELECT avatar FROM users WHERE id = $userId";
    $sth = $db->query($query);
    $currentAvatar = $sth->fetch()['avatar'];

    unlink(__DIR__."/uploads/avatar/$currentAvatar");



    //on protège contre les écritures
    chmod($newFileName, 0644);

    $avatarNameForDb = "$newName.$fileExtension";

    //on enrgistre l'image dans la base de données
    
    $query = "UPDATE users SET avatar=:avatar WHERE id=:id";
    $sth = $db->prepare($query);
    $sth->bindValue(":avatar", $avatarNameForDb, PDO::PARAM_STR);
    $sth->bindValue(":id", $_SESSION['user']['id'], PDO::PARAM_INT);
    $sth->execute();

} 

?>

<form method="post" enctype="multipart/form-data">
    <div >
        <label for="file">Choisir une image</label>
        <input type="file" name="image" id="file">
    </div>
    <button type="submit">Ajouter</button>
</form>

<?php
require_once 'includes/footer.php';

