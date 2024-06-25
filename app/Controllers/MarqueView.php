<?php

namespace App\Controllers;

class MarqueView {
    public function displayMarque($marque) {
        if ($marque) {
            echo "<div class='info-marque'>";
            echo '<div><img src="' . htmlspecialchars($marque['url_logo_marque']) . '">';
            echo "<h1>" . htmlspecialchars($marque['nom_marque']) . "</h1></div>";
            echo "<p>" . htmlspecialchars($marque['descri_marque']) . "</p>";
            echo '</div><div class="liste-modeles">';
            $count = 0;
            echo '<div class="row">'; 
            foreach ($marque['modeles'] as $modele) {
                if ($count > 0 && $count % 3 == 0) {
                    echo '</div><div class="row">'; 
                }
                echo '<div class="modele">';
                if (empty($modele['url_photo'])) {
                    echo '<img src="https://thumbs.dreamstime.com/b/sch%C3%A9ma-voiture-48227977.jpg" alt="' . htmlspecialchars($modele['nom_modele']) . '">';
                } else {
                    echo '<img src="' . htmlspecialchars($modele['url_photo']) . '" alt="' . htmlspecialchars($modele['nom_modele']) . '">';
                }
                echo '<div class="info-modele"><p>' . htmlspecialchars($marque['nom_marque']) .' - '. htmlspecialchars($modele['nom_modele']) .' - '. htmlspecialchars($modele['annee_debut']) . '</p></div>';
                echo '<div class="outils-modele"><p><a href="/modifier/'. htmlspecialchars($modele['num_modele']) .'">Modifier</a>'.' - '.'<a href="/detail/'. htmlspecialchars($modele['num_modele']) .'">Voir plus</a></p></div>';
                echo '</div>';
                $count++;
            }
            echo '</div></div>'; 
        } else {
            echo "<p>Marque non trouvée</p>";
        }
    }

    public function displayAdminMarque($get) {
        $vehicules = Catalogue::getVoitures();
        Marque::ajouterOuModifierMarque();
        
        usort($vehicules['marques'], function($a, $b) {
            return strcmp($a['nom_marque'], $b['nom_marque']);
        });
    
        echo '<div class="admin-marques">';
        echo '    <div class="liste-marques">';
        echo '        <ul>';
        foreach ($vehicules['marques'] as $marque) {
            echo '            <a href="/administrateur/marques/' . htmlspecialchars($marque['num_marque']) . '"><li>' . htmlspecialchars($marque['nom_marque']) . '</li></a>';
        }
        echo '        </ul>';
        echo '    </div>';
    
        if (isset($get['marque'])) {
            $num_marque = (int) $get['marque'];
            $marque = Marque::getMarqueByNum($num_marque);
            if ($marque) {
                echo '<div class="ajouter-marque">';
                echo '    <a href="/administrateur/marques">< Retour</a>';
                echo '    <div>';
                echo '        <img src="' . htmlspecialchars($marque['url_logo_marque']) . '">';
                echo '        <h1>' . htmlspecialchars($marque['nom_marque']) . '</h1>';
                echo '    </div>';
                echo '    <form method="post">';
                echo '        <input type="hidden" name="num_marque" value="' . htmlspecialchars($marque['num_marque']) . '">';
                echo '        <label for="nom_marque">Nom de la marque :</label><br>';
                echo '        <input type="text" id="nom_marque" name="nom_marque" value="' . htmlspecialchars($marque['nom_marque']) . '" required><br><br>';
                echo '        <label for="url_logo_marque">URL du logo :</label><br>';
                echo '        <input type="text" id="url_logo_marque" name="url_logo_marque" value="' . htmlspecialchars($marque['url_logo_marque']) . '"><br><br>';
                echo '        <label for="descri_marque">Description :</label><br>';
                echo '        <textarea id="descri_marque" name="descri_marque" rows="4" required>' . htmlspecialchars($marque['descri_marque']) . '</textarea><br><br>';
                echo '        <input type="submit" id="submit" value="Enregistrer les modifications">';
                echo '    </form>';
                echo '</div>';
            } else {
                echo '<p>Marque non trouvée.</p>';
            }
        } else {
            echo '<div class="ajouter-marque">';
            echo '    <h2>Ajouter une nouvelle marque</h2>';
            echo '    <form method="post">';
            echo '        <label for="nom_marque">Nom de la marque :</label><br>';
            echo '        <input type="text" id="nom_marque" name="nom_marque" required><br><br>';
            echo '        <label for="url_logo_marque">URL du logo :</label><br>';
            echo '        <input type="text" id="url_logo_marque" name="url_logo_marque" required><br><br>';
            echo '        <label for="descri_marque">Description :</label><br>';
            echo '        <textarea id="descri_marque" name="descri_marque" rows="4" required></textarea><br><br>';
            echo '        <input type="submit" value="Ajouter">';
            echo '    </form>';
            echo '</div>';
        }
        echo '</div>';
    }
    
}
