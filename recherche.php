<?php
$json_data = file_get_contents('data.json');
$vehicules = json_decode($json_data, true);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vintage Cars</title>
    <link rel="stylesheet"  href="style/style.css">
</head>
<body>
    <header>
        <div class="container">
            <a href="/">
                <h1>Vintage Cars</h1>
            </a>
            <nav>
                <ul>
                    <li><a href="tous_les_modeles.php">Tous les modèles</a></li>
                    <li>
                        <a id="bouton" class="bouton" tabindex="0">Marques</a>
                        <div class="menu" id="menu">
                          <?php 
                            foreach ($vehicules['marques'] as $marque) {
                                    echo '<a href="marque.php?marque='.$marque['num_marque'].'">'.$marque['nom_marque'].'</a>';
                            }
                          ?>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
    <p>Recherche de voitures</p>
    <form method="GET">
                        <?php
                            
                        ?>
                        <div class="form-group">
                            <label for="marque">Marque :</label>
                            <select id="marque" name="marque">
                                <option>Toutes</option>
                                
                                <option value="1">Alfa Roméo</option>
                                
                            </select>
                        </div>
                        <?php
                        ?>
                        <div class="form-group">
                            <label for="modele">Nom du modèle</label>
                            <input type="text" id="modele" name="modele">
                        </div>

                        <div class="form-group">
                            <label for="annee">Année</label>
                            <input type="number" id="annee" name="annee">
                        </div>

                        <input type="submit" value="Rechercher">

                    
                    </form>



    </main>
</body>
</html>
