<?php

namespace App\Controllers;

class ModeleView {
    public static function afficherModele($modele, $marque) {
        if (empty($modele->url_photo)) {
            echo '<img src="https://thumbs.dreamstime.com/b/sch%C3%A9ma-voiture-48227977.jpg" alt="' . htmlspecialchars($modele->nom_modele) . '">';
        } else {
            echo '<img src="' . htmlspecialchars($modele->url_photo) . '" alt="' . htmlspecialchars($modele->nom_modele) . '">';
        }
        echo '<div class="caracteristique-modele">';
        echo '<h1>A PROPOS DE CE MODÈLE :</h1></br>';
        echo '<h1>' . htmlspecialchars($marque['nom_marque']) . ' - ' . htmlspecialchars($modele->nom_modele) . '</h1>';
        if ($modele->annee_debut == $modele->annee_fin) {
            echo '<p>' . htmlspecialchars($modele->annee_debut) . '</p>';
        } else {
            echo '<p>' . htmlspecialchars($modele->annee_debut) . ' - ' . htmlspecialchars($modele->annee_fin) . '</p>';
        }
        echo '<h3>Puissance DIN :</h3>';
        if ($modele->puissance_max == $modele->puissance_min) {
            echo '<p>' . htmlspecialchars($modele->puissance_max) . 'ch</p>';
        } else {
            echo '<p>' . htmlspecialchars($modele->puissance_min) . ' ch - ' . htmlspecialchars($modele->puissance_max) . ' ch</p>';
        }
        echo '<h3>Cote du modèle :</h3>';
        echo '<p>' . htmlspecialchars($modele->prix_actuel) . ' €</p>';
        echo '<h3>Prix initial :</h3>';
        echo '<p>' . htmlspecialchars($modele->prix_neuf) . ' €</p>';
        echo '<h3>Production :</h3>';
        echo '<p>' . htmlspecialchars($modele->nbr_produit) . ' véhicules produits</p>';
        echo '<strong>' . htmlspecialchars($modele->description) . '</strong>';
        echo '<div class="outils-modele"><p><a href="/modifier/' . htmlspecialchars($modele->num_modele) . '">Modifier</a>  - <a href="/marque/' . htmlspecialchars($marque['num_marque']) . '">Voir la marque</a></p></div>';
        echo '</div>';
    }
    public static function afficherFormulaireModele($action, $modele = null, $marque = null) {
        $isModification = $action === 'modifier';
        $actionUrl = $isModification ? "/modifier/{$modele->num_modele}" : "/ajouter";
        $titre = $isModification ? "Modifier le modèle" : "Ajouter un nouveau modèle";

        echo "<h1 style='margin-bottom: 20px'>{$titre}</h1>";
        echo "<form action=\"{$actionUrl}\" method=\"post\" class=\"form-vehicules\">";
        echo '<div class="formulaire">';
        echo '<label for="nom_marque">Marque : ' . htmlspecialchars($marque['nom_marque'] ?? '') . '</label><br>';
        if ($isModification) {
            echo "<input type=\"hidden\" name=\"marqueIndex\" value=\"" . htmlspecialchars($marque['num_marque']) . "\">";
            echo "<input type=\"hidden\" name=\"modeleIndex\" value=\"" . htmlspecialchars($modele->num_modele) . "\">";
        } else {
            echo '<select name="num_marque" id="num_marque" required>';
            $vehicules = Catalogue::getVoitures();
            foreach ($vehicules['marques'] as $marque) {
                echo "<option value='" . $marque['num_marque'] . "'>" . $marque['nom_marque'] . "</option>";
            }
            echo "</select>";
        }

        echo '<label for="nom_modele">Nom du modèle :</label>';
        echo '<input type="text" id="nom_modele" name="nom_modele" value="' . htmlspecialchars($modele->nom_modele ?? '') . '" required><br>';
        echo '<label for="annee_debut">Année de début :</label>';
        echo '<input type="text" id="annee_debut" name="annee_debut" value="' . htmlspecialchars($modele->annee_debut ?? '') . '" required><br>';
        echo '<label for="annee_fin">Année de fin :</label>';
        echo '<input type="text" id="annee_fin" name="annee_fin" value="' . htmlspecialchars($modele->annee_fin ?? '') . '" required><br>';
        echo '<label for="nbr_produit">Nombre produit :</label>';
        echo '<input type="text" id="nbr_produit" name="nbr_produit" value="' . htmlspecialchars($modele->nbr_produit ?? '') . '" required><br>';
        echo '<input id="submit" type="submit" name="submit" value="' . ($isModification ? "Modifier" : "Ajouter") . '">';
        echo '</div><div class="formulaire">';
        echo '<div><label for="puissance_max">Puissance max :</label>';
        echo '<input type="text" id="puissance" name="puissance_max" value="' . htmlspecialchars($modele->puissance_max ?? '') . '"required><br>';
        echo '<label for="puissance_min">Puissance min :</label>';
        echo '<input type="text" id="puissance" name="puissance_min" value="' . htmlspecialchars($modele->puissance_min ?? '') . '"required><br></div>';
        echo '<div><label for="prix_neuf">Prix neuf :</label>';
        echo '<input type="text" id="prix_neuf" name="prix_neuf" value="' . htmlspecialchars($modele->prix_neuf ?? '') . '"required><br>';
        echo '<label for="prix_actuel">Prix actuel :</label>';
        echo '<input type="text" id="prix_actuel" name="prix_actuel" value="' . htmlspecialchars($modele->prix_actuel ?? '') . '"required><br></div>';
        echo '<label for="url_photo">URL de la photo :</label>';
        echo '<input type="text" id="url_photo" name="url_photo" value="' . htmlspecialchars($modele->url_photo ?? '') . '"><br>';
        echo '<label for="description">Description :</label>';
        echo '<textarea id="description" name="description" required>' . htmlspecialchars($modele->description ?? '') . '</textarea><br>';
        echo '</div></form>';
    }
}

