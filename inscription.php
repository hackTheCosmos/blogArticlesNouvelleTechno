<?php
session_start();

//protection de la route, si on est déjà connecté on ne peut pas accéder à la page inscription
if(isset($_SESSION['user'])) {
    header("Location: userSpace.php");
    exit;
}
    //traitement du formulaire d'inscription
    // On vérifie si le formulaire a été envoyé
    if(!empty($_POST)) {
        //on vérifie que tous les champs sont remplis
        if(isset($_POST['name'], $_POST['email'], $_POST['pass'])
        && !empty($_POST['name'])
        && !empty($_POST['email'])
        && !empty($_POST['pass'])) {
            //le formulaire est complet, on protège les données et on les récupère 
            $name = strip_tags($_POST['name']);
            //initialisation du tableau qui contient les erreurs des formulaires
            $_SESSION['error'] = [];
            if(strlen($name) < 2) {
                $_SESSION['error'][] = "Le pseudo choisi est trop court";
            }
            // on vérifie que l'email a le bon format
            if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'][] = "L'adresse email est incorect";
                 
               
            } else {
                $email = strip_tags($_POST['email']);
            }

            $pass = strip_tags($_POST['pass']);

            if ($_SESSION['error'] === []) {

                $hashPass = password_hash($pass, PASSWORD_ARGON2ID);
                
                // on enregistre l'utilisateur en base de données
                require_once 'includes/connect.php';
                
                // on vérifie si un utilisateur avec le meme email existe déjà
                
                $query = "SELECT * FROM users WHERE email = :email";
                $sth = $db->prepare($query);
                $sth->bindValue(":email" , $email, PDO::PARAM_STR);
                $sth->execute();
                $user = $sth->fetch();
                //si aucun meme email existe déjà, on inscrit l'utilisateur
                if (!$user) {
                    
                    $query = "INSERT INTO users (name, email, pass, roles) VALUES (:name, :email, '$hashPass', '[\"ROLE_USER\"]')";
                    $sth = $db->prepare($query);
                    $sth->bindValue(":name" , $name, PDO::PARAM_STR);
                    $sth->bindValue(":email" , $email, PDO::PARAM_STR);
                    $sth->execute();
                    
                    // on récupère l'id du nouvel utilisateur
                    $id = $db->lastInsertId();
                    
                    // on connecte l'utilisateur
                    
                    $_SESSION['user'] = [
                        "id" => $id,
                        "name" => $name,
                        "email" => $email,
                        "roles" => ["ROLE_USER"],
                    ];
                    
                    // on redirige vers la page "espace personnel"
                    header("Location: userSpace.php");
                } else {
                    $_SESSION['error'] = ["un compte avec cet email existe déjà"];
                }
                
            } 
        } else {
            $_SESSION['error'] = ["le formulaire est incomplet"];
        }
    }

    $title = "Inscription";
    require_once 'includes/header.php';
    require_once 'includes/navBar.php';
   
?>
<h1>Inscription</h1>
<?php
//affichage des erreurs liées au formulaire
if(isset($_SESSION['error'])) {
    foreach($_SESSION['error'] as $message) {
        ?>

        <p><?=$message?></p>

        <?php
    }
    unset($_SESSION['error']);
}
?>
<form method="post">
    <div>
        <label for="name">Pseudo</label>
        <input type="text" name="name" id="name">
    </div>
    <div>
        <label for="email">Email</label>
        <input type="email" name="email" id="email">
    </div>
    <div>
        <label for="pass">Mot de passe</label>
        <input type="password" name="pass" id="pass">
    </div>
    <button type="submit">M'inscrire</button>
</form>


<?php
    require_once 'includes/footer.php';

  
