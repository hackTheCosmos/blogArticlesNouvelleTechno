<?php
session_start();
//protection de la route, si on est pas connecté on ne peut pas accéder à la page espace utilisateur
if(!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}



$title = "Espace Utilisateur";

require_once 'includes/connect.php';
require_once 'includes/header.php';
require_once 'includes/navBar.php';

//récupération de l'avatar dans la base de données
$id = $_SESSION['user']['id'];
$query ="SELECT avatar FROM users WHERE id = $id";
$sth = $db->query($query);
$avatar = $sth->fetch();

$avatarPath =$avatar['avatar'];


?>

<h1>Bonjour <?= $_SESSION['user']['name']?></h1>
<img class ="avatar" src="uploads/avatar/<?=$avatarPath?>" alt="avatar">

<a href="uploadAvatar.php">Ajouter ou modifier mon avatar</a>

<?php

require_once 'includes/footer.php';