<?php

// on traite le formulaire d'ajout d'un article
if(!empty($_POST)) {
    // si $_POST n'est pas vide, on vérifie que toutes les données sont présentes
    if(isset($_POST['title'], $_POST['content'])
    && !empty($_POST['title']) && !empty($_POST['content'])) {

        // le formulaire est complet, on récupère alors les données du formulaire en les protégeant des failles xss
        $articleTitleFromForm = strip_tags($_POST['title']);
        $articleContentFromForm = htmlspecialchars($_POST['content']);

        // on enregistre les données
        require_once "../../includes/connect.php";
        $query = "INSERT INTO articles (title, content) VALUES (:title, :content)";
        $sth = $db->prepare($query);
        $sth->bindValue(':title', $articleTitleFromForm, PDO::PARAM_STR);
        $sth->bindValue(':content', $articleContentFromForm, PDO::PARAM_STR);

        if(!$sth->execute()) {
            die("Une erreure est survenue");
        } else {
            //on récupère l'id de l'article ajouté
            $id = $db->lastInsertId();
            die("article ajouté sous le numero $id");
        }

    } else {
        die("le formulaire est incomplet");
    }
}

$title = "Ajouter un article";
//on inclut le header
require_once '../../includes/header.php';

// on inclut la navBar
require_once '../../includes/navBar.php';
?>

<h1>Ajouter un article</h1>

<form method = "POST">
    <div>
        <label for="title">Titre</label>
        <input type="text" name="title" id="title">
    </div>
    <div>
        <label for="content">Contenu</label>
        <textarea name ="content" id ="content" ></textarea>
    </div>
    <button type="submit">Enregistrer</button>
</form>


<?php
// on inclut le footer
require_once '../../includes/footer.php';