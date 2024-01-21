<?php
    //traitement du formulaire d'inscription
    // On vérifie si le formulaire a été envoyé
    if(!empty($_POST)) {
        if(isset($_POST['email'], $_POST['pass'])
        && !empty($_POST['email'])
        && !empty($_POST['pass'])) {
            //on vérifie que l'email a le bon format
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                die("Votre email n'a pas le bon format");
            } else {
                require_once 'includes/connect.php';

                // on vérifie si un utilisateur avec le meme email existe déjà

                $query = "SELECT 
                * FROM users WHERE email = :email";
                $sth = $db->prepare($query);
                $sth->bindValue(":email" , $_POST['email'], PDO::PARAM_STR);
                $sth->execute();
                $user = $sth->fetch();
                //si aucun meme email existe déjà, on inscrit l'utilisateur
                if (!$user) {
                    die("email et/ou mot de passe incorrect"); 
                } else {
                    //il y a bien un utilisateur avec cet email, on vérifie si le mot de passe correspond
                    if(!password_verify($_POST['pass'], $user['pass'])) {
                        die("email et/ou mot de passe incorrect");
                    } else {
                        //l'email et le mot de passe sont correct, on ouvre la session (connexion de l'utilisateur)
                        session_start();
                    }
                }
            }  
        }  
    }

    $title = "Connexion";
    require_once 'includes/header.php';
    require_once 'includes/navBar.php';
   
?>
<h1>Connexion</h1>
<form method="post">
    <div>
        <label for="email">Email</label>
        <input type="email" name="email" id="email">
    </div>
    <div>
        <label for="pass">Mot de passe</label>
        <input type="password" name="pass" id="pass">
    </div>
    <button type="submit">Se connecter</button>
</form>


<?php
    require_once 'includes/footer.php';

  
