<?php

namespace App\Controllers;

class Catalogue {
    public $marques;

    public function __construct($marques = []) {
        $this->marques = $marques;
    }
    public static function getVoitures() {
        $jsonData = file_get_contents('../requetes/data.json');
        return json_decode($jsonData, true);
    }

    public static function saveVoitures($data) {
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents('../requetes/data.json', $jsonData);
    }

    public static function deleteModele($numModele) {
        $vehicules = self::getVoitures();
        foreach ($vehicules['marques'] as &$marque) {
            foreach ($marque['modeles'] as $index => $modele) {
                if ($modele['num_modele'] == $numModele) {
                    array_splice($marque['modeles'], $index, 1);
                    self::saveVoitures($vehicules);
                    return;
                }
            }
        }
    }

    public static function filterModeles($modeles, $modeleRecherche, $anneeRecherche) {
        return array_filter($modeles, function($modele) use ($modeleRecherche, $anneeRecherche) {
            $matchesModele = $modeleRecherche === '' || stripos($modele['nom_modele'], $modeleRecherche) !== false;
            $matchesAnnee = $anneeRecherche === '' || ($modele['annee_debut'] == $anneeRecherche);
            return $matchesModele && $matchesAnnee;
        });
    }
}