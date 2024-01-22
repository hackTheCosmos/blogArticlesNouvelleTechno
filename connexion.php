<?php
session_start();

//protection de la route, si on est déjà connecté on ne peut pas accéder à la page connexion
if(isset($_SESSION['user'])) {
    header("Location: userSpace.php");
    exit;
}
    //traitement du formulaire d'inscription
    // On vérifie si le formulaire a été envoyé
    if(!empty($_POST)) {
        if(isset($_POST['email'], $_POST['pass'])
        && !empty($_POST['email'])
        && !empty($_POST['pass'])) {
            //initialisation du tableau qui contient les erreurs des formulaires
            $_SESSION['error'] = [];
            //on vérifie que l'email a le bon format
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'][] = "Votre email n'a pas le bon format";
            } else {

                if($_SESSION['error'] === []) {

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
                        $_SESSION['error'][] = "email et/ou mot de passe incorrect"; 
                    } else {
                        //il y a bien un utilisateur avec cet email, on vérifie si le mot de passe correspond
                        if(!password_verify($_POST['pass'], $user['pass'])) {
                            $_SESSION['error'][] = "email et/ou mot de passe incorrect"; 
                        } else {
                            //l'email et le mot de passe sont correct, on ouvre la session (connexion de l'utilisateur)
                            
                            $_SESSION['user'] = [
                                "id" => $user['id'],
                                "name" => $user['name'],
                                "email" => $user['email'],
                                "roles" => $user['roles'],
                            ];
                            
                            // on redirige vers la page "espace personnel"
                            header("Location: userSpace.php");
                        }
                    }
                }
            }  
        }  else {
            $_SESSION['error'][] = "Formulaire incomplet";
        }
    } 

    $title = "Connexion";
    require_once 'includes/header.php';
    require_once 'includes/navBar.php';
   
?>
<h1>Connexion</h1>
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

  
