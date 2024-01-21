<?php

define('DBHOST', 'localhost');
define('DBUSER', 'root');
define('DBPASS', '');
define ('DBNAME', 'tuto_php');

//on définit le DSN (Data Source Name) de connexion
$dsn = "mysql:dbname=". DBNAME . ";host=". DBHOST;

try {
    //on se connecte à la base de données en "instanciant" PDO
    $db = new PDO ($dsn, DBUSER, DBPASS);

    //on définit le charset à "utf8"
    $db->exec('SET NAMES utf8');

    //on définit la méthode de récupération des données (fetch)
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    die($e->getMessage());
}
