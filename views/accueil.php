<?php
use App\Controllers\Modele;
use App\Controllers\Marque;
use App\Controllers\Catalogue;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vintage Cars</title>
    <link rel="stylesheet"  href="../style.css">
</head>
<body>
    <header>
        <div class="container">
            <a href="/">
                <h1>Vintage Cars</h1>
                <img src="../image/car.png">
            </a>
            <nav>
                <ul>
                    <li><a href="/modeles">Tous les modèles</a></li>
                    <li>
                        <a id="bouton" class="bouton" tabindex="0">Marques</a>
                        <div class="menu" id="menu">
                          <?php 
                            $dataController->afficherMarques();
                          ?>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
    <main class="main-accueil">
        <div class="accueil">
            <div class="slideshow-container">
                             
            </div>
            <div class="nouveau">
                <?php
                $dataController->afficher3Modeles()
                ?>
            </div>
        </div>
        <div class="info-site">
            <h2>Bienvenue sur Vintage Cars</h2>
            <p>Le site dédié aux passionnés de voitures vintage!</br>
            Ici, les amateurs de véhicules d'époque peuvent partager leur passion en ajoutant et modifiant </br>des articles détaillés sur une vaste gamme de modèles et de marques de voitures classiques.</br>
                Que vous soyez un expert cherchant à enrichir la base de connaissances avec des informations précises </br>ou un néophyte souhaitant découvrir l'histoire fascinante des automobiles vintage, </br>notre plateforme collaborative vous offre les outils nécessaires.</br>
                </p><p class="decale">Explorez notre collection d'articles, contribuez vos connaissances et plongez dans le monde captivant des voitures d'antan sur notre site ! </p>
        </div>
        <div class="bas-du-site">
            <div class="info-bas">
                <div class="presentation">
                    <img src="../image/car.png">
                    <p><strong>Vintage Cars</strong>, site de partage</br> d'information pour les vehicules</br> vintage, de luxe et de collection.</p>
                </div>
                <ul>
                    <li><h2>Notre Site</h2></li>
                    <li><a href="/accueil">Accueil</a></li>
                    <li><a href="/modeles">Les Véhicules</a></li>
                    <li><a href="/recherche">Recherche</a></li>
                    <li><a href="/ajouter">Ajouter un véhicules</a></li>
                    <li><a href="/connexion">Connexion</a></li>
                </ul>
                <div class="contact">
                    <h2>Contact</h2>
                    <h3>Email : contact@vintage-cars.com</h3>
                </div>
            </div>
            <h3>© 2024 Vintage Cars - Tous droits réservés</h3>
        </div>
    </main>
    <footer>
    <?php include "footer.php" ?>
    </footer>
</body>
</html>