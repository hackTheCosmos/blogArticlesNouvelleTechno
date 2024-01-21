<?php
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
            // on vérifie que l'email a le bon format
            if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                die("L'addresse email est incorect");
            } else {
                $email = strip_tags($_POST['email']);
            }

            $pass = strip_tags($_POST['pass']);
            $hashPass = password_hash($pass, PASSWORD_ARGON2ID);

            // on enregistre l'utilisateur en base de données
            require_once 'includes/connect.php';

            // on vérifie si un utilisateur avec le meme email existe déjà

            $query = "SELECT id FROM users WHERE email = :email";
            $sth = $db->prepare($query);
            $sth->bindValue(":email" , $email, PDO::PARAM_STR);
            $sth->execute();
            $id = $sth->fetch();
            //si aucun meme email existe déjà, on inscrit l'utilisateur
            if (!$id) {
                
                $query = "INSERT INTO users (name, email, pass, roles) VALUES (:name, :email, '$hashPass', '[\"ROLE_USER\"]')";
                $sth = $db->prepare($query);
                $sth->bindValue(":name" , $name, PDO::PARAM_STR);
                $sth->bindValue(":email" , $email, PDO::PARAM_STR);
                $sth->execute();

                // on connecte l'utilisateur
            } else {
                die("un compte avec cet email existe déjà");
            }

        } else {
            die("le formulaire est incomplet");
        }
    }

    $title = "Inscription";
    require_once 'includes/header.php';
    require_once 'includes/navBar.php';
   
?>
<h1>Inscription</h1>
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

  
