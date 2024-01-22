<nav>
    <ul>
        <li>
            <div class="nav__icon__link__wrapper">
                <i class="fa-solid fa-house"></i>
                <a href="index.php">Accueil</a>
            </div>
        </li>
        <li>
            <div class="nav__icon__link__wrapper">
                <i class="fa-regular fa-newspaper"></i>
                <a href="articles.php">Articles</a>
            </div>
        </li>

        <?php if(!isset($_SESSION['user'])) : ?>

        <li>
            <div class="nav__icon__link__wrapper">
                <i class="fa-solid fa-right-to-bracket"></i>
                <a href="connexion.php">Connexion</a>
            </div>
        </li>
        <li>
            <div class="nav__icon__link__wrapper">
                <i class="fa-solid fa-user-plus"></i>
                <a href="inscription.php">Inscription</a>
            </div>
        </li>

        <?php else : ?>

        <li>
            <div class="nav__icon__link__wrapper">
                <i class="fa-solid fa-user"></i>
                <a href="userSpace.php">Espace utilisateur</a>
            </div>
        </li>    
        <li>
            <div class="nav__icon__link__wrapper">
                <i class="fa-solid fa-power-off"></i>
                <a href="deconnexion.php">Se déconnecter</a>
            </div>
        </li>

        <?php endif;

        if(isset($_SESSION['user'])) :
            if ($_SESSION['user']['roles'] === '["ROLE_USER", "ROLE_ADMIN"]'):
                ?>
        <li>
        <div class="nav__icon__link__wrapper">
                <i class="fa-solid fa-feather-pointed"></i>
                <a href="ajout.php">Ajouter un article</a>
            </div>
            
        </li>
        
        <?php
            endif;
        endif;
        ?>
       
        <li>Services</li>
        <li>Contact</li>
    </ul>
</nav>