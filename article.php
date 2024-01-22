<?php
session_start();

    // on vérifie si on a un id d'article dans l'url avec la méthode GET
    if(!isset($_GET['id']) || empty($_GET['id'])) {
        // Je n'ai pas d'id dans l'url
        header("Location: articles.php");
        exit;
    } else {
        //je récupère l'id de l'url
        $id = $_GET['id'];
        
        //on va chercher les articles dans la base de données
        // on se connecte à la base de données
        require_once 'includes/connect.php';
        
        //on écrit la requête
        $query = "SELECT * FROM articles WHERE id = :id";
        
        //on prépare la requete
        $sth = $db->prepare($query);
        $sth->bindValue(":id", $id, PDO::PARAM_INT).
        
        // on execute la requête
        $sth->execute();
        
        // on récupère l'article
        $article = $sth->fetch();

        //on vérifie si l'article est vide
        if(!$article) {
            //pas d'article
            http_response_code(404);
            echo "article innexistant";
            exit;
        } else {
            // on a un article
            $title = strip_tags($article['title']);

            // inclusion du header et de la navBar
            require_once 'includes/header.php';
            require_once 'includes/navBar.php';
    

        }

    }

   
    
?>
    <article>
        <h1><?=strip_tags($article['title'])?></h1>
        <p>Publié le : <?=$article['created_at']?></p>
        <div><?=strip_tags($article['content'])?></div>
    </article>
<?php
    require_once 'includes/footer.php';
?>