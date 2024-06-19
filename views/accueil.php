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
                            $vehicules = data();
                            foreach ($vehicules['marques'] as $marque) {
                                echo '<a href="/marque/'.$marque['num_marque'].'">'.$marque['nom_marque'].'</a>';
                            }
                          ?>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
    <main class="main-admin">
        <div class="nouveau">
        <?php
                $count = 1;
                foreach ($vehicules['marques'] as $marque) {
                    foreach ($marque['modeles'] as $modele) {
                            if ($count < 4 ) {
                            
                            echo '<div class="modele">';
                          
                            if (empty($modele['url_photo'])) {
                                echo '<img src="https://thumbs.dreamstime.com/b/sch%C3%A9ma-voiture-48227977.jpg" alt="' . $modele['nom_modele'] . '">';
                            }
                            else {
                                echo '<img src="' . $modele['url_photo'].'" alt="' . $modele['nom_modele'] . '">';
                            }
                            echo '<div class="info-modele"><p>' . $marque['nom_marque'] .' - '.$modele['nom_modele'] .' - '.$modele['annee_debut'] . '</p></div>';
        
                            echo '<div class="outils-modele"><p><a href="/modifier/'.$modele['num_modele'].'">Modifier</a>'.' - '.'<a href="/detail/'.$modele['num_modele'].'">Voir plus</a>'.' - '.'<a href="/marque/'.$marque['num_marque'].'">Voir la marque</a></p></div>';

                            
                            echo '</div>'; 
                            $count++;
                            } else {
                                break;
                            }
                        }
                        }

            echo '</div>'; 
            ?>
        </div>
        <div class="accueil">
            <div class="slideshow-container">
                <div class="mySlides fade">
                    <img src="https://www.vintage-cars-collection.com/frmkscss/images/2.jpg" style="width:100%">
                </div>
                <div class="mySlides fade">
                    <img src="https://i.redd.it/s3mk6cfxwgd61.jpg" style="width:100%">
                </div>
            </div>
            <script>
            let slideIndex = 0;
            showSlides();

            function showSlides() {
                let slides = document.getElementsByClassName("mySlides");
                for (let i = 0; i < slides.length; i++) {
                    slides[i].style.display = "none";  
                }
                slideIndex++;
                if (slideIndex > slides.length) {slideIndex = 1}    
                slides[slideIndex-1].style.display = "block";  
                setTimeout(showSlides, 10000); 
            }
            </script>
            <div class="info-site">
                <h2>Bienvenue sur Vintage Cars</h2>
                <p>Le site dédié aux passionnés de voitures vintage!</br>
                Ici, les amateurs de véhicules d'époque peuvent partager leur passion en ajoutant et modifiant des articles détaillés sur une vaste gamme de modèles et de marques de voitures classiques.</br>
                Que vous soyez un expert cherchant à enrichir la base de connaissances avec des informations précises ou un néophyte souhaitant découvrir l'histoire fascinante des automobiles vintage, notre plateforme collaborative vous offre les outils nécessaires.</br>
                </p><p class="decale">Explorez notre collection d'articles, contribuez vos connaissances et plongez dans le monde captivant des voitures d'antan sur notre site ! </p>
            </div>
        </div>
    </main>
    <footer>
    <?php include "footer.php" ?>
    </footer>
</body>
</html>