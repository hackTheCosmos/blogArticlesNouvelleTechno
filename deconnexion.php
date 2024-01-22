<?php
session_start();
//protection de la route, si on est pas connecté on ne peut pas accéder à la page deconnexion
if(!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}
unset($_SESSION['user']);

header("Location: index.php");