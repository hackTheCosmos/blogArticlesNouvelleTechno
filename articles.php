<?php
session_start();

    //on va chercher les articles dans la base de données
    // on se connecte à la base de données
    require_once 'includes/connect.php';

    //on écrit la requête
    $query = "SELECT * FROM articles ORDER BY created_at DESC";

    // on execute la requête
    $sth = $db->query($query);

    // on récupère les données
    $articles = $sth->fetchAll();

    $title = "Liste des articles";
    
    require_once 'includes/header.php';
    require_once 'includes/navBar.php';
    // diviser le code
?>
    <h1>Les Articles</h1>
    <p>Voici la liste des articles</p>

    <section>
<?php
    foreach($articles as $article) : ?>
        
        <article>
            <h1>
                <a href ="article.php?id=<?=$article['id']?>">
                    <?=strip_tags($article['title'])?>
                </a>
            </h1>
            <p>Publié le : <?=$article['created_at']?></p>
            <div><?=strip_tags($article['content'])?></div>
        </article>
        
        <?php endforeach; ?>

    </section>
<?php
    require_once 'includes/footer.php';
?>